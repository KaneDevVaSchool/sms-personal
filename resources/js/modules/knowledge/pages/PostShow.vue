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
        :title="post.title"
    >
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-muted mb-2">{{ post.category?.name ?? 'Uncategorized' }} · {{ post.author?.name }}</p>
                <div class="d-flex flex-wrap gap-2 justify-content-end">
                    <Link :href="route('knowledge.posts.edit', post.id)" class="btn btn-outline-primary btn-sm">Edit</Link>
                    <DangerButton type="button" @click="destroyPost(post.id)">Delete</DangerButton>
                </div>
            </div>
        </div>

        <article class="card">
            <div class="card-body article-body" v-html="post.body ?? '<p class=text-muted>No content.</p>'" />
            <div class="card-footer d-flex flex-wrap gap-3 text-muted small">
                <span>Status: {{ post.status ?? '—' }}</span>
                <span v-if="post.published_at">Published {{ post.published_at }}</span>
            </div>
        </article>
    </AppLayout>
</template>
