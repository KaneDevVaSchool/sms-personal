<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="text-center w-75 m-auto mb-4">
            <h4 class="text-dark-50 pb-0">Verify Email</h4>
            <p class="text-muted mb-0">
                Please verify your email address using the link we sent you, or request a new link below.
            </p>
        </div>

        <div v-if="verificationLinkSent" class="alert alert-success mb-3" role="alert">
            A new verification link has been sent to your email address.
        </div>

        <form @submit.prevent="submit" class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <PrimaryButton :disabled="form.processing">Resend Verification Email</PrimaryButton>
            <Link :href="route('logout')" method="post" as="button" class="btn btn-link">Log Out</Link>
        </form>
    </GuestLayout>
</template>
