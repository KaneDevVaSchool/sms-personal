<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Row = {
    id: number;
    name: string;
    partner: string | null;
    status: string | null;
    expires_at: string | null;
    files_count: number;
};

defineProps<{
    contracts: Paginated<Row>;
}>();
</script>

<template>
    <Head title="Contracts" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Contracts' },
        ]"
    >
        <div class="mb-4 flex justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">Contracts</h1>
            <Link :href="route('contracts.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-500">
                New contract
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Partner</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Expires</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Files</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="c in contracts.data" :key="c.id">
                        <td class="px-4 py-3">
                            <Link :href="route('contracts.show', c.id)" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                                {{ c.name }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ c.partner ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ c.status ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ c.expires_at ? String(c.expires_at).slice(0, 10) : '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ c.files_count }}</td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="contracts.links.length" :links="contracts.links" />
        </div>
    </AppLayout>
</template>
