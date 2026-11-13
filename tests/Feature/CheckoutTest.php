<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_requires_authentication(): void
    {
        $response = $this->post(route('checkout.store'), ['product_id' => 1]);

        $response->assertRedirect(route('login'));
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
}
