<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="text-center w-75 m-auto mb-4">
            <h4 class="text-dark-50 text-center pb-0">Sign In</h4>
            <p class="text-muted mb-0">Enter your email and password to access OPS Platform.</p>
        </div>

        <div v-if="status" class="alert alert-success mb-3" role="alert">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="mb-3">
                <InputLabel for="email" value="Email address" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <InputLabel for="password" value="Password" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-muted fs-12"
                    >
                        Forgot your password?
                    </Link>
                </div>
                <div class="input-group input-group-merge">
                    <TextInput
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password"
                    />
                    <div
                        class="input-group-text"
                        role="button"
                        tabindex="0"
                        @click="showPassword = !showPassword"
                    >
                        <span class="password-eye"></span>
                    </div>
                </div>
                <InputError :message="form.errors.password" />
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <Checkbox id="remember" name="remember" v-model:checked="form.remember" />
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
            </div>

            <div class="mb-0 text-center">
                <PrimaryButton class="w-100" :disabled="form.processing">Log In</PrimaryButton>
            </div>
        </form>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="text-white-50 mb-0">
                    Don't have an account?
                    <Link
                        :href="route('register')"
                        class="text-white ms-1 link-offset-3 text-decoration-underline"
                    >
                        <b>Sign Up</b>
                    </Link>
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
