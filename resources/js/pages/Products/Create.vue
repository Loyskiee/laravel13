<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';

defineProps({
    categories: Array,
});

const units = [
    'pcs',
    'kg', 
    'g', 
    'l', 
    'ml', 
    'box', 
    'pack', 
    'm'
];

const form = useForm({
    name: '',
    category_id: '', 
    description: '', 
    quantity: 0, 
    minimum_stock: 0, 
    unit: 'pcs', 
    image: '' 
});

const submit = () => form.post(route('products.store'));

</script>

<template>
        <Head title="Create Product" />

        <div class="mx-auto max-w-2xl p-4">
            <h1 class="mb-4 text-xl font-semibold">Create Product</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium">Name</label>
                    <input id="name" v-model="form.name" class="mt-1 w-full rounded border px-3 py-2" required />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-4 md:grid-cols-2">

                    <div>
                        <label class="block text-sm">Category</label>
                        <select v-model="form.category_id" class="mt-1 w-full rounded border px-3 py-2" required>
                            <option value="">Select</option>
                            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                        <InputError :message="form.errors.category_id" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm">Description</label>
                    <textarea v-model="form.description" class="mt-1 w-full rounded border px-3 py-2" />
                    <InputError :message="form.errors.description" />
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="block text-sm">Quantity</label>
                        <input type="number" v-model.number="form.quantity" min="0" class="mt-1 w-full rounded border px-3 py-2" />
                        <InputError :message="form.errors.quantity" />
                    </div>

                    <div>
                        <label class="block text-sm">Minimum stock</label>
                        <input type="number" v-model.number="form.minimum_stock" min="0" class="mt-1 w-full rounded border px-3 py-2" />
                        <InputError :message="form.errors.minimum_stock" />
                    </div>

                    <div>
                        <label class="block text-sm">Unit</label>
                        <select v-model="form.unit" class="mt-1 w-full rounded border px-3 py-2">
                            <option v-for="u in units" :key="u" :value="u">{{ u }}</option>
                        </select>
                        <InputError :message="form.errors.unit" />
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" :disabled="form.processing" class="rounded bg-secondary px-4 py-2 text-secondary-foreground hover:bg-secondary/90 transition-colors disabled:opacity-50">
                        {{ form.processing ? 'Creating...' : 'Create' }}
                    </button>

                    <Link :href="route('products.index')" class="px-4 py-2 text-gray-600">Back</Link>
                </div>
            </form>
        </div>
</template>
