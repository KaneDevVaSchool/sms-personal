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
    >
        <div class="mb-4 flex justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">Categories</h1>
            <div class="flex gap-2">
                <Link :href="route('knowledge.posts.index')" class="rounded-md border px-3 py-2 text-sm dark:border-gray-600">Posts</Link>
                <Link :href="route('knowledge.categories.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white">New</Link>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-950">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Slug</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-for="c in categories.data" :key="c.id">
                        <td class="px-4 py-3 font-medium">{{ c.name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ c.slug }}</td>
                        <td class="px-4 py-3 text-right">
                            <Link :href="route('knowledge.categories.edit', c.id)" class="mr-2 text-sm text-indigo-600 dark:text-indigo-400">
                                Edit
                            </Link>
                            <DangerButton type="button" class="py-1 text-xs" @click="destroyCat(c.id)">Delete</DangerButton>
                        </td>
                    </tr>
                </tbody>
            </table>
            <PaginationLinks v-if="categories.links.length" :links="categories.links" />
        </div>
    </AppLayout>
</template>
