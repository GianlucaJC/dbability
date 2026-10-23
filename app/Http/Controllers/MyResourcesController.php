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
                    'download_url' => route('downloads.show', $order->product),
                    'purchased_at' => $order->paid_at?->format('d/m/Y'),
                ]),
        ]);
    }
}
