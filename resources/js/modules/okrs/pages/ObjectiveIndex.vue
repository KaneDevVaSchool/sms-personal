<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
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
        title="Objectives"
    >
        <PageHeader title="Objectives" :create-href="route('objectives.create')" create-label="New objective" />

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Period</th>
                            <th>Team</th>
                            <th>Owner</th>
                            <th>KRs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="o in objectives.data" :key="o.id">
                            <td>
                                <Link :href="route('objectives.show', o.id)" class="fw-medium">{{ o.title }}</Link>
                                <Link :href="route('objectives.key-results.index', o.id)" class="ms-2 small text-muted">
                                    Key results
                                </Link>
                            </td>
                            <td>Q{{ o.quarter ?? '—' }} {{ o.year ?? '' }}</td>
                            <td>{{ o.team?.name ?? '—' }}</td>
                            <td>{{ o.owner?.name ?? '—' }}</td>
                            <td>{{ o.key_results_count }}</td>
                        </tr>
                        <tr v-if="!objectives.data.length">
                            <td colspan="5" class="text-center text-muted py-4">No objectives found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="objectives.links.length" :links="objectives.links" />
        </div>
    </AppLayout>
</template>
