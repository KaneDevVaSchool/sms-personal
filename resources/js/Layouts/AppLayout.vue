<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import BreadcrumbNav from '@/components/BreadcrumbNav.vue';
import NotificationBell from '@/components/NotificationBell.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { mainNav } from '@/utils/navigation';
import type { BreadcrumbItem } from '@/types/common';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [{ label: 'Dashboard', href: route('dashboard') }],
    },
);

const page = usePage();
const sidebarOpen = ref(false);
const dark = ref(false);

onMounted(() => {
    dark.value =
        localStorage.getItem('ops-theme') === 'dark' ||
        (!localStorage.getItem('ops-theme') &&
            window.matchMedia('(prefers-color-scheme: dark)').matches);
    applyTheme();
});

function applyTheme() {
    document.documentElement.classList.toggle('dark', dark.value);
}

function toggleTheme() {
    dark.value = !dark.value;
    localStorage.setItem('ops-theme', dark.value ? 'dark' : 'light');
    applyTheme();
}

const userName = computed(
    () => (page.props.auth as { user?: { name: string } })?.user?.name ?? '',
);
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-950">
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-black/40 lg:hidden"
            @click="sidebarOpen = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 transform border-r border-gray-200 bg-white transition-transform dark:border-gray-800 dark:bg-gray-900 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex h-16 items-center border-b border-gray-200 px-4 dark:border-gray-800">
                <Link :href="route('dashboard')" class="flex items-center gap-2">
                    <ApplicationLogo class="h-8 w-8 fill-current text-gray-800 dark:text-gray-100" />
                    <span class="text-sm font-bold text-gray-900 dark:text-gray-100">OPS Platform</span>
                </Link>
            </div>
            <nav class="space-y-1 p-3">
                <Link
                    v-for="item in mainNav"
                    :key="item.routeName"
                    :href="route(item.routeName)"
                    class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                    :class="{
                        'bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-white': route().current(
                            item.routeName,
                        ),
                    }"
                >
                    {{ item.label }}
                </Link>
                <Link
                    :href="route('team.departments.index')"
                    class="ml-3 block rounded-md px-3 py-1.5 text-xs text-gray-500 hover:text-gray-800 dark:hover:text-gray-200"
                >
                    Departments
                </Link>
                <Link
                    :href="route('knowledge.categories.index')"
                    class="ml-3 block rounded-md px-3 py-1.5 text-xs text-gray-500 hover:text-gray-800 dark:hover:text-gray-200"
                >
                    Categories
                </Link>
            </nav>
        </aside>

        <div class="lg:pl-64">
            <header
                class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 dark:border-gray-800 dark:bg-gray-900 sm:px-6"
            >
                <button
                    type="button"
                    class="rounded-md p-2 text-gray-600 lg:hidden dark:text-gray-300"
                    aria-label="Open menu"
                    @click="sidebarOpen = true"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>

                <div class="flex flex-1 items-center justify-end gap-2">
                    <button
                        type="button"
                        class="rounded-md p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800"
                        aria-label="Toggle dark mode"
                        @click="toggleTheme"
                    >
                        {{ dark ? 'Light' : 'Dark' }}
                    </button>
                    <NotificationBell />
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium text-gray-700 dark:bg-gray-900 dark:text-gray-200"
                                >
                                    {{ userName }}
                                </button>
                            </span>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                Log out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                <BreadcrumbNav :items="breadcrumbs" />
                <slot />
            </main>
        </div>
    </div>
</template>
