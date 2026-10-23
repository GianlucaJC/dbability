<script setup>
defineProps({
    kind: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <svg v-if="kind === 'hero'" viewBox="0 0 560 400" class="activity-art h-full w-full" role="img" aria-label="Percorso colorato di attività per bambini">
        <rect width="560" height="400" rx="42" fill="#CAE8CA" />
        <circle cx="86" cy="82" r="34" fill="#B4CB8D" />
        <path class="trail" d="M34 300C112 224 140 360 218 286S319 192 388 259s65 1 140-59" fill="none" stroke="#3A9ECB" stroke-linecap="round" stroke-width="15" stroke-dasharray="1 28" />
        <path d="M46 310C117 236 146 362 225 289S327 198 391 265s70 2 142-55" fill="none" stroke="#3A9ECB" stroke-linecap="round" stroke-width="6" />
        <path d="M200 112c20-31 51-28 67 0 16 27 2 49-17 59-20 11-28 27-17 51" fill="none" stroke="#778363" stroke-linecap="round" stroke-width="16" />
        <circle class="dot" cx="187" cy="82" r="18" fill="#7FAB74" />
        <path class="shape" d="M344 69l23 18-11 29-30 1-13-26z" fill="#FFFFFF" />
        <path class="shape" d="M448 119l10 20 23 3-17 16 4 23-20-11-21 11 5-23-17-16 23-3z" fill="#91A86B" />
        <circle class="dot" cx="461" cy="275" r="26" fill="#B4CB8D" />
        <circle class="dot" cx="76" cy="220" r="16" fill="#3A9ECB" />
    </svg>

    <svg v-else-if="kind === 'movement'" viewBox="0 0 360 230" class="activity-art h-full w-full" role="img" aria-label="Illustrazione di un percorso di movimento">
        <rect width="360" height="230" rx="26" fill="#CAE8CA" />
        <circle cx="70" cy="54" r="28" fill="#B4CB8D" />
        <path class="trail" d="M40 181c37-67 75 31 115-27s66-65 94-11 48 26 82-42" fill="none" stroke="#3A9ECB" stroke-linecap="round" stroke-width="12" stroke-dasharray="1 22" />
        <path d="M44 181c37-67 75 31 115-27s66-65 94-11 48 26 82-42" fill="none" stroke="#3A9ECB" stroke-linecap="round" stroke-width="4" />
        <circle class="dot" cx="55" cy="180" r="13" fill="#3A9ECB" />
        <path class="shape" d="M246 55l17 14-8 20-22 1-10-19z" fill="#FFFFFF" />
        <path class="shape" d="M150 55l12 12-12 12-12-12z" fill="#7FAB74" />
    </svg>

    <svg v-else-if="kind === 'pregrafismo'" viewBox="0 0 360 230" class="activity-art h-full w-full" role="img" aria-label="Illustrazione di tracciati e matite colorate">
        <rect width="360" height="230" rx="26" fill="#FFFFFF" />
        <path d="M54 43v144M88 43v144" stroke="#3A9ECB" stroke-linecap="round" stroke-width="7" />
        <path d="M126 55l42 28-42 30 42 30-42 30 42 30" fill="none" stroke="#3A9ECB" stroke-linecap="round" stroke-width="7" />
        <path class="trail" d="M232 52c60 24-25 51 27 76s-32 56 28 78" fill="none" stroke="#778363" stroke-linecap="round" stroke-width="8" stroke-dasharray="1 18" />
        <path class="shape" d="M302 39l16 16-42 43-17-17z" fill="#7FAB74" />
        <path class="shape" d="M276 98l-7 18 17-7z" fill="#B4CB8D" />
        <circle class="dot" cx="54" cy="36" r="9" fill="#3A9ECB" />
        <circle class="dot" cx="232" cy="46" r="9" fill="#778363" />
    </svg>

    <svg v-else viewBox="0 0 360 230" class="activity-art h-full w-full" role="img" aria-label="Illustrazione di una routine quotidiana">
        <rect width="360" height="230" rx="26" fill="#CAE8CA" />
        <rect x="80" y="34" width="152" height="164" rx="18" fill="#FFFFFF" />
        <rect x="105" y="64" width="18" height="18" rx="5" fill="#B4CB8D" />
        <path d="m109 73 5 5 11-13" fill="none" stroke="#778363" stroke-linecap="round" stroke-width="4" />
        <path d="M140 73h60M105 111h95M105 145h70" stroke="#7FAB74" stroke-linecap="round" stroke-width="9" />
        <circle class="dot" cx="276" cy="72" r="22" fill="#3A9ECB" />
        <path d="m269 72 6 6 13-16" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-width="5" />
        <path class="shape" d="M45 161l14 11-6 17-18 1-8-16z" fill="#91A86B" />
    </svg>
</template>

<style scoped>
@media (prefers-reduced-motion: no-preference) {
    .activity-art .trail,
    .activity-art .dot,
    .activity-art .shape {
        transform-box: fill-box;
        transform-origin: center;
    }

    .activity-art:hover .trail {
        animation: draw-path 1.25s linear infinite;
    }

    .activity-art:hover .dot {
        animation: float-dot 1.1s ease-in-out infinite alternate;
    }

    .activity-art:hover .shape {
        animation: turn-shape 1.5s ease-in-out infinite alternate;
    }
}

@keyframes draw-path {
    to {
        stroke-dashoffset: -46;
    }
}

@keyframes float-dot {
    to {
        transform: translateY(-7px) scale(1.08);
    }
}

@keyframes turn-shape {
    to {
        transform: rotate(10deg) scale(1.08);
    }
}
</style>
