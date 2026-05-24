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
        title="Edit department"
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
                        <InputLabel for="manager_id" value="Manager (optional)" />
                        <select id="manager_id" v-model="form.manager_id" class="form-select">
                            <option value="">— None —</option>
                            <option v-for="u in users" :key="u.id" :value="u.id">{{ u.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.manager_id" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                        <Link :href="route('team.departments.index')" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
