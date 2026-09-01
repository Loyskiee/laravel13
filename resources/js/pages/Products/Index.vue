<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { route } from 'ziggy-js';
import StockBadge from '@/components/StockBadge.vue';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters?.search ?? '');
const category_id = ref(props.filters?.category_id ?? '');
const stock_status = ref(props.filters?.stock_status ?? '');

let t;

watch(
    [search, category_id, stock_status],
    () => {
        clearTimeout(t);

        t = setTimeout(
            () =>
                router.get(
                    route('products.index'),
                    {
                        search: search.value || undefined,
                        category_id: category_id.value || undefined,
                        stock_status: stock_status.value || undefined,
                    },
                    {
                        preserveState: true,
                        replace: true,
                    },
                ),
            300,
        );
    },
);
</script>

<template>
        <Head title="Products" />

        <div class="p-4">
            <div
                class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between"
            >
                <h1 class="text-xl font-semibold">Products</h1>

                <Link
                    :href="route('products.create')"
                    class="rounded bg-blue-600 px-4 py-2 text-white"
                >
                    New Product
                </Link>
            </div>

            <div class="mb-4 grid gap-3 md:grid-cols-3">
                <input
                    v-model="search"
                    placeholder="Search name or SKU"
                    class="rounded border px-3 py-2"
                />

                <select
                    v-model="category_id"
                    class="rounded border px-3 py-2"
                >
                    <option value="">All categories</option>

                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>

                <select
                    v-model="stock_status"
                    class="rounded border px-3 py-2"
                >
                    <option value="">All stock</option>
                    <option value="low">Low stock</option>
                    <option value="out">Out of stock</option>
                    <option value="in">In stock</option>
                </select>
            </div>

            <div
                v-if="products.data.length === 0"
                class="rounded border p-8 text-center text-gray-500"
            >
                No products yet.
            </div>

            <div v-else>
                <div class="hidden overflow-x-auto rounded border md:block">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left">Name</th>
                                <th class="px-3 py-2">SKU</th>
                                <th class="px-3 py-2">Category</th>
                                <th class="px-3 py-2">Qty</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="p in products.data"
                                :key="p.id"
                                class="border-t"
                            >
                                <td class="px-3 py-2">
                                    <Link
                                        :href="route('products.show', p.id)"
                                        class="text-blue-600 hover:underline"
                                    >
                                        {{ p.name }}
                                    </Link>
                                </td>

                                <td class="px-3 py-2">
                                    {{ p.sku }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ p.category?.name ?? '—' }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ p.quantity }}
                                </td>

                                <td class="px-3 py-2">
                                    <StockBadge
                                        :quantity="p.quantity"
                                        :minimum_stock="p.minimum_stock"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 md:hidden">
                    <div
                        v-for="p in products.data"
                        :key="p.id"
                        class="rounded border p-4"
                    >
                        <div class="flex justify-between">
                            <Link
                                :href="route('products.show', p.id)"
                                class="font-medium text-blue-600"
                            >
                                {{ p.name }}
                            </Link>

                            <StockBadge
                                :quantity="p.quantity"
                                :minimum_stock="p.minimum_stock"
                            />
                        </div>

                        <p class="text-sm text-gray-500">
                            {{ p.sku }} •
                            {{ p.category?.name ?? 'No category' }} •
                            Qty {{ p.quantity }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
</template>
