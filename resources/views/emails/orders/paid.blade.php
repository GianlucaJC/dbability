<x-mail::message>
# Grazie per il tuo acquisto!

Abbiamo ricevuto il pagamento di **€ {{ number_format($order->amount_cents / 100, 2, ',', '.') }}** per **{{ $order->product->title }}**.

@if ($order->purchase_type === 'digital')
Puoi scaricare il tuo PDF in qualsiasi momento usando il pulsante qui sotto. Conserva questa email: il link resterà valido.

<x-mail::button :url="$downloadUrl">
Scarica il PDF
</x-mail::button>
@else
La versione cartacea verrà stampata e spedita all'indirizzo indicato durante il checkout:

{{ $order->shipping_name }}<br>
{{ $order->shipping_address_line1 }}@if ($order->shipping_address_line2), {{ $order->shipping_address_line2 }}@endif<br>
{{ $order->shipping_postal_code }} {{ $order->shipping_city }}@if ($order->shipping_state) ({{ $order->shipping_state }})@endif<br>
{{ $order->shipping_country }}
@endif

Per qualsiasi domanda rispondi pure a questa email.

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
