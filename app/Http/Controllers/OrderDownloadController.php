<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderDownloadController extends Controller
{
    public function show(Order $order): StreamedResponse
    {
        abort_unless($order->status === 'paid' && $order->purchase_type === 'digital', 403);

        $product = $order->product;

        abort_if(blank($product->file_path) || ! Storage::disk('local')->exists($product->file_path), 404);

        return Storage::disk('local')->download($product->file_path, $product->slug.'.pdf');
    }
}
