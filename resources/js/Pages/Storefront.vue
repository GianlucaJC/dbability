<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import ActivityArt from '@/Components/ActivityArt.vue';
import BrandMark from '@/Components/BrandMark.vue';
import DBabilityLogo from '@/Components/DBabilityLogo.vue';

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

const visualFor = (slug) => ({
    'giochi-movimento-coordinazione': {
        art: 'movement',
        card: 'border-[#3A9ECB]/30 bg-white',
        accent: 'text-[#3A9ECB]',
        button: 'bg-[#3A9ECB] hover:bg-[#3A9DCA]',
    },
    'percorsi-pregrafismo-creativo': {
        art: 'pregrafismo',
        card: 'border-[#CAE8CA] bg-[#CAE8CA]/40',
        accent: 'text-[#778363]',
        button: 'bg-[#778363] hover:bg-[#63704F]',
    },
    'routine-autonomia-quotidiana': {
        art: 'autonomia',
        card: 'border-[#D8E1C5] bg-[#F3F7EB]',
        accent: 'text-[#778363]',
        button: 'bg-[#778363] hover:bg-[#63704F]',
    },
}[slug] ?? {
    art: 'autonomia',
    card: 'border-[#B4CB8D] bg-white',
    accent: 'text-[#778363]',
    button: 'bg-[#778363] hover:bg-[#63704F]',
});

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
    <main class="min-h-screen overflow-hidden bg-white text-[#2A2A2A]">
        <header class="fixed inset-x-0 top-0 z-50 border-b border-[#CAE8CA] bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5">
                <Link :href="route('home')" class="flex items-center gap-3">
                    <DBabilityLogo class="block h-12 w-52 sm:h-14 sm:w-64" />
                </Link>
                <nav class="flex items-center gap-4 text-sm font-medium">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-[#2A2A2A] hover:text-[#778363]">Area personale</Link>
                    <template v-else>
                        <Link :href="route('login')" class="text-[#2A2A2A] hover:text-[#778363]">Accedi</Link>
                        <Link :href="route('register')" class="rounded-full bg-[#3A9ECB] px-4 py-2 text-white shadow-sm hover:bg-[#3A9DCA]">Registrati</Link>
                    </template>
                </nav>
            </div>
        </header>

        <section class="relative overflow-hidden bg-gradient-to-br from-[#778363] via-[#7FAB74] to-[#CAE8CA]">
            <div class="absolute -left-20 top-16 h-44 w-44 rounded-full bg-white/15 blur-2xl"></div>
            <div class="absolute right-0 top-0 h-56 w-56 rounded-full bg-white/20 blur-2xl"></div>
            <div class="relative mx-auto max-w-6xl px-6 pb-20 pt-36">
            <div class="grid items-center gap-12 md:grid-cols-2">
                <div class="relative">
                    <p class="mb-4 inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-bold uppercase tracking-[0.14em] text-white">DBability</p>
                    <h1 class="text-4xl font-bold leading-tight text-white md:text-6xl">Imparare giocando,<br/><span class="text-[#CAE8CA]">un tratto alla volta.</span></h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-white/90">Risorse digitali per genitori e bambini: attività colorate, semplici e rispettose dei tempi di ciascuno.</p>
                    <a href="#risorse" class="mt-8 inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 font-semibold text-[#778363] shadow-lg shadow-[#63704F]/30 hover:bg-[#CAE8CA]">Scopri le attività <span aria-hidden="true">→</span></a>
                </div>
                <div class="relative">
                    <div class="absolute -right-5 -top-5 h-20 w-20 rounded-full bg-[#B4CB8D]"></div>
                    <div class="overflow-hidden rounded-[2.5rem] bg-white p-3 shadow-xl shadow-[#CAE8CA] ring-8 ring-[#CAE8CA]/40">
                        <ActivityArt kind="hero" />
                    </div>
                    <div class="absolute -bottom-5 -left-5 rounded-2xl bg-white px-5 py-4 shadow-lg">
                        <p class="font-bold text-[#2A2A2A]">Materiali da usare insieme</p>
                        <p class="mt-1 text-sm text-[#737373]">Gioco, cura e piccoli traguardi.</p>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <section id="risorse" class="bg-white py-20">
            <div class="mx-auto max-w-6xl px-6">
                <div class="max-w-2xl">
                    <p class="font-bold uppercase tracking-[0.16em] text-[#778363]">Il catalogo</p>
                    <h2 class="mt-3 text-3xl font-bold text-[#2A2A2A] md:text-4xl">Scegli un piccolo percorso da fare insieme</h2>
                    <p class="mt-4 text-lg leading-8 text-[#737373]">Ogni risorsa propone un’attività concreta e ripetibile, con indicazioni pensate per chi accompagna il bambino.</p>
                </div>
                <p v-if="checkoutError" class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-700">{{ checkoutError }}</p>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    <article v-for="product in products" :key="product.id" class="group flex flex-col overflow-hidden rounded-[2rem] border p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl" :class="visualFor(product.slug).card">
                        <div class="overflow-hidden rounded-[1.4rem] bg-white p-2">
                            <ActivityArt :kind="visualFor(product.slug).art" />
                        </div>
                        <div class="px-3 pb-3 pt-6">
                            <p class="text-xs font-bold uppercase tracking-[0.14em]" :class="visualFor(product.slug).accent">Attività guidata</p>
                        <h3 class="text-xl font-bold text-[#2A2A2A]">{{ product.title }}</h3>
                        <p class="mt-4 flex-1 leading-7 text-[#737373]">{{ product.description }}</p>
                        <div class="mt-8 flex items-center justify-between gap-4">
                            <strong class="text-2xl text-[#778363]">€ {{ product.price }}</strong>
                            <button type="button" class="rounded-full px-4 py-2 text-sm font-semibold text-white shadow-sm disabled:cursor-wait disabled:opacity-60" :class="visualFor(product.slug).button" :disabled="processingProductId === product.id" @click="purchase(product.id)">
                                {{ processingProductId === product.id ? 'Attendere...' : demoCheckout ? 'Prova il checkout' : 'Acquista PDF' }}
                            </button>
                        </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="bg-gradient-to-r from-[#778363] via-[#7FAB74] to-[#3A9ECB] py-14 text-white">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-6 px-6 text-center md:flex-row md:text-left">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.16em] text-[#CAE8CA]">Risorse digitali</p>
                    <h2 class="mt-2 text-2xl font-bold">Pronte quando vi serve un momento insieme.</h2>
                </div>
                <div class="flex flex-wrap justify-center gap-3 text-sm font-semibold">
                    <span class="rounded-full bg-white/15 px-4 py-2">PDF stampabili</span>
                    <span class="rounded-full bg-white/15 px-4 py-2">Download immediato</span>
                    <span class="rounded-full bg-white/15 px-4 py-2">Pagamento sicuro</span>
                </div>
            </div>
        </section>

        <div v-if="selectedProduct" class="fixed inset-0 z-50 flex items-center justify-center bg-[#2A2A2A]/60 p-4" @click.self="closeDemo">
            <section class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-2xl" aria-modal="true" role="dialog" aria-labelledby="demo-checkout-title">
                <template v-if="demoStep === 'details'">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wider text-[#778363]">Checkout dimostrativo</p>
                            <h2 id="demo-checkout-title" class="mt-2 text-2xl font-bold text-[#2A2A2A]">{{ selectedProduct.title }}</h2>
                        </div>
                        <button type="button" class="text-2xl leading-none text-[#737373] hover:text-[#2A2A2A]" aria-label="Chiudi" @click="closeDemo">×</button>
                    </div>
                    <p class="mt-5 leading-7 text-[#737373]">{{ selectedProduct.description }}</p>
                    <div class="mt-6 rounded-2xl bg-[#CAE8CA]/40 p-4">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-[#2A2A2A]">Risorsa digitale PDF</span>
                            <strong class="text-xl text-[#778363]">€ {{ selectedProduct.price }}</strong>
                        </div>
                        <p class="mt-2 text-sm text-[#737373]">Questa è una simulazione: nessun pagamento verrà richiesto e non verrà creato alcun ordine.</p>
                    </div>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a :href="selectedProduct.demo_download_url ?? demoPdfUrl" target="_blank" rel="noopener" class="rounded-full border border-[#778363] px-5 py-3 text-center font-semibold text-[#778363] hover:bg-[#CAE8CA]/40">
                            {{ selectedProduct.demo_download_url ? 'Apri questa risorsa' : 'Apri PDF di prova' }}
                        </a>
                        <button type="button" class="rounded-full bg-[#3A9ECB] px-5 py-3 font-semibold text-white hover:bg-[#3A9DCA]" @click="simulatePayment">Simula pagamento</button>
                    </div>
                </template>

                <div v-else-if="demoStep === 'processing'" class="py-10 text-center">
                    <div class="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-[#CAE8CA] border-t-[#3A9ECB]"></div>
                    <h2 class="mt-5 text-2xl font-bold text-[#2A2A2A]">Pagamento in elaborazione</h2>
                    <p class="mt-2 text-[#737373]">Stiamo simulando una transazione sicura.</p>
                </div>

                <div v-else class="py-4 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#CAE8CA] text-2xl text-[#778363]">✓</div>
                    <h2 class="mt-5 text-2xl font-bold text-[#2A2A2A]">Pagamento simulato con successo</h2>
                    <p class="mt-3 leading-7 text-[#737373]">In modalità reale, il PDF acquistato sarebbe ora disponibile nell’area personale.</p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                        <a :href="selectedProduct.demo_download_url ?? demoPdfUrl" target="_blank" rel="noopener" class="rounded-full bg-[#3A9ECB] px-5 py-3 font-semibold text-white hover:bg-[#3A9DCA]">
                            {{ selectedProduct.demo_download_url ? 'Scarica questa risorsa' : 'Scarica il PDF di prova' }}
                        </a>
                        <button type="button" class="rounded-full border border-[#B4CB8D] px-5 py-3 font-semibold text-[#2A2A2A] hover:bg-[#CAE8CA]/40" @click="closeDemo">Chiudi</button>
                    </div>
                </div>
            </section>
        </div>

        <footer class="bg-[#2A2A2A] py-10 text-center text-sm text-white">
            <p>DBability</p>
            <p class="mt-2 text-[#CAE8CA]">Le risorse non sostituiscono una valutazione o un trattamento professionale.</p>
        </footer>
    </main>
</template>
