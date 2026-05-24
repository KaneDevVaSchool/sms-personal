<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Row = {
    id: number;
    title: string;
    year: number | null;
    quarter: number | null;
    team?: { id: number; name: string } | null;
    owner?: { id: number; name: string } | null;
    key_results_count: number;
};

defineProps<{
    objectives: Paginated<Row>;
}>();
</script>

<template>
    <Head title="OKRs — Objectives" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives' },
        ]"
    >
        <div class="mb-4 flex justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">Objectives</h1>
            <Link :href="route('objectives.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white">New objective</Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Title</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Period</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Team</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Owner</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">KRs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="o in objectives.data" :key="o.id">
                        <td class="px-4 py-3">
                            <Link :href="route('objectives.show', o.id)" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                                {{ o.title }}
                            </Link>
                            <Link
                                :href="route('objectives.key-results.index', o.id)"
                                class="ml-2 text-xs text-gray-500 hover:text-indigo-600 dark:text-indigo-400"
                            >
                                Key results
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">Q{{ o.quarter ?? '—' }} {{ o.year ?? '' }}</td>
                        <td class="px-4 py-3 text-sm">{{ o.team?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ o.owner?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ o.key_results_count }}</td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="objectives.links.length" :links="objectives.links" />
        </div>
    </AppLayout>
</template>
