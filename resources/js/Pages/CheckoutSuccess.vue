<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    sessionId: String,
    purchaseType: String,
    orderStatus: String,
    downloadUrl: String,
});

const page = usePage();
const isAuthenticated = computed(() => Boolean(page.props.auth?.user));
const isPaid = computed(() => props.orderStatus === 'paid');
</script>

<template>
    <main class="flex min-h-screen items-center justify-center bg-amber-50 px-6 text-center">
        <section class="max-w-lg rounded-3xl bg-white p-10 shadow-lg">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-teal-100 text-2xl text-teal-700">✓</div>
            <h1 class="mt-6 text-3xl font-bold text-teal-950">Grazie per il tuo acquisto</h1>
            <p class="mt-4 leading-7 text-stone-600">
                <template v-if="purchaseType === 'print'">Il pagamento è stato ricevuto. La versione cartacea verrà spedita all’indirizzo indicato durante il checkout. Riceverai un’email di conferma dell’ordine.</template>
                <template v-else-if="isPaid">Il pagamento è stato confermato. Puoi scaricare subito il tuo PDF: ti abbiamo inviato anche un’email con il link, così potrai riscaricarlo in futuro.</template>
                <template v-else>Il pagamento è stato ricevuto. Stiamo confermando l’ordine: a conferma avvenuta riceverai un’email con il link per scaricare il PDF.</template>
            </p>
            <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                <a v-if="downloadUrl" :href="downloadUrl" class="inline-block rounded-full bg-teal-700 px-6 py-3 font-semibold text-white hover:bg-teal-800">Scarica il PDF</a>
                <Link v-if="isAuthenticated" :href="route('dashboard')" class="inline-block rounded-full px-6 py-3 font-semibold" :class="downloadUrl ? 'border border-teal-700 text-teal-700 hover:bg-teal-50' : 'bg-teal-700 text-white hover:bg-teal-800'">Vai all’area personale</Link>
                <Link v-else :href="route('home')" class="inline-block rounded-full border border-teal-700 px-6 py-3 font-semibold text-teal-700 hover:bg-teal-50">Torna al negozio</Link>
            </div>
        </section>
    </main>
</template>
