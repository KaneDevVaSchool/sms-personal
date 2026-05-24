<script setup lang="ts">
import type { Paginated } from '@/types/common';
import { Link } from '@inertiajs/vue3';

type LinkItem = Paginated<unknown>['links'][number];

defineProps<{
    links: LinkItem[];
}>();
</script>

<template>
    <nav class="flex flex-wrap items-center justify-end gap-1 border-t border-gray-100 px-4 py-3 dark:border-gray-800">
        <template v-for="(link, idx) in links" :key="idx">
            <Link
                v-if="link.url"
                :href="link.url"
                preserve-scroll
                preserve-state
                class="rounded px-3 py-1 text-xs"
                :class="
                    link.active
                        ? 'bg-indigo-100 font-semibold text-indigo-800 dark:bg-indigo-950 dark:text-indigo-200'
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800'
                "
            >
                <span v-html="link.label" />
            </Link>
            <span
                v-else
                class="rounded px-3 py-1 text-xs opacity-40"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
