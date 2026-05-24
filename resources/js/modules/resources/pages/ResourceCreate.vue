<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    resourceTypes: { id: number; name: string }[];
    teams: { id: number; name: string }[];
}>();

const form = useForm({
    name: '',
    resource_type_id: '' as string | number,
    type: '',
    url: '',
    owner_team_id: '' as string | number,
    status: '',
    expires_at: '',
    cost_monthly: '' as string | number,
    notes: '',
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
        .post(route('resources.store'));
}
</script>

<template>
    <Head title="Create resource" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Resources', href: route('resources.index') },
            { label: 'Create' },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold text-gray-900 dark:text-gray-100">Create resource</h1>

        <form class="max-w-2xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="resource_type_id" value="Resource type" />
                <select
                    id="resource_type_id"
                    v-model="form.resource_type_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100"
                >
                    <option value="">—</option>
                    <option v-for="t in resourceTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.resource_type_id" />
            </div>
            <div>
                <InputLabel for="type" value="Type (legacy label)" />
                <TextInput id="type" v-model="form.type" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.type" />
            </div>
            <div>
                <InputLabel for="url" value="URL" />
                <TextInput id="url" v-model="form.url" type="url" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.url" />
            </div>
            <div>
                <InputLabel for="owner_team_id" value="Owner team" />
                <select
                    id="owner_team_id"
                    v-model="form.owner_team_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100"
                >
                    <option value="">—</option>
                    <option v-for="tm in teams" :key="tm.id" :value="tm.id">{{ tm.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.owner_team_id" />
            </div>
            <div>
                <InputLabel for="status" value="Status" />
                <TextInput id="status" v-model="form.status" type="text" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.status" />
            </div>
            <div>
                <InputLabel for="expires_at" value="Expires at" />
                <TextInput id="expires_at" v-model="form.expires_at" type="date" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.expires_at" />
            </div>
            <div>
                <InputLabel for="cost_monthly" value="Cost (monthly)" />
                <TextInput id="cost_monthly" v-model="form.cost_monthly" type="number" step="0.01" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.cost_monthly" />
            </div>
            <div>
                <InputLabel for="notes" value="Notes" />
                <textarea
                    id="notes"
                    v-model="form.notes"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100"
                />
                <InputError class="mt-2" :message="form.errors.notes" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Link
                    :href="route('resources.index')"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </AppLayout>
</template>
