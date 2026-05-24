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
        :title="`${project.name} — Tasks`"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <p class="text-muted mb-0 small">Drag tasks between columns; order syncs automatically.</p>
                <Link :href="route('projects.show', project.id)" class="btn btn-outline-primary btn-sm">
                    Project overview
                </Link>
            </div>
        </div>

        <div class="row g-3">
            <div v-for="col in statusColumns" :key="col" class="col-md-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-2">{{ columnLabels[col] ?? col }}</h5>
                        <div class="d-flex gap-2">
                            <TextInput v-model="draftTitles[col]" type="text" class="form-control form-control-sm" placeholder="Quick add title" />
                            <PrimaryButton
                                type="button"
                                class="btn-sm"
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
                        class="card-body d-flex flex-column gap-2 kanban-column-body"
                        ghost-class="opacity-50"
                        style="min-height: 280px"
                        @end="persistReorder"
                    >
                        <template #item="{ element }">
                            <div class="card mb-0 shadow-sm" style="cursor: grab">
                                <div class="card-body py-2 px-3">
                                    <p class="mb-0 fw-medium small">{{ element.title }}</p>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
