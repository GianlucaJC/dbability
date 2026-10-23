<template>
    <svg class="dbability-logo" viewBox="0 0 330 96" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="DBability">
        <text class="logo-letter logo-d" x="0" y="76" fill="#3A9ECB" font-family="Arial, sans-serif" font-size="78" font-weight="700">D</text>
        <text class="logo-letter logo-b" x="49" y="76" fill="#778363" font-family="Arial, sans-serif" font-size="78" font-weight="700">B</text>
        <text x="106" y="72" fill="#778363" font-family="Trebuchet MS, Arial Rounded MT Bold, sans-serif" font-size="45" font-weight="700" font-style="italic" letter-spacing="-2">ability</text>
        <path d="M111 80c45 7 126 7 205-2" fill="none" stroke="#CAE8CA" stroke-linecap="round" stroke-width="5" />

        <g class="logo-person person-one">
            <circle cx="27" cy="15" r="10" fill="#3A9ECB" stroke="#FFFFFF" stroke-width="3" />
            <path d="M27 30 10 63M27 30l18 33" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-width="11" />
            <path d="M27 30 10 63M27 30l18 33" fill="none" stroke="#3A9ECB" stroke-linecap="round" stroke-width="6" />
            <circle r="7" fill="#3A9ECB" stroke="#FFFFFF" stroke-width="2">
                <animateMotion dur="3.6s" repeatCount="indefinite" path="M10 63 L27 30 L27 15" keyPoints="0;0.62;1;1" keyTimes="0;0.32;0.48;1" calcMode="linear" />
            </circle>
        </g>

        <g class="logo-person person-two">
            <circle cx="82" cy="13" r="10" fill="#7FAB74" stroke="#FFFFFF" stroke-width="3" />
            <path d="M82 28 64 63M82 28l18 35" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-width="11" />
            <path d="M82 28 64 63M82 28l18 35" fill="none" stroke="#778363" stroke-linecap="round" stroke-width="6" />
            <circle r="7" fill="#7FAB74" stroke="#FFFFFF" stroke-width="2">
                <animateMotion begin="700ms" dur="3.6s" repeatCount="indefinite" path="M64 63 L82 28 L82 13" keyPoints="0;0.62;1;1" keyTimes="0;0.32;0.48;1" calcMode="linear" />
            </circle>
        </g>
    </svg>
</template>
