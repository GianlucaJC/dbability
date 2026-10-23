<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class StorefrontController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Storefront', [
            'demoCheckout' => config('services.demo_checkout'),
            'demoPdfUrl' => route('demo.pdf'),
            'products' => Product::query()
                ->where('is_active', true)
                ->orderBy('price_cents')
                ->get(['id', 'title', 'slug', 'description', 'price_cents'])
                ->map(fn (Product $product) => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'price' => $product->price,
                    'demo_download_url' => config('services.demo_checkout')
                        && filled($product->file_path)
                        && Storage::disk('local')->exists($product->file_path)
                            ? route('demo.download', $product)
                            : null,
                ]),
        ]);
    }
}
