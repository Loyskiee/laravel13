<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import InputError from '@/components/InputError.vue';


const props = defineProps({
    category: Object,
});

// prefilled prop, that came through the category
const form = useForm({
    name: props.category.name
});

const submit = () => {
    form.put(route('categories.update', props.category.id));
}

const destroy = () => {
    if(confirm('Delete this category? This cannot be undone.')) {
        form.delete(route('categories.destroy', props.category.id));
    }
}

</script>

<template>
    <AppLayout>
        <Head :title="`Category: ${category.name}`" />

        <div class="mx-auto max-w-md p-4">
            <h1 class="mb-4 text-xl font-semibold">Edit Category</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text" class="w-full rounded border border-gray-300 px-3 py-2" />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" :disabled="form.processing" class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>

                    <button type="button" @click="destroy" class="rounded bg-red-600 px-4 py-2 text-white">Delete</button>

                    <Link :href="route('categories.index')" class="text-sm text-gray-600 hover:underline">Back to list</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>