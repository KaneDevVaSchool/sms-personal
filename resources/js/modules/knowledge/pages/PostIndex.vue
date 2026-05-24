<script setup lang="ts">
import PaginationLinks from '@/Components/PaginationLinks.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { Paginated } from '@/types/common';

type PostRow = {
    id: number;
    title: string;
    status: string | null;
    updated_at: string;
    category?: { id: number; name: string } | null;
    author?: { id: number; name: string } | null;
};

const props = defineProps<{
    posts: Paginated<PostRow>;
    filters: { q: string };
}>();

const filterForm = useForm({ q: props.filters.q ?? '' });

function search() {
    filterForm.get(route('knowledge.posts.index'), { preserveState: true, replace: true });
}
</script>

<template>
    <Head title="Knowledge — Posts" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Posts' },
        ]"
        title="Knowledge posts"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h4 class="header-title mb-0">Knowledge posts</h4>
                <div class="d-flex flex-wrap gap-2">
                    <Link :href="route('knowledge.categories.index')" class="btn btn-light btn-sm">Categories</Link>
                    <Link :href="route('knowledge.posts.create')" class="btn btn-primary btn-sm">New post</Link>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form class="row g-3 align-items-end" @submit.prevent="search">
                    <div class="col-md-10">
                        <InputLabel for="q" value="Search" />
                        <input id="q" v-model="filterForm.q" type="search" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        <PrimaryButton type="submit" :disabled="filterForm.processing">Search</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in posts.data" :key="p.id">
                            <td>
                                <Link :href="route('knowledge.posts.show', p.id)" class="fw-medium">{{ p.title }}</Link>
                            </td>
                            <td>{{ p.category?.name ?? '—' }}</td>
                            <td>{{ p.author?.name ?? '—' }}</td>
                            <td>{{ p.status ?? '—' }}</td>
                        </tr>
                        <tr v-if="!posts.data.length">
                            <td colspan="4" class="text-center text-muted py-4">No posts found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <PaginationLinks v-if="posts.links.length" :links="posts.links" />
        </div>
    </AppLayout>
</template>
