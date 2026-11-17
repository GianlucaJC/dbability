<?php

namespace Tests\Feature;

use App\Mail\OrderPaidMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_start_a_checkout(): void
    {
        $response = $this->postJson(route('checkout.store'), [
            'product_id' => 999,
            'purchase_type' => 'digital',
        ]);

        // Il guest non viene rimandato al login: arriva alla validazione.
        $response->assertUnprocessable()
            ->assertJsonValidationErrors('product_id');
    }

    public function test_checkout_validates_the_product(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('checkout.store'), [
            'product_id' => 999,
            'purchase_type' => 'digital',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('product_id');
    }

    public function test_checkout_requires_a_supported_purchase_type(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'title' => 'Risorsa',
            'slug' => Product::STORE_SLUG,
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3500,
            'print_price_cents' => 4500,
        ]);

        $response = $this->actingAs($user)->postJson(route('checkout.store'), [
            'product_id' => $product->id,
            'purchase_type' => 'download_and_print',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('purchase_type');
    }

    public function test_checkout_cannot_purchase_a_non_featured_product(): void
    {
        $user = User::factory()->create();
        config()->set('services.stripe.secret', 'sk_test_fake');
        $product = Product::create([
            'title' => 'Risorsa precedente',
            'slug' => 'risorsa-precedente',
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3500,
        ]);

        $response = $this->actingAs($user)->postJson(route('checkout.store'), [
            'product_id' => $product->id,
            'purchase_type' => 'digital',
        ]);

        $response->assertNotFound();
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_only_paid_orders_can_download_a_product(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'title' => 'Risorsa privata',
            'slug' => Product::STORE_SLUG,
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 4000,
        ]);

        $response = $this->actingAs($user)->get(route('downloads.show', $product));

        $response->assertForbidden();
    }

    public function test_a_print_order_does_not_grant_the_digital_download(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'title' => 'Risorsa privata',
            'slug' => Product::STORE_SLUG,
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 4000,
            'file_path' => 'pdfs/risorsa-privata.pdf',
        ]);
        $user->orders()->create([
            'product_id' => $product->id,
            'purchase_type' => 'print',
            'amount_cents' => 4500,
            'currency' => 'eur',
            'status' => 'paid',
        ]);

        $response = $this->actingAs($user)->get(route('downloads.show', $product));

        $response->assertForbidden();
    }

    public function test_paid_print_webhook_saves_the_shipping_address(): void
    {
        config()->set('services.stripe.webhook_secret', 'whsec_test');
        $user = User::factory()->create();
        $product = Product::create([
            'title' => 'Risorsa privata',
            'slug' => Product::STORE_SLUG,
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3500,
            'print_price_cents' => 4500,
        ]);
        $order = $user->orders()->create([
            'product_id' => $product->id,
            'purchase_type' => 'print',
            'amount_cents' => 4500,
            'currency' => 'eur',
            'status' => 'pending',
            'stripe_checkout_session_id' => 'cs_test_print',
        ]);
        $payload = json_encode([
            'id' => 'evt_test_print',
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_print',
                    'object' => 'checkout.session',
                    'payment_status' => 'paid',
                    'payment_intent' => 'pi_test_print',
                    'collected_information' => [
                        'shipping_details' => [
                            'name' => 'Mario Rossi',
                            'address' => [
                                'line1' => 'Via Roma 1',
                                'line2' => null,
                                'city' => 'Milano',
                                'state' => 'MI',
                                'postal_code' => '20100',
                                'country' => 'IT',
                            ],
                        ],
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR);
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test');

        $response = $this->call(
            'POST',
            route('stripe.webhook'),
            [],
            [],
            [],
            ['HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}"],
            $payload,
        );

        $response->assertNoContent();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
            'shipping_name' => 'Mario Rossi',
            'shipping_address_line1' => 'Via Roma 1',
            'shipping_city' => 'Milano',
            'shipping_postal_code' => '20100',
            'shipping_country' => 'IT',
        ]);
    }

    public function test_paid_digital_webhook_for_a_guest_saves_the_email_and_sends_the_download_link(): void
    {
        Mail::fake();
        config()->set('services.stripe.webhook_secret', 'whsec_test');
        $product = $this->createFeaturedProduct();
        $order = Order::create([
            'user_id' => null,
            'product_id' => $product->id,
            'purchase_type' => 'digital',
            'amount_cents' => 3500,
            'currency' => 'eur',
            'status' => 'pending',
            'stripe_checkout_session_id' => 'cs_test_guest',
        ]);

        $response = $this->postWebhook([
            'id' => 'cs_test_guest',
            'object' => 'checkout.session',
            'payment_status' => 'paid',
            'payment_intent' => 'pi_test_guest',
            'customer_details' => ['email' => 'guest@example.com'],
        ]);

        $response->assertNoContent();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
            'customer_email' => 'guest@example.com',
            'user_id' => null,
        ]);
        Mail::assertSent(OrderPaidMail::class, fn (OrderPaidMail $mail) => $mail->hasTo('guest@example.com') && $mail->order->is($order));
    }

    public function test_a_guest_order_is_linked_to_an_existing_account_with_the_same_email(): void
    {
        Mail::fake();
        config()->set('services.stripe.webhook_secret', 'whsec_test');
        $user = User::factory()->create(['email' => 'cliente@example.com']);
        $product = $this->createFeaturedProduct();
        $order = Order::create([
            'user_id' => null,
            'product_id' => $product->id,
            'purchase_type' => 'digital',
            'amount_cents' => 3500,
            'currency' => 'eur',
            'status' => 'pending',
            'stripe_checkout_session_id' => 'cs_test_link',
        ]);

        $this->postWebhook([
            'id' => 'cs_test_link',
            'object' => 'checkout.session',
            'payment_status' => 'paid',
            'payment_intent' => 'pi_test_link',
            'customer_details' => ['email' => 'cliente@example.com'],
        ])->assertNoContent();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
            'customer_email' => 'cliente@example.com',
            'user_id' => $user->id,
        ]);
    }

    public function test_a_signed_link_downloads_the_purchased_pdf(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('pdfs/risorsa.pdf', '%PDF-1.4 contenuto di prova');
        $product = $this->createFeaturedProduct(['file_path' => 'pdfs/risorsa.pdf']);
        $order = Order::create([
            'user_id' => null,
            'customer_email' => 'guest@example.com',
            'product_id' => $product->id,
            'purchase_type' => 'digital',
            'amount_cents' => 3500,
            'currency' => 'eur',
            'status' => 'paid',
        ]);

        $response = $this->get(URL::signedRoute('orders.download', ['order' => $order->id]));

        $response->assertOk()
            ->assertDownload($product->slug.'.pdf');
    }

    public function test_the_order_download_requires_a_valid_signature(): void
    {
        $product = $this->createFeaturedProduct(['file_path' => 'pdfs/risorsa.pdf']);
        $order = Order::create([
            'user_id' => null,
            'product_id' => $product->id,
            'purchase_type' => 'digital',
            'amount_cents' => 3500,
            'currency' => 'eur',
            'status' => 'paid',
        ]);

        $this->get(route('orders.download', $order))->assertForbidden();
    }

    public function test_an_unpaid_order_cannot_be_downloaded_even_with_a_valid_signature(): void
    {
        $product = $this->createFeaturedProduct(['file_path' => 'pdfs/risorsa.pdf']);
        $order = Order::create([
            'user_id' => null,
            'product_id' => $product->id,
            'purchase_type' => 'digital',
            'amount_cents' => 3500,
            'currency' => 'eur',
            'status' => 'pending',
        ]);

        $this->get(URL::signedRoute('orders.download', ['order' => $order->id]))->assertForbidden();
    }

    private function createFeaturedProduct(array $attributes = []): Product
    {
        return Product::create(array_merge([
            'title' => 'Risorsa in evidenza',
            'slug' => Product::STORE_SLUG,
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3500,
            'print_price_cents' => 4500,
        ], $attributes));
    }

    private function postWebhook(array $sessionObject): \Illuminate\Testing\TestResponse
    {
        $payload = json_encode([
            'id' => 'evt_'.$sessionObject['id'],
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => ['object' => $sessionObject],
        ], JSON_THROW_ON_ERROR);
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test');

        return $this->call(
            'POST',
            route('stripe.webhook'),
            [],
            [],
            [],
            ['HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}"],
            $payload,
        );
    }
}
