<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Profile' },
        ]"
        title="Profile"
    >
        <div class="row">
            <div class="col-xl-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <UpdatePasswordForm />
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
