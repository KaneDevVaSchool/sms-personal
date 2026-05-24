<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Row = {
    id: number;
    name: string;
    partner: string | null;
    status: string | null;
    expires_at: string | null;
    files_count: number;
};

defineProps<{
    contracts: Paginated<Row>;
}>();
</script>

<template>
    <Head title="Contracts" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Contracts' },
        ]"
        title="Contracts"
    >
        <PageHeader title="Contracts" :create-href="route('contracts.create')" create-label="New contract" />

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Partner</th>
                            <th>Status</th>
                            <th>Expires</th>
                            <th>Files</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in contracts.data" :key="c.id">
                            <td>
                                <Link :href="route('contracts.show', c.id)" class="fw-medium">{{ c.name }}</Link>
                            </td>
                            <td>{{ c.partner ?? '—' }}</td>
                            <td>{{ c.status ?? '—' }}</td>
                            <td>{{ c.expires_at ? String(c.expires_at).slice(0, 10) : '—' }}</td>
                            <td>{{ c.files_count }}</td>
                        </tr>
                        <tr v-if="!contracts.data.length">
                            <td colspan="5" class="text-center text-muted py-4">No contracts found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="contracts.links.length" :links="contracts.links" />
        </div>
    </AppLayout>
</template>
