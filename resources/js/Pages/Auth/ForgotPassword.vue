<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="text-center w-75 m-auto mb-4">
            <h4 class="text-dark-50 pb-0">Reset Password</h4>
            <p class="text-muted mb-0">
                Enter your email and we will send you a password reset link.
            </p>
        </div>

        <div v-if="status" class="alert alert-success mb-3" role="alert">{{ status }}</div>

        <form @submit.prevent="submit">
            <div class="mb-3">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="mb-0 text-center">
                <PrimaryButton class="w-100" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
