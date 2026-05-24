<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const showingNavigationDropdown = ref(false);

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <div class="min-vh-100 bg-light">
        <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom shadow-sm">
            <div class="container">
                <Link :href="route('dashboard')" class="navbar-brand d-flex align-items-center gap-2">
                    <ApplicationLogo style="height: 2rem" />
                    <span class="fw-bold text-primary">OPS Platform</span>
                </Link>

                <button
                    class="navbar-toggler"
                    type="button"
                    @click="showingNavigationDropdown = !showingNavigationDropdown"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" :class="{ show: showingNavigationDropdown }">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink>
                        </li>
                    </ul>

                    <div class="d-none d-md-block">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button type="button" class="btn btn-light btn-sm">{{ user?.name }}</button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <div class="d-md-none border-top pt-2 mt-2">
                        <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <header v-if="$slots.header" class="bg-white border-bottom">
            <div class="container py-3">
                <slot name="header" />
            </div>
        </header>

        <main class="container py-4">
            <slot />
        </main>
    </div>
</template>
