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
                $order->update([
                    'status' => 'paid',
                    'stripe_payment_intent_id' => is_string($session->payment_intent) ? $session->payment_intent : null,
                    'paid_at' => now(),
                ]);
            }
        }

        return response()->noContent();
    }
}
