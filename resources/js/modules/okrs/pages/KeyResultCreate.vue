<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

type ObjectiveLite = { id: number; title: string };

const props = defineProps<{ objective: ObjectiveLite }>();

const form = useForm({
    title: '',
    target: '' as string | number,
    current: '' as string | number,
    unit: '',
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            target: data.target === '' ? null : data.target,
            current: data.current === '' ? null : data.current,
            unit: data.unit === '' ? null : data.unit,
        }))
        .post(route('objectives.key-results.store', props.objective.id));
}
</script>

<template>
    <Head title="Create key result" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives', href: route('objectives.index') },
            { label: objective.title, href: route('objectives.show', objective.id) },
            { label: 'Key results', href: route('objectives.key-results.index', objective.id) },
            { label: 'Create' },
        ]"
        :title="`New key result · ${objective.title}`"
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
                        <InputLabel for="target" value="Target" />
                        <TextInput id="target" v-model="form.target" type="number" step="any" />
                        <InputError class="mt-1" :message="form.errors.target" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="current" value="Current" />
                        <TextInput id="current" v-model="form.current" type="number" step="any" />
                        <InputError class="mt-1" :message="form.errors.current" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="unit" value="Unit" />
                        <TextInput id="unit" v-model="form.unit" />
                        <InputError class="mt-1" :message="form.errors.unit" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                        <Link :href="route('objectives.key-results.index', objective.id)" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
