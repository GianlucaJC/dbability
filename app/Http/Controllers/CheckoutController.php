<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCheckoutRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    public function store(CreateCheckoutRequest $request): JsonResponse
    {
        $stripeSecret = config('services.stripe.secret');

        if (blank($stripeSecret)) {
            throw new \LogicException('STRIPE_SECRET non è configurata.');
        }

        $product = Product::query()
            ->whereKey($request->integer('product_id'))
            ->where('is_active', true)
            ->firstOrFail();

        $order = $request->user()->orders()->create([
            'product_id' => $product->id,
            'amount_cents' => $product->price_cents,
            'currency' => 'eur',
            'status' => 'pending',
        ]);

        $checkoutSession = (new StripeClient($stripeSecret))->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('home'),
            'client_reference_id' => (string) $order->id,
            'customer_email' => $request->user()->email,
            'metadata' => ['order_id' => (string) $order->id],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->price_cents,
                    'product_data' => [
                        'name' => $product->title,
                        'description' => Str::limit($product->description, 500, ''),
                    ],
                ],
                'quantity' => 1,
            ]],
        ]);

        $order->update(['stripe_checkout_session_id' => $checkoutSession->id]);

        return response()->json(['checkout_url' => $checkoutSession->url]);
    }

    public function success(Request $request): Response
    {
        return Inertia::render('CheckoutSuccess', [
            'sessionId' => $request->string('session_id')->toString(),
        ]);
    }
}
