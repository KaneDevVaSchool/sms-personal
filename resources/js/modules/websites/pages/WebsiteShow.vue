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
    >
        <div class="mb-6 flex flex-wrap justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">{{ website.name }}</h1>
            <div class="flex gap-2">
                <Link
                    :href="route('websites.edit', website.id)"
                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                >
                    Edit
                </Link>
                <DangerButton type="button" class="text-sm tracking-normal normal-case" @click="destroySite(website.id)">
                    Delete
                </DangerButton>
            </div>
        </div>

        <dl class="mb-8 max-w-2xl space-y-3 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="grid gap-y-3">
                <div>
                    <dt class="text-xs uppercase text-gray-500">URL</dt>
                    <dd>
                        <a
                            v-if="website.url"
                            :href="website.url"
                            class="text-indigo-600 hover:underline dark:text-indigo-400"
                            target="_blank"
                            rel="noopener"
                        >
                            {{ website.url }}
                        </a>
                        <span v-else class="text-gray-500">—</span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-gray-500">Status</dt>
                    <dd>{{ website.status ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-gray-500">Tech stack</dt>
                    <dd>{{ website.tech_stack ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-gray-500">CMS</dt>
                    <dd>{{ website.cms ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-gray-500">SSL expires</dt>
                    <dd>{{ website.ssl_expires_at ? String(website.ssl_expires_at).slice(0, 10) : '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-gray-500">Resource</dt>
                    <dd>{{ website.resource?.name ?? '—' }}</dd>
                </div>
            </div>
        </dl>

        <h2 class="mb-3 text-lg font-medium">Environments</h2>
        <ul class="divide-y divide-gray-200 rounded-lg border border-gray-200 dark:divide-gray-800 dark:border-gray-800">
            <li v-if="!website.environments?.length" class="p-4 text-gray-500">None</li>
            <li v-for="e in website.environments" :key="e.id" class="flex justify-between p-4">
                <span>{{ e.name ?? 'Environment' }}</span>
                <span class="text-sm text-gray-500">{{ e.url ?? '—' }}</span>
            </li>
        </ul>
    </AppLayout>
</template>
