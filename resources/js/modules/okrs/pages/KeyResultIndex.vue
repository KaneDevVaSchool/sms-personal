<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
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
        :title="`Key results · ${objective.title}`"
    >
        <PageHeader
            :title="`Key results · ${objective.title}`"
            :create-href="route('objectives.key-results.create', objective.id)"
            create-label="New key result"
        />

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Target</th>
                            <th>Check-ins</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="kr in keyResults.data" :key="kr.id">
                            <td>
                                <Link
                                    :href="route('objectives.key-results.checkins.index', [objective.id, kr.id])"
                                    class="fw-medium"
                                >
                                    {{ kr.title }}
                                </Link>
                            </td>
                            <td>{{ kr.target ?? '—' }}</td>
                            <td>{{ kr.checkins_count }}</td>
                            <td class="text-end">
                                <Link
                                    :href="route('objectives.key-results.edit', [objective.id, kr.id])"
                                    class="btn btn-sm btn-outline-primary me-2"
                                >
                                    Edit
                                </Link>
                                <DangerButton type="button" class="btn-sm" @click="destroyKr(kr)">Delete</DangerButton>
                            </td>
                        </tr>
                        <tr v-if="!keyResults.data.length">
                            <td colspan="4" class="text-center text-muted py-4">No key results found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="keyResults.links.length" :links="keyResults.links" />
        </div>
    </AppLayout>
</template>
