<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type UserRow = {
    id: number;
    name: string;
    email: string;
    department?: { id: number; name: string } | null;
    roles?: { id: number; name: string }[];
};

defineProps<{
    users: Paginated<UserRow>;
}>();
</script>

<template>
    <Head title="Team — Users" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Team' },
        ]"
        title="Team members"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h4 class="header-title mb-0">Team members</h4>
                <Link :href="route('team.departments.index')" class="btn btn-outline-primary btn-sm">
                    Manage departments
                </Link>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Roles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user.id">
                            <td>
                                <Link :href="route('team.users.show', user.id)" class="fw-medium">
                                    {{ user.name }}
                                </Link>
                            </td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.department?.name ?? '—' }}</td>
                            <td>{{ user.roles?.map((r) => r.name).join(', ') || '—' }}</td>
                        </tr>
                        <tr v-if="!users.data.length">
                            <td colspan="4" class="text-center text-muted py-4">No team members found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="users.links.length" :links="users.links" />
        </div>
    </AppLayout>
</template>
