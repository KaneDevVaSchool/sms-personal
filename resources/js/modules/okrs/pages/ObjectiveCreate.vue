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
        title="Create objective"
    >
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <InputLabel for="title" value="Title" />
                        <TextInput id="title" v-model="form.title" required />
                        <InputError class="mt-1" :message="form.errors.title" />
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
                        <InputLabel for="owner_id" value="Owner" />
                        <select id="owner_id" v-model="form.owner_id" class="form-select">
                            <option value="">—</option>
                            <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.owner_id" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="quarter" value="Quarter (1–4)" />
                        <TextInput id="quarter" v-model="form.quarter" type="number" min="1" max="4" />
                        <InputError class="mt-1" :message="form.errors.quarter" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="year" value="Year" />
                        <TextInput id="year" v-model="form.year" type="number" />
                        <InputError class="mt-1" :message="form.errors.year" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                        <Link :href="route('objectives.index')" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
