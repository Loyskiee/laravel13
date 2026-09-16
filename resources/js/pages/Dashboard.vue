<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { AlertTriangle, Archive, Ban, LayoutGrid } from '@lucide/vue';
import { dashboard } from '@/routes';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const props = defineProps<{
    stats: { total_products: number; total_quantity: number; low_stock: number; out_of_stock: number; categories: number };
    needsAttention: { id: number; name: string; sku: string; quantity: number; minimum_stock: number; category: { name: string } | null }[];
    recentActivity: { data: { id: number; type: string; quantity: number; reason: string; before_quantity: number; after_quantity: number; created_at: string; product: { id: number; name: string; sku: string }; user: { name: string } }[] };
}>();
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">
        <!-- Page header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">Inventory Overview</h1>
                <p class="mt-1 text-sm text-muted-foreground">Monitor your products and stock levels.</p>
            </div>

            <div class="flex shrink-0 items-center gap-2">
                <Link :href="route('inventories.index')" class="inline-flex items-center rounded-lg border border-border bg-background px-4 py-2 text-sm font-medium text-foreground shadow-xs transition-colors hover:bg-accent">
                    Adjust Stock
                </Link>

                <Link :href="route('products.create')" class="rounded bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90 transition-colors">
                    New Product
                </Link>
            </div>
        </div>

        <!-- Stat cards -->
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            <!-- Total Products -->
            <div class="col-span-2 flex flex-col gap-2 rounded-xl bg-primary p-4 text-primary-foreground sm:col-span-1 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-widest text-white/70">Total</span>
                    <Archive class="size-4 text-white/60" />
                </div>

                <div class="text-4xl font-bold tracking-tight">{{ stats.total_products }}</div>
                <div class="text-xs text-white/70">Total products</div>
            </div>

            <!-- Low Stock -->
            <div class="flex flex-col gap-2 rounded-xl border border-border bg-card p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">Low Stock</span>
                    <AlertTriangle class="size-4 text-amber-500" />
                </div>

                <div class="text-3xl font-bold tracking-tight">{{ stats.low_stock }}</div>
                <div class="text-xs text-muted-foreground">{{ stats.low_stock }} need attention</div>
            </div>

            <!-- Out of Stock -->
            <div class="flex flex-col gap-2 rounded-xl border border-border bg-card p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">Out</span>
                    <Ban class="size-4 text-destructive" />
                </div>

                <div class="text-3xl font-bold tracking-tight">{{ stats.out_of_stock }}</div>
                <div class="text-xs text-muted-foreground">Out of stock items</div>
            </div>

            <!-- Categories -->
            <div class="flex flex-col gap-2 rounded-xl border border-border bg-card p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">Categories</span>
                    <LayoutGrid class="size-4 text-muted-foreground" />
                </div>

                <div class="text-3xl font-bold tracking-tight">{{ stats.categories }}</div>
                <div class="text-xs text-muted-foreground">Active product groups</div>
            </div>
        </div>

        <!-- Lower panels -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <!-- Needs Attention -->
            <div class="rounded-xl border border-border bg-card p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-foreground">Needs Attention</h2>
                    <span class="inline-flex items-center rounded-full bg-destructive/15 px-2.5 py-0.5 text-xs font-medium text-destructive">
                        {{ stats.low_stock + stats.out_of_stock }} Critical
                    </span>
                </div>

                <div v-if="needsAttention.length === 0" class="rounded border border-dashed p-8 text-center text-sm text-muted-foreground">All stocked — no attention needed.</div>

                <div v-else class="flex flex-col gap-2">
                    <div
                        v-for="item in needsAttention"
                        :key="item.id"
                        class="flex items-center gap-3 rounded-lg border border-border bg-background px-4 py-3"
                    >
                        <div class="flex size-8 shrink-0 items-center justify-center rounded-lg" :class="item.quantity === 0 ? 'bg-destructive/10' : 'bg-amber-100'">
                            <Ban v-if="item.quantity === 0" class="size-4 text-destructive" />
                            <AlertTriangle v-else class="size-4 text-amber-600" />
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm font-medium text-foreground">{{ item.name }}</div>
                            <div class="text-xs" :class="item.quantity === 0 ? 'text-destructive' : 'text-muted-foreground'">
                                {{ item.quantity === 0 ? 'Out of stock' : `${item.quantity} units left` }}
                            </div>
                        </div>

                        <Link :href="route('products.show', item.id)" class="shrink-0 rounded-md border border-border bg-background px-3 py-1.5 text-xs font-medium text-foreground hover:bg-accent">
                            Restock
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Recent Inventory Activity -->
            <div class="rounded-xl border border-border bg-card p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-foreground">Recent Inventory Activity</h2>
                    <Link :href="route('inventories.index')" class="text-sm text-muted-foreground hover:text-foreground">View All</Link>
                </div>

                <div v-if="recentActivity.data.length === 0" class="text-sm text-muted-foreground">No recent movements.</div>

                <div v-else class="flex flex-col gap-4">
                    <div v-for="activity in recentActivity.data" :key="activity.id" class="flex items-start gap-3">
                        <div class="mt-1.5 flex shrink-0 items-center justify-center">
                            <div class="size-2.5 rounded-full" :class="{ 'bg-emerald-500': activity.type === 'in', 'bg-amber-500': activity.type === 'adjustment', 'bg-red-400': activity.type === 'out', 'bg-gray-400': activity.type === 'initial' }" />
                        </div>

                        <div class="min-w-0">
                            <div class="text-sm font-medium text-foreground">{{ activity.type }}: {{ activity.product.name }}</div>
                            <div class="text-xs text-muted-foreground">{{ activity.quantity }} units — {{ activity.reason }} • {{ activity.user.name }} • {{ new Date(activity.created_at).toLocaleString() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
