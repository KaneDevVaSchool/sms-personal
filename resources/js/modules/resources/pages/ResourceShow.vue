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
    >
        <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ resource.name }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Details</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <Link
                    :href="route('resources.edit', resource.id)"
                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:hover:bg-gray-800"
                >
                    Edit
                </Link>
                <DangerButton type="button" class="text-sm normal-case tracking-normal" @click="destroyResource(resource.id)">
                    Delete
                </DangerButton>
            </div>
        </div>

        <dl class="max-w-2xl space-y-4 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div>
                <dt class="text-xs font-medium uppercase text-gray-500">Type</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ resource.resource_type?.name ?? resource.type ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase text-gray-500">Owner team</dt>
                <dd class="text-gray-900 dark:text-gray-100">{{ resource.owner_team?.name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase text-gray-500">URL</dt>
                <dd>
                    <a v-if="resource.url" :href="resource.url" target="_blank" rel="noopener" class="text-indigo-600 hover:underline dark:text-indigo-400">{{ resource.url }}</a>
                    <span v-else class="text-gray-500">—</span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase text-gray-500">Status</dt>
                <dd>{{ resource.status ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase text-gray-500">Expires</dt>
                <dd>{{ resource.expires_at ? String(resource.expires_at).slice(0, 10) : '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase text-gray-500">Cost (monthly)</dt>
                <dd>{{ resource.cost_monthly ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase text-gray-500">Notes</dt>
                <dd class="whitespace-pre-wrap text-gray-800 dark:text-gray-200">{{ resource.notes || '—' }}</dd>
            </div>
        </dl>
    </AppLayout>
</template>
