<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import draggable from 'vuedraggable';

type TaskItem = {
    id: number;
    title: string;
};

const props = defineProps<{
    project: { id: number; name: string };
    tasksByStatus: Record<string, TaskItem[]>;
    statusColumns: string[];
}>();

const columnLabels: Record<string, string> = {
    todo: 'Todo',
    in_progress: 'In progress',
    review: 'Review',
    done: 'Done',
};

/** Local board — one array per Kanban column (keys match backend status strings). */
const board = reactive<Record<string, TaskItem[]>>({});

watch(
    () => [props.tasksByStatus, props.statusColumns],
    () => {
        for (const col of props.statusColumns) {
            board[col] = [...(props.tasksByStatus[col] ?? [])];
        }
    },
    { immediate: true, deep: true },
);

function persistReorder() {
    const payload: { id: number; sort_order: number; status: string }[] = [];
    for (const status of props.statusColumns) {
        (board[status] ?? []).forEach((t, idx) => {
            payload.push({ id: t.id, sort_order: idx, status });
        });
    }
    if (!payload.length) {
        return;
    }
    router.patch(route('projects.tasks.reorder', props.project.id), { tasks: payload }, { preserveScroll: true });
}

const draftTitles = reactive<Record<string, string>>({});
for (const c of props.statusColumns) {
    draftTitles[c] = '';
}

const addTaskForm = useForm({
    title: '',
    status: '',
    milestone_id: null as number | null,
    description: '' as string | null,
    priority: '' as string | null,
    sort_order: null as number | null,
    due_date: '' as string | null,
});

function submitQuick(column: string) {
    addTaskForm.title = draftTitles[column] ?? '';
    addTaskForm.status = column;
    addTaskForm
        .transform((data) => ({
            ...data,
            due_date: data.due_date === '' ? null : data.due_date,
            description: data.description === '' ? null : data.description,
            priority: data.priority === '' ? null : data.priority,
            sort_order: data.sort_order,
        }))
        .post(route('projects.tasks.store', props.project.id), {
            preserveScroll: true,
            onSuccess: () => {
                draftTitles[column] = '';
            },
        });
}
</script>

<template>
    <Head :title="`${project.name} · Task board`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Projects', href: route('projects.index') },
            { label: project.name, href: route('projects.show', project.id) },
            { label: 'Board' },
        ]"
    >
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold dark:text-gray-100">{{ project.name }} — Tasks</h1>
                <p class="text-sm text-gray-500">Drag tasks between columns; order syncs automatically.</p>
            </div>
            <Link :href="route('projects.show', project.id)" class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                ← Project overview
            </Link>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <section v-for="col in statusColumns" :key="col" class="flex min-h-[320px] flex-col rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-950">
                <div class="border-b border-gray-200 px-3 py-2 dark:border-gray-800">
                    <h2 class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ columnLabels[col] ?? col }}
                    </h2>
                    <div class="mt-2 flex gap-2">
                        <TextInput v-model="draftTitles[col]" type="text" class="flex-1 text-xs" placeholder="Quick add title" />
                        <PrimaryButton
                            type="button"
                            class="py-2 text-xs normal-case tracking-normal"
                            :disabled="addTaskForm.processing || !(draftTitles[col]?.trim?.())"
                            @click="submitQuick(col)"
                        >
                            Add
                        </PrimaryButton>
                    </div>
                    <InputError :message="addTaskForm.errors.title" class="mt-1" />
                </div>

                <draggable
                    v-model="board[col]"
                    item-key="id"
                    group="tasks"
                    class="flex flex-1 flex-col gap-2 p-2"
                    ghost-class="opacity-60"
                    @end="persistReorder"
                >
                    <template #item="{ element }">
                        <div class="cursor-grab rounded border border-gray-200 bg-white p-2 shadow-sm active:cursor-grabbing dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm font-medium">{{ element.title }}</p>
                        </div>
                    </template>
                </draggable>
            </section>
        </div>
    </AppLayout>
</template>
