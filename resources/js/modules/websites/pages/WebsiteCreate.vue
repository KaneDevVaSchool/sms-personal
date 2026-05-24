<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    resourcesList: { id: number; name: string }[];
}>();

const form = useForm({
    name: '',
    url: '',
    status: '',
    tech_stack: '',
    cms: '',
    ssl_expires_at: '',
    resource_id: '' as string | number,
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            ssl_expires_at: data.ssl_expires_at === '' ? null : data.ssl_expires_at,
            resource_id: data.resource_id === '' ? null : data.resource_id,
        }))
        .post(route('websites.store'));
}
</script>

<template>
    <Head title="Create website" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Websites', href: route('websites.index') },
            { label: 'Create' },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold dark:text-gray-100">Create website</h1>

        <form class="max-w-xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="url" value="URL" />
                <TextInput id="url" v-model="form.url" type="url" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.url" />
            </div>
            <div>
                <InputLabel for="status" value="Status" />
                <TextInput id="status" v-model="form.status" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.status" />
            </div>
            <div>
                <InputLabel for="tech_stack" value="Tech stack" />
                <TextInput id="tech_stack" v-model="form.tech_stack" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.tech_stack" />
            </div>
            <div>
                <InputLabel for="cms" value="CMS" />
                <TextInput id="cms" v-model="form.cms" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.cms" />
            </div>
            <div>
                <InputLabel for="ssl_expires_at" value="SSL expires at" />
                <TextInput id="ssl_expires_at" v-model="form.ssl_expires_at" type="date" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.ssl_expires_at" />
            </div>
            <div>
                <InputLabel for="resource_id" value="Linked resource" />
                <select
                    id="resource_id"
                    v-model="form.resource_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100"
                >
                    <option value="">—</option>
                    <option v-for="r in resourcesList" :key="r.id" :value="r.id">{{ r.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.resource_id" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Link
                    :href="route('websites.index')"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest hover:bg-gray-50 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </AppLayout>
</template>
