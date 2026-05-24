<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="mb-4">
            <h4 class="header-title">Profile Information</h4>
            <p class="text-muted mb-0">Update your account's profile information and email address.</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))">
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

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="mb-3">
                <p class="text-muted">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button" class="btn btn-link p-0 align-baseline">
                        Click here to re-send the verification email.
                    </Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="alert alert-success py-2">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <span v-if="form.recentlySuccessful" class="text-muted small">Saved.</span>
            </div>
        </form>
    </section>
</template>
