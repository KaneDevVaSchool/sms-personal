<script setup>
defineOptions({ inheritAttrs: false });
</script>

<template>
    <button type="button" class="btn btn-secondary" v-bind="$attrs">
        <slot />
    </button>
</template>
