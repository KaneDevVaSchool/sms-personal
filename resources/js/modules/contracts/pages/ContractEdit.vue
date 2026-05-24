<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

type C = {
    id: number;
    name: string;
    partner: string | null;
    type: string | null;
    value: string | number | null;
    signed_at: string | null;
    expires_at: string | null;
    status: string | null;
};

const props = defineProps<{ contract: C }>();

function d(v: string | null) {
    if (!v) {
        return '';
    }
    return v.includes('T') ? v.slice(0, 10) : v;
}

const form = useForm({
    name: props.contract.name,
    partner: props.contract.partner ?? '',
    type: props.contract.type ?? '',
    value: props.contract.value ?? '',
    signed_at: d(props.contract.signed_at),
    expires_at: d(props.contract.expires_at),
    status: props.contract.status ?? '',
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            value: data.value === '' ? null : data.value,
            signed_at: data.signed_at === '' ? null : data.signed_at,
            expires_at: data.expires_at === '' ? null : data.expires_at,
        }))
        .patch(route('contracts.update', props.contract.id));
}
</script>

<template>
    <Head title="Edit contract" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Contracts', href: route('contracts.index') },
            { label: contract.name },
        ]"
        :title="`Edit · ${contract.name}`"
    >
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" v-model="form.name" required />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="partner" value="Partner" />
                        <TextInput id="partner" v-model="form.partner" />
                        <InputError class="mt-1" :message="form.errors.partner" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="type" value="Type" />
                        <TextInput id="type" v-model="form.type" />
                        <InputError class="mt-1" :message="form.errors.type" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="value" value="Value" />
                        <TextInput id="value" v-model="form.value" type="number" step="0.01" />
                        <InputError class="mt-1" :message="form.errors.value" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="signed_at" value="Signed at" />
                        <TextInput id="signed_at" v-model="form.signed_at" type="date" />
                        <InputError class="mt-1" :message="form.errors.signed_at" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="expires_at" value="Expires at" />
                        <TextInput id="expires_at" v-model="form.expires_at" type="date" />
                        <InputError class="mt-1" :message="form.errors.expires_at" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="status" value="Status" />
                        <TextInput id="status" v-model="form.status" />
                        <InputError class="mt-1" :message="form.errors.status" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                        <Link :href="route('contracts.show', contract.id)" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
