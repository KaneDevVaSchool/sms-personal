<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

type Checkin = {
    id: number;
    value: string | number | null;
    checked_at?: string | null;
};

type Kr = {
    id: number;
    title: string;
    target: string | number | null;
    current: string | number | null;
    unit: string | null;
    checkins?: Checkin[];
};

type ObjectiveShowModel = {
    id: number;
    title: string;
    year: number | null;
    quarter: number | null;
    team?: { id: number; name: string } | null;
    owner?: { id: number; name: string } | null;
    key_results?: Kr[];
};

defineProps<{ objective: ObjectiveShowModel }>();

function destroyObjective(id: number) {
    if (confirm('Delete objective and nested data?')) {
        router.delete(route('objectives.destroy', id));
    }
}
</script>

<template>
    <Head :title="`${objective.title} · Objective`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives', href: route('objectives.index') },
            { label: objective.title },
        ]"
    >
        <div class="mb-6 flex flex-wrap justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">Q{{ objective.quarter ?? '—' }} {{ objective.year ?? '' }}</p>
                <h1 class="text-2xl font-semibold dark:text-gray-100">{{ objective.title }}</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ objective.team?.name }} · Owned by {{ objective.owner?.name }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <Link
                    :href="route('objectives.key-results.index', objective.id)"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white"
                >
                    Key results
                </Link>
                <Link :href="route('objectives.edit', objective.id)" class="rounded-md border px-3 py-2 text-sm dark:border-gray-600">Edit</Link>
                <DangerButton type="button" class="text-sm tracking-normal normal-case" @click="destroyObjective(objective.id)">Delete</DangerButton>
            </div>
        </div>

        <h2 class="mb-3 text-lg font-medium">Key results</h2>
        <div class="space-y-3">
            <div v-if="!objective.key_results?.length" class="rounded-lg border border-dashed p-6 text-center text-gray-500">No key results yet.</div>
            <div
                v-for="kr in objective.key_results ?? []"
                :key="kr.id"
                class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="flex flex-wrap justify-between gap-4">
                    <div>
                        <h3 class="font-semibold">{{ kr.title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ kr.current ?? '?' }} → {{ kr.target ?? '?' }} {{ kr.unit ?? '' }}</p>
                    </div>
                    <Link
                        :href="route('objectives.key-results.checkins.index', [objective.id, kr.id])"
                        class="text-sm text-indigo-600 hover:underline dark:text-indigo-400"
                    >
                        Check-ins
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
