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
        :title="user.name"
    >
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <InputLabel value="Email" />
                        <p class="mb-0 text-muted">{{ user.email }}</p>
                    </div>

                    <div class="mb-3">
                        <InputLabel for="department_id" value="Department" />
                        <select id="department_id" v-model="form.department_id" class="form-select">
                            <option :value="null">—</option>
                            <option v-for="d in departments" :key="d.id" :value="d.id">{{ d.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.department_id" />
                    </div>

                    <div class="mb-3">
                        <InputLabel value="Roles" />
                        <div class="mt-1">
                            <div v-for="role in roleOptions" :key="role" class="form-check">
                                <input
                                    :id="`role-${role}`"
                                    v-model="form.roles"
                                    type="checkbox"
                                    class="form-check-input"
                                    :value="role"
                                />
                                <label class="form-check-label" :for="`role-${role}`">{{ role }}</label>
                            </div>
                        </div>
                        <InputError class="mt-1" :message="form.errors.roles" />
                    </div>

                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
