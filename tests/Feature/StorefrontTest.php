<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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

    public function test_demo_checkout_can_download_a_product_file_without_authentication(): void
    {
        config()->set('services.demo_checkout', true);
        Storage::fake('local');
        Storage::disk('local')->put('pdfs/risorsa-di-prova.pdf', 'Contenuto del PDF di test.');

        $product = Product::create([
            'title' => 'Risorsa di prova',
            'slug' => 'risorsa-di-prova',
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3500,
            'file_path' => 'pdfs/risorsa-di-prova.pdf',
        ]);

        $response = $this->get(route('demo.download', $product));

        $response->assertDownload('risorsa-di-prova.pdf');
    }
}
