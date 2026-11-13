<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MyResourcesController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Dashboard', [
            'orders' => $request->user()->orders()
                ->with('product:id,title,slug')
                ->where('status', 'paid')
                ->latest('paid_at')
                ->get()
                ->map(fn ($order) => [
                    'id' => $order->id,
                    'title' => $order->product->title,
                    'purchase_type' => $order->purchase_type,
                    'shipping_name' => $order->shipping_name,
                    'shipping_address' => collect([
                        $order->shipping_address_line1,
                        $order->shipping_address_line2,
                        $order->shipping_postal_code,
                        $order->shipping_city,
                        $order->shipping_state,
                        $order->shipping_country,
                    ])->filter()->implode(', '),
                    'download_url' => $order->purchase_type === 'digital'
                        ? route('downloads.show', $order->product)
                        : null,
                    'purchased_at' => $order->paid_at?->format('d/m/Y'),
                ]),
        ]);
    }
}
