<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    sessionId: String,
});
</script>

<template>
    <main class="flex min-h-screen items-center justify-center bg-amber-50 px-6 text-center">
        <section class="max-w-lg rounded-3xl bg-white p-10 shadow-lg">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-teal-100 text-2xl text-teal-700">✓</div>
            <h1 class="mt-6 text-3xl font-bold text-teal-950">Grazie per il tuo acquisto</h1>
            <p class="mt-4 leading-7 text-stone-600">Il pagamento è stato ricevuto. Stiamo confermando l’ordine: appena completato, troverai il PDF nella tua area personale.</p>
            <Link :href="route('dashboard')" class="mt-8 inline-block rounded-full bg-teal-700 px-6 py-3 font-semibold text-white hover:bg-teal-800">Vai all’area personale</Link>
        </section>
    </main>
</template>
