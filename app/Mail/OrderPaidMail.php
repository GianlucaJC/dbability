<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrderPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->order->purchase_type === 'digital'
                ? 'Il tuo PDF è pronto per il download - DBability'
                : 'Conferma ordine - DBability',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.paid',
            with: [
                'order' => $this->order,
                'downloadUrl' => $this->order->purchase_type === 'digital'
                    ? URL::signedRoute('orders.download', ['order' => $this->order->id])
                    : null,
            ],
        );
    }
}
