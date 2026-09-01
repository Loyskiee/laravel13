<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import CategoryCard from '@/components/CategoryCard.vue';
import Pagination from '@/components/Pagination.vue';

defineProps({
    categories: Object,
});
</script>

<template>
    <AppLayout>
        <Head title="Categories" />

        <div class="p-4">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold">Categories</h1>
                <Link :href="route('categories.create')" class="rounded bg-blue-600 px-4 py-2 text-white">New Category</Link>
            </div>

            <div v-if="categories.data.length === 0" class="rounded border p-8 text-center text-gray-500">No categories yet.</div>

            <div v-else>
                <div class="grid gap-4 md:grid-cols-3">
                    <CategoryCard v-for="category in categories.data" :key="category.id" :category="category" />
                </div>

                <Pagination :links="categories.links" class="mt-4" />
            </div>
        </div>
    </AppLayout>
</template>