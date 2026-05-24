<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

type ResourcePayload = {
    id: number;
    name: string;
    resource_type_id: number | null;
    type: string | null;
    url: string | null;
    owner_team_id: number | null;
    status: string | null;
    expires_at: string | null;
    cost_monthly: string | number | null;
    notes: string | null;
};

const props = defineProps<{
    resource: ResourcePayload;
    resourceTypes: { id: number; name: string }[];
    teams: { id: number; name: string }[];
}>();

function toDateInput(v: string | null): string {
    if (!v) {
        return '';
    }
    return v.includes('T') ? v.slice(0, 10) : v;
}

const form = useForm({
    name: props.resource.name,
    resource_type_id: props.resource.resource_type_id ?? ('' as string | number),
    type: props.resource.type ?? '',
    url: props.resource.url ?? '',
    owner_team_id: props.resource.owner_team_id ?? ('' as string | number),
    status: props.resource.status ?? '',
    expires_at: toDateInput(props.resource.expires_at),
    cost_monthly: props.resource.cost_monthly ?? '',
    notes: props.resource.notes ?? '',
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            resource_type_id: data.resource_type_id === '' ? null : data.resource_type_id,
            owner_team_id: data.owner_team_id === '' ? null : data.owner_team_id,
            expires_at: data.expires_at === '' ? null : data.expires_at,
            cost_monthly: data.cost_monthly === '' ? null : data.cost_monthly,
        }))
        .patch(route('resources.update', props.resource.id));
}
</script>

<template>
    <Head title="Edit resource" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Resources', href: route('resources.index') },
            { label: `Edit · ${resource.name}` },
        ]"
        :title="`Edit · ${resource.name}`"
    >
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" v-model="form.name" type="text" required />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="resource_type_id" value="Resource type" />
                        <select id="resource_type_id" v-model="form.resource_type_id" class="form-select">
                            <option value="">—</option>
                            <option v-for="t in resourceTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.resource_type_id" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="type" value="Type (legacy label)" />
                        <TextInput id="type" v-model="form.type" type="text" />
                        <InputError class="mt-1" :message="form.errors.type" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="url" value="URL" />
                        <TextInput id="url" v-model="form.url" type="url" />
                        <InputError class="mt-1" :message="form.errors.url" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="owner_team_id" value="Owner team" />
                        <select id="owner_team_id" v-model="form.owner_team_id" class="form-select">
                            <option value="">—</option>
                            <option v-for="tm in teams" :key="tm.id" :value="tm.id">{{ tm.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.owner_team_id" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="status" value="Status" />
                        <TextInput id="status" v-model="form.status" type="text" />
                        <InputError class="mt-1" :message="form.errors.status" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="expires_at" value="Expires at" />
                        <TextInput id="expires_at" v-model="form.expires_at" type="date" />
                        <InputError class="mt-1" :message="form.errors.expires_at" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="cost_monthly" value="Cost (monthly)" />
                        <TextInput id="cost_monthly" v-model="form.cost_monthly" type="number" step="0.01" />
                        <InputError class="mt-1" :message="form.errors.cost_monthly" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="notes" value="Notes" />
                        <textarea id="notes" v-model="form.notes" rows="4" class="form-control" />
                        <InputError class="mt-1" :message="form.errors.notes" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                        <Link :href="route('resources.show', resource.id)" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
