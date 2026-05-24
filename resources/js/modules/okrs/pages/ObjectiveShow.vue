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
        :title="objective.title"
    >
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-muted mb-1">Q{{ objective.quarter ?? '—' }} {{ objective.year ?? '' }}</p>
                <p class="text-muted mb-2">{{ objective.team?.name }} · Owned by {{ objective.owner?.name }}</p>
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <Link :href="route('objectives.key-results.index', objective.id)" class="btn btn-primary btn-sm">
                        Key results
                    </Link>
                    <Link :href="route('objectives.edit', objective.id)" class="btn btn-outline-primary btn-sm">Edit</Link>
                    <DangerButton type="button" @click="destroyObjective(objective.id)">Delete</DangerButton>
                </div>
            </div>
        </div>

        <h5 class="mb-3">Key results</h5>
        <div v-if="!objective.key_results?.length" class="card mb-3">
            <div class="card-body text-center text-muted">No key results yet.</div>
        </div>
        <div v-for="kr in objective.key_results ?? []" :key="kr.id" class="card mb-3">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div>
                    <h6 class="mb-1">{{ kr.title }}</h6>
                    <p class="text-muted mb-0 small">{{ kr.current ?? '?' }} → {{ kr.target ?? '?' }} {{ kr.unit ?? '' }}</p>
                </div>
                <Link
                    :href="route('objectives.key-results.checkins.index', [objective.id, kr.id])"
                    class="btn btn-outline-primary btn-sm"
                >
                    Check-ins
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
