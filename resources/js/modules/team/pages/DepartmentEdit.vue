<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    department: { id: number; name: string; manager_id: number | null };
    users: { id: number; name: string }[];
}>();

const form = useForm({
    name: props.department.name,
    manager_id: props.department.manager_id ?? ('' as string | number),
});

function submit() {
    form.transform((data) => ({
        ...data,
        manager_id: data.manager_id === '' ? null : data.manager_id,
    })).put(route('team.departments.update', props.department.id));
}
</script>

<template>
    <Head title="Edit department" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Departments', href: route('team.departments.index') },
            { label: 'Edit' },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold text-gray-900 dark:text-gray-100">Edit department</h1>

        <form class="max-w-xl space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="manager_id" value="Manager (optional)" />
                <select
                    id="manager_id"
                    v-model="form.manager_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100"
                >
                    <option value="">— None —</option>
                    <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.manager_id" />
            </div>
            <div class="flex items-center gap-3">
                <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                <Link
                    :href="route('team.departments.index')"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </AppLayout>
</template>
