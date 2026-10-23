<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    orders: Array,
});
</script>

<template>
    <Head title="Le mie risorse" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Le mie risorse</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p v-if="orders.length === 0" class="text-gray-600">
                            Non hai ancora acquistato risorse.
                            <Link :href="route('home')" class="font-semibold text-teal-700 hover:text-teal-900">Vai al catalogo</Link>
                        </p>
                        <ul v-else class="divide-y divide-gray-100">
                            <li v-for="order in orders" :key="order.id" class="flex items-center justify-between gap-4 py-4 first:pt-0">
                                <div>
                                    <p class="font-semibold text-teal-950">{{ order.title }}</p>
                                    <p class="mt-1 text-sm text-gray-500">Acquistato il {{ order.purchased_at }}</p>
                                </div>
                                <a :href="order.download_url" class="rounded-full bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">Scarica PDF</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
