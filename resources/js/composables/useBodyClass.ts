import { onMounted, onUnmounted } from 'vue';

/**
 * Toggle CSS classes on document.body for layout-specific Jidox styling.
 */
export function useBodyClass(className: string): void {
    onMounted(() => {
        document.body.classList.add(className);
    });

    onUnmounted(() => {
        document.body.classList.remove(className);
    });
}
