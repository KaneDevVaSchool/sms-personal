<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Row = {
    id: number;
    name: string;
    url: string | null;
    status: string | null;
    resource?: { id: number; name: string } | null;
};

defineProps<{
    websites: Paginated<Row>;
}>();
</script>

<template>
    <Head title="Websites" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Websites' },
        ]"
        title="Websites"
    >
        <PageHeader title="Websites" :create-href="route('websites.create')" create-label="New website" />

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th>Resource</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="w in websites.data" :key="w.id">
                            <td>
                                <Link :href="route('websites.show', w.id)" class="fw-medium">{{ w.name }}</Link>
                            </td>
                            <td>{{ w.url ?? '—' }}</td>
                            <td>{{ w.status ?? '—' }}</td>
                            <td>{{ w.resource?.name ?? '—' }}</td>
                        </tr>
                        <tr v-if="!websites.data.length">
                            <td colspan="4" class="text-center text-muted py-4">No websites found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="websites.links.length" :links="websites.links" />
        </div>
    </AppLayout>
</template>
