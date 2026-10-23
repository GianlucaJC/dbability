<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function show(Request $request, Product $product): StreamedResponse
    {
        $hasPurchasedProduct = $request->user()->orders()
            ->where('product_id', $product->id)
            ->where('status', 'paid')
            ->exists();

        abort_unless($hasPurchasedProduct, 403);
        abort_if(blank($product->file_path) || ! Storage::disk('local')->exists($product->file_path), 404);

        return Storage::disk('local')->download($product->file_path, $product->slug.'.pdf');
    }
}
