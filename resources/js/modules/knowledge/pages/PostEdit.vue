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
        :title="`Edit · ${post.title}`"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end">
                <DangerButton type="button" @click="destroyPost">Delete</DangerButton>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <InputLabel for="title" value="Title" />
                        <TextInput id="title" v-model="form.title" required />
                        <InputError class="mt-1" :message="form.errors.title" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="slug" value="Slug (optional)" />
                        <TextInput id="slug" v-model="form.slug" />
                        <InputError class="mt-1" :message="form.errors.slug" />
                    </div>
                    <div class="mb-3">
                        <InputLabel value="Body" />
                        <TipTapBodyField v-model="form.body" />
                        <InputError class="mt-1" :message="form.errors.body" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="category_id" value="Category" />
                        <select id="category_id" v-model="form.category_id" class="form-select">
                            <option value="">—</option>
                            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.category_id" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="status" value="Status" />
                        <TextInput id="status" v-model="form.status" />
                        <InputError class="mt-1" :message="form.errors.status" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="published_at" value="Published at" />
                        <TextInput id="published_at" v-model="form.published_at" type="datetime-local" />
                        <InputError class="mt-1" :message="form.errors.published_at" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                        <Link :href="route('knowledge.posts.show', post.id)" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
