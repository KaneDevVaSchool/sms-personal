<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Dept = {
    id: number;
    name: string;
    manager?: { id: number; name: string } | null;
};

defineProps<{
    departments: Paginated<Dept>;
}>();

function destroyDept(id: number) {
    if (confirm('Delete this department?')) {
        router.delete(route('team.departments.destroy', id));
    }
}
</script>

<template>
    <Head title="Team — Departments" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Departments' },
        ]"
        title="Departments"
    >
        <PageHeader
            title="Departments"
            :create-href="route('team.departments.create')"
            create-label="New department"
        />

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Manager</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in departments.data" :key="row.id">
                            <td class="fw-medium">{{ row.name }}</td>
                            <td>{{ row.manager?.name ?? '—' }}</td>
                            <td class="text-end">
                                <Link :href="route('team.departments.edit', row.id)" class="btn btn-sm btn-outline-primary me-2">
                                    Edit
                                </Link>
                                <DangerButton type="button" class="btn-sm" @click="destroyDept(row.id)">
                                    Delete
                                </DangerButton>
                            </td>
                        </tr>
                        <tr v-if="!departments.data.length">
                            <td colspan="3" class="text-center text-muted py-4">No departments found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="departments.links.length > 3" :links="departments.links" />
        </div>
    </AppLayout>
</template>
