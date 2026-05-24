<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
    createHref?: string;
    createLabel?: string;
}>();
</script>

<template>
    <div class="row mb-3">
        <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h4 class="header-title mb-0">{{ title }}</h4>
            <Link v-if="createHref" :href="createHref" class="btn btn-primary btn-sm">
                {{ createLabel ?? 'Create' }}
            </Link>
        </div>
    </div>
</template>
