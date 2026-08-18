<script setup>
import AppLayout from '../../layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import InputError from '../../components/InputError.vue';

const statuses = [
    { value: 'todo', label: 'To Do' },
    { value: 'in_progress', label: 'In Progress' },
    { value: 'completed', label: 'Completed' },
];

const priorities = [
    { value: 'low', label: 'Low' },
    { value: 'medium', label: 'Medium' },
    { value: 'high', label: 'High' },
];

const props = defineProps({
    categories: Array,
});

const form = useForm({
    category_id: props.categories[0]?.id ?? '',
    title: '',
    description: '',
    status: 'todo',
    priority: 'medium',
    due_date: '',
});

const submit = () => {
    form.post(route('tasks.store'));
};
</script>

<template>
    <AppLayout>
        <Head title="Create Task" />
        <div class="mb-4">
            <form @submit.prevent="submit">
                <!-- Category -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700"
                        >Category</label
                    >
                    <select
                        v-model="form.category_id"
                        class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                    >
                        <option value="" disabled>Select a category</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <InputError
                        :message="form.errors.category_id"
                        class="mt-1"
                    />
                </div>

                <!-- Title -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700"
                        >Title</label
                    >
                    <input
                        v-model="form.title"
                        type="text"
                        class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                    />
                    <InputError :message="form.errors.title" class="mt-1" />
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700"
                        >Description</label
                    >
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                    />
                    <InputError
                        :message="form.errors.description"
                        class="mt-1"
                    />
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700"
                        >Status</label
                    >
                    <select
                        v-model="form.status"
                        class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                    >
                        <option
                            v-for="s in statuses"
                            :key="s.value"
                            :value="s.value"
                        >
                            {{ s.label }}
                        </option>
                    </select>
                    <InputError :message="form.errors.status" class="mt-1" />
                </div>

                <!-- Priority -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700"
                        >Priority</label
                    >
                    <select
                        v-model="form.priority"
                        class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                    >
                        <option
                            v-for="p in priorities"
                            :key="p.value"
                            :value="p.value"
                        >
                            {{ p.label }}
                        </option>
                    </select>
                    <InputError :message="form.errors.priority" class="mt-1" />
                </div>

                <!-- Due Date -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700"
                        >Due Date</label
                    >
                    <input
                        v-model="form.due_date"
                        type="date"
                        min="today"
                        max="2099-12-31"
                        class="mt-1 w-full rounded border border-gray-300 px-3 py-2"
                    />
                    <InputError :message="form.errors.due_date" class="mt-1" />
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50"
                >
                    {{ form.processing ? 'Creating...' : 'Create Task' }}
                </button>
            </form>
        </div>
    </AppLayout>
</template>
