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
        title="Create category"
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
                        <InputLabel for="slug" value="Slug (optional)" />
                        <TextInput id="slug" v-model="form.slug" />
                        <InputError class="mt-1" :message="form.errors.slug" />
                    </div>
                    <div class="mb-3">
                        <InputLabel for="parent_id" value="Parent" />
                        <select id="parent_id" v-model="form.parent_id" class="form-select">
                            <option value="">—</option>
                            <option v-for="p in parentCategories" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.parent_id" />
                    </div>
                    <div class="d-flex gap-2">
                        <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                        <Link :href="route('knowledge.categories.index')" class="btn btn-light">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
