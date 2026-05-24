<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

type Env = {
    id: number;
    name: string | null;
    url: string | null;
};

type SiteShow = {
    id: number;
    name: string;
    url: string | null;
    status: string | null;
    tech_stack: string | null;
    cms: string | null;
    ssl_expires_at: string | null;
    resource?: { id: number; name: string } | null;
    environments?: Env[];
};

defineProps<{
    website: SiteShow;
}>();

function destroySite(id: number) {
    if (confirm('Delete this website?')) {
        router.delete(route('websites.destroy', id));
    }
}
</script>

<template>
    <Head :title="`${website.name} · Website`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Websites', href: route('websites.index') },
            { label: website.name },
        ]"
        :title="website.name"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap justify-content-end gap-2">
                <Link :href="route('websites.edit', website.id)" class="btn btn-outline-primary btn-sm">Edit</Link>
                <DangerButton type="button" @click="destroySite(website.id)">Delete</DangerButton>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-muted">URL</dt>
                    <dd class="col-sm-9">
                        <a v-if="website.url" :href="website.url" target="_blank" rel="noopener">{{ website.url }}</a>
                        <span v-else class="text-muted">—</span>
                    </dd>
                    <dt class="col-sm-3 text-muted">Status</dt>
                    <dd class="col-sm-9">{{ website.status ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Tech stack</dt>
                    <dd class="col-sm-9">{{ website.tech_stack ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">CMS</dt>
                    <dd class="col-sm-9">{{ website.cms ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">SSL expires</dt>
                    <dd class="col-sm-9">{{ website.ssl_expires_at ? String(website.ssl_expires_at).slice(0, 10) : '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Resource</dt>
                    <dd class="col-sm-9">{{ website.resource?.name ?? '—' }}</dd>
                </dl>
            </div>
        </div>

        <h5 class="mb-3">Environments</h5>
        <div class="card">
            <ul class="list-group list-group-flush">
                <li v-if="!website.environments?.length" class="list-group-item text-muted">None</li>
                <li v-for="e in website.environments" :key="e.id" class="list-group-item d-flex justify-content-between">
                    <span>{{ e.name ?? 'Environment' }}</span>
                    <span class="text-muted">{{ e.url ?? '—' }}</span>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
