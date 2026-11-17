<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <main class="min-h-screen bg-white text-[#2A2A2A]">
        <header class="border-b border-[#c7f5f6] bg-white">
            <div class="mx-auto flex max-w-4xl items-center justify-between px-6 py-5">
                <Link :href="route('home')" class="font-bold text-[#01d1d6] hover:text-[#019fa3]">&larr; Torna a DBability</Link>
            </div>
        </header>

        <article class="mx-auto max-w-4xl px-6 py-12 leading-7">
            <h1 class="text-3xl font-bold">Termini e condizioni di vendita</h1>
            <p class="mt-2 text-sm text-[#737373]">Ultimo aggiornamento: 17 novembre 2026</p>

            <h2 class="mt-10 text-xl font-bold">1. Venditore</h2>
            <p class="mt-3">
                Dott.ssa <strong>Daniela Belli</strong>, Terapista della Riabilitazione, iscritta all'Albo n. 83 TNPEE<br>
                Sede legale: Via Fosso Casale 6, 65020 Villa Oliveti (PE)<br>
                Ubicazione esercizio: Galleria Karol Wojtyla 17, 66020 San Giovanni Teatino (CH)<br>
                Codice fiscale: BLLDNL85C67C632W &mdash; Partita IVA: 01928510682<br>
                Email: <a href="mailto:dottoressadanielabelli@gmail.com" class="text-[#ea5494] underline">dottoressadanielabelli@gmail.com</a>
            </p>

            <h2 class="mt-10 text-xl font-bold">2. Oggetto</h2>
            <p class="mt-3">Le presenti condizioni regolano la vendita a distanza, tramite il sito www.dbability.it, di risorse educative in formato <strong>PDF digitale</strong> (consegna tramite download ed email) e in formato <strong>cartaceo stampato</strong> (consegna tramite spedizione in Italia). Le risorse hanno finalit&agrave; educativa e ludica e non sostituiscono una valutazione o un trattamento professionale.</p>

            <h2 class="mt-10 text-xl font-bold">3. Prezzi e pagamento</h2>
            <p class="mt-3">I prezzi indicati sul sito sono espressi in euro e comprensivi delle imposte applicabili; per il formato cartaceo il prezzo include la spedizione in Italia. Il pagamento avviene tramite la piattaforma sicura <strong>Stripe</strong> con i metodi di volta in volta disponibili (carte di pagamento, wallet e altri). L'ordine si considera concluso al buon esito del pagamento, confermato da email riepilogativa.</p>

            <h2 class="mt-10 text-xl font-bold">4. Consegna</h2>
            <ul class="mt-3 list-disc pl-6">
                <li><strong>PDF digitale</strong>: disponibile immediatamente dopo il pagamento tramite link di download e inviato in allegato all'email di conferma. Per gli utenti registrati resta inoltre disponibile nell'area personale.</li>
                <li><strong>Formato cartaceo</strong>: stampato e spedito all'indirizzo indicato in fase di pagamento, di norma entro 10 giorni lavorativi.</li>
            </ul>

            <h2 class="mt-10 text-xl font-bold">5. Diritto di recesso</h2>
            <p class="mt-3"><strong>Prodotto cartaceo</strong>: il consumatore pu&ograve; recedere entro <strong>14 giorni</strong> dal ricevimento, senza indicarne il motivo, dandone comunicazione all'indirizzo email del Venditore. Il prodotto va restituito integro; le spese di reso sono a carico del consumatore. Il rimborso avviene entro 14 giorni dal ricevimento del reso, con lo stesso mezzo di pagamento.</p>
            <p class="mt-3"><strong>PDF digitale</strong>: trattandosi di contenuto digitale fornito su supporto non materiale, ai sensi dell'art. 59, comma 1, lett. o) del Codice del Consumo il diritto di recesso &egrave; <strong>escluso</strong> qualora l'esecuzione sia iniziata con l'accordo espresso del consumatore e con la sua presa d'atto della conseguente perdita del diritto di recesso. Procedendo all'acquisto del PDF digitale, il consumatore acconsente espressamente alla fornitura immediata del contenuto e riconosce di perdere il diritto di recesso.</p>

            <h2 class="mt-10 text-xl font-bold">6. Garanzia legale di conformit&agrave;</h2>
            <p class="mt-3">Ai prodotti si applica la garanzia legale di conformit&agrave; prevista dagli artt. 128 e ss. del Codice del Consumo. In caso di contenuto difettoso o non conforme (es. file non leggibile, copia cartacea danneggiata), contattare il Venditore per la sostituzione o il rimborso.</p>

            <h2 class="mt-10 text-xl font-bold">7. Propriet&agrave; intellettuale</h2>
            <p class="mt-3">I contenuti acquistati sono destinati esclusivamente all'uso personale, familiare o professionale diretto dell'acquirente. Sono vietate la riproduzione, la condivisione, la rivendita e la diffusione, anche parziali, senza autorizzazione scritta del Venditore.</p>

            <h2 class="mt-10 text-xl font-bold">8. Reclami e risoluzione delle controversie</h2>
            <p class="mt-3">Per ogni reclamo &egrave; possibile scrivere a <a href="mailto:dottoressadanielabelli@gmail.com" class="text-[#ea5494] underline">dottoressadanielabelli@gmail.com</a>. Per le controversie con consumatori &egrave; competente il foro del luogo di residenza o domicilio del consumatore. &Egrave; inoltre disponibile la piattaforma europea di risoluzione online delle controversie: <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener" class="text-[#ea5494] underline">ec.europa.eu/consumers/odr</a>.</p>
        </article>

        <footer class="border-t border-[#c7f5f6] py-8 text-center text-sm text-[#737373]">
            <Link :href="route('privacy')" class="text-[#01d1d6] hover:underline">Privacy Policy</Link>
        </footer>
    </main>
</template>
