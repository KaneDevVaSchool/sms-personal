<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Row = {
    id: number;
    service: string;
    label: string;
    api_key_hint: string | null;
    quota_limit: string | number | null;
    team?: { id: number; name: string } | null;
    project?: { id: number; name: string } | null;
};

defineProps<{
    accounts: Paginated<Row>;
}>();
</script>

<template>
    <Head title="AI accounts" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'AI accounts' },
        ]"
    >
        <div class="mb-4 flex justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">AI accounts</h1>
            <Link :href="route('ai-accounts.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white">New</Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Label</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Service</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Team</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Project</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="a in accounts.data" :key="a.id">
                        <td class="px-4 py-3">
                            <Link :href="route('ai-accounts.show', a.id)" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                                {{ a.label }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ a.service }}</td>
                        <td class="px-4 py-3 text-sm">{{ a.team?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ a.project?.name ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="accounts.links.length" :links="accounts.links" />
        </div>
    </AppLayout>
</template>
