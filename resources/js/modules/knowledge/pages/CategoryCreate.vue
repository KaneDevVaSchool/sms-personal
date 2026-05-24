<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    parentCategories: { id: number; name: string }[];
}>();

const form = useForm({
    name: '',
    slug: '',
    parent_id: '' as string | number,
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            parent_id: data.parent_id === '' ? null : data.parent_id,
            slug: data.slug === '' ? null : data.slug,
        }))
        .post(route('knowledge.categories.store'));
}
</script>

<template>
    <Head title="Create category" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Categories', href: route('knowledge.categories.index') },
            { label: 'Create' },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold dark:text-gray-100">Create category</h1>

        <form class="max-w-xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput id="name" v-model="form.name" required class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
                <InputLabel for="slug" value="Slug (optional)" />
                <TextInput id="slug" v-model="form.slug" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.slug" />
            </div>
            <div>
                <InputLabel for="parent_id" value="Parent" />
                <select id="parent_id" v-model="form.parent_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100">
                    <option value="">—</option>
                    <option v-for="p in parentCategories" :key="p.id" :value="p.id">{{ p.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.parent_id" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Link :href="route('knowledge.categories.index')" class="inline-flex items-center border px-4 py-2 text-xs uppercase">Cancel</Link>
            </div>
        </form>
    </AppLayout>
</template>
