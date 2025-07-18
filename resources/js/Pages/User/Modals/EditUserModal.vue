<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';


const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    user: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    name: '',
    email: '',
});

const emit = defineEmits(['close']);

const submit = function () {
    form.put(route('users.update', props.user.id), {
        onSuccess: () => {
            emit('close');
            Swal.fire({
                title: 'Success',
                text: 'User updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
            });
        },
        onFinish: () => {
            form.reset();
        },
    });
};

watch(
    () => props.user,
    (newUser) => {
        form.name = newUser ? newUser.name : '';
        form.email = newUser ? newUser.email : '';
    },
    { immediate: true }
);

</script>

<template>
    <Modal :show="props.show" @close="emit('close')">
        <div class="p-6">
            <h2 class="py-2 text-lg font-medium text-gray-900">Edit User {{ form.name }}</h2>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="name" value="Name" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <SecondaryButton @click="emit('close')">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton
                        class="ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        type="submit"
                    >
                        Edit
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
