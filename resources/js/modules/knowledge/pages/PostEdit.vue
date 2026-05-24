<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TipTapBodyField from '@/modules/knowledge/components/TipTapBodyField.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

type Post = {
    id: number;
    title: string;
    slug: string;
    body: string | null;
    category_id: number | null;
    status: string | null;
    published_at: string | null;
};

const props = defineProps<{
    post: Post;
    categories: { id: number; name: string }[];
}>();

function publishedLocal(v: string | null) {
    if (!v) {
        return '';
    }
    if (v.includes('T')) {
        return v.slice(0, 16);
    }
    return `${v.slice(0, 10)}T09:00`;
}

const form = useForm({
    title: props.post.title,
    slug: props.post.slug ?? '',
    body: props.post.body ?? '',
    category_id: props.post.category_id ?? ('' as string | number),
    status: props.post.status ?? '',
    published_at: publishedLocal(props.post.published_at),
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            category_id: data.category_id === '' ? null : data.category_id,
            slug: data.slug === '' ? null : data.slug,
            published_at: data.published_at === '' ? null : data.published_at,
        }))
        .patch(route('knowledge.posts.update', props.post.id));
}

function destroyPost() {
    if (confirm('Delete this post?')) {
        router.delete(route('knowledge.posts.destroy', props.post.id));
    }
}
</script>

<template>
    <Head title="Edit post" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Posts', href: route('knowledge.posts.index') },
            { label: post.title },
        ]"
    >
        <div class="mb-6 flex flex-wrap justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">Edit post</h1>
            <DangerButton type="button" class="text-sm tracking-normal normal-case" @click="destroyPost">Delete</DangerButton>
        </div>

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
                <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                <Link :href="route('knowledge.posts.show', post.id)" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs uppercase">Cancel</Link>
            </div>
        </form>
    </AppLayout>
</template>
