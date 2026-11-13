<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import BookPreviewPage from '@/Components/BookPreviewPage.vue';

const pages = [
    { kind: 'cover', eyebrow: 'Un libro da esplorare', title: 'Percorsi di pregrafismo creativo', number: 'Copertina' },
    { kind: 'curves', eyebrow: 'Primo percorso', title: 'Segui le linee', description: 'Un tratto alla volta, con calma.', number: '01' },
    { kind: 'zigzag', eyebrow: 'Secondo percorso', title: 'Su, giù e ancora', description: 'Allena il gesto con forme diverse.', number: '02' },
    { kind: 'dots', eyebrow: 'Terzo percorso', title: 'Unisci i punti', description: 'Scopri dove ti porta il percorso.', number: '03' },
    { kind: 'shapes', eyebrow: 'Quarto percorso', title: 'Riconosci le forme', description: 'Disegna, osserva e riprova.', number: '04' },
    { kind: 'curves', eyebrow: 'Ultimo percorso', title: 'Il tuo spazio creativo', description: 'Ogni pagina è una nuova scoperta.', number: '05' },
];

const pageIndex = ref(0);
const isFlipping = ref(false);
const pageLabel = computed(() => {
    const leftPage = pages[pageIndex.value];
    const rightPage = pages[(pageIndex.value + 1) % pages.length];

    return `Anteprima: ${leftPage.number} e ${rightPage.number}`;
});

let flipTimer;
let prefersReducedMotion = false;

function pageAt(offset) {
    return pages[(pageIndex.value + offset) % pages.length];
}

function finishPageTurn() {
    pageIndex.value = (pageIndex.value + 2) % pages.length;
    isFlipping.value = false;
}

function turnPage() {
    if (isFlipping.value) {
        return;
    }

    if (prefersReducedMotion) {
        finishPageTurn();
        return;
    }

    isFlipping.value = true;
}

onMounted(() => {
    prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!prefersReducedMotion) {
        flipTimer = window.setInterval(turnPage, 4200);
    }
});

onBeforeUnmount(() => {
    window.clearInterval(flipTimer);
});
</script>

<template>
    <section class="book-preview" aria-label="Anteprima sfogliabile del libro">
        <div class="book-spread" aria-hidden="true">
            <div class="book-page book-page--left">
                <BookPreviewPage :page="pageAt(0)" />
            </div>
            <div class="book-page book-page--right">
                <BookPreviewPage :page="pageAt(1)" />
            </div>
            <div class="book-leaf" :class="{ 'book-leaf--turning': isFlipping }" @animationend="finishPageTurn">
                <div class="book-leaf__face book-leaf__front">
                    <BookPreviewPage :page="pageAt(1)" />
                </div>
                <div class="book-leaf__face book-leaf__back">
                    <BookPreviewPage :page="pageAt(2)" />
                </div>
            </div>
            <div class="book-spine"></div>
            <div v-if="isFlipping" class="book-light" />
        </div>
        <div class="book-preview__controls">
            <div>
                <p class="book-preview__title">Anteprima del libro</p>
                <p class="book-preview__status" aria-live="polite">{{ pageLabel }}</p>
            </div>
            <button type="button" class="book-preview__button" :disabled="isFlipping" @click="turnPage">
                Sfoglia <span aria-hidden="true">→</span>
            </button>
        </div>
    </section>
</template>

<style scoped>
.book-preview {
    border: 1px solid rgb(176 230 205 / 75%);
    border-radius: 1.25rem;
    background: rgb(255 255 255 / 96%);
    padding: 0.6rem;
    box-shadow: 0 18px 40px rgb(42 42 42 / 18%);
    backdrop-filter: blur(8px);
}

.book-spread {
    position: relative;
    display: flex;
    height: clamp(9.5rem, 38vw, 13rem);
    perspective: 1200px;
    filter: drop-shadow(0 8px 9px rgb(42 42 42 / 13%));
}

.book-page {
    position: absolute;
    top: 0.35rem;
    bottom: 0.35rem;
    width: 50%;
    overflow: hidden;
    background: #fffefa;
}

.book-page--left {
    left: 0;
    border-radius: 0.75rem 0 0 0.75rem;
    box-shadow: inset -8px 0 12px -12px #2a2a2a;
}

.book-page--right {
    right: 0;
    border-radius: 0 0.75rem 0.75rem 0;
    box-shadow: inset 8px 0 12px -12px #2a2a2a;
}

.book-leaf {
    position: absolute;
    z-index: 2;
    top: 0.35rem;
    right: 0;
    bottom: 0.35rem;
    width: 50%;
    transform-style: preserve-3d;
    transform-origin: left center;
}

.book-leaf--turning {
    animation: turn-page 1.15s cubic-bezier(0.45, 0.05, 0.25, 1) forwards;
}

.book-leaf__face {
    position: absolute;
    inset: 0;
    overflow: hidden;
    backface-visibility: hidden;
    background: #fffefa;
}

.book-leaf__front {
    border-radius: 0 0.75rem 0.75rem 0;
    box-shadow: inset 8px 0 12px -12px #2a2a2a;
}

.book-leaf__back {
    transform: rotateY(180deg);
    border-radius: 0.75rem 0 0 0.75rem;
    box-shadow: inset -8px 0 12px -12px #2a2a2a;
}

.book-spine {
    position: absolute;
    z-index: 3;
    top: 0.35rem;
    bottom: 0.35rem;
    left: 50%;
    width: 0.75rem;
    transform: translateX(-50%);
    background: linear-gradient(90deg, rgb(42 42 42 / 9%), rgb(255 255 255 / 35%), rgb(42 42 42 / 8%));
    pointer-events: none;
}

.book-light {
    position: absolute;
    z-index: 4;
    inset: 0;
    border-radius: 0.75rem;
    background: linear-gradient(110deg, transparent 25%, rgb(255 255 255 / 75%) 48%, transparent 70%);
    animation: light-sweep 1.15s ease-out forwards;
    pointer-events: none;
}

.book-preview__controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.45rem 0.15rem 0.05rem;
}

.book-preview__title {
    color: #2a2a2a;
    font-size: 0.7rem;
    font-weight: 800;
}

.book-preview__status {
    color: #737373;
    font-size: 0.58rem;
}

.book-preview__button {
    flex: none;
    border-radius: 9999px;
    background: #00b050;
    padding: 0.45rem 0.8rem;
    color: white;
    font-size: 0.68rem;
    font-weight: 700;
    transition: background-color 150ms ease;
}

.book-preview__button:hover {
    background: #008c40;
}

.book-preview__button:disabled {
    cursor: wait;
    opacity: 0.65;
}

.book-preview__button:focus-visible {
    outline: 3px solid #37b8ba;
    outline-offset: 3px;
}

@keyframes turn-page {
    from {
        transform: rotateY(0);
    }

    to {
        transform: rotateY(-180deg);
    }
}

@keyframes light-sweep {
    from {
        opacity: 0;
        transform: translateX(-18%);
    }

    45% {
        opacity: 0.7;
    }

    to {
        opacity: 0;
        transform: translateX(18%);
    }
}

@media (prefers-reduced-motion: reduce) {
    .book-leaf--turning,
    .book-light {
        animation: none;
    }

    .book-preview__button {
        transition: none;
    }
}
</style>
