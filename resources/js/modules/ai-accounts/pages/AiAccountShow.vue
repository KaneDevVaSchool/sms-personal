<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

type Acc = {
    id: number;
    service: string;
    label: string;
    api_key_hint: string | null;
    quota_limit: string | number | null;
    quota_used: string | number | null;
    billing_date: string | null;
    team?: { id: number; name: string } | null;
    project?: { id: number; name: string } | null;
};

defineProps<{ account: Acc }>();

function destroyAcc(id: number) {
    if (confirm('Delete this AI account?')) {
        router.delete(route('ai-accounts.destroy', id));
    }
}
</script>

<template>
    <Head :title="`${account.label} · AI account`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'AI accounts', href: route('ai-accounts.index') },
            { label: account.label },
        ]"
    >
        <div class="mb-6 flex flex-wrap justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold dark:text-gray-100">{{ account.label }}</h1>
                <p class="text-sm text-gray-500">{{ account.service }}</p>
            </div>
            <div class="flex gap-2">
                <Link :href="route('ai-accounts.edit', account.id)" class="rounded-md border px-3 py-2 text-sm dark:border-gray-600">Edit</Link>
                <DangerButton type="button" class="text-sm tracking-normal normal-case" @click="destroyAcc(account.id)">Delete</DangerButton>
            </div>
        </div>

        <dl class="max-w-xl space-y-3 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div><dt class="text-xs uppercase text-gray-500">Key hint</dt>{{ account.api_key_hint ?? '—' }}</div>
            <div><dt class="text-xs uppercase text-gray-500">Quota</dt>{{ account.quota_used ?? '?' }} / {{ account.quota_limit ?? '∞' }}</div>
            <div>
                <dt class="text-xs uppercase text-gray-500">Billing</dt>{{ account.billing_date ? String(account.billing_date).slice(0, 10) : '—' }}
            </div>
            <div><dt class="text-xs uppercase text-gray-500">Team</dt>{{ account.team?.name ?? '—' }}</div>
            <div><dt class="text-xs uppercase text-gray-500">Project</dt>{{ account.project?.name ?? '—' }}</div>
        </dl>
    </AppLayout>
</template>
