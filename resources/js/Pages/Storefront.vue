<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import ActivityArt from '@/Components/ActivityArt.vue';
import BookPreview from '@/Components/BookPreview.vue';
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
const selectedPurchaseType = ref('digital');
const demoStep = ref('details');
const authPrompt = ref(null);

const visualFor = (slug) => ({
    'giochi-movimento-coordinazione': {
        art: 'movement',
        card: 'border-[#01d1d6]/30 bg-white',
        accent: 'text-[#01d1d6]',
        button: 'bg-[#01d1d6] hover:bg-[#019fa3]',
    },
    'percorsi-pregrafismo-creativo': {
        art: 'pregrafismo',
        card: 'border-[#f7c7da] bg-[#fff5f9]',
        accent: 'text-[#ea5494]',
        button: 'bg-[#ea5494] hover:bg-[#c93e78]',
    },
    'routine-autonomia-quotidiana': {
        art: 'autonomia',
        card: 'border-[#c7f5f6] bg-[#f7fdfe]',
        accent: 'text-[#01d1d6]',
        button: 'bg-[#01d1d6] hover:bg-[#019fa3]',
    },
}[slug] ?? {
    art: 'autonomia',
    card: 'border-[#c7f5f6] bg-white',
    accent: 'text-[#01d1d6]',
    button: 'bg-[#01d1d6] hover:bg-[#019fa3]',
});

async function startCheckout(productId, purchaseType) {
    checkoutError.value = '';
    processingProductId.value = productId;

    try {
        const response = await axios.post(route('checkout.store'), { product_id: productId, purchase_type: purchaseType });
        window.location.assign(response.data.checkout_url);
    } catch (error) {
        checkoutError.value = error.response?.data?.message ?? 'Non è stato possibile avviare il pagamento. Riprova tra poco.';
        processingProductId.value = null;
    }
}

function purchase(productId, purchaseType) {
    if (props.demoCheckout) {
        selectedProduct.value = props.products.find((product) => product.id === productId);
        selectedPurchaseType.value = purchaseType;
        demoStep.value = 'details';
        return;
    }

    if (!page.props.auth.user) {
        authPrompt.value = { productId, purchaseType };
        return;
    }

    startCheckout(productId, purchaseType);
}

function continueAsGuest() {
    const { productId, purchaseType } = authPrompt.value;
    authPrompt.value = null;
    startCheckout(productId, purchaseType);
}

