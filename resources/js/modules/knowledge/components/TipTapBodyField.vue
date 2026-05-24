<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import { onBeforeUnmount, watch } from 'vue';

const props = withDefaults(
    defineProps<{
        modelValue: string;
    }>(),
    { modelValue: '' },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const editor = useEditor({
    content: props.modelValue || '',
    extensions: [StarterKit],
    editorProps: {
        attributes: {
            class:
                'min-h-[220px] rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100 dark:focus-visible:ring-indigo-400',
        },
    },
    onUpdate: ({ editor: ed }) => {
        emit('update:modelValue', ed.getHTML());
    },
});

watch(
    () => props.modelValue,
    (html) => {
        const ed = editor.value;
        if (!ed) {
            return;
        }
        const cur = ed.getHTML();
        if ((html || '') !== cur) {
            ed.commands.setContent(html || '', false);
        }
    },
);

onBeforeUnmount(() => editor.value?.destroy());
</script>

<template>
    <div>
        <EditorContent v-if="editor" :editor="editor" />
    </div>
</template>
