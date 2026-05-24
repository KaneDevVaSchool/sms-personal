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
        title="Create post"
    >
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
                        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                        <Link :href="route('knowledge.posts.index')" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
