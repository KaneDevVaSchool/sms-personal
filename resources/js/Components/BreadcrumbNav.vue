<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types/common';

defineProps<{
    items: BreadcrumbItem[];
}>();
</script>

<template>
    <nav aria-label="Breadcrumb">
        <ol class="breadcrumb m-0">
            <li
                v-for="(item, index) in items"
                :key="index"
                class="breadcrumb-item"
                :class="{ active: !item.href && index === items.length - 1 }"
                :aria-current="!item.href && index === items.length - 1 ? 'page' : undefined"
            >
                <Link v-if="item.href" :href="item.href">{{ item.label }}</Link>
                <span v-else>{{ item.label }}</span>
            </li>
        </ol>
    </nav>
</template>
