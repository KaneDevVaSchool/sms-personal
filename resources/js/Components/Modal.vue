<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: 'lg',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

const modalEl = ref(null);
let modalInstance = null;

const dialogClass = computed(() => {
    return {
        sm: 'modal-sm',
        md: '',
        lg: 'modal-lg',
        xl: 'modal-xl',
        '2xl': 'modal-xl',
    }[props.maxWidth];
});

function getBootstrapModal() {
    return window.bootstrap?.Modal ?? null;
}

watch(
    () => props.show,
    (visible) => {
        const Modal = getBootstrapModal();
        if (!modalEl.value || !Modal) {
            return;
        }
        if (!modalInstance) {
            modalInstance = new Modal(modalEl.value, {
                backdrop: props.closeable ? true : 'static',
                keyboard: props.closeable,
            });
            modalEl.value.addEventListener('hidden.bs.modal', () => emit('close'));
        }
        if (visible) {
            modalInstance.show();
        } else {
            modalInstance.hide();
        }
    },
);

onMounted(() => {
    const Modal = getBootstrapModal();
    if (props.show && modalEl.value && Modal) {
        modalInstance = new Modal(modalEl.value);
        modalInstance.show();
    }
});

onUnmounted(() => {
    modalInstance?.dispose();
});
</script>

<template>
    <div ref="modalEl" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" :class="dialogClass">
            <div class="modal-content">
                <slot />
            </div>
        </div>
    </div>
</template>
