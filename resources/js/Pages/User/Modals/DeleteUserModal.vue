<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Swal from 'sweetalert2';
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    userId: {
        default: null,
    },
});

const emit = defineEmits(['close']);

const deleteUser = function () {
    router.delete(route('users.destroy', props.userId), {
        onSuccess: () => {
            if (page.props.flash.success)
                Swal.fire({
                    title: 'Success',
                    text: page.props.flash.success,
                    icon: 'success',
                    confirmButtonText: 'OK',
                });
        },
        onError: (error) => {
            Swal.fire({
                title: 'Error',
                text: error.error,
                icon: 'error',
                confirmButtonText: 'OK',
            });
        },
        onFinish: () => {
            emit('close');
        },
    });
};
</script>

<template>
    <Modal :show="props.show" @close="emit('close')">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this account?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="emit('close')">
                    Cancel
                </SecondaryButton>

                <DangerButton class="ms-3" @click="deleteUser">
                    Delete Account
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
