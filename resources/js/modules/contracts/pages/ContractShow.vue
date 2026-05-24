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
        :title="contract.name"
    >
        <div class="row mb-3">
            <div class="col-12 d-flex flex-wrap justify-content-end gap-2">
                <Link :href="route('contracts.edit', contract.id)" class="btn btn-outline-primary btn-sm">Edit</Link>
                <DangerButton type="button" @click="destroyContract">Delete</DangerButton>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3 text-muted">Partner</dt>
                    <dd class="col-sm-9">{{ contract.partner ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Type</dt>
                    <dd class="col-sm-9">{{ contract.type ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Value</dt>
                    <dd class="col-sm-9">{{ contract.value ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Status</dt>
                    <dd class="col-sm-9">{{ contract.status ?? '—' }}</dd>
                    <dt class="col-sm-3 text-muted">Signed / expires</dt>
                    <dd class="col-sm-9">
                        Signed {{ contract.signed_at ? String(contract.signed_at).slice(0, 10) : '—' }} · Expires
                        {{ contract.expires_at ? String(contract.expires_at).slice(0, 10) : '—' }}
                    </dd>
                </dl>
            </div>
        </div>

        <h5 class="mb-3">Attach file</h5>
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3 align-items-end" @submit.prevent="submitFile">
                    <div class="col-md-auto">
                        <InputLabel for="file" value="File" />
                        <input id="file" type="file" class="form-control" @change="onFileInput" />
                        <InputError class="mt-1" :message="fileForm.errors.file" />
                    </div>
                    <div class="col-md-auto">
                        <PrimaryButton type="submit" :disabled="fileForm.processing || !fileForm.file">Upload</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>

        <h5 class="mb-3">Versions</h5>
        <div class="card">
            <ul class="list-group list-group-flush">
                <li v-if="!contract.files?.length" class="list-group-item text-muted">No files yet.</li>
                <li v-for="f in contract.files" :key="f.id" class="list-group-item d-flex justify-content-between">
                    <span>v{{ f.version }} {{ f.original_name ?? 'Attachment' }}</span>
                </li>
            </ul>
        </div>
    </AppLayout>
</template>