function closeAuthPrompt() {
    authPrompt.value = null;
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
        <header class="fixed inset-x-0 top-0 z-50 border-b border-[#c7f5f6] bg-white/95 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center gap-2 px-4 py-3 sm:px-6 sm:py-5">
                <Link :href="route('home')" class="flex min-w-0 flex-1">
                    <DBabilityLogo class="block h-auto w-full max-w-[13.5rem] sm:h-14 sm:w-64 sm:max-w-none" />
                </Link>
                <nav class="flex shrink-0 items-center gap-2 text-xs font-medium sm:gap-4 sm:text-sm">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-[#2A2A2A] hover:text-[#01d1d6]">Area personale</Link>
                    <template v-else>
                        <Link :href="route('login')" class="whitespace-nowrap text-[#2A2A2A] hover:text-[#01d1d6]">Accedi</Link>
                        <Link :href="route('register')" class="whitespace-nowrap rounded-full bg-[#ea5494] px-3 py-2 text-white shadow-sm hover:bg-[#c93e78] sm:px-4">Registrati</Link>
                    </template>
                </nav>
            </div>
        </header>

        <section class="relative overflow-hidden bg-gradient-to-br from-[#01d1d6] to-[#ea5494]">
            <div class="absolute -left-20 top-16 h-44 w-44 rounded-full bg-white/15 blur-2xl"></div>
            <div class="absolute right-0 top-0 h-56 w-56 rounded-full bg-white/20 blur-2xl"></div>
            <div class="relative mx-auto max-w-6xl px-6 pb-20 pt-36">
            <div class="grid items-center gap-12 md:grid-cols-2">
                <div class="relative">
                    <p class="mb-4 inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-bold uppercase tracking-[0.14em] text-white">DBability</p>
                    <h1 class="text-4xl font-bold leading-tight text-white md:text-6xl">Imparare giocando,<br/><span class="text-white/90">un tratto alla volta.</span></h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-white/90">Risorse digitali per genitori e bambini: attività colorate, semplici e rispettose dei tempi di ciascuno.</p>
                    <a href="#risorse" class="mt-8 inline-flex items-center gap-2 rounded-full bg-white px-6 py-3 font-semibold text-[#ea5494] shadow-lg shadow-[#ea5494]/30 hover:bg-[#fff5f9]">Scopri le attività <span aria-hidden="true">→</span></a>
                </div>
                <div class="relative">
                    <div class="absolute -right-5 -top-5 h-20 w-20 rounded-full bg-[#ea5494]"></div>
                    <div class="relative pb-28">
                        <div class="overflow-hidden rounded-[2.5rem] bg-white p-3 shadow-xl shadow-[#01d1d6]/30 ring-8 ring-white/40">
                        <ActivityArt kind="hero" />
                        </div>
                        <BookPreview class="absolute inset-x-3 bottom-[-1.25rem] z-10 mx-auto w-[92%] max-w-lg" />
                    </div>
                </div>
            </div>
            </div>
        </section>

        <section id="profilo" class="bg-[#f7fdfe] py-20">
            <div class="mx-auto grid max-w-6xl items-center gap-12 px-6 md:grid-cols-[0.85fr_1.15fr]">
                <figure class="relative mx-auto w-full max-w-sm">
                    <div class="absolute -left-4 -top-4 h-24 w-24 rounded-full bg-[#c7f5f6]"></div>
                    <img
                        src="/images/daniela-belli.jpg"
                        alt="La Dott.ssa Daniela Belli nel suo studio"
                        class="relative aspect-[4/5] w-full rounded-[2rem] object-cover object-center shadow-xl ring-8 ring-white"
                        loading="lazy"
                    />
                    <figcaption class="absolute inset-x-4 bottom-4 rounded-2xl bg-white/95 p-4 shadow-lg backdrop-blur">
                        <p class="font-bold text-[#2A2A2A]">Dott.ssa Daniela Belli</p>
                        <p class="mt-1 text-sm leading-5 text-[#737373]">Terapista della Neuro e Psicomotricità dell’età evolutiva</p>
                    </figcaption>
                </figure>

                <div>
                    <p class="font-bold uppercase tracking-[0.16em] text-[#01d1d6]">Chi sono</p>
                    <h2 class="mt-3 text-3xl font-bold text-[#2A2A2A] md:text-4xl">Esperienza e formazione al servizio della crescita</h2>
                    <p class="mt-4 text-lg leading-8 text-[#737373]">
                        Daniela Belli è Terapista della Neuro e Psicomotricità dell’età evolutiva. Accompagna bambini e famiglie con percorsi e attività pensati per sostenere lo sviluppo e l’autonomia.
                    </p>

                    <div class="mt-8 rounded-[1.75rem] border border-[#c7f5f6] bg-white p-6 shadow-sm sm:p-8">
                        <h3 class="text-xl font-bold text-[#2A2A2A]">Formazione e specializzazioni</h3>
                        <ul class="mt-5 space-y-3 text-sm leading-6 text-[#5F5F5F] sm:text-base">
                            <li class="flex gap-3"><span class="font-bold text-[#01d1d6]">✓</span><span>Laurea in Terapia della Neuropsicomotricità dell’età evolutiva presso l’Università Cattolica del Sacro Cuore di Roma.</span></li>
                            <li class="flex gap-3"><span class="font-bold text-[#01d1d6]">✓</span><span>Master certificato CENAF in Psicomotricità Funzionale.</span></li>
                            <li class="flex gap-3"><span class="font-bold text-[#01d1d6]">✓</span><span>Certificazione in Terapia Manuale Osteopatica Neonatale e Pediatrica.</span></li>
                            <li class="flex gap-3"><span class="font-bold text-[#01d1d6]">✓</span><span>Formazione nei metodi Sabbadini, Feuerstein, NEURAC, ARM, Benso, Amorosi e Ambrosini.</span></li>
                            <li class="flex gap-3"><span class="font-bold text-[#01d1d6]">✓</span><span>Certificazione in Rieducazione Posturale Globale con Metodo Mézières.</span></li>
                        </ul>
                        <a
                            href="https://www.danielabelli.it/chi_sono.php?i=2"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-7 inline-flex items-center gap-2 rounded-full bg-[#ea5494] px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-[#c93e78]"
                        >
                            Scopri il curriculum completo <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="risorse" class="bg-white py-20">
            <div class="mx-auto max-w-6xl px-6">
                <div class="max-w-2xl">
                    <p class="font-bold uppercase tracking-[0.16em] text-[#01d1d6]">Il PDF</p>
                    <h2 class="mt-3 text-3xl font-bold text-[#2A2A2A] md:text-4xl">Un percorso, due modalità di acquisto</h2>
                    <p class="mt-4 text-lg leading-8 text-[#737373]">Il PDF propone attività concrete e ripetibili, con indicazioni pensate per chi accompagna il bambino. Scarica il materiale giusto per te: utile per operatori, terapisti, famiglie e scuole.</p>
                </div>
                <p v-if="checkoutError" class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-700">{{ checkoutError }}</p>
                <div class="mt-10 grid gap-6">
                    <article v-for="product in products" :key="product.id" class="group flex flex-col overflow-hidden rounded-[2rem] border p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl" :class="visualFor(product.slug).card">
                        <div class="overflow-hidden rounded-[1.4rem] bg-white p-2">
                            <ActivityArt :kind="visualFor(product.slug).art" />
                        </div>
                        <div class="px-3 pb-3 pt-6">
                            <p class="text-xs font-bold uppercase tracking-[0.14em]" :class="visualFor(product.slug).accent">Attività guidata</p>
                        <h3 class="text-xl font-bold text-[#2A2A2A]">{{ product.title }}</h3>
                        <p class="mt-4 flex-1 leading-7 text-[#737373]">{{ product.description }}</p>
                        <div class="mt-8 grid gap-3 sm:grid-cols-2">
                            <button type="button" class="flex items-center justify-between gap-3 rounded-2xl px-5 py-4 text-left font-semibold text-white shadow-sm disabled:cursor-wait disabled:opacity-60" :class="visualFor(product.slug).button" :disabled="processingProductId === product.id" @click="purchase(product.id, 'digital')">
                                <span>{{ processingProductId === product.id ? 'Attendere...' : demoCheckout ? 'Prova digitale' : 'PDF digitale' }}</span>
                                <strong>€ {{ product.digital_price }}</strong>
                            </button>
                            <button type="button" class="flex items-center justify-between gap-3 rounded-2xl border-2 border-[#ea5494] bg-white px-5 py-4 text-left font-semibold text-[#ea5494] hover:bg-[#fff5f9] disabled:cursor-wait disabled:opacity-60" :disabled="processingProductId === product.id" @click="purchase(product.id, 'print')">
                                <span>{{ processingProductId === product.id ? 'Attendere...' : demoCheckout ? 'Prova cartaceo' : 'Cartaceo stampato' }}<small class="mt-1 block text-xs font-normal text-[#737373]">Spedizione inclusa in Italia</small></span>
                                <strong>€ {{ product.print_price }}</strong>
                            </button>
                        </div>
                        <p v-if="!demoCheckout" class="mt-4 text-xs leading-5 text-[#737373]">
                            Acquistando accetti i <Link :href="route('terms')" class="underline hover:text-[#2A2A2A]">Termini e condizioni</Link> e la <Link :href="route('privacy')" class="underline hover:text-[#2A2A2A]">Privacy Policy</Link>.
                            Per il PDF digitale acconsenti alla fornitura immediata del contenuto e rinunci al diritto di recesso.
                        </p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="bg-gradient-to-r from-[#01d1d6] to-[#ea5494] py-14 text-white">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-6 px-6 text-center md:flex-row md:text-left">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.16em] text-white/90">Scegli come riceverlo</p>
                    <h2 class="mt-2 text-2xl font-bold">Il formato giusto per un momento insieme.</h2>
                </div>
                <div class="flex flex-wrap justify-center gap-3 text-sm font-semibold">
                    <span class="rounded-full bg-white/15 px-4 py-2">Download immediato</span>
                    <span class="rounded-full bg-white/15 px-4 py-2">Stampa e spedizione incluse in Italia</span>
                    <span class="rounded-full bg-white/15 px-4 py-2">Pagamento sicuro</span>
                </div>
            </div>
        </section>

        <section id="contatti" class="bg-[#f7fdfe] py-16">
            <div class="mx-auto grid max-w-6xl gap-10 px-6 md:grid-cols-2 md:items-center">
                <div>
                    <p class="font-bold uppercase tracking-[0.16em] text-[#01d1d6]">Contatti</p>
                    <h2 class="mt-3 text-3xl font-bold text-[#2A2A2A]">Restiamo in contatto</h2>
                    <p class="mt-3 max-w-lg leading-7 text-[#737373]">Per informazioni sulle attività e sui materiali, puoi contattare Daniela o visitare il suo sito.</p>
                    <a
                        href="https://www.danielabelli.it"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-6 inline-flex items-center gap-2 rounded-full border border-[#01d1d6] bg-white px-5 py-3 font-semibold text-[#01d1d6] shadow-sm transition hover:bg-[#f7fdfe]"
                    >
                        Visita danielabelli.it <span aria-hidden="true">↗</span>
                    </a>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <a href="mailto:dottoressadanielabelli@gmail.com" class="rounded-2xl bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#01d1d6]">Email</span>
                        <span class="mt-2 block break-all font-semibold text-[#2A2A2A]">dottoressadanielabelli@gmail.com</span>
                    </a>
                    <a href="tel:+393479573184" class="rounded-2xl bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#01d1d6]">Telefono</span>
                        <span class="mt-2 block font-semibold text-[#2A2A2A]">+39 347 9573184</span>
                    </a>
                    <a href="https://www.facebook.com/dott.ssa.danielabelli/" target="_blank" rel="noopener noreferrer" class="rounded-2xl bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#01d1d6]">Facebook</span>
                        <span class="mt-2 block font-semibold text-[#2A2A2A]">Dott.ssa Daniela Belli ↗</span>
                    </a>
                    <a href="https://instagram.com/dott.ssa_daniela_belli/" target="_blank" rel="noopener noreferrer" class="rounded-2xl bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#ea5494]">Instagram</span>
                        <span class="mt-2 block font-semibold text-[#2A2A2A]">@dott.ssa_daniela_belli ↗</span>
                    </a>
                    <a href="https://wa.me/393479573184" target="_blank" rel="noopener noreferrer" class="rounded-2xl bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:col-span-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#01d1d6]">WhatsApp</span>
                        <span class="mt-2 block font-semibold text-[#2A2A2A]">Scrivi a Daniela ↗</span>
                    </a>
                </div>
            </div>
        </section>

        <section id="dove-ricevo" class="bg-white py-16">
            <div class="mx-auto max-w-6xl px-6">
                <p class="font-bold uppercase tracking-[0.16em] text-[#01d1d6]">Dove ricevo</p>
                <h2 class="mt-3 text-3xl font-bold text-[#2A2A2A]">Le sedi</h2>
                <div class="mt-8 grid gap-5 md:grid-cols-2">
                    <article class="rounded-[1.75rem] border border-[#c7f5f6] bg-[#f7fdfe] p-6 sm:p-8">
                        <p class="text-sm font-bold uppercase tracking-wider text-[#01d1d6]">San Giovanni Teatino (CH)</p>
                        <h3 class="mt-3 text-xl font-bold text-[#2A2A2A]">Studio di Riabilitazione Neuromotoria e Logopedica</h3>
                        <p class="mt-2 leading-7 text-[#737373]">Galleria Woytila 17</p>
                        <a
                            href="https://www.google.com/maps/search/?api=1&query=Galleria+Woytila+17%2C+San+Giovanni+Teatino"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-5 inline-flex items-center gap-2 font-semibold text-[#01d1d6] hover:underline"
                        >
                            Indicazioni stradali <span aria-hidden="true">↗</span>
                        </a>
                    </article>
                    <article class="rounded-[1.75rem] border border-[#f7c7da] bg-[#fff5f9] p-6 sm:p-8">
                        <p class="text-sm font-bold uppercase tracking-wider text-[#ea5494]">Pianella (PE)</p>
                        <h3 class="mt-3 text-xl font-bold text-[#2A2A2A]">Studio Osteomed</h3>
                        <p class="mt-2 leading-7 text-[#737373]">Contrada Quercia Dell’Ompiso 28</p>
                        <a
                            href="https://www.google.com/maps/search/?api=1&query=Contrada+Quercia+Dell%27Ompiso+28%2C+Pianella"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-5 inline-flex items-center gap-2 font-semibold text-[#ea5494] hover:underline"
                        >
                            Indicazioni stradali <span aria-hidden="true">↗</span>
                        </a>
                    </article>
                </div>
            </div>
        </section>

        <div v-if="authPrompt" class="fixed inset-0 z-50 flex items-center justify-center bg-[#2A2A2A]/60 p-4" @click.self="closeAuthPrompt">
            <section class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-2xl" aria-modal="true" role="dialog" aria-labelledby="auth-prompt-title">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-[#01d1d6]">Prima dell'acquisto</p>
                        <h2 id="auth-prompt-title" class="mt-2 text-2xl font-bold text-[#2A2A2A]">Non hai effettuato l'accesso</h2>
                    </div>
                    <button type="button" class="text-2xl leading-none text-[#737373] hover:text-[#2A2A2A]" aria-label="Chiudi" @click="closeAuthPrompt">×</button>
                </div>
                <p class="mt-5 leading-7 text-[#737373]">
                    Puoi acquistare comunque, senza creare un account:
                    <template v-if="authPrompt.purchaseType === 'digital'"> riceverai via email un link per scaricare il PDF, valido anche in futuro.</template>
                    <template v-else> riceverai via email la conferma dell'ordine con i dati di spedizione.</template>
                </p>
                <p class="mt-3 leading-7 text-[#737373]">Con un account gratuito, invece, ritroverai sempre i tuoi acquisti nell'area personale.</p>
                <p class="mt-4 text-xs leading-5 text-[#737373]">
                    Procedendo accetti i <Link :href="route('terms')" class="underline hover:text-[#2A2A2A]">Termini e condizioni</Link> e la <Link :href="route('privacy')" class="underline hover:text-[#2A2A2A]">Privacy Policy</Link><template v-if="authPrompt.purchaseType === 'digital'">, acconsenti alla fornitura immediata del PDF e rinunci al diritto di recesso</template>.
                </p>
                <div class="mt-6 flex flex-col gap-3">
                    <button type="button" class="rounded-full bg-[#ea5494] px-5 py-3 font-semibold text-white hover:bg-[#c93e78]" @click="continueAsGuest">Continua senza account</button>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <Link :href="route('login')" class="flex-1 rounded-full border border-[#01d1d6] px-5 py-3 text-center font-semibold text-[#01d1d6] hover:bg-[#f7fdfe]">Accedi</Link>
                        <Link :href="route('register')" class="flex-1 rounded-full border border-[#01d1d6] px-5 py-3 text-center font-semibold text-[#01d1d6] hover:bg-[#f7fdfe]">Registrati</Link>
                    </div>
                </div>
            </section>
        </div>

        <div v-if="selectedProduct" class="fixed inset-0 z-50 flex items-center justify-center bg-[#2A2A2A]/60 p-4" @click.self="closeDemo">
            <section class="w-full max-w-lg rounded-3xl bg-white p-8 shadow-2xl" aria-modal="true" role="dialog" aria-labelledby="demo-checkout-title">
                <template v-if="demoStep === 'details'">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wider text-[#01d1d6]">Checkout dimostrativo</p>
                            <h2 id="demo-checkout-title" class="mt-2 text-2xl font-bold text-[#2A2A2A]">{{ selectedProduct.title }}</h2>
                        </div>
                        <button type="button" class="text-2xl leading-none text-[#737373] hover:text-[#2A2A2A]" aria-label="Chiudi" @click="closeDemo">×</button>
                    </div>
                    <p class="mt-5 leading-7 text-[#737373]">{{ selectedProduct.description }}</p>
                    <div class="mt-6 rounded-2xl bg-[#f7fdfe] p-4">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-[#2A2A2A]">{{ selectedPurchaseType === 'print' ? 'Edizione cartacea' : 'PDF digitale' }}</span>
                            <strong class="text-xl text-[#ea5494]">€ {{ selectedPurchaseType === 'print' ? selectedProduct.print_price : selectedProduct.digital_price }}</strong>
                        </div>
                        <p v-if="selectedPurchaseType === 'print'" class="mt-2 text-sm text-[#737373]">Stampa e spedizione in Italia incluse. L’indirizzo verrà richiesto nel checkout.</p>
                        <p class="mt-2 text-sm text-[#737373]">Questa è una simulazione: nessun pagamento verrà richiesto e non verrà creato alcun ordine.</p>
                    </div>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a v-if="selectedPurchaseType === 'digital'" :href="selectedProduct.demo_download_url ?? demoPdfUrl" target="_blank" rel="noopener" class="rounded-full border border-[#01d1d6] px-5 py-3 text-center font-semibold text-[#01d1d6] hover:bg-[#f7fdfe]">
                            {{ selectedProduct.demo_download_url ? 'Apri questa risorsa' : 'Apri PDF di prova' }}
                        </a>
                        <button type="button" class="rounded-full bg-[#ea5494] px-5 py-3 font-semibold text-white hover:bg-[#c93e78]" @click="simulatePayment">Simula pagamento</button>
                    </div>
                </template>

                <div v-else-if="demoStep === 'processing'" class="py-10 text-center">
                    <div class="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-[#c7f5f6] border-t-[#01d1d6]"></div>
                    <h2 class="mt-5 text-2xl font-bold text-[#2A2A2A]">Pagamento in elaborazione</h2>
                    <p class="mt-2 text-[#737373]">Stiamo simulando una transazione sicura.</p>
                </div>

                <div v-else class="py-4 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#c7f5f6] text-2xl text-[#01d1d6]">✓</div>
                    <h2 class="mt-5 text-2xl font-bold text-[#2A2A2A]">Pagamento simulato con successo</h2>
                    <p class="mt-3 leading-7 text-[#737373]">{{ selectedPurchaseType === 'print' ? 'In modalità reale, il prodotto cartaceo verrebbe spedito all’indirizzo indicato nel checkout.' : 'In modalità reale, il PDF acquistato sarebbe ora disponibile nell’area personale.' }}</p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                        <a v-if="selectedPurchaseType === 'digital'" :href="selectedProduct.demo_download_url ?? demoPdfUrl" target="_blank" rel="noopener" class="rounded-full bg-[#01d1d6] px-5 py-3 font-semibold text-white hover:bg-[#019fa3]">
                            {{ selectedProduct.demo_download_url ? 'Scarica questa risorsa' : 'Scarica il PDF di prova' }}
                        </a>
                        <button type="button" class="rounded-full border border-[#c7f5f6] px-5 py-3 font-semibold text-[#2A2A2A] hover:bg-[#f7fdfe]" @click="closeDemo">Chiudi</button>
                    </div>
                </div>
            </section>
        </div>

        <footer class="bg-[#2A2A2A] py-10 text-center text-sm text-white">
            <p>DBability</p>
            <p class="mt-2 text-[#c7f5f6]">Le risorse non sostituiscono una valutazione o un trattamento professionale.</p>
            <p class="mt-4 text-xs text-[#c7f5f6]">Dott.ssa Daniela Belli &mdash; Terapista della Riabilitazione, Albo n. 83 TNPEE<br>Via Fosso Casale 6, 65020 Villa Oliveti (PE) &mdash; P.IVA 01928510682</p>
            <p class="mt-3 text-xs">
                <Link :href="route('privacy')" class="text-[#01d1d6] hover:underline">Privacy Policy</Link>
                <span class="mx-2 text-[#737373]">|</span>
                <Link :href="route('terms')" class="text-[#01d1d6] hover:underline">Termini e condizioni</Link>
            </p>
        </footer>

        <a
            href="https://wa.me/393479573184?text=Ciao%20Daniela%2C%20vorrei%20ricevere%20informazioni."
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Contatta Daniela subito su WhatsApp"
            title="Contatta Daniela su WhatsApp"
            class="fixed bottom-5 right-5 z-40 inline-flex h-14 items-center gap-2 rounded-full bg-[#01d1d6] px-4 font-bold text-white shadow-xl transition hover:-translate-y-1 hover:bg-[#019fa3] focus-visible:outline focus-visible:outline-4 focus-visible:outline-offset-2 focus-visible:outline-white"
        >
            <svg viewBox="0 0 24 24" class="h-7 w-7 shrink-0" fill="none" aria-hidden="true">
                <path d="M20.2 11.8a8.2 8.2 0 0 1-12.1 7.2L3.5 20l1-4.4a8.2 8.2 0 1 1 15.7-3.8Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" />
                <path d="M9 8.3c.2-.5.4-.5.7-.5h.5c.2 0 .4.1.5.4l.7 1.7c.1.2.1.4-.1.6l-.5.6c-.2.2-.2.4 0 .6.4.7 1 1.3 1.7 1.7.2.1.4.1.6-.1l.7-.8c.2-.2.4-.2.6-.1l1.5.7c.3.1.4.3.4.5 0 .3-.2 1.2-.7 1.6-.5.4-1.1.6-1.8.4-1-.2-2.2-.8-3.3-1.8-1.2-1.1-2-2.4-2.2-3.3-.2-.9.1-1.7.5-2.2.2-.3.4-.4.7-.4Z" fill="currentColor" />
            </svg>
            <span class="hidden sm:inline">Scrivimi su WhatsApp</span>
        </a>
    </main>
</template>
