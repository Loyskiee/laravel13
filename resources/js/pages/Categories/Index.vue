<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';

defineProps({
categories: Object // this must always match whatever is in the controller which is 'categories' => $categories
})

</script>

<template>
    <AppLayout>
          <Head title="Categories"/>
    
        <div class="mb-4 flex items-center justify-between">
            <h1>Categories</h1>
            <Link
            :href="route('categories.create')"
            class="rounded bg-blue-600 px-4 py-2 text-white"
            >
            New Category
            </Link>
        </div>
        <ul class="space-y-2">
            <li v-for="category in categories.data" :key="category.id">
                <span
                    class="inline-block h-3 w-3 rounded-full"
                    :style="{ backgroundColor: category.color }"
                >  
                </span>
                <Link 
                :href="route('categories.show', category.id)"
                class="text-blue-600 hover:underline"
                >
                    {{ category.name }}
                </Link>
            </li>
        </ul> 
    </AppLayout>
</template>