<script setup>
import AppLayout from '../../layouts/AppLayout.vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
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
    task: Object,
});

// prefilled form
const form = useForm({
    category_id: props.task.category_id,
    title: props.task.title,
    description: props.task.description,
    status: props.task.status,
    priority: props.task.priority,
    due_date: props.task.due_date,
});

const submit = () => {
    form.put(route('tasks.update', props.task.id));
};

const destroy = () => {
    if (confirm('Delete this task?. This cannot be undone.')) {
        form.delete(route('tasks.destroy', props.task.id));
    }
};
</script>

<template>
    <AppLayout>
        <Head :title="`Task: ${task.title}`" />

        <div>
            <form @submit.prevent="submit">
                <div>
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
                            v-for="status in statuses"
                            :key="status.value"
                            :value="status.value"
                        >
                            {{ status.label }}
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
                            v-for="priority in priorities"
                            :key="priority.value"
                            :value="priority.value"
                        >
                            {{ priority.label }}
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
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>

                <button
                    type="button"
                    @click="destroy"
                    class="rounded bg-red-600 px-4 py-2 text-white"
                >
                    Delete
                </button>
                <Link
                    :href="route('tasks.index')"
                    class="text-sm text-gray-600 hover:underline"
                >
                    Back to list
                </Link>
            </form>
        </div>
    </AppLayout>
</template>
