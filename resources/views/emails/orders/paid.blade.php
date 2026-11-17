<x-mail::message>
# Grazie per il tuo acquisto!

Abbiamo ricevuto il pagamento di **€ {{ number_format($order->amount_cents / 100, 2, ',', '.') }}** per **{{ $order->product->title }}**.

@if ($order->purchase_type === 'digital')
Trovi il PDF **in allegato** a questa email. Puoi anche scaricarlo in qualsiasi momento usando il pulsante qui sotto: conserva questa email, il link resterà valido.

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

Questa email è stata inviata da un indirizzo non presidiato: **ti preghiamo di non rispondere**. Per qualsiasi domanda scrivi a [dottoressadanielabelli@gmail.com](mailto:dottoressadanielabelli@gmail.com).

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
