<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type ObjectiveLite = {
    id: number;
    title: string;
};

type KR = {
    id: number;
    title: string;
    target: string | number | null;
    checkins_count: number;
};

const props = defineProps<{
    objective: ObjectiveLite;
    keyResults: Paginated<KR>;
}>();

function destroyKr(row: KR) {
    if (confirm('Delete key result “' + row.title + '”?')) {
        router.delete(route('objectives.key-results.destroy', [props.objective.id, row.id]));
    }
}
</script>

<template>
    <Head :title="`Key results · ${objective.title}`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives', href: route('objectives.index') },
            { label: objective.title, href: route('objectives.show', objective.id) },
            { label: 'Key results' },
        ]"
    >
        <div class="mb-4 flex flex-wrap justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">Key results · {{ objective.title }}</h1>
            <Link
                :href="route('objectives.key-results.create', objective.id)"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white"
            >
                New key result
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Title</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Target</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Check-ins</th>
                        <th class="px-4 py-3 text-right text-xs uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="kr in keyResults.data" :key="kr.id">
                        <td class="px-4 py-3 font-medium">
                            <Link
                                :href="route('objectives.key-results.checkins.index', [objective.id, kr.id])"
                                class="text-indigo-600 hover:underline dark:text-indigo-400"
                            >
                                {{ kr.title }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ kr.target ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ kr.checkins_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link
                                :href="route('objectives.key-results.edit', [objective.id, kr.id])"
                                class="mr-2 text-sm text-indigo-600 dark:text-indigo-400"
                            >
                                Edit
                            </Link>
                            <DangerButton type="button" class="py-1 text-xs" @click="destroyKr(kr)">Delete</DangerButton>
                        </td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="keyResults.links.length" :links="keyResults.links" />
        </div>
    </AppLayout>
</template>
