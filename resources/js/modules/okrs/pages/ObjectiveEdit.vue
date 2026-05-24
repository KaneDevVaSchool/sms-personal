<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

type O = {
    id: number;
    title: string;
    team_id: number | null;
    owner_id: number | null;
    quarter: number | null;
    year: number | null;
};

const props = defineProps<{
    objective: O;
    teams: { id: number; name: string }[];
    users: { id: number; name: string }[];
}>();

const form = useForm({
    title: props.objective.title,
    team_id: props.objective.team_id ?? ('' as string | number),
    owner_id: props.objective.owner_id ?? ('' as string | number),
    quarter: props.objective.quarter ?? ('' as string | number),
    year: props.objective.year ?? ('' as string | number),
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
        .patch(route('objectives.update', props.objective.id));
}
</script>

<template>
    <Head title="Edit objective" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives', href: route('objectives.index') },
            { label: objective.title },
        ]"
        :title="`Edit · ${objective.title}`"
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
                        <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                        <Link :href="route('objectives.show', objective.id)" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
