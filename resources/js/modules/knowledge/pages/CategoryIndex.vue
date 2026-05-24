<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type Cat = {
    id: number;
    name: string;
    slug: string;
};

defineProps<{
    categories: Paginated<Cat>;
}>();

function destroyCat(id: number) {
    if (confirm('Delete this category?')) {
        router.delete(route('knowledge.categories.destroy', id));
    }
}
</script>

<template>
    <Head title="Knowledge — Categories" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Categories' },
        ]"
        title="Categories"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h4 class="header-title mb-0">Categories</h4>
                <div class="d-flex flex-wrap gap-2">
                    <Link :href="route('knowledge.posts.index')" class="btn btn-light btn-sm">Posts</Link>
                    <Link :href="route('knowledge.categories.create')" class="btn btn-primary btn-sm">New category</Link>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in categories.data" :key="c.id">
                            <td class="fw-medium">{{ c.name }}</td>
                            <td>{{ c.slug }}</td>
                            <td class="text-end">
                                <Link :href="route('knowledge.categories.edit', c.id)" class="btn btn-sm btn-outline-primary me-2">
                                    Edit
                                </Link>
                                <DangerButton type="button" class="btn-sm" @click="destroyCat(c.id)">Delete</DangerButton>
                            </td>
                        </tr>
                        <tr v-if="!categories.data.length">
                            <td colspan="3" class="text-center text-muted py-4">No categories found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="categories.links.length" :links="categories.links" />
        </div>
    </AppLayout>
</template>
