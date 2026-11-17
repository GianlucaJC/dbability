<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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
            ->where('slug', Product::STORE_SLUG)
            ->where('is_active', true)
            ->firstOrFail();
        $purchaseType = $request->string('purchase_type')->toString();
        $isPrint = $purchaseType === 'print';
        $amountCents = $isPrint ? $product->print_price_cents : $product->price_cents;

        $user = $request->user();

        $order = Order::create([
            'user_id' => $user?->id,
            'customer_email' => $user?->email,
            'product_id' => $product->id,
            'purchase_type' => $purchaseType,
            'amount_cents' => $amountCents,
            'currency' => 'eur',
            'status' => 'pending',
        ]);

        $sessionParameters = [
            'mode' => 'payment',
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('home'),
            'client_reference_id' => (string) $order->id,
            'metadata' => ['order_id' => (string) $order->id],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $amountCents,
                    'product_data' => [
                        'name' => $product->title.($isPrint ? ' - Edizione cartacea' : ' - PDF digitale'),
                        'description' => Str::limit(
                            $product->description.($isPrint ? ' Spedizione inclusa.' : ' PDF digitale.'),
                            500,
                            '',
                        ),
                    ],
                ],
                'quantity' => 1,
            ]],
        ];

        if ($user !== null) {
            $sessionParameters['customer_email'] = $user->email;
        }

        if ($isPrint) {
            $sessionParameters['shipping_address_collection'] = [
                'allowed_countries' => ['IT'],
            ];
        }

        $checkoutSession = (new StripeClient($stripeSecret))->checkout->sessions->create($sessionParameters);

        $order->update(['stripe_checkout_session_id' => $checkoutSession->id]);

        return response()->json(['checkout_url' => $checkoutSession->url]);
    }

    public function success(Request $request): Response
    {
        $sessionId = $request->string('session_id')->toString();
        $order = Order::query()
            ->where('stripe_checkout_session_id', $sessionId)
            ->first();

        return Inertia::render('CheckoutSuccess', [
            'sessionId' => $sessionId,
            'purchaseType' => $order?->purchase_type,
            'orderStatus' => $order?->status,
            'downloadUrl' => $order !== null && $order->status === 'paid' && $order->purchase_type === 'digital'
                ? URL::signedRoute('orders.download', ['order' => $order->id])
                : null,
        ]);
    }
}
