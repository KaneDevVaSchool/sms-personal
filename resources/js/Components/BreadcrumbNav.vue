<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types/common';

defineProps<{
    items: BreadcrumbItem[];
}>();
</script>

<template>
    <nav aria-label="Breadcrumb" class="mb-4 text-sm text-gray-500 dark:text-gray-400">
        <ol class="flex flex-wrap items-center gap-1">
            <li v-for="(item, index) in items" :key="index" class="flex items-center gap-1">
                <span v-if="index > 0" aria-hidden="true">/</span>
                <Link
                    v-if="item.href"
                    :href="item.href"
                    class="hover:text-gray-800 dark:hover:text-gray-200"
                >
                    {{ item.label }}
                </Link>
                <span v-else class="font-medium text-gray-800 dark:text-gray-200">
                    {{ item.label }}
                </span>
            </li>
        </ol>
    </nav>
</template>
