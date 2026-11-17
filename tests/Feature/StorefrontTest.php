<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_storefront_displays_only_the_featured_product_and_both_prices(): void
    {
        $product = Product::create([
            'title' => 'Percorso di prova',
            'slug' => Product::STORE_SLUG,
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3000,
            'print_price_cents' => 4500,
            'is_active' => true,
        ]);
        Product::create([
            'title' => 'Altra risorsa',
            'slug' => 'altra-risorsa',
            'description' => 'Non deve essere mostrata.',
            'price_cents' => 2000,
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront')
                ->has('products', 1)
                ->where('products.0.id', $product->id)
                ->where('products.0.digital_price', '30,00')
                ->where('products.0.print_price', '45,00')
                ->where('demoCheckout', false));
    }

    public function test_storefront_exposes_available_product_files_in_demo_mode(): void
    {
        config()->set('services.demo_checkout', true);
        Storage::fake('local');
        Storage::disk('local')->put('pdfs/risorsa-di-prova.pdf', 'Contenuto del PDF di test.');

        Product::create([
            'title' => 'Risorsa di prova',
            'slug' => Product::STORE_SLUG,
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3500,
            'file_path' => 'pdfs/risorsa-di-prova.pdf',
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('products.0.demo_download_url', route('demo.download', Product::STORE_SLUG)));
    }

    public function test_legal_pages_are_available(): void
    {
        $this->get(route('privacy'))->assertOk();
        $this->get(route('terms'))->assertOk();
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
            'slug' => Product::STORE_SLUG,
            'description' => 'Descrizione della risorsa.',
            'price_cents' => 3500,
            'file_path' => 'pdfs/risorsa-di-prova.pdf',
        ]);

        $response = $this->get(route('demo.download', $product));

        $response->assertDownload(Product::STORE_SLUG.'.pdf');
    }
}
