<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import InputError from '@/components/InputError.vue';


const form = useForm({
    name: ''
});

const submit = () => {
    form.post(route('categories.store'));
}
</script>

<template>
        <Head title="Create Category" />

        <div class="mx-auto max-w-md p-4">
            <h1 class="mb-4 text-xl font-semibold">Create Category</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text" class="w-full rounded border border-gray-300 px-3 py-2" />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" :disabled="form.processing" class="rounded bg-secondary px-4 py-2 text-secondary-foreground hover:bg-secondary/90 transition-colors disabled:opacity-50">
                        {{ form.processing ? 'Creating...' : 'Create Category' }}
                    </button>

                    <Link :href="route('categories.index')" class="text-sm text-gray-600 hover:underline">Go back</Link>
                </div>
            </form>
        </div>
</template>