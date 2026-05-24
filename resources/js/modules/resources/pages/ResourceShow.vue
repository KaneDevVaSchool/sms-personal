<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

type ResourceShow = {
    id: number;
    name: string;
    resource_type?: { id: number; name: string } | null;
    owner_team?: { id: number; name: string } | null;
    type: string | null;
    url: string | null;
    status: string | null;
    expires_at: string | null;
    cost_monthly: string | number | null;
    notes: string | null;
};

defineProps<{
    resource: ResourceShow;
}>();

function destroyResource(id: number) {
    if (confirm('Delete this resource?')) {
        router.delete(route('resources.destroy', id));
    }
}
</script>

<template>
    <Head :title="`${resource.name} · Resource`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Resources', href: route('resources.index') },
            { label: resource.name },
        ]"
        :title="resource.name"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-end gap-2">
                <Link :href="route('resources.edit', resource.id)" class="btn btn-outline-primary btn-sm">Edit</Link>
                <DangerButton type="button" @click="destroyResource(resource.id)">Delete</DangerButton>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-muted">Type</dt>
                    <dd class="col-sm-9">{{ resource.resource_type?.name ?? resource.type ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Owner team</dt>
                    <dd class="col-sm-9">{{ resource.owner_team?.name ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">URL</dt>
                    <dd class="col-sm-9">
                        <a v-if="resource.url" :href="resource.url" target="_blank" rel="noopener">{{ resource.url }}</a>
                        <span v-else class="text-muted">—</span>
                    </dd>
                    <dt class="col-sm-3 text-muted">Status</dt>
                    <dd class="col-sm-9">{{ resource.status ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Expires</dt>
                    <dd class="col-sm-9">{{ resource.expires_at ? String(resource.expires_at).slice(0, 10) : '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Cost (monthly)</dt>
                    <dd class="col-sm-9">{{ resource.cost_monthly ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Notes</dt>
                    <dd class="col-sm-9 text-pre-wrap">{{ resource.notes || '—' }}</dd>
                </dl>
            </div>
        </div>
    </AppLayout>
</template>
