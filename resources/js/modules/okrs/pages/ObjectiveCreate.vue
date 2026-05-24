<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    teams: { id: number; name: string }[];
    users: { id: number; name: string }[];
}>();

const form = useForm({
    title: '',
    team_id: '' as string | number,
    owner_id: '' as string | number,
    quarter: '' as string | number,
    year: '' as string | number,
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            team_id: data.team_id === '' ? null : data.team_id,
            owner_id: data.owner_id === '' ? null : data.owner_id,
            quarter: data.quarter === '' ? null : data.quarter,
            year: data.year === '' ? null : data.year,
        }))
        .post(route('objectives.store'));
}
</script>

<template>
    <Head title="Create objective" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives', href: route('objectives.index') },
            { label: 'Create' },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold dark:text-gray-100">Create objective</h1>

        <form class="max-w-xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="title" value="Title" />
                <TextInput id="title" v-model="form.title" required class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.title" />
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
                <InputLabel for="owner_id" value="Owner" />
                <select id="owner_id" v-model="form.owner_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100">
                    <option value="">—</option>
                    <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.owner_id" />
            </div>
            <div>
                <InputLabel for="quarter" value="Quarter (1–4)" />
                <TextInput id="quarter" v-model="form.quarter" type="number" min="1" max="4" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.quarter" />
            </div>
            <div>
                <InputLabel for="year" value="Year" />
                <TextInput id="year" v-model="form.year" type="number" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.year" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Link :href="route('objectives.index')" class="inline-flex border px-4 py-2 text-xs uppercase">Cancel</Link>
            </div>
        </form>
    </AppLayout>
</template>
