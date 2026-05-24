<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Row = {
    id: number;
    name: string;
    status: string | null;
    due_date: string | null;
    owner?: { id: number; name: string } | null;
};

defineProps<{
    projects: Paginated<Row>;
}>();
</script>

<template>
    <Head title="Projects" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Projects' },
        ]"
        title="Projects"
    >
        <PageHeader title="Projects" :create-href="route('projects.create')" create-label="New project" />

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in projects.data" :key="p.id">
                            <td>
                                <Link :href="route('projects.show', p.id)" class="fw-medium">{{ p.name }}</Link>
                                <Link :href="route('projects.tasks.index', p.id)" class="ms-2 small text-muted">Board</Link>
                            </td>
                            <td>{{ p.owner?.name ?? '—' }}</td>
                            <td>{{ p.status ?? '—' }}</td>
                            <td>{{ p.due_date ? String(p.due_date).slice(0, 10) : '—' }}</td>
                        </tr>
                        <tr v-if="!projects.data.length">
                            <td colspan="4" class="text-center text-muted py-4">No projects found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="projects.links.length" :links="projects.links" />
        </div>
    </AppLayout>
</template>
