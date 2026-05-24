<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    users: { id: number; name: string }[];
}>();

const form = useForm({
    name: '',
    description: '',
    status: '',
    owner_id: '' as string | number,
    due_date: '',
    progress_percent: '' as string | number,
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            owner_id: data.owner_id === '' ? null : data.owner_id,
            due_date: data.due_date === '' ? null : data.due_date,
            progress_percent: data.progress_percent === '' ? null : data.progress_percent,
        }))
        .post(route('projects.store'));
}
</script>

<template>
    <Head title="Create project" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Projects', href: route('projects.index') },
            { label: 'Create' },
        ]"
        title="Create project"
    >
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" v-model="form.name" required />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="description" value="Description" />
                        <textarea id="description" v-model="form.description" rows="4" class="form-control" />
                        <InputError class="mt-1" :message="form.errors.description" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="status" value="Status" />
                        <TextInput id="status" v-model="form.status" />
                        <InputError class="mt-1" :message="form.errors.status" />
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
                        <InputLabel for="due_date" value="Due date" />
                        <TextInput id="due_date" v-model="form.due_date" type="date" />
                        <InputError class="mt-1" :message="form.errors.due_date" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="progress_percent" value="Progress %" />
                        <TextInput id="progress_percent" v-model="form.progress_percent" type="number" min="0" max="100" />
                        <InputError class="mt-1" :message="form.errors.progress_percent" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                        <Link :href="route('projects.index')" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
