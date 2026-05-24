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
    >
        <h1 class="mb-6 text-2xl font-semibold dark:text-gray-100">Create project</h1>

        <form class="max-w-xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" required class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="description" value="Description" />
                <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100" />
                <InputError class="mt-2" :message="form.errors.description" />
            </div>
            <div>
                <InputLabel for="status" value="Status" />
                <TextInput id="status" v-model="form.status" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.status" />
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
                <InputLabel for="due_date" value="Due date" />
                <TextInput id="due_date" v-model="form.due_date" type="date" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.due_date" />
            </div>
            <div>
                <InputLabel for="progress_percent" value="Progress %" />
                <TextInput id="progress_percent" v-model="form.progress_percent" type="number" min="0" max="100" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.progress_percent" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Link
                    :href="route('projects.index')"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest hover:bg-gray-50 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </AppLayout>
</template>
