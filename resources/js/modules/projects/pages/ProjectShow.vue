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
        :title="project.name"
    >
        <div class="row mb-3">
            <div class="col-12">
                <p v-if="project.description" class="text-muted mb-2">{{ project.description }}</p>
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <Link :href="route('projects.tasks.index', project.id)" class="btn btn-primary btn-sm">Task board</Link>
                    <Link :href="route('projects.edit', project.id)" class="btn btn-outline-primary btn-sm">Edit</Link>
                    <DangerButton type="button" @click="destroyProject(project.id)">Delete</DangerButton>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-muted">Owner</dt>
                    <dd class="col-sm-9">{{ project.owner?.name ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Status</dt>
                    <dd class="col-sm-9">{{ project.status ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Due</dt>
                    <dd class="col-sm-9">{{ project.due_date ? String(project.due_date).slice(0, 10) : '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Progress</dt>
                    <dd class="col-sm-9">{{ project.progress_percent ?? '—' }}%</dd>
                </dl>
            </div>
        </div>
    </AppLayout>
</template>
