<script setup>
const checked = defineModel('checked', {
    type: Boolean,
    default: false,
});

defineProps({
    name: {
        type: String,
    },
    id: {
        type: String,
    },
});
</script>

<template>
    <input
        :id="id"
        :name="name"
        v-model="checked"
        type="checkbox"
        class="form-check-input"
    />
</template>
