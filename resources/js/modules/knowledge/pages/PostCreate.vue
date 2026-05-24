<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import TipTapBodyField from '@/modules/knowledge/components/TipTapBodyField.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    categories: { id: number; name: string }[];
}>();

const form = useForm({
    title: '',
    slug: '',
    body: '',
    category_id: '' as string | number,
    status: '',
    published_at: '',
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            category_id: data.category_id === '' ? null : data.category_id,
            slug: data.slug === '' ? null : data.slug,
            published_at: data.published_at === '' ? null : data.published_at,
        }))
        .post(route('knowledge.posts.store'));
}
</script>

<template>
    <Head title="Create post" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Posts', href: route('knowledge.posts.index') },
            { label: 'Create' },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold dark:text-gray-100">Create post</h1>

        <form class="max-w-3xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="title" value="Title" />
                <TextInput id="title" v-model="form.title" required class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.title" />
            </div>
            <div>
                <InputLabel for="slug" value="Slug (optional)" />
                <TextInput id="slug" v-model="form.slug" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.slug" />
            </div>
            <div>
                <InputLabel value="Body" />
                <TipTapBodyField v-model="form.body" />
                <InputError class="mt-2" :message="form.errors.body" />
            </div>
            <div>
                <InputLabel for="category_id" value="Category" />
                <select
                    id="category_id"
                    v-model="form.category_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100"
                >
                    <option value="">—</option>
                    <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.category_id" />
            </div>
            <div>
                <InputLabel for="status" value="Status" />
                <TextInput id="status" v-model="form.status" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.status" />
            </div>
            <div>
                <InputLabel for="published_at" value="Published at" />
                <TextInput id="published_at" v-model="form.published_at" type="datetime-local" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.published_at" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Link :href="route('knowledge.posts.index')" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs uppercase">Cancel</Link>
            </div>
        </form>
    </AppLayout>
</template>
