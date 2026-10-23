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

        $response = $this->actingAs($user)->postJson(route('checkout.store'), ['product_id' => 999]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('product_id');
    }

    public function test_only_paid_orders_can_download_a_product(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'title' => 'Risorsa privata',
            'slug' => 'risorsa-privata',
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 4000,
        ]);

        $response = $this->actingAs($user)->get(route('downloads.show', $product));

        $response->assertForbidden();
    }
}
