<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <header class="mb-4">
            <h4 class="header-title text-danger">Delete Account</h4>
            <p class="text-muted mb-0">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="modal-header">
                <h5 class="modal-title">Delete account?</h5>
                <button type="button" class="btn-close" aria-label="Close" @click="closeModal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">
                    Please enter your password to confirm you would like to permanently delete your account.
                </p>
                <div class="mb-3">
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" />
                </div>
            </div>
            <div class="modal-footer">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <DangerButton :disabled="form.processing" @click="deleteUser">Delete Account</DangerButton>
            </div>
        </Modal>
    </section>
</template>
