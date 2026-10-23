<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_displays_active_products(): void
    {
        $product = Product::create([
            'title' => 'Percorso di prova',
            'slug' => 'percorso-di-prova',
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3000,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront')
                ->has('products', 1)
                ->where('products.0.id', $product->id)
                ->where('products.0.price', '30,00')
                ->where('demoCheckout', false));
    }

    public function test_demo_pdf_is_available_without_authentication(): void
    {
        $response = $this->get(route('demo.pdf'));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
