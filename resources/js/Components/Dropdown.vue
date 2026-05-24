<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: String,
        default: 'dropdown-menu-end',
    },
});

const open = ref(false);
const root = ref(null);

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

const onDocClick = (e) => {
    if (open.value && root.value && !root.value.contains(e.target)) {
        open.value = false;
    }
};

onMounted(() => {
    document.addEventListener('keydown', closeOnEscape);
    document.addEventListener('click', onDocClick);
});

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.removeEventListener('click', onDocClick);
});
</script>

<template>
    <div ref="root" class="dropdown" :class="{ show: open }">
        <div @click.stop="open = !open">
            <slot name="trigger" />
        </div>
        <div
            class="dropdown-menu dropdown-menu-animated"
            :class="[contentClasses, { show: open }]"
            @click="open = false"
        >
            <slot name="content" />
        </div>
    </div>
</template>
