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

    <AppLayout :breadcrumbs="[{ label: 'Dashboard' }]" title="Overview">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
            <div class="col">
                <Link :href="route('team.users.index')" class="text-reset">
                    <div class="card widget-icon-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-muted text-uppercase fs-13 mt-0">Team</h5>
                                    <h3 class="my-3">{{ widgets.team?.users_count ?? 0 }}</h3>
                                    <p class="mb-0 text-muted text-truncate">
                                        {{ widgets.team?.departments_count ?? 0 }} departments
                                    </p>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title text-bg-primary rounded rounded-3 fs-3 widget-icon-box-avatar shadow"
                                    >
                                        <i class="ri-team-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col">
                <Link :href="route('resources.index')" class="text-reset">
                    <div class="card widget-icon-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-muted text-uppercase fs-13 mt-0">Resources</h5>
                                    <h3 class="my-3">{{ widgets.resources ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title text-bg-success rounded rounded-3 fs-3 widget-icon-box-avatar shadow"
                                    >
                                        <i class="ri-server-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col">
                <Link :href="route('websites.index')" class="text-reset">
                    <div class="card widget-icon-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-muted text-uppercase fs-13 mt-0">Websites</h5>
                                    <h3 class="my-3">{{ widgets.websites ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title text-bg-info rounded rounded-3 fs-3 widget-icon-box-avatar shadow"
                                    >
                                        <i class="ri-global-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col">
                <Link :href="route('projects.index')" class="text-reset">
                    <div class="card widget-icon-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-muted text-uppercase fs-13 mt-0">Projects</h5>
                                    <h3 class="my-3">{{ widgets.projects ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title text-bg-warning rounded rounded-3 fs-3 widget-icon-box-avatar shadow"
                                    >
                                        <i class="ri-kanban-view"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col">
                <Link :href="route('knowledge.posts.index')" class="text-reset">
                    <div class="card widget-icon-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-muted text-uppercase fs-13 mt-0">Knowledge</h5>
                                    <h3 class="my-3">{{ widgets.knowledge?.posts_count ?? 0 }}</h3>
                                    <p class="mb-0 text-muted text-truncate">
                                        {{ widgets.knowledge?.categories_count ?? 0 }} categories
                                    </p>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title text-bg-danger rounded rounded-3 fs-3 widget-icon-box-avatar shadow"
                                    >
                                        <i class="ri-book-2-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col">
                <Link :href="route('contracts.index')" class="text-reset">
                    <div class="card widget-icon-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-muted text-uppercase fs-13 mt-0">Contracts</h5>
                                    <h3 class="my-3">{{ widgets.contracts ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title text-bg-dark rounded rounded-3 fs-3 widget-icon-box-avatar shadow"
                                    >
                                        <i class="ri-file-text-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col">
                <Link :href="route('ai-accounts.index')" class="text-reset">
                    <div class="card widget-icon-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-muted text-uppercase fs-13 mt-0">AI Accounts</h5>
                                    <h3 class="my-3">{{ widgets.ai_accounts ?? 0 }}</h3>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title text-bg-purple rounded rounded-3 fs-3 widget-icon-box-avatar shadow"
                                    >
                                        <i class="ri-robot-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="col">
                <Link :href="route('objectives.index')" class="text-reset">
                    <div class="card widget-icon-box">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-muted text-uppercase fs-13 mt-0">OKRs</h5>
                                    <h3 class="my-3">{{ widgets.okrs ?? 0 }}</h3>
                                    <p class="mb-0 text-muted text-truncate">Active objectives</p>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span
                                        class="avatar-title text-bg-secondary rounded rounded-3 fs-3 widget-icon-box-avatar shadow"
                                    >
                                        <i class="ri-bar-chart-box-line"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
