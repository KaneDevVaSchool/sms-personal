<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

type CF = {
    id: number;
    version: number;
    original_name: string | null;
    created_at?: string | null;
};

type CShow = {
    id: number;
    name: string;
    partner: string | null;
    type: string | null;
    value: string | number | null;
    signed_at: string | null;
    expires_at: string | null;
    status: string | null;
    files?: CF[];
};

const props = defineProps<{ contract: CShow }>();

const fileForm = useForm({
    file: null as File | null,
});

function submitFile() {
    if (!fileForm.file) {
        return;
    }
    fileForm.post(route('contracts.files.store', props.contract.id), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => fileForm.reset('file'),
    });
}

function onFileInput(e: Event) {
    const input = e.target as HTMLInputElement;
    fileForm.file = input.files?.[0] ?? null;
}

function destroyContract() {
    if (confirm('Delete this contract?')) {
        router.delete(route('contracts.destroy', props.contract.id));
    }
}
</script>

<template>
    <Head :title="`${contract.name} · Contract`" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Contracts', href: route('contracts.index') },
            { label: contract.name },
        ]"
    >
        <div class="mb-6 flex flex-wrap justify-between gap-4">
            <h1 class="text-2xl font-semibold dark:text-gray-100">{{ contract.name }}</h1>
            <div class="flex gap-2">
                <Link :href="route('contracts.edit', contract.id)" class="rounded-md border px-3 py-2 text-sm dark:border-gray-600">Edit</Link>
                <DangerButton type="button" class="text-sm tracking-normal normal-case" @click="destroyContract">Delete</DangerButton>
            </div>
        </div>

        <dl class="mb-8 max-w-2xl space-y-2 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex gap-6">
                <div>
                    <dt class="text-xs uppercase text-gray-500">Partner</dt>
                    <dd>{{ contract.partner ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-gray-500">Type</dt>
                    <dd>{{ contract.type ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-gray-500">Value</dt>
                    <dd>{{ contract.value ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase text-gray-500">Status</dt>
                    <dd>{{ contract.status ?? '—' }}</dd>
                </div>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Signed {{ contract.signed_at ? String(contract.signed_at).slice(0, 10) : '—' }} · Expires
                {{ contract.expires_at ? String(contract.expires_at).slice(0, 10) : '—' }}
            </div>
        </dl>

        <h2 class="mb-3 text-lg font-medium">Attach file</h2>
        <form class="mb-10 flex flex-wrap items-end gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submitFile">
            <div>
                <InputLabel for="file" value="File" />
                <input
                    id="file"
                    type="file"
                    class="mt-1 block text-sm text-gray-600 dark:text-gray-400"
                    @change="onFileInput"
                />
                <InputError class="mt-2" :message="fileForm.errors.file" />
            </div>
            <PrimaryButton type="submit" :disabled="fileForm.processing || !fileForm.file">Upload</PrimaryButton>
        </form>

        <h2 class="mb-3 text-lg font-medium">Versions</h2>
        <ul class="divide-y divide-gray-200 rounded-lg border border-gray-200 dark:divide-gray-800 dark:border-gray-800">
            <li v-if="!contract.files?.length" class="p-4 text-gray-500">No files yet.</li>
            <li v-for="f in contract.files" :key="f.id" class="flex justify-between p-4 text-sm">
                <span>v{{ f.version }} {{ f.original_name ?? 'Attachment' }}</span>
            </li>
        </ul>
    </AppLayout>
</template>
