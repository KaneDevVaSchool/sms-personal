<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
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

    <AppLayout :breadcrumbs="[{ label: 'Dashboard', href: route('dashboard') }, { label: title }]" :title="title">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-4">Scaffolding page: wire real UI components here.</p>
                <pre class="bg-light p-3 rounded small mb-0">{{ JSON.stringify(snapshot, null, 2) }}</pre>
            </div>
        </div>
    </AppLayout>
</template>
