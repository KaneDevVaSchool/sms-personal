<script setup lang="ts">
import type { Paginated } from '@/types/common';
import { Link } from '@inertiajs/vue3';

type LinkItem = Paginated<unknown>['links'][number];

defineProps<{
    links: LinkItem[];
}>();
</script>

<template>
    <nav class="d-flex justify-content-end p-3 border-top">
        <ul class="pagination pagination-sm mb-0">
            <template v-for="(link, idx) in links" :key="idx">
                <li v-if="link.url" class="page-item" :class="{ active: link.active }">
                    <Link
                        :href="link.url"
                        preserve-scroll
                        preserve-state
                        class="page-link"
                    >
                        <span v-html="link.label" />
                    </Link>
                </li>
                <li v-else class="page-item disabled">
                    <span class="page-link" v-html="link.label" />
                </li>
            </template>
        </ul>
    </nav>
</template>
