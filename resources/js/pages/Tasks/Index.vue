<script setup>
import AppLayout from '../../layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import CategoryCard from '../../components/CategoryCard.vue';
import Pagination from '../../components/Pagination.vue';

defineProps({
    categories: Object,
});
</script>

<template>
    <AppLayout>
        <Head title="Categories" />

        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Categories</h1>
            <Link
                :href="route('categories.create')"
                class="rounded bg-blue-600 px-4 py-2 text-white"
            >
                New Category
            </Link>
        </div>

        <div v-if="categories.data.length" class="space-y-3">
            <CategoryCard
                v-for="category in categories.data"
                :key="category.id"
                :category="category"
            />
        </div>
        <p v-else class="mt-6 text-sm text-gray-500">No categories found.</p>

        <Pagination :links="categories.links" />
    </AppLayout>
</template>
