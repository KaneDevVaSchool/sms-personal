<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Row = {
    id: number;
    name: string;
    url: string | null;
    status: string | null;
    resource?: { id: number; name: string } | null;
};

defineProps<{
    websites: Paginated<Row>;
}>();
</script>

<template>
    <Head title="Websites" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Websites' },
        ]"
    >
        <div class="mb-4 flex justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Websites</h1>
            <Link :href="route('websites.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                New website
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">URL</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Resource</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="w in websites.data" :key="w.id">
                        <td class="px-4 py-3">
                            <Link :href="route('websites.show', w.id)" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                                {{ w.name }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ w.url ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ w.status ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ w.resource?.name ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="websites.links.length" :links="websites.links" />
        </div>
    </AppLayout>
</template>
