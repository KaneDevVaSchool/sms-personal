<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

type ObjectiveLite = { id: number; title: string };
type KR = {
    id: number;
    title: string;
    target: string | number | null;
    current: string | number | null;
    unit: string | null;
};

const props = defineProps<{ objective: ObjectiveLite; keyResult: KR }>();

const form = useForm({
    title: props.keyResult.title,
    target: props.keyResult.target ?? '',
    current: props.keyResult.current ?? '',
    unit: props.keyResult.unit ?? '',
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            target: data.target === '' ? null : data.target,
            current: data.current === '' ? null : data.current,
            unit: data.unit === '' ? null : data.unit,
        }))
        .patch(route('objectives.key-results.update', [props.objective.id, props.keyResult.id]));
}
</script>

<template>
    <Head title="Edit key result" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives', href: route('objectives.index') },
            { label: objective.title, href: route('objectives.show', objective.id) },
            { label: 'Key results', href: route('objectives.key-results.index', objective.id) },
            { label: keyResult.title },
        ]"
        title="Edit key result"
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
                        <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                        <Link :href="route('objectives.key-results.index', objective.id)" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
