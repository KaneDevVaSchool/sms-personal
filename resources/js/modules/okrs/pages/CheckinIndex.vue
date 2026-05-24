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
        title="Check-ins"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <p class="text-muted mb-0">{{ keyResult.title }} · {{ objective.title }}</p>
                <Link
                    :href="route('objectives.key-results.checkins.create', [objective.id, keyResult.id])"
                    class="btn btn-primary btn-sm"
                >
                    New check-in
                </Link>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>When</th>
                            <th>Value</th>
                            <th>User</th>
                            <th>Note</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in checkins.data" :key="c.id">
                            <td>{{ c.checked_at ? String(c.checked_at).replace('T', ' ').slice(0, 16) : '—' }}</td>
                            <td class="fw-medium">{{ c.value }}</td>
                            <td>{{ c.user?.name ?? '—' }}</td>
                            <td class="text-truncate" style="max-width: 12rem">{{ c.note ?? '—' }}</td>
                            <td class="text-end">
                                <Link
                                    :href="route('objectives.key-results.checkins.edit', [objective.id, keyResult.id, c.id])"
                                    class="btn btn-sm btn-outline-primary me-2"
                                >
                                    Edit
                                </Link>
                                <DangerButton type="button" class="btn-sm" @click="destroyCheckin(c)">Delete</DangerButton>
                            </td>
                        </tr>
                        <tr v-if="!checkins.data.length">
                            <td colspan="5" class="text-center text-muted py-4">No check-ins found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="checkins.links.length" :links="checkins.links" />
        </div>
    </AppLayout>
</template>
