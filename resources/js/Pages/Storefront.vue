<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    products: Array,
    demoCheckout: Boolean,
    demoPdfUrl: String,
});

const page = usePage();
const processingProductId = ref(null);
const checkoutError = ref('');
const selectedProduct = ref(null);
const demoStep = ref('details');

async function startCheckout(productId) {
    checkoutError.value = '';
    processingProductId.value = productId;

    try {
        const response = await axios.post(route('checkout.store'), { product_id: productId });
        window.location.assign(response.data.checkout_url);
    } catch (error) {
        checkoutError.value = error.response?.data?.message ?? 'Non è stato possibile avviare il pagamento. Riprova tra poco.';
        processingProductId.value = null;
    }
}

function purchase(productId) {
    if (props.demoCheckout) {
        selectedProduct.value = props.products.find((product) => product.id === productId);
        demoStep.value = 'details';
        return;
    }

    if (!page.props.auth.user) {
        router.visit(route('login'));
        return;
    }

    startCheckout(productId);
}

function simulatePayment() {
    demoStep.value = 'processing';

    window.setTimeout(() => {
        demoStep.value = 'complete';
    }, 900);
}

function closeDemo() {
    selectedProduct.value = null;
    demoStep.value = 'details';
}
</script>

<template>
    <main class="min-h-screen bg-amber-50 text-stone-800">
        <header class="border-b border-amber-100 bg-white/90">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
                <Link :href="route('home')" class="text-2xl font-bold tracking-tight text-teal-800">DbAbility</Link>
                <nav class="flex items-center gap-4 text-sm font-medium">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-teal-800 hover:text-teal-600">Area personale</Link>
                    <template v-else>
                        <Link :href="route('login')" class="text-teal-800 hover:text-teal-600">Accedi</Link>
                        <Link :href="route('register')" class="rounded-full bg-teal-700 px-4 py-2 text-white hover:bg-teal-800">Registrati</Link>
                    </template>
                </nav>
            </div>
        </header>

        <section class="mx-auto max-w-6xl px-6 pb-16 pt-20">
            <div class="grid items-center gap-12 md:grid-cols-2">
                <div>
                    <p class="mb-4 font-semibold uppercase tracking-[0.2em] text-teal-700">Daniela Belli Abilità</p>
                    <h1 class="text-4xl font-bold leading-tight text-teal-950 md:text-6xl">Piccoli passi, grandi conquiste.</h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-stone-600">Risorse digitali pensate per genitori e bambini: attività semplici, guidate e rispettose dei tempi di ciascuno.</p>
                    <a href="#risorse" class="mt-8 inline-block rounded-full bg-orange-500 px-6 py-3 font-semibold text-white shadow-sm hover:bg-orange-600">Scopri le risorse</a>
                </div>
                <div class="rounded-3xl bg-teal-700 p-10 text-white shadow-xl">
                    <p class="text-xl font-semibold">Materiali da usare insieme.</p>
                    <p class="mt-3 leading-7 text-teal-100">PDF scaricabili per accompagnare lo sviluppo neuropsicomotorio attraverso il gioco e le routine quotidiane.</p>
                    <div class="mt-8 flex gap-3">
                        <span class="rounded-full bg-white/15 px-4 py-2 text-sm">Download immediato</span>
                        <span class="rounded-full bg-white/15 px-4 py-2 text-sm">Pagamento sicuro</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="risorse" class="bg-white py-16">
            <div class="mx-auto max-w-6xl px-6">
                <div class="max-w-2xl">
                    <p class="font-semibold text-orange-600">IL CATALOGO</p>
                    <h2 class="mt-2 text-3xl font-bold text-teal-950">Scegli il percorso più adatto alla tua famiglia</h2>
                </div>
                <p v-if="checkoutError" class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-700">{{ checkoutError }}</p>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    <article v-for="product in products" :key="product.id" class="flex flex-col rounded-2xl border border-stone-200 bg-stone-50 p-6 shadow-sm">
                        <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 text-xl">✦</div>
                        <h3 class="text-xl font-bold text-teal-950">{{ product.title }}</h3>
                        <p class="mt-4 flex-1 leading-7 text-stone-600">{{ product.description }}</p>
                        <div class="mt-8 flex items-center justify-between gap-4">
                            <strong class="text-2xl text-teal-800">€ {{ product.price }}</strong>
                            <button type="button" class="rounded-full bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800 disabled:cursor-wait disabled:opacity-60" :disabled="processingProductId === product.id" @click="purchase(product.id)">
                                {{ processingProductId === product.id ? 'Attendere...' : demoCheckout ? 'Prova il checkout' : 'Acquista PDF' }}
                            </button>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <div v-if="selectedProduct" class="fixed inset-0 z-50 flex items-center justify-center bg-teal-950/60 p-4" @click.self="closeDemo">
            <section class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-2xl" aria-modal="true" role="dialog" aria-labelledby="demo-checkout-title">
                <template v-if="demoStep === 'details'">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wider text-orange-600">Checkout dimostrativo</p>
                            <h2 id="demo-checkout-title" class="mt-2 text-2xl font-bold text-teal-950">{{ selectedProduct.title }}</h2>
                        </div>
                        <button type="button" class="text-2xl leading-none text-stone-400 hover:text-stone-700" aria-label="Chiudi" @click="closeDemo">×</button>
                    </div>
                    <p class="mt-5 leading-7 text-stone-600">{{ selectedProduct.description }}</p>
                    <div class="mt-6 rounded-2xl bg-amber-50 p-4">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-stone-700">Risorsa digitale PDF</span>
                            <strong class="text-xl text-teal-800">€ {{ selectedProduct.price }}</strong>
                        </div>
                        <p class="mt-2 text-sm text-stone-600">Questa è una simulazione: nessun pagamento verrà richiesto e non verrà creato alcun ordine.</p>
                    </div>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a :href="demoPdfUrl" target="_blank" rel="noopener" class="rounded-full border border-teal-700 px-5 py-3 text-center font-semibold text-teal-800 hover:bg-teal-50">Apri PDF di prova</a>
                        <button type="button" class="rounded-full bg-teal-700 px-5 py-3 font-semibold text-white hover:bg-teal-800" @click="simulatePayment">Simula pagamento</button>
                    </div>
                </template>

                <div v-else-if="demoStep === 'processing'" class="py-10 text-center">
                    <div class="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-teal-100 border-t-teal-700"></div>
                    <h2 class="mt-5 text-2xl font-bold text-teal-950">Pagamento in elaborazione</h2>
                    <p class="mt-2 text-stone-600">Stiamo simulando una transazione sicura.</p>
                </div>

                <div v-else class="py-4 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-teal-100 text-2xl text-teal-700">✓</div>
                    <h2 class="mt-5 text-2xl font-bold text-teal-950">Pagamento simulato con successo</h2>
                    <p class="mt-3 leading-7 text-stone-600">In modalità reale, il PDF acquistato sarebbe ora disponibile nell’area personale.</p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                        <a :href="selectedProduct.demo_download_url ?? demoPdfUrl" target="_blank" rel="noopener" class="rounded-full bg-teal-700 px-5 py-3 font-semibold text-white hover:bg-teal-800">
                            {{ selectedProduct.demo_download_url ? 'Scarica questa risorsa' : 'Scarica il PDF di prova' }}
                        </a>
                        <button type="button" class="rounded-full border border-stone-300 px-5 py-3 font-semibold text-stone-700 hover:bg-stone-50" @click="closeDemo">Chiudi</button>
                    </div>
                </div>
            </section>
        </div>

        <footer class="bg-teal-950 py-10 text-center text-sm text-teal-100">
            <p>DbAbility — Daniela Belli Abilità</p>
            <p class="mt-2 text-teal-300">Le risorse non sostituiscono una valutazione o un trattamento professionale.</p>
        </footer>
    </main>
</template>
