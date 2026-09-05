<script setup lang="ts">
import { computed } from 'vue';
const props = defineProps<{ quantity: number; minimum_stock: number }>();
const status = computed(() => props.quantity === 0 ? 'out' : props.quantity > 0 && props.quantity <= props.minimum_stock ? 'low' : 'in');
const label = computed(() => ({ out: 'Out of stock', low: 'Low stock', in: 'In stock' } as const)[status.value]);
const cls = computed(() => ({ out: 'bg-red-100 text-red-800', low: 'bg-yellow-100 text-yellow-800', in: 'bg-green-100 text-green-800' } as const)[status.value]);
</script>
<template>
  <span class="rounded-full px-2 py-0.5 text-xs font-medium" :class="cls">{{ label }}</span>
</template>
