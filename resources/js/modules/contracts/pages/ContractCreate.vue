<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    partner: '',
    type: '',
    value: '' as string | number,
    signed_at: '',
    expires_at: '',
    status: '',
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            value: data.value === '' ? null : data.value,
            signed_at: data.signed_at === '' ? null : data.signed_at,
            expires_at: data.expires_at === '' ? null : data.expires_at,
        }))
        .post(route('contracts.store'));
}
</script>

<template>
    <Head title="Create contract" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Contracts', href: route('contracts.index') },
            { label: 'Create' },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold dark:text-gray-100">Create contract</h1>

        <form class="max-w-xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" required class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="partner" value="Partner" />
                <TextInput id="partner" v-model="form.partner" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.partner" />
            </div>
            <div>
                <InputLabel for="type" value="Type" />
                <TextInput id="type" v-model="form.type" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.type" />
            </div>
            <div>
                <InputLabel for="value" value="Value" />
                <TextInput id="value" v-model="form.value" type="number" step="0.01" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.value" />
            </div>
            <div>
                <InputLabel for="signed_at" value="Signed at" />
                <TextInput id="signed_at" v-model="form.signed_at" type="date" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.signed_at" />
            </div>
            <div>
                <InputLabel for="expires_at" value="Expires at" />
                <TextInput id="expires_at" v-model="form.expires_at" type="date" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.expires_at" />
            </div>
            <div>
                <InputLabel for="status" value="Status" />
                <TextInput id="status" v-model="form.status" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.status" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Link :href="route('contracts.index')" class="inline-flex border px-4 py-2 text-xs uppercase">Cancel</Link>
            </div>
        </form>
    </AppLayout>
</template>
