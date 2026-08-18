<script setup>
import AppLayout from '../../layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import InputError from '../../components/InputError.vue';

const colors = [
    '#ef4444',
    '#f97316',
    '#f59e0b',
    '#22c55e',
    '#06b6d4',
    '#3b82f6',
    '#8b5cf6',
    '#ec4899',
];

const form = useForm({
    name: '',
    color: colors[0]
});

const submit = () => {
    form.post(route('categories.store'));
}
</script>

<template>
    <AppLayout>
           <div class="mb-4">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input
                         v-model="form.name"
                         type="text"
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                        >
                       <InputError :message="form.errors.name"  class="mt-1"/>
                    </div>

                 <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Color</label>
                    <div class="mt-2 flex gap-3">
                        <button
                            v-for="color in colors"
                            :key="color"
                            type="button"
                            class="h-8 w-8 rounded-full border border-gray-300"
                            :style="{ backgroundColor: color }"
                            :class="{ 'ring-2 ring-gray-900 ring-offset-2': form.color === color }"
                            @click="form.color = color"
                            :aria-label="`Select color ${color}`"
                        ></button>
                    </div>
                    <InputError :message="form.errors.color"  class="mt-1"/>
                 </div>

                 <button
                 type="submit"
                 :disabled="form.processing"
                 class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
                 >
                     {{ form.processing ? 'Creating...' : 'Create Category' }}
                 </button>

                    <Link :href="route('categories.index')" class="text-sm text-gray-600 hover:underline">
                        Go back
                    </Link>
                </form>
           </div>
    </AppLayout>
</template>