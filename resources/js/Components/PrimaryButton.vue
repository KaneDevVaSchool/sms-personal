<script setup>
import { computed, useAttrs } from 'vue';

defineOptions({ inheritAttrs: false });

const attrs = useAttrs();

const buttonType = computed(() => {
    const type = attrs.type;
    return typeof type === 'string' ? type : 'button';
});
</script>

<template>
    <button class="btn btn-primary" v-bind="attrs" :type="buttonType">
        <slot />
    </button>
</template>
