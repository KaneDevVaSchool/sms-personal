<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

type Site = {
    id: number;
    name: string;
    url: string | null;
    status: string | null;
    tech_stack: string | null;
    cms: string | null;
    ssl_expires_at: string | null;
    resource_id: number | null;
};

const props = defineProps<{
    website: Site;
    resourcesList: { id: number; name: string }[];
}>();

function toDate(v: string | null) {
    if (!v) {
        return '';
    }
    return v.includes('T') ? v.slice(0, 10) : v;
}

const form = useForm({
    name: props.website.name,
    url: props.website.url ?? '',
    status: props.website.status ?? '',
    tech_stack: props.website.tech_stack ?? '',
    cms: props.website.cms ?? '',
    ssl_expires_at: toDate(props.website.ssl_expires_at),
    resource_id: props.website.resource_id ?? ('' as string | number),
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            ssl_expires_at: data.ssl_expires_at === '' ? null : data.ssl_expires_at,
            resource_id: data.resource_id === '' ? null : data.resource_id,
        }))
        .patch(route('websites.update', props.website.id));
}
</script>

<template>
    <Head title="Edit website" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Websites', href: route('websites.index') },
            { label: website.name },
        ]"
        :title="`Edit · ${website.name}`"
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
                        <InputLabel for="url" value="URL" />
                        <TextInput id="url" v-model="form.url" type="url" />
                        <InputError class="mt-1" :message="form.errors.url" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="status" value="Status" />
                        <TextInput id="status" v-model="form.status" />
                        <InputError class="mt-1" :message="form.errors.status" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="tech_stack" value="Tech stack" />
                        <TextInput id="tech_stack" v-model="form.tech_stack" />
                        <InputError class="mt-1" :message="form.errors.tech_stack" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="cms" value="CMS" />
                        <TextInput id="cms" v-model="form.cms" />
                        <InputError class="mt-1" :message="form.errors.cms" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="ssl_expires_at" value="SSL expires at" />
                        <TextInput id="ssl_expires_at" v-model="form.ssl_expires_at" type="date" />
                        <InputError class="mt-1" :message="form.errors.ssl_expires_at" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="resource_id" value="Linked resource" />
                        <select id="resource_id" v-model="form.resource_id" class="form-select">
                            <option value="">—</option>
                            <option v-for="r in resourcesList" :key="r.id" :value="r.id">{{ r.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.resource_id" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                        <Link :href="route('websites.show', website.id)" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
