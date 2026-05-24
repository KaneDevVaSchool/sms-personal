<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="text-center w-75 m-auto mb-4">
            <h4 class="text-dark-50 pb-0">Confirm Password</h4>
            <p class="text-muted mb-0">Please confirm your password before continuing.</p>
        </div>

        <form @submit.prevent="submit">
            <div class="mb-3">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="mb-0 text-center">
                <PrimaryButton class="w-100" :disabled="form.processing">Confirm</PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
