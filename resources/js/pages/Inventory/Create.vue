<script setup>
import InputError from '@/components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    products: Array,
});

const form = useForm({
    product_id: '',
    type: 'in',
    quantity: 1,
    reason: '',
    corrected_quantity: null,
});

const submit = () => form.post(route('inventories.store'));
</script>

<template>
        <Head title="Record Inventory Movement" />

        <div class="mx-auto max-w-md p-4">
            <h1 class="mb-4 text-xl font-semibold">Record Movement</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Product</label>
                    <select v-model="form.product_id" class="w-full rounded border px-3 py-2" required>
                        <option value="">Select</option>
                        <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                    <InputError :message="form.errors.product_id" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Type</label>
                    <select v-model="form.type" class="w-full rounded border px-3 py-2">
                        <option value="in">Stock In</option>
                        <option value="out">Stock Out</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                    <InputError :message="form.errors.type" />
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ form.type === 'adjustment' ? 'Corrected quantity' : 'Quantity' }}</label>
                    <input
                        v-if="form.type === 'adjustment'"
                        v-model.number="form.corrected_quantity"
                        type="number"
                        min="0"
                        class="w-full rounded border px-3 py-2"
                        required
                    />
                    <input v-else v-model.number="form.quantity" type="number" min="1" class="w-full rounded border px-3 py-2" required />
                    <InputError :message="form.errors.quantity" />
                    <InputError :message="form.errors.corrected_quantity" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Reason</label>
                    <textarea v-model="form.reason" class="w-full rounded border px-3 py-2" required />
                    <InputError :message="form.errors.reason" />
                </div>

                <div class="flex gap-2">
                    <button type="submit" :disabled="form.processing" class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Record' }}
                    </button>
                    <Link :href="route('inventories.index')" class="px-4 py-2 text-sm text-gray-600 hover:underline">Back to history</Link>
                </div>
            </form>
        </div>
</template>
