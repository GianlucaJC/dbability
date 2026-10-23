<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DemoDownloadController extends Controller
{
    public function show(Product $product): StreamedResponse
    {
        abort_unless(config('services.demo_checkout'), 404);
        abort_if(blank($product->file_path) || ! Storage::disk('local')->exists($product->file_path), 404);

        return Storage::disk('local')->download($product->file_path, $product->slug.'.pdf');
    }
}
