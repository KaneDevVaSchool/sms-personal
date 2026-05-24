<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type ResourceRow = {
    id: number;
    name: string;
    status: string | null;
    url: string | null;
    resource_type?: { id: number; name: string } | null;
    owner_team?: { id: number; name: string } | null;
};

const props = defineProps<{
    resources: Paginated<ResourceRow>;
    resourceTypes: { id: number; name: string }[];
    filters: { status: string; resource_type_id: string | number | null };
}>();

const filterForm = useForm({
    status: props.filters.status ?? '',
    resource_type_id:
        props.filters.resource_type_id === null || props.filters.resource_type_id === undefined || props.filters.resource_type_id === ''
            ? ''
            : String(props.filters.resource_type_id),
});

function applyFilters() {
    filterForm.get(route('resources.index'), { preserveState: true, preserveScroll: true, replace: true });
}
</script>

<template>
    <Head title="Resources" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Resources' },
        ]"
    >
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Resources</h1>
            <Link
                :href="route('resources.create')"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-500"
            >
                New resource
            </Link>
        </div>

        <form
            class="mb-4 flex flex-wrap items-end gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
            @submit.prevent="applyFilters"
        >
            <div>
                <InputLabel value="Status" />
                <input
                    v-model="filterForm.status"
                    type="text"
                    class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100"
                    placeholder="e.g. active"
                />
            </div>
            <div>
                <InputLabel value="Type" />
                <select
                    v-model="filterForm.resource_type_id"
                    class="mt-1 block w-56 rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100"
                >
                    <option value="">Any</option>
                    <option v-for="t in resourceTypes" :key="t.id" :value="String(t.id)">{{ t.name }}</option>
                </select>
            </div>
            <PrimaryButton type="submit" :disabled="filterForm.processing">Filter</PrimaryButton>
        </form>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="row in resources.data" :key="row.id">
                        <td class="px-4 py-3">
                            <Link
                                :href="route('resources.show', row.id)"
                                class="font-medium text-indigo-600 hover:underline dark:text-indigo-400"
                            >
                                {{ row.name }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ row.resource_type?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ row.status ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <Link
                                :href="route('resources.edit', row.id)"
                                class="text-indigo-600 hover:underline dark:text-indigo-400"
                            >
                                Edit
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="resources.links.length" :links="resources.links" />
        </div>
    </AppLayout>
</template>
