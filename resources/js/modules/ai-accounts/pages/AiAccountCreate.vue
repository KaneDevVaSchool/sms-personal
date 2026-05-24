<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    teams: { id: number; name: string }[];
    projects: { id: number; name: string }[];
}>();

const form = useForm({
    service: '',
    label: '',
    api_key: '',
    api_key_hint: '',
    quota_limit: '' as string | number,
    quota_used: '' as string | number,
    billing_date: '',
    team_id: '' as string | number,
    project_id: '' as string | number,
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
        .post(route('ai-accounts.store'));
}
</script>

<template>
    <Head title="Create AI account" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'AI accounts', href: route('ai-accounts.index') },
            { label: 'Create' },
        ]"
        title="Create AI account"
    >
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <InputLabel for="service" value="Service" />
                        <TextInput id="service" v-model="form.service" required />
                        <InputError class="mt-1" :message="form.errors.service" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="label" value="Label" />
                        <TextInput id="label" v-model="form.label" required />
                        <InputError class="mt-1" :message="form.errors.label" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="api_key" value="API key" />
                        <TextInput id="api_key" v-model="form.api_key" type="password" autocomplete="off" />
                        <InputError class="mt-1" :message="form.errors.api_key" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="api_key_hint" value="API key hint" />
                        <TextInput id="api_key_hint" v-model="form.api_key_hint" />
                        <InputError class="mt-1" :message="form.errors.api_key_hint" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="quota_limit" value="Quota limit" />
                        <TextInput id="quota_limit" v-model="form.quota_limit" type="number" step="0.01" />
                        <InputError class="mt-1" :message="form.errors.quota_limit" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="quota_used" value="Quota used" />
                        <TextInput id="quota_used" v-model="form.quota_used" type="number" step="0.01" />
                        <InputError class="mt-1" :message="form.errors.quota_used" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="billing_date" value="Billing date" />
                        <TextInput id="billing_date" v-model="form.billing_date" type="date" />
                        <InputError class="mt-1" :message="form.errors.billing_date" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="team_id" value="Team" />
                        <select id="team_id" v-model="form.team_id" class="form-select">
                            <option value="">—</option>
                            <option v-for="t in teams" :key="t.id" :value="t.id">{{ t.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.team_id" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="project_id" value="Project" />
                        <select id="project_id" v-model="form.project_id" class="form-select">
                            <option value="">—</option>
                            <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.project_id" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                        <Link :href="route('ai-accounts.index')" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
