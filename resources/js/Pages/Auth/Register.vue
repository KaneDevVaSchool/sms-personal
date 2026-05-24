<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="text-center w-75 m-auto mb-4">
            <h4 class="text-dark-50 pb-0">Sign Up</h4>
            <p class="text-muted mb-0">Create your OPS Platform account.</p>
        </div>

        <form @submit.prevent="submit">
            <div class="mb-3">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" type="text" v-model="form.name" required autofocus autocomplete="name" />
                <InputError :message="form.errors.name" />
            </div>

            <div class="mb-3">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" v-model="form.email" required autocomplete="username" />
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
                <PrimaryButton class="w-100" :disabled="form.processing">Register</PrimaryButton>
            </div>
        </form>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="text-white-50 mb-0">
                    Already have an account?
                    <Link :href="route('login')" class="text-white ms-1 link-offset-3 text-decoration-underline">
                        <b>Sign In</b>
                    </Link>
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
