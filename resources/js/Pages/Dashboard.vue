<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

type Widgets = {
    team?: { users_count?: number; departments_count?: number };
    resources?: number;
    websites?: number;
    projects?: number;
    knowledge?: { posts_count?: number; categories_count?: number };
    contracts?: number;
    ai_accounts?: number;
    okrs?: number;
};

defineProps<{
    widgets: Widgets;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="[{ label: 'Dashboard' }]">
        <h1 class="mb-8 text-2xl font-semibold text-gray-900 dark:text-gray-100">Overview</h1>

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            <Link
                :href="route('team.users.index')"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-600"
            >
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Team</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-50">{{ widgets.team?.users_count ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500">users · {{ widgets.team?.departments_count ?? 0 }} departments</p>
            </Link>

            <Link
                :href="route('resources.index')"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-600"
            >
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Resources</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-50">{{ widgets.resources ?? 0 }}</p>
            </Link>

            <Link
                :href="route('websites.index')"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-600"
            >
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Websites</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-50">{{ widgets.websites ?? 0 }}</p>
            </Link>

            <Link
                :href="route('projects.index')"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-600"
            >
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Projects</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-50">{{ widgets.projects ?? 0 }}</p>
            </Link>

            <Link
                :href="route('knowledge.posts.index')"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-600"
            >
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Knowledge</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-50">{{ widgets.knowledge?.posts_count ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ widgets.knowledge?.categories_count ?? 0 }} categories</p>
            </Link>

            <Link
                :href="route('contracts.index')"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-600"
            >
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Contracts</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-50">{{ widgets.contracts ?? 0 }}</p>
            </Link>

            <Link
                :href="route('ai-accounts.index')"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-600"
            >
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">AI accounts</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-50">{{ widgets.ai_accounts ?? 0 }}</p>
            </Link>

            <Link
                :href="route('objectives.index')"
                class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-600"
            >
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">OKRs</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-50">{{ widgets.okrs ?? 0 }}</p>
                <p class="mt-1 text-xs text-gray-500">active objectives count</p>
            </Link>
        </div>
    </AppLayout>
</template>
