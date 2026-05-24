<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

type Acc = {
    id: number;
    service: string;
    label: string;
    api_key_hint: string | null;
    quota_limit: string | number | null;
    quota_used: string | number | null;
    billing_date: string | null;
    team_id: number | null;
    project_id: number | null;
};

const props = defineProps<{
    account: Acc;
    teams: { id: number; name: string }[];
    projects: { id: number; name: string }[];
}>();

function bd(v: string | null) {
    if (!v) {
        return '';
    }
    return v.includes('T') ? v.slice(0, 10) : v;
}

const form = useForm({
    service: props.account.service,
    label: props.account.label,
    api_key: '' as string,
    api_key_hint: props.account.api_key_hint ?? '',
    quota_limit: props.account.quota_limit ?? '',
    quota_used: props.account.quota_used ?? '',
    billing_date: bd(props.account.billing_date),
    team_id: props.account.team_id ?? ('' as string | number),
    project_id: props.account.project_id ?? ('' as string | number),
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            quota_limit: data.quota_limit === '' ? null : data.quota_limit,
            quota_used: data.quota_used === '' ? null : data.quota_used,
            billing_date: data.billing_date === '' ? null : data.billing_date,
            api_key: data.api_key === '' ? null : data.api_key,
            team_id: data.team_id === '' ? null : data.team_id,
            project_id: data.project_id === '' ? null : data.project_id,
        }))
        .patch(route('ai-accounts.update', props.account.id));
}
</script>

<template>
    <Head title="Edit AI account" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'AI accounts', href: route('ai-accounts.index') },
            { label: account.label },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold dark:text-gray-100">Edit AI account</h1>

        <form class="max-w-xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="service" value="Service" />
                <TextInput id="service" v-model="form.service" required class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.service" />
            </div>
            <div>
                <InputLabel for="label" value="Label" />
                <TextInput id="label" v-model="form.label" required class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.label" />
            </div>
            <div>
                <InputLabel for="api_key" value="API key (leave blank to keep)" />
                <TextInput id="api_key" v-model="form.api_key" type="password" autocomplete="off" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.api_key" />
            </div>
            <div>
                <InputLabel for="api_key_hint" value="API key hint" />
                <TextInput id="api_key_hint" v-model="form.api_key_hint" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.api_key_hint" />
            </div>
            <div>
                <InputLabel for="quota_limit" value="Quota limit" />
                <TextInput id="quota_limit" v-model="form.quota_limit" type="number" step="0.01" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.quota_limit" />
            </div>
            <div>
                <InputLabel for="quota_used" value="Quota used" />
                <TextInput id="quota_used" v-model="form.quota_used" type="number" step="0.01" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.quota_used" />
            </div>
            <div>
                <InputLabel for="billing_date" value="Billing date" />
                <TextInput id="billing_date" v-model="form.billing_date" type="date" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.billing_date" />
            </div>
            <div>
                <InputLabel for="team_id" value="Team" />
                <select id="team_id" v-model="form.team_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100">
                    <option value="">—</option>
                    <option v-for="t in teams" :key="t.id" :value="t.id">{{ t.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.team_id" />
            </div>
            <div>
                <InputLabel for="project_id" value="Project" />
                <select id="project_id" v-model="form.project_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100">
                    <option value="">—</option>
                    <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.project_id" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                <Link :href="route('ai-accounts.show', account.id)" class="inline-flex border px-4 py-2 text-xs uppercase">Cancel</Link>
            </div>
        </form>
    </AppLayout>
</template>
