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
            <h1 class="text-3xl font-bold">Privacy Policy</h1>
            <p class="mt-2 text-sm text-[#737373]">Informativa ai sensi degli artt. 13-14 del Regolamento (UE) 2016/679 ("GDPR") &mdash; Ultimo aggiornamento: 17 novembre 2026</p>

            <h2 class="mt-10 text-xl font-bold">1. Titolare del trattamento</h2>
            <p class="mt-3">
                Dott.ssa <strong>Daniela Belli</strong>, Terapista della Riabilitazione, iscritta all'Albo n. 83 TNPEE<br>
                Sede legale: Via Fosso Casale 6, 65020 Villa Oliveti (PE)<br>
                Codice fiscale: BLLDNL85C67C632W &mdash; Partita IVA: 01928510682<br>
                Email di contatto: <a href="mailto:dottoressadanielabelli@gmail.com" class="text-[#ea5494] underline">dottoressadanielabelli@gmail.com</a>
            </p>

            <h2 class="mt-10 text-xl font-bold">2. Dati personali trattati</h2>
            <ul class="mt-3 list-disc pl-6">
                <li><strong>Dati di acquisto</strong>: indirizzo email, importo e tipo di acquisto; per i prodotti cartacei anche nome e indirizzo di spedizione.</li>
                <li><strong>Dati di registrazione</strong> (facoltativa): nome, email e password (conservata in forma cifrata).</li>
                <li><strong>Dati di pagamento</strong>: i dati della carta o del metodo di pagamento sono raccolti e trattati esclusivamente da Stripe; il Titolare non vi ha mai accesso.</li>
                <li><strong>Dati di navigazione tecnici</strong>: log del server e cookie tecnici indispensabili al funzionamento del sito.</li>
            </ul>

            <h2 class="mt-10 text-xl font-bold">3. Finalit&agrave; e basi giuridiche</h2>
            <ul class="mt-3 list-disc pl-6">
                <li><strong>Vendita e consegna dei prodotti</strong> (gestione ordini, pagamento, invio del PDF via email, spedizione del cartaceo, area personale): esecuzione del contratto (art. 6.1.b GDPR).</li>
                <li><strong>Adempimenti fiscali e contabili</strong>: obbligo di legge (art. 6.1.c GDPR).</li>
                <li><strong>Sicurezza del sito e prevenzione delle frodi</strong>: legittimo interesse (art. 6.1.f GDPR).</li>
            </ul>
            <p class="mt-3">Il conferimento dei dati &egrave; necessario per concludere l'acquisto: in loro assenza non &egrave; possibile fornire i prodotti.</p>

            <h2 class="mt-10 text-xl font-bold">4. Destinatari dei dati</h2>
            <p class="mt-3">I dati sono trattati dai seguenti fornitori, nominati responsabili del trattamento o autonomi titolari per i servizi di rispettiva competenza:</p>
            <ul class="mt-3 list-disc pl-6">
                <li><strong>Stripe Payments Europe, Ltd.</strong> (Irlanda) &mdash; gestione dei pagamenti. Eventuali trasferimenti extra UE avvengono sulla base di Clausole Contrattuali Standard. <a href="https://stripe.com/it/privacy" target="_blank" rel="noopener" class="text-[#ea5494] underline">Privacy policy di Stripe</a>.</li>
                <li><strong>Aruba S.p.A.</strong> (Italia) &mdash; hosting del sito e servizio email.</li>
                <li>Professionisti incaricati degli adempimenti fiscali e contabili.</li>
            </ul>
            <p class="mt-3">I dati non sono oggetto di diffusione n&eacute; di processi decisionali automatizzati o profilazione.</p>

            <h2 class="mt-10 text-xl font-bold">5. Conservazione</h2>
            <ul class="mt-3 list-disc pl-6">
                <li>Dati degli ordini: 10 anni dalla conclusione dell'acquisto (obblighi civilistici e fiscali).</li>
                <li>Dati dell'account registrato: fino alla cancellazione dell'account da parte dell'utente.</li>
                <li>Log tecnici: per il tempo strettamente necessario alla sicurezza del servizio.</li>
            </ul>

            <h2 class="mt-10 text-xl font-bold">6. Diritti dell'interessato</h2>
            <p class="mt-3">Ai sensi degli artt. 15-22 GDPR puoi esercitare in qualsiasi momento i diritti di accesso, rettifica, cancellazione, limitazione, portabilit&agrave; e opposizione scrivendo a <a href="mailto:dottoressadanielabelli@gmail.com" class="text-[#ea5494] underline">dottoressadanielabelli@gmail.com</a>. Hai inoltre il diritto di proporre reclamo al Garante per la Protezione dei Dati Personali (<a href="https://www.garanteprivacy.it" target="_blank" rel="noopener" class="text-[#ea5494] underline">www.garanteprivacy.it</a>).</p>

            <h2 class="mt-10 text-xl font-bold">7. Cookie</h2>
            <p class="mt-3">Questo sito utilizza esclusivamente <strong>cookie tecnici</strong> indispensabili al funzionamento (cookie di sessione e di protezione CSRF), per i quali non &egrave; richiesto il consenso. Non sono utilizzati cookie di profilazione, di marketing o di analisi. La pagina di pagamento &egrave; ospitata da Stripe sul dominio checkout.stripe.com, dove si applica la cookie policy di Stripe.</p>
        </article>

        <footer class="border-t border-[#c7f5f6] py-8 text-center text-sm text-[#737373]">
            <Link :href="route('terms')" class="text-[#01d1d6] hover:underline">Termini e condizioni di vendita</Link>
        </footer>
    </main>
</template>
