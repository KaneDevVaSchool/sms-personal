<script setup lang="ts">
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
    >
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Departments</h1>
            <Link
                :href="route('team.departments.create')"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-500"
            >
                New department
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Manager</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="row in departments.data" :key="row.id">
                        <td class="px-4 py-3 font-medium">{{ row.name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                            {{ row.manager?.name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <Link
                                :href="route('team.departments.edit', row.id)"
                                class="mr-3 text-sm text-indigo-600 hover:underline dark:text-indigo-400"
                            >
                                Edit
                            </Link>
                            <DangerButton type="button" class="py-1 text-xs" @click="destroyDept(row.id)">
                                Delete
                            </DangerButton>
                        </td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="departments.links.length > 3" :links="departments.links" />
        </div>
    </AppLayout>
</template>
