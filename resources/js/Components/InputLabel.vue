<script setup>
defineProps({
    value: {
        type: String,
    },
    for: {
        type: String,
    },
});
</script>

<template>
    <label class="form-label" :for="$props.for">
        <span v-if="value">{{ value }}</span>
        <slot v-else />
    </label>
</template>
