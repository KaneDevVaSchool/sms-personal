<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, Link, router } from '@inertiajs/vue3';

type PostShow = {
    id: number;
    title: string;
    body: string | null;
    status: string | null;
    published_at: string | null;
    category?: { id: number; name: string } | null;
    author?: { id: number; name: string } | null;
};

defineProps<{
    post: PostShow;
}>();

function destroyPost(id: number) {
    if (confirm('Delete this post?')) {
        router.delete(route('knowledge.posts.destroy', id));
    }
}
</script>

<template>
    <Head :title="`${post.title} · Post`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Posts', href: route('knowledge.posts.index') },
            { label: post.title },
        ]"
    >
        <div class="mb-6 flex flex-wrap justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">{{ post.category?.name ?? 'Uncategorized' }} · {{ post.author?.name }}</p>
                <h1 class="text-2xl font-semibold dark:text-gray-100">{{ post.title }}</h1>
            </div>
            <div class="flex gap-2">
                <Link
                    :href="route('knowledge.posts.edit', post.id)"
                    class="rounded-md border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-800"
                >
                    Edit
                </Link>
                <DangerButton type="button" class="text-sm tracking-normal normal-case" @click="destroyPost(post.id)">Delete</DangerButton>
            </div>
        </div>

        <article class="rounded-lg border border-gray-200 bg-white p-6 prose-headings:text-gray-900 dark:border-gray-800 dark:bg-gray-900 dark:prose-headings:text-gray-100">
            <div class="article-body text-gray-900 dark:text-gray-100 [&_a]:text-indigo-600 dark:[&_a]:text-indigo-400" v-html="post.body ?? '<p class=text-gray-500>No content.</p>'" />
            <footer class="mt-6 flex flex-wrap gap-4 border-t pt-4 text-sm text-gray-500">
                <span>Status: {{ post.status ?? '—' }}</span>
                <span v-if="post.published_at">Published {{ post.published_at }}</span>
            </footer>
        </article>
    </AppLayout>
</template>
