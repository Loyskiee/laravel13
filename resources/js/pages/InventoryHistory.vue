<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Pagination from '@/components/Pagination.vue';

const props = defineProps({
    movements: Object,
    filters: Object,
});

const search = ref(props.filters?.search ?? '');
const type = ref(props.filters?.type ?? '');

let t;

watch(
    [search, type],
    () => {
        clearTimeout(t);

        t = setTimeout(
            () =>
                router.get(
                    route('inventories.index'),
                    {
                        search: search.value || undefined,
                        type: type.value || undefined,
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
        <Head title="Inventory History" />

        <div class="p-4">
            <h1 class="text-xl font-semibold">Inventory History</h1>

            <div class="mt-4 grid gap-3 md:grid-cols-2">
                <input
                    v-model="search"
                    placeholder="Search product or reason"
                    class="rounded border px-3 py-2"
                />

                <select
                    v-model="type"
                    class="rounded border px-3 py-2"
                >
                    <option value="">All types</option>
                    <option value="in">In</option>
                    <option value="out">Out</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="initial">Initial</option>
                </select>
            </div>

            <div
                v-if="movements.data.length === 0"
                class="mt-4 rounded border p-8 text-center text-gray-500"
            >
                No movements yet.
            </div>

            <div v-else class="mt-4">
                <div class="hidden overflow-x-auto rounded border md:block">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left">Product</th>
                                <th class="px-3 py-2 text-left">Type</th>
                                <th class="px-3 py-2 text-left">Qty</th>
                                <th class="px-3 py-2 text-left">Reason</th>
                                <th class="px-3 py-2 text-left">User</th>
                                <th class="px-3 py-2 text-left">Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="m in movements.data"
                                :key="m.id"
                                class="border-t"
                            >
                                <td class="px-3 py-2">
                                    <Link
                                        :href="route('products.show', m.product.id)"
                                        class="text-blue-600 hover:underline"
                                    >
                                        {{ m.product.name }}
                                    </Link>

                                    <span class="text-gray-500">{{ m.product.sku }}</span>
                                </td>

                                <td class="px-3 py-2 capitalize">
                                    {{ m.type }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ m.quantity }} ({{ m.before_quantity }} → {{ m.after_quantity }})
                                </td>

                                <td class="px-3 py-2">
                                    {{ m.reason }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ m.user.name }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ new Date(m.created_at).toLocaleString() }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="space-y-3 md:hidden">
                    <div
                        v-for="m in movements.data"
                        :key="m.id"
                        class="rounded border p-4"
                    >
                        <div class="flex justify-between text-sm">
                            <span class="font-medium">{{ m.product.name }}</span>
                            <span class="capitalize text-gray-500">{{ m.type }}</span>
                        </div>

                        <p class="mt-1 text-xs text-gray-500">
                            {{ m.quantity }} ({{ m.before_quantity }} → {{ m.after_quantity }}) • {{ m.reason }} • {{ m.user.name }} • {{ new Date(m.created_at).toLocaleDateString() }}
                        </p>
                    </div>
                </div>

                <Pagination
                    :links="movements.links"
                    class="mt-4"
                />
            </div>
        </div>
</template>
