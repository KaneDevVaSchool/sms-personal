<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

type UserDetail = {
    id: number;
    name: string;
    email: string;
    department_id: number | null;
    roles?: { name: string }[];
};

const props = defineProps<{
    user: UserDetail;
    departments: { id: number; name: string }[];
    roleOptions: string[];
}>();

const form = useForm({
    department_id: props.user.department_id,
    roles: props.user.roles?.map((r) => r.name) ?? [],
});

function submit() {
    form.patch(route('team.users.update', props.user.id));
}
</script>

<template>
    <Head :title="`Team — ${user.name}`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Team', href: route('team.users.index') },
            { label: user.name },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ user.name }}</h1>

        <form class="max-w-lg space-y-4 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel value="Email" />
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ user.email }}</p>
            </div>

            <div>
                <InputLabel for="department_id" value="Department" />
                <select
                    id="department_id"
                    v-model="form.department_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-950"
                >
                    <option :value="null">—</option>
                    <option v-for="d in departments" :key="d.id" :value="d.id">{{ d.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.department_id" />
            </div>

            <div>
                <InputLabel value="Roles" />
                <div class="mt-2 space-y-2">
                    <label v-for="role in roleOptions" :key="role" class="flex items-center gap-2 text-sm">
                        <input v-model="form.roles" type="checkbox" :value="role" class="rounded border-gray-300" />
                        {{ role }}
                    </label>
                </div>
                <InputError class="mt-2" :message="form.errors.roles" />
            </div>

            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
        </form>
    </AppLayout>
</template>
