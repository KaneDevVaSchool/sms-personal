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
    >
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">Knowledge posts</h1>
            <div class="flex flex-wrap gap-3">
                <Link
                    :href="route('knowledge.categories.index')"
                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                >
                    Categories
                </Link>
                <Link :href="route('knowledge.posts.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-500">
                    New post
                </Link>
            </div>
        </div>

        <form class="mb-4 flex gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="search">
            <div class="flex-1">
                <InputLabel for="q" value="Search" />
                <input id="q" v-model="filterForm.q" type="search" class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100" />
            </div>
            <div class="flex items-end">
                <PrimaryButton type="submit" :disabled="filterForm.processing">Search</PrimaryButton>
            </div>
        </form>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Author</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="p in posts.data" :key="p.id">
                        <td class="px-4 py-3">
                            <Link :href="route('knowledge.posts.show', p.id)" class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">
                                {{ p.title }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ p.category?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ p.author?.name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ p.status ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="posts.links.length" :links="posts.links" />
        </div>
    </AppLayout>
</template>
