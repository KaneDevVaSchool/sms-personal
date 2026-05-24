<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Obj = { id: number; title: string };
type Kr = { id: number; title: string };

type Cin = {
    id: number;
    value: string | number | null;
    note: string | null;
    checked_at: string | null;
    user?: { id: number; name: string } | null;
};

const props = defineProps<{
    objective: Obj;
    keyResult: Kr;
    checkins: Paginated<Cin>;
}>();

function destroyCheckin(c: Cin) {
    if (confirm('Delete check-in from ' + c.checked_at + '?')) {
        router.delete(route('objectives.key-results.checkins.destroy', [props.objective.id, props.keyResult.id, c.id]));
    }
}
</script>

<template>
    <Head :title="`Check-ins · ${keyResult.title}`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives', href: route('objectives.index') },
            { label: objective.title, href: route('objectives.show', objective.id) },
            { label: 'Key results', href: route('objectives.key-results.index', objective.id) },
            { label: keyResult.title },
            { label: 'Check-ins' },
        ]"
    >
        <div class="mb-4 flex flex-wrap justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold dark:text-gray-100">Check-ins</h1>
                <p class="text-sm text-gray-500">{{ keyResult.title }} · {{ objective.title }}</p>
            </div>
            <Link
                :href="route('objectives.key-results.checkins.create', [objective.id, keyResult.id])"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white"
            >
                New check-in
            </Link>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">When</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Value</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">User</th>
                        <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Note</th>
                        <th class="px-4 py-3 text-right text-xs uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="c in checkins.data" :key="c.id">
                        <td class="px-4 py-3 text-sm">{{ c.checked_at ? String(c.checked_at).replace('T', ' ').slice(0, 16) : '—' }}</td>
                        <td class="px-4 py-3 text-sm font-medium">{{ c.value }}</td>
                        <td class="px-4 py-3 text-sm">{{ c.user?.name ?? '—' }}</td>
                        <td class="px-4 py-3 max-w-xs truncate text-sm text-gray-600">{{ c.note ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link
                                :href="route('objectives.key-results.checkins.edit', [objective.id, keyResult.id, c.id])"
                                class="mr-2 text-sm text-indigo-600 dark:text-indigo-400"
                            >
                                Edit
                            </Link>
                            <DangerButton type="button" class="py-1 text-xs" @click="destroyCheckin(c)">Delete</DangerButton>
                        </td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="checkins.links.length" :links="checkins.links" />
        </div>
    </AppLayout>
</template>
