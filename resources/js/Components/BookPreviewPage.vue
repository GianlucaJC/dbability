<script setup>
defineProps({
    page: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <article class="preview-page" :class="`preview-page--${page.kind}`">
        <p class="preview-page__eyebrow">{{ page.eyebrow }}</p>
        <h3>{{ page.title }}</h3>
        <p v-if="page.description" class="preview-page__description">{{ page.description }}</p>

        <svg v-if="page.kind === 'cover'" class="preview-page__art" viewBox="0 0 160 90" aria-hidden="true">
            <path d="M12 64c22-37 34 31 58-3s33-35 47-5 24 11 34-17" fill="none" stroke="#01d1d6" stroke-linecap="round" stroke-width="6" stroke-dasharray="1 13" />
            <circle cx="30" cy="27" r="10" fill="#ea5494" />
            <path d="m113 18 7 12 14 2-10 10 2 14-13-7-12 7 2-14-10-10 14-2z" fill="#f7c7da" />
        </svg>
        <svg v-else-if="page.kind === 'curves'" class="preview-page__art" viewBox="0 0 160 90" aria-hidden="true">
            <path d="M12 22c26 0 26 46 52 46s26-46 52-46 26 46 42 46" fill="none" stroke="#01d1d6" stroke-linecap="round" stroke-width="5" />
            <circle cx="13" cy="22" r="5" fill="#ea5494" />
            <circle cx="147" cy="68" r="5" fill="#ea5494" />
        </svg>
        <svg v-else-if="page.kind === 'zigzag'" class="preview-page__art" viewBox="0 0 160 90" aria-hidden="true">
            <path d="M12 22h28l18 23-18 23H12m54-46h28l18 23-18 23H66m54-46h28" fill="none" stroke="#ea5494" stroke-linecap="round" stroke-linejoin="round" stroke-width="5" />
            <circle cx="12" cy="22" r="5" fill="#01d1d6" />
        </svg>
        <svg v-else-if="page.kind === 'dots'" class="preview-page__art" viewBox="0 0 160 90" aria-hidden="true">
            <path d="M13 68c27 0 22-46 48-46s22 46 48 46 23-46 38-46" fill="none" stroke="#01d1d6" stroke-linecap="round" stroke-width="5" stroke-dasharray="1 11" />
            <circle cx="13" cy="68" r="6" fill="#ea5494" />
            <path d="m130 18 7 12 14 2-10 10 2 14-13-7-12 7 2-14-10-10 14-2z" fill="#ea5494" />
        </svg>
        <svg v-else class="preview-page__art" viewBox="0 0 160 90" aria-hidden="true">
            <circle cx="37" cy="45" r="23" fill="none" stroke="#01d1d6" stroke-width="5" />
            <rect x="73" y="22" width="46" height="46" rx="8" fill="none" stroke="#ea5494" stroke-width="5" />
            <path d="m137 20 24 48h-48z" fill="none" stroke="#ea5494" stroke-linejoin="round" stroke-width="5" />
        </svg>

        <span class="preview-page__number">{{ page.number }}</span>
    </article>
</template>

<style scoped>
.preview-page {
    position: relative;
    display: flex;
    height: 100%;
    flex-direction: column;
    overflow: hidden;
    padding: 0.7rem 0.65rem 0.9rem;
    background: #fffefa;
    color: #2a2a2a;
}

.preview-page--cover {
    background: linear-gradient(145deg, #f7fdfe, #fff5f9 66%);
}

.preview-page__eyebrow {
    color: #ea5494;
    font-size: 0.48rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    line-height: 1.3;
    text-transform: uppercase;
}

h3 {
    margin-top: 0.38rem;
    font-size: clamp(0.65rem, 2.2vw, 0.82rem);
    font-weight: 800;
    line-height: 1.2;
}

.preview-page--cover h3 {
    color: #ea5494;
    font-size: clamp(0.78rem, 2.6vw, 1rem);
}

.preview-page__description {
    margin-top: 0.35rem;
    color: #737373;
    font-size: 0.55rem;
    line-height: 1.35;
}

.preview-page__art {
    width: 100%;
    height: auto;
    max-height: 4.25rem;
    margin-top: auto;
}

.preview-page__number {
    position: absolute;
    right: 0.65rem;
    bottom: 0.35rem;
    color: #8d9991;
    font-size: 0.5rem;
}
</style>
