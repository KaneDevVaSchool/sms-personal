<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Row = {
    id: number;
    service: string;
    label: string;
    api_key_hint: string | null;
    quota_limit: string | number | null;
    team?: { id: number; name: string } | null;
    project?: { id: number; name: string } | null;
};

defineProps<{
    accounts: Paginated<Row>;
}>();
</script>

<template>
    <Head title="AI accounts" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'AI accounts' },
        ]"
        title="AI accounts"
    >
        <PageHeader title="AI accounts" :create-href="route('ai-accounts.create')" create-label="New account" />

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Label</th>
                            <th>Service</th>
                            <th>Team</th>
                            <th>Project</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="a in accounts.data" :key="a.id">
                            <td>
                                <Link :href="route('ai-accounts.show', a.id)" class="fw-medium">{{ a.label }}</Link>
                            </td>
                            <td>{{ a.service }}</td>
                            <td>{{ a.team?.name ?? '—' }}</td>
                            <td>{{ a.project?.name ?? '—' }}</td>
                        </tr>
                        <tr v-if="!accounts.data.length">
                            <td colspan="4" class="text-center text-muted py-4">No AI accounts found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="accounts.links.length" :links="accounts.links" />
        </div>
    </AppLayout>
</template>
