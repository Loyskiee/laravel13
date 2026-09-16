<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    inventory: Object,
});
</script>

<template>
    <AppLayout>
        <Head :title="`Movement #${inventory.id}`" />

        <div class="mx-auto max-w-2xl p-4">
            <h1 class="text-xl font-semibold">Movement {{ inventory.id }} — {{ inventory.type }}</h1>

            <div class="mt-4 space-y-2 rounded border p-4 text-sm">
                <p><span class="font-medium">Product:</span> <Link :href="route('products.show', inventory.product.id)" class="text-blue-600 hover:underline">{{ inventory.product.name }}</Link> ({{ inventory.product.sku }})</p>
                <p><span class="font-medium">Quantity:</span> {{ inventory.quantity }} ({{ inventory.before_quantity }} → {{ inventory.after_quantity }})</p>
                <p><span class="font-medium">Reason:</span> {{ inventory.reason }}</p>
                <p><span class="font-medium">User:</span> {{ inventory.user.name }}</p>
                <p><span class="font-medium">Date:</span> {{ inventory.created_at }}</p>
            </div>

            <Link :href="route('inventories.index')" class="mt-4 inline-block text-sm text-gray-600 hover:underline">Back to history</Link>
        </div>
    </AppLayout>
</template>
