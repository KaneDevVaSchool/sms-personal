<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

type Proj = {
    id: number;
    name: string;
    description: string | null;
    status: string | null;
    due_date: string | null;
    progress_percent: number | null;
    owner?: { id: number; name: string } | null;
};

defineProps<{
    project: Proj;
}>();

function destroyProject(id: number) {
    if (confirm('Delete this project?')) {
        router.delete(route('projects.destroy', id));
    }
}
</script>

<template>
    <Head :title="`${project.name} · Project`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Projects', href: route('projects.index') },
            { label: project.name },
        ]"
    >
        <div class="mb-6 flex flex-wrap justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold dark:text-gray-100">{{ project.name }}</h1>
                <p v-if="project.description" class="mt-2 text-gray-600 dark:text-gray-400">{{ project.description }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <Link
                    :href="route('projects.tasks.index', project.id)"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-500"
                >
                    Task board
                </Link>
                <Link
                    :href="route('projects.edit', project.id)"
                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                >
                    Edit
                </Link>
                <DangerButton type="button" class="text-sm tracking-normal normal-case" @click="destroyProject(project.id)">Delete</DangerButton>
            </div>
        </div>

        <dl class="max-w-2xl space-y-3 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div><dt class="text-xs uppercase text-gray-500">Owner</dt>{{ project.owner?.name ?? '—' }}</div>
            <div><dt class="text-xs uppercase text-gray-500">Status</dt>{{ project.status ?? '—' }}</div>
            <div>
                <dt class="text-xs uppercase text-gray-500">Due</dt>
                {{ project.due_date ? String(project.due_date).slice(0, 10) : '—' }}
            </div>
            <div><dt class="text-xs uppercase text-gray-500">Progress</dt>{{ project.progress_percent ?? '—' }}%</div>
        </dl>
    </AppLayout>
</template>
