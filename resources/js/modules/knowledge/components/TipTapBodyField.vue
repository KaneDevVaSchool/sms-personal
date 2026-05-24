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
            class: 'form-control min-vh-25 border rounded p-2',
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
    <div class="border rounded">
        <EditorContent v-if="editor" :editor="editor" />
    </div>
</template>
