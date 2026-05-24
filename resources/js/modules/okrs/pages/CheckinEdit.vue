<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

type Obj = { id: number; title: string };
type Kr = { id: number; title: string };

type Cin = {
    id: number;
    value: string | number | null;
    note: string | null;
    checked_at: string | null;
};

const props = defineProps<{
    objective: Obj;
    keyResult: Kr;
    checkin: Cin;
}>();

function datetimeLocal(iso: string | null): string {
    if (!iso) {
        return '';
    }
    const s = iso.replace(' ', 'T');
    return s.includes('.') ? s.slice(0, 16) : s.slice(0, 16);
}

const form = useForm({
    value: props.checkin.value ?? '',
    note: props.checkin.note ?? '',
    checked_at: datetimeLocal(props.checkin.checked_at),
});

function submit() {
    form
        .transform((data) => ({
            ...data,
            checked_at: data.checked_at === '' ? null : data.checked_at,
            note: data.note === '' ? null : data.note,
        }))
        .patch(route('objectives.key-results.checkins.update', [props.objective.id, props.keyResult.id, props.checkin.id]));
}
</script>

<template>
    <Head title="Edit OKR check-in" />

    <AppLayout
        :breadcrumbs="[
            { label: 'Dashboard', href: route('dashboard') },
            { label: 'Objectives', href: route('objectives.index') },
            { label: objective.title, href: route('objectives.show', objective.id) },
            { label: 'Key results', href: route('objectives.key-results.index', objective.id) },
            {
                label: keyResult.title,
                href: route('objectives.key-results.checkins.index', [objective.id, keyResult.id]),
            },
            { label: 'Edit check-in' },
        ]"
    >
        <h1 class="mb-6 text-2xl font-semibold dark:text-gray-100">Edit check-in</h1>

        <form class="max-w-xl space-y-5 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900" @submit.prevent="submit">
            <div>
                <InputLabel for="value" value="Value" />
                <TextInput id="value" v-model="form.value" type="number" step="any" required class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.value" />
            </div>
            <div>
                <InputLabel for="note" value="Note" />
                <textarea id="note" v-model="form.note" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-950 dark:text-gray-100" />
                <InputError class="mt-2" :message="form.errors.note" />
            </div>
            <div>
                <InputLabel for="checked_at" value="Checked at (optional)" />
                <TextInput id="checked_at" v-model="form.checked_at" type="datetime-local" class="mt-1 block w-full" />
                <InputError class="mt-2" :message="form.errors.checked_at" />
            </div>
            <div class="flex gap-3">
                <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                <Link
                    :href="route('objectives.key-results.checkins.index', [objective.id, keyResult.id])"
                    class="inline-flex border px-4 py-2 text-xs uppercase"
                >
                    Cancel
                </Link>
            </div>
        </form>
    </AppLayout>
</template>
