<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="text-center w-75 m-auto mb-4">
            <h4 class="text-dark-50 pb-0">New Password</h4>
            <p class="text-muted mb-0">Choose a new password for your account.</p>
        </div>

        <form @submit.prevent="submit">
            <div class="mb-3">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" v-model="form.email" required autofocus autocomplete="username" />
                <InputError :message="form.errors.email" />
            </div>

            <div class="mb-3">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" type="password" v-model="form.password" required autocomplete="new-password" />
                <InputError :message="form.errors.password" />
            </div>

            <div class="mb-3">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError :message="form.errors.password_confirmation" />
            </div>

            <div class="mb-0 text-center">
                <PrimaryButton class="w-100" :disabled="form.processing">Reset Password</PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
