<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
    },
    method: {
        type: String,
        default: 'get',
    },
    as: {
        type: String,
        default: 'a',
    },
});
</script>

<template>
    <Link v-if="as === 'button'" :href="href" :method="method" as="button" class="dropdown-item">
        <slot />
    </Link>
    <Link v-else :href="href" :method="method" class="dropdown-item">
        <slot />
    </Link>
</template>
