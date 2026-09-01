<script setup lang="ts">    
import { Link } from '@inertiajs/vue3';
import {route} from 'ziggy-js';

defineProps<{
    category: { id: number; name: string; description?: string | null; products_count?: number };
}>();
</script>

<template>
    <Link :href="route('categories.show', category.id)" class="flex flex-col rounded-xl border p-4 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <h3 class="font-medium">{{ category.name }}</h3>
        <p v-if="category.description" class="mt-1 line-clamp-2 text-sm text-gray-500">{{ category.description }}</p>
        <div v-if="category.products_count !== undefined" class="mt-2 text-xs text-gray-500">{{ category.products_count }} products</div>
    </Link>
</template>
