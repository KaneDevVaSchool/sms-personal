<script setup lang="ts">
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
    >
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Team members</h1>
            <Link
                :href="route('team.departments.index')"
                class="text-sm text-indigo-600 hover:underline dark:text-indigo-400"
            >
                Manage departments
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Department</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Roles</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="user in users.data" :key="user.id">
                        <td class="px-4 py-3">
                            <Link
                                :href="route('team.users.show', user.id)"
                                class="font-medium text-indigo-600 hover:underline dark:text-indigo-400"
                            >
                                {{ user.name }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ user.email }}</td>
                        <td class="px-4 py-3 text-sm">{{ user.department?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ user.roles?.map((r) => r.name).join(', ') || '—' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
