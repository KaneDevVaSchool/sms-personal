<script setup lang="ts">
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
    >
        <div class="mb-4 flex justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Projects</h1>
            <Link :href="route('projects.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-500">New project</Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Owner</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Due</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="p in projects.data" :key="p.id">
                        <td class="px-4 py-3">
                            <Link :href="route('projects.show', p.id)" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                                {{ p.name }}
                            </Link>
                            <Link
                                :href="route('projects.tasks.index', p.id)"
                                class="ml-2 text-xs text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400"
                            >
                                Board
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ p.owner?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ p.status ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ p.due_date ? String(p.due_date).slice(0, 10) : '—' }}</td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="projects.links.length" :links="projects.links" />
        </div>
    </AppLayout>
</template>
