<script setup lang="ts">
import BreadcrumbNav from '@/Components/BreadcrumbNav.vue';
import NotificationBell from '@/Components/NotificationBell.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { isNavActive, isNavGroupActive, mainNav } from '@/utils/navigation';
import type { BreadcrumbItem } from '@/types/common';

const props = withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
        title?: string;
    }>(),
    {
        breadcrumbs: () => [{ label: 'Dashboard', href: route('dashboard') }],
    },
);

const page = usePage();

const userName = computed(
    () => (page.props.auth as { user?: { name: string; email?: string } })?.user?.name ?? '',
);

const pageTitle = computed(
    () => props.title ?? props.breadcrumbs[props.breadcrumbs.length - 1]?.label ?? 'Dashboard',
);

onMounted(() => {
    document.body.classList.remove('authentication-bg', 'position-relative');
    const stored = localStorage.getItem('ops-theme');
    if (stored === 'dark') {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
    } else if (stored === 'light') {
        document.documentElement.setAttribute('data-bs-theme', 'light');
    }
});

function toggleTheme() {
    const html = document.documentElement;
    const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-bs-theme', next);
    localStorage.setItem('ops-theme', next);
}
</script>

<template>
    <div class="wrapper">
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">
                    <div class="logo-topbar ops-brand">
                        <Link :href="route('dashboard')" class="logo-light">
                            <span class="logo-lg ops-brand-text">OPS Platform</span>
                            <span class="logo-sm ops-brand-text">OPS</span>
                        </Link>
                        <Link :href="route('dashboard')" class="logo-dark">
                            <span class="logo-lg ops-brand-text">OPS Platform</span>
                            <span class="logo-sm ops-brand-text">OPS</span>
                        </Link>
                    </div>

                    <button type="button" class="button-toggle-menu" aria-label="Toggle menu">
                        <i class="ri-menu-2-fill"></i>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-1">
                    <li class="d-none d-sm-inline-block">
                        <button
                            type="button"
                            class="nav-link btn btn-link border-0"
                            id="light-dark-mode"
                            aria-label="Toggle theme"
                            @click="toggleTheme"
                        >
                            <i class="ri-moon-fill fs-22"></i>
                        </button>
                    </li>
                    <li>
                        <NotificationBell />
                    </li>
                    <li class="dropdown me-md-2">
                        <a
                            class="nav-link dropdown-toggle arrow-none nav-user px-2"
                            data-bs-toggle="dropdown"
                            href="#"
                            role="button"
                            aria-haspopup="false"
                            aria-expanded="false"
                        >
                            <span class="account-user-avatar">
                                <span
                                    class="avatar-title rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px"
                                >
                                    <i class="ri-user-3-line"></i>
                                </span>
                            </span>
                            <span class="d-lg-flex flex-column gap-1 d-none">
                                <h5 class="my-0">{{ userName }}</h5>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome!</h6>
                            </div>
                            <Link :href="route('profile.edit')" class="dropdown-item">
                                <i class="ri-account-circle-fill align-middle me-1"></i>
                                <span>Profile</span>
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="dropdown-item"
                            >
                                <i class="ri-logout-box-fill align-middle me-1"></i>
                                <span>Logout</span>
                            </Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="leftside-menu">
            <Link :href="route('dashboard')" class="logo logo-light ops-brand">
                <span class="logo-lg">OPS Platform</span>
                <span class="logo-sm">OPS</span>
            </Link>
            <Link :href="route('dashboard')" class="logo logo-dark ops-brand">
                <span class="logo-lg">OPS Platform</span>
                <span class="logo-sm">OPS</span>
            </Link>

            <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
                <i class="ri-checkbox-blank-circle-line align-middle"></i>
            </div>

            <div class="button-close-fullsidebar">
                <i class="ri-close-fill align-middle"></i>
            </div>

            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <ul class="side-nav">
                    <li class="side-nav-title mt-1">Main</li>

                    <template v-for="item in mainNav" :key="item.routeName">
                        <li v-if="item.children?.length" class="side-nav-item">
                            <a
                                data-bs-toggle="collapse"
                                :href="`#${item.collapseId}`"
                                class="side-nav-link"
                                :class="{ active: isNavGroupActive(item) }"
                                :aria-expanded="isNavGroupActive(item)"
                            >
                                <i :class="item.icon"></i>
                                <span>{{ item.label }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div
                                :id="item.collapseId"
                                class="collapse"
                                :class="{ show: isNavGroupActive(item) }"
                            >
                                <ul class="side-nav-second-level">
                                    <li v-for="child in item.children" :key="child.routeName">
                                        <Link
                                            :href="route(child.routeName)"
                                            :class="{ active: isNavActive(child.routeName) }"
                                        >
                                            {{ child.label }}
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li v-else class="side-nav-item">
                            <Link
                                :href="route(item.routeName)"
                                class="side-nav-link"
                                :class="{ active: isNavActive(item.routeName) }"
                            >
                                <i :class="item.icon"></i>
                                <span>{{ item.label }}</span>
                            </Link>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <BreadcrumbNav :items="breadcrumbs" />
                                </div>
                                <h4 class="page-title">{{ pageTitle }}</h4>
                            </div>
                        </div>
                    </div>
                    <slot />
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            {{ new Date().getFullYear() }} © OPS Platform
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
