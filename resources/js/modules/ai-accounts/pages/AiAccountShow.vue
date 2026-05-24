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
        :title="account.label"
    >
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-muted mb-2">{{ account.service }}</p>
                <div class="d-flex flex-wrap justify-content-end gap-2">
                    <Link :href="route('ai-accounts.edit', account.id)" class="btn btn-outline-primary btn-sm">Edit</Link>
                    <DangerButton type="button" @click="destroyAcc(account.id)">Delete</DangerButton>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-muted">Key hint</dt>
                    <dd class="col-sm-9">{{ account.api_key_hint ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Quota</dt>
                    <dd class="col-sm-9">{{ account.quota_used ?? '?' }} / {{ account.quota_limit ?? '∞' }}</dd>
                    <dt class="col-sm-3 text-muted">Billing</dt>
                    <dd class="col-sm-9">{{ account.billing_date ? String(account.billing_date).slice(0, 10) : '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Team</dt>
                    <dd class="col-sm-9">{{ account.team?.name ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Project</dt>
                    <dd class="col-sm-9">{{ account.project?.name ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </AppLayout>
</template>
