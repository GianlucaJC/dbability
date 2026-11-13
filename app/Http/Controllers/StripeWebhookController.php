<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $webhookSecret = config('services.stripe.webhook_secret');

        if (blank($webhookSecret)) {
            throw new \LogicException('STRIPE_WEBHOOK_SECRET non è configurata.');
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature'),
                $webhookSecret,
            );
        } catch (\UnexpectedValueException|SignatureVerificationException) {
            abort(400, 'Webhook Stripe non valido.');
        }

        if ($event->type === 'checkout.session.completed' && $event->data->object->payment_status === 'paid') {
            $session = $event->data->object;
            $order = Order::query()
                ->where('stripe_checkout_session_id', $session->id)
                ->first();

            if ($order !== null && $order->status !== 'paid') {
                $paidOrder = [
                    'status' => 'paid',
                    'stripe_payment_intent_id' => is_string($session->payment_intent) ? $session->payment_intent : null,
                    'paid_at' => now(),
                ];

                if ($order->purchase_type === 'print') {
                    $shippingDetails = $session->collected_information?->shipping_details
                        ?? $session->shipping_details
                        ?? null;
                    $shippingAddress = $shippingDetails?->address;

                    $paidOrder += [
                        'shipping_name' => $shippingDetails?->name,
                        'shipping_address_line1' => $shippingAddress?->line1,
                        'shipping_address_line2' => $shippingAddress?->line2,
                        'shipping_city' => $shippingAddress?->city,
                        'shipping_state' => $shippingAddress?->state,
                        'shipping_postal_code' => $shippingAddress?->postal_code,
                        'shipping_country' => $shippingAddress?->country,
                    ];
                }

                $order->update($paidOrder);
            }
        }

        return response()->noContent();
    }
}
