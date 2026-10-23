<script setup>
defineProps({
    kind: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <svg v-if="kind === 'hero'" viewBox="0 0 560 400" class="h-full w-full" role="img" aria-label="Percorso colorato di attività per bambini">
        <rect width="560" height="400" rx="42" fill="#E0F2FE" />
        <circle cx="86" cy="82" r="34" fill="#FDE68A" />
        <path d="M34 300C112 224 140 360 218 286S319 192 388 259s65 1 140-59" fill="none" stroke="#F59E0B" stroke-linecap="round" stroke-width="15" stroke-dasharray="1 28" />
        <path d="M46 310C117 236 146 362 225 289S327 198 391 265s70 2 142-55" fill="none" stroke="#7C3AED" stroke-linecap="round" stroke-width="6" />
        <path d="M200 112c20-31 51-28 67 0 16 27 2 49-17 59-20 11-28 27-17 51" fill="none" stroke="#0F766E" stroke-linecap="round" stroke-width="16" />
        <circle cx="187" cy="82" r="18" fill="#FB7185" />
        <path d="M344 69l23 18-11 29-30 1-13-26z" fill="#A7F3D0" />
        <path d="M448 119l10 20 23 3-17 16 4 23-20-11-21 11 5-23-17-16 23-3z" fill="#F9A8D4" />
        <circle cx="461" cy="275" r="26" fill="#C4B5FD" />
        <circle cx="76" cy="220" r="16" fill="#67E8F9" />
    </svg>

    <svg v-else-if="kind === 'movement'" viewBox="0 0 360 230" class="h-full w-full" role="img" aria-label="Illustrazione di un percorso di movimento">
        <rect width="360" height="230" rx="26" fill="#DBEAFE" />
        <circle cx="70" cy="54" r="28" fill="#FDE68A" />
        <path d="M40 181c37-67 75 31 115-27s66-65 94-11 48 26 82-42" fill="none" stroke="#FB7185" stroke-linecap="round" stroke-width="12" stroke-dasharray="1 22" />
        <path d="M44 181c37-67 75 31 115-27s66-65 94-11 48 26 82-42" fill="none" stroke="#2563EB" stroke-linecap="round" stroke-width="4" />
        <circle cx="55" cy="180" r="13" fill="#2563EB" />
        <path d="M246 55l17 14-8 20-22 1-10-19z" fill="#A7F3D0" />
        <path d="M150 55l12 12-12 12-12-12z" fill="#C4B5FD" />
    </svg>

    <svg v-else-if="kind === 'pregrafismo'" viewBox="0 0 360 230" class="h-full w-full" role="img" aria-label="Illustrazione di tracciati e matite colorate">
        <rect width="360" height="230" rx="26" fill="#F3E8FF" />
        <path d="M54 43v144M88 43v144" stroke="#7C3AED" stroke-linecap="round" stroke-width="7" />
        <path d="M126 55l42 28-42 30 42 30-42 30 42 30" fill="none" stroke="#A855F7" stroke-linecap="round" stroke-width="7" />
        <path d="M232 52c60 24-25 51 27 76s-32 56 28 78" fill="none" stroke="#F59E0B" stroke-linecap="round" stroke-width="8" stroke-dasharray="1 18" />
        <path d="M302 39l16 16-42 43-17-17z" fill="#FB7185" />
        <path d="M276 98l-7 18 17-7z" fill="#FDE68A" />
        <circle cx="54" cy="36" r="9" fill="#7C3AED" />
        <circle cx="232" cy="46" r="9" fill="#F59E0B" />
    </svg>

    <svg v-else viewBox="0 0 360 230" class="h-full w-full" role="img" aria-label="Illustrazione di una routine quotidiana">
        <rect width="360" height="230" rx="26" fill="#DCFCE7" />
        <rect x="80" y="34" width="152" height="164" rx="18" fill="#FFFFFF" />
        <rect x="105" y="64" width="18" height="18" rx="5" fill="#FDE68A" />
        <path d="m109 73 5 5 11-13" fill="none" stroke="#0F766E" stroke-linecap="round" stroke-width="4" />
        <path d="M140 73h60M105 111h95M105 145h70" stroke="#86EFAC" stroke-linecap="round" stroke-width="9" />
        <circle cx="276" cy="72" r="22" fill="#F9A8D4" />
        <path d="m269 72 6 6 13-16" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-width="5" />
        <path d="M45 161l14 11-6 17-18 1-8-16z" fill="#67E8F9" />
    </svg>
</template>
