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

// Mock data — replace with Inertia props when backend is ready
const stats = {
    total: { value: '1,284', change: '+12% from last month' },
    lowStock: { value: 42, note: '8 items need attention' },
    outOfStock: { value: 15, note: 'Out of stock items' },
    categories: { value: 24, note: 'Active product groups' },
};

const attentionItems = [
    { id: 1, name: 'Organic Coffee Beans', status: 'out', label: 'Out of stock' },
    { id: 2, name: 'Green Tea Matcha', status: 'low', label: '5 units left' },
    { id: 3, name: 'Almond Milk 1L', status: 'low', label: '12 units left' },
];

const recentActivity = [
    {
        id: 1,
        title: 'Stock-in: Arabica Blend',
        detail: '+50 units by Sarah M. • 10 mins ago',
        type: 'in',
    },
    {
        id: 2,
        title: 'Adjustment: Inventory Count',
        detail: '-2 units (Damaged) • 2 hours ago',
        type: 'adjustment',
    },
    {
        id: 3,
        title: 'Stock-out: Espresso Roast',
        detail: '-15 units (Order #8821) • 5 hours ago',
        type: 'out',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-6">

        <!-- Page header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    Inventory Overview
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Monitor your products and stock levels.
                </p>
            </div>
            <div class="flex shrink-0 items-center gap-2">
                <button
                    class="inline-flex items-center rounded-lg border border-border bg-background px-4 py-2 text-sm font-medium text-foreground shadow-xs transition-colors hover:bg-accent"
                >
                    Adjust Stock
                </button>
                <button
                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#1a4a3a] px-4 py-2 text-sm font-medium text-white shadow-xs transition-colors hover:bg-[#163d30]"
                >
                    <span class="text-base leading-none">+</span>
                    Add Product
                </button>
            </div>
        </div>

        <!-- Stat cards -->
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">

            <!-- Total Products -->
            <div class="col-span-2 flex flex-col gap-2 rounded-xl bg-[#1a4a3a] p-4 text-white sm:col-span-1 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-widest text-white/70">Total</span>
                    <Archive class="size-4 text-white/60" />
                </div>
                <div class="text-4xl font-bold tracking-tight">
                    {{ stats.total.value }}
                </div>
                <div class="text-xs text-white/70">{{ stats.total.change }}</div>
            </div>

            <!-- Low Stock -->
            <div class="flex flex-col gap-2 rounded-xl border border-border bg-[#fff5f5] p-4 dark:bg-destructive/10">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">Low Stock</span>
                    <AlertTriangle class="size-4 text-destructive" />
                </div>
                <div class="text-3xl font-bold tracking-tight text-destructive">
                    {{ stats.lowStock.value }}
                </div>
                <div class="text-xs text-destructive/80">{{ stats.lowStock.note }}</div>
            </div>

            <!-- Out of Stock -->
            <div class="flex flex-col gap-2 rounded-xl border border-border bg-card p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">Out</span>
                    <Ban class="size-4 text-muted-foreground" />
                </div>
                <div class="text-3xl font-bold tracking-tight text-foreground">
                    {{ stats.outOfStock.value }}
                </div>
                <div class="text-xs text-muted-foreground">{{ stats.outOfStock.note }}</div>
            </div>

            <!-- Categories -->
            <div class="flex flex-col gap-2 rounded-xl border border-border bg-card p-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">Categories</span>
                    <LayoutGrid class="size-4 text-muted-foreground" />
                </div>
                <div class="text-3xl font-bold tracking-tight text-foreground">
                    {{ stats.categories.value }}
                </div>
                <div class="text-xs text-muted-foreground">{{ stats.categories.note }}</div>
            </div>
        </div>

        <!-- Lower panels -->
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            <!-- Needs Attention -->
            <div class="rounded-xl border border-border bg-card p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-foreground">Needs Attention</h2>
                    <span class="inline-flex items-center rounded-full bg-destructive/15 px-2.5 py-0.5 text-xs font-medium text-destructive">
                        8 Critical
                    </span>
                </div>

                <div class="flex flex-col gap-2">
                    <div
                        v-for="item in attentionItems"
                        :key="item.id"
                        class="flex items-center gap-3 rounded-lg border border-border bg-background px-4 py-3"
                    >
                        <!-- Icon -->
                        <div
                            class="flex size-8 shrink-0 items-center justify-center rounded-lg"
                            :class="item.status === 'out' ? 'bg-destructive/10' : 'bg-muted'"
                        >
                            <Ban
                                v-if="item.status === 'out'"
                                class="size-4 text-destructive"
                            />
                            <AlertTriangle
                                v-else
                                class="size-4 text-muted-foreground"
                            />
                        </div>

                        <!-- Name + status -->
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm font-medium text-foreground">
                                {{ item.name }}
                            </div>
                            <div
                                class="text-xs"
                                :class="item.status === 'out' ? 'text-destructive' : 'text-muted-foreground'"
                            >
                                {{ item.label }}
                            </div>
                        </div>

                        <!-- Restock button -->
                        <button class="shrink-0 rounded-md border border-border bg-background px-3 py-1.5 text-xs font-medium text-foreground transition-colors hover:bg-accent">
                            Restock
                        </button>
                    </div>
                </div>
            </div>

            <!-- Recent Inventory Activity -->
            <div class="rounded-xl border border-border bg-card p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-foreground">Recent Inventory Activity</h2>
                    <button class="text-sm text-muted-foreground transition-colors hover:text-foreground">
                        View All
                    </button>
                </div>

                <div class="flex flex-col gap-4">
                    <div
                        v-for="activity in recentActivity"
                        :key="activity.id"
                        class="flex items-start gap-3"
                    >
                        <!-- Dot -->
                        <div class="mt-1.5 flex shrink-0 items-center justify-center">
                            <div
                                class="size-2.5 rounded-full"
                                :class="{
                                    'bg-[#1a4a3a]': activity.type === 'in',
                                    'bg-[#1a4a3a]/60': activity.type === 'adjustment',
                                    'bg-muted-foreground/40': activity.type === 'out',
                                }"
                            />
                        </div>

                        <!-- Text -->
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-foreground">
                                {{ activity.title }}
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{ activity.detail }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
