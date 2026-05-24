<script setup lang="ts">
import PageHeader from '@/Components/PageHeader.vue';
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type ResourceRow = {
    id: number;
    name: string;
    status: string | null;
    url: string | null;
    resource_type?: { id: number; name: string } | null;
    owner_team?: { id: number; name: string } | null;
};

const props = defineProps<{
    resources: Paginated<ResourceRow>;
    resourceTypes: { id: number; name: string }[];
    filters: { status: string; resource_type_id: string | number | null };
}>();

const filterForm = useForm({
    status: props.filters.status ?? '',
    resource_type_id:
        props.filters.resource_type_id === null || props.filters.resource_type_id === undefined || props.filters.resource_type_id === ''
            ? ''
            : String(props.filters.resource_type_id),
});

function applyFilters() {
    filterForm.get(route('resources.index'), { preserveState: true, preserveScroll: true, replace: true });
}
</script>

<template>
    <Head title="Resources" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Resources' },
        ]"
        title="Resources"
    >
        <PageHeader
            title="Resources"
            :create-href="route('resources.create')"
            create-label="New resource"
        />

        <div class="card mb-3">
            <div class="card-body">
                <form class="row g-3 align-items-end" @submit.prevent="applyFilters">
                    <div class="col-md-4">
                        <InputLabel value="Status" />
                        <input v-model="filterForm.status" type="text" class="form-control" placeholder="e.g. active" />
                    </div>
                    <div class="col-md-4">
                        <InputLabel value="Type" />
                        <select v-model="filterForm.resource_type_id" class="form-select">
                            <option value="">Any</option>
                            <option v-for="t in resourceTypes" :key="t.id" :value="String(t.id)">{{ t.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <PrimaryButton type="submit" :disabled="filterForm.processing">Filter</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in resources.data" :key="row.id">
                            <td>
                                <Link :href="route('resources.show', row.id)" class="fw-medium">
                                    {{ row.name }}
                                </Link>
                            </td>
                            <td>{{ row.resource_type?.name ?? '—' }}</td>
                            <td>{{ row.status ?? '—' }}</td>
                            <td class="text-end">
                                <Link :href="route('resources.edit', row.id)" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="!resources.data.length">
                            <td colspan="4" class="text-center text-muted py-4">No resources found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="resources.links.length" :links="resources.links" />
        </div>
    </AppLayout>
</template>
