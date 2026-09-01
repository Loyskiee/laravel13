<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{ links: { url: string | null; label: string; active: boolean }[] }>();
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap gap-2">
        <Link v-for="(link, i) in links" :key="i" :href="link.url ?? '#'" :class="['rounded border px-3 py-1 text-sm', link.active ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-50', !link.url ? 'pointer-events-none opacity-50' : '']" :aria-current="link.active ? 'page' : undefined" v-html="link.label" />
    </div>
</template>
