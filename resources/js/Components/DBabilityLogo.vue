<template>
    <svg class="dbability-logo" viewBox="0 0 330 96" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="DBability">
        <text class="logo-letter logo-d" x="0" y="76" fill="#3A9ECB" font-family="Arial, sans-serif" font-size="78" font-weight="700">D</text>
        <text class="logo-letter logo-b" x="49" y="76" fill="#778363" font-family="Arial, sans-serif" font-size="78" font-weight="700">B</text>
        <text x="106" y="72" fill="#778363" font-family="Trebuchet MS, Arial Rounded MT Bold, sans-serif" font-size="45" font-weight="700" font-style="italic" letter-spacing="-2">ability</text>
        <path d="M111 80c45 7 126 7 205-2" fill="none" stroke="#CAE8CA" stroke-linecap="round" stroke-width="5" />

        <g class="logo-person person-one">
            <circle class="logo-head head-one" cx="27" cy="15" r="10" fill="#3A9ECB" stroke="#FFFFFF" stroke-width="3" />
            <path d="M27 30 10 63M27 30l18 33" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-width="11" />
            <path d="M27 30 10 63M27 30l18 33" fill="none" stroke="#3A9ECB" stroke-linecap="round" stroke-width="6" />
        </g>

        <g class="logo-person person-two">
            <circle class="logo-head head-two" cx="82" cy="13" r="10" fill="#7FAB74" stroke="#FFFFFF" stroke-width="3" />
            <path d="M82 28 64 63M82 28l18 35" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-width="11" />
            <path d="M82 28 64 63M82 28l18 35" fill="none" stroke="#778363" stroke-linecap="round" stroke-width="6" />
        </g>
    </svg>
</template>

<style scoped>
@media (prefers-reduced-motion: no-preference) {
    .dbability-logo .logo-person {
        transform-box: fill-box;
        transform-origin: center;
    }

    .dbability-logo .logo-head {
        transform-box: fill-box;
        transform-origin: center;
        animation: head-arrives 3.2s ease-in-out infinite;
    }

    .dbability-logo .head-two {
        animation-delay: 380ms;
    }

    .dbability-logo .logo-person {
        animation: child-celebrates 3.2s ease-in-out infinite;
    }
}

@keyframes head-arrives {
    0%,
    12% {
        opacity: 0;
        transform: translateY(30px) scale(.7);
    }

    22% {
        opacity: 1;
    }

    48%,
    72% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    86%,
    100% {
        opacity: 0;
        transform: translateY(30px) scale(.7);
    }
}

@keyframes child-celebrates {
    0%,
    20%,
    76%,
    100% {
        transform: translateY(0) rotate(0);
    }

    40%,
    60% {
        transform: translateY(-3px) rotate(-3deg);
    }
}
</style>
