<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import StockBadge from '@/components/StockBadge.vue';

const props = defineProps({
    product: Object,
    categories: Array,
});

const units = ['pcs', 'kg', 'g', 'l', 'ml', 'box', 'pack', 'm'];

const form = useForm({
    name: props.product.name,
    sku: props.product.sku,
    category_id: props.product.category_id ?? '',
    description: props.product.description ?? '',
    minimum_stock: props.product.minimum_stock,
    unit: props.product.unit,
    image: props.product.image ?? '',
});

const submit = () => {
    form.put(route('products.update', props.product.id));
};

const destroy = () => {
    if (confirm('Delete this product? This cannot be undone.')) {
        form.delete(route('products.destroy', props.product.id));
    }
};
</script>

<template>
    <AppLayout>
        <Head :title="`Product: ${product.name}`" />

        <div class="mx-auto max-w-2xl p-4">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold">{{ product.name }}</h1>
                <StockBadge :quantity="product.quantity" :minimum_stock="product.minimum_stock" />
            </div>

            <div class="mb-4 rounded border p-3 text-sm text-gray-600">
                <p><span class="font-medium">Quantity:</span> {{ product.quantity }} {{ product.unit }}</p>
                <p v-if="form.errors.product" class="mt-1 text-red-600">{{ form.errors.product }}</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="form.name" type="text" class="w-full rounded border border-gray-300 px-3 py-2" />
                    <InputError :message="form.errors.name" class="mt-1" />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">SKU</label>
                        <input v-model="form.sku" type="text" class="w-full rounded border border-gray-300 px-3 py-2" />
                        <InputError :message="form.errors.sku" class="mt-1" />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Category</label>
                        <select v-model="form.category_id" class="w-full rounded border border-gray-300 px-3 py-2">
                            <option value="">No category</option>
                            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                        <InputError :message="form.errors.category_id" class="mt-1" />
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.description" class="w-full rounded border border-gray-300 px-3 py-2" />
                    <InputError :message="form.errors.description" class="mt-1" />
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Minimum stock</label>
                        <input v-model.number="form.minimum_stock" type="number" min="0" class="w-full rounded border border-gray-300 px-3 py-2" />
                        <InputError :message="form.errors.minimum_stock" class="mt-1" />
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Unit</label>
                        <select v-model="form.unit" class="w-full rounded border border-gray-300 px-3 py-2">
                            <option v-for="u in units" :key="u" :value="u">{{ u }}</option>
                        </select>
                        <InputError :message="form.errors.unit" class="mt-1" />
                    </div>
                </div>

                <p class="text-xs text-gray-500">Quantity is managed via inventory operations, not here.</p>

                <div v-if="product.inventory_movements?.length" class="rounded border p-3">
                    <h2 class="mb-2 text-sm font-medium">Recent movements</h2>
                    <ul class="space-y-1 text-sm">
                        <li v-for="m in product.inventory_movements" :key="m.id" class="flex justify-between">
                            <span>{{ m.type }} — {{ m.quantity }} ({{ m.reason }})</span>
                            <span class="text-gray-500">{{ m.created_at }}</span>
                        </li>
                    </ul>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" :disabled="form.processing" class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>

                    <button type="button" @click="destroy" class="rounded bg-red-600 px-4 py-2 text-white">Delete</button>

                    <Link :href="route('products.index')" class="text-sm text-gray-600 hover:underline">Back to list</Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
