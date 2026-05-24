<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: 'Ops',
    },
    snapshot: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                {{ title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="mb-4 text-sm text-gray-600 dark:text-gray-300">
                            Scaffolding page: wire real UI components here.
                        </p>
                        <pre
                            class="overflow-x-auto rounded bg-gray-100 p-4 text-xs text-gray-900 dark:bg-gray-950 dark:text-gray-100"
                        >{{ JSON.stringify(snapshot, null, 2) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
