<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

const form = useForm({
    company: '',
    contactPerson: '',
    email: '',
    phone: '',
    companyAddress: '',
});

const submit = function () {
    form.post(route('clients.store'), {
        onSuccess: () => {
            // call form.reset before closing the modal to ensure the form is cleared
            form.reset();
            emit('close');
            Swal.fire({
                title: 'Success',
                text: 'Client added successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
            });
        },
    });
};
</script>

<template>
    <Modal :show="props.show" @close="emit('close')">
        <div class="p-6">
            <h2 class="py-2 text-lg font-medium text-gray-900">Add Client</h2>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="company" value="Company" />

                    <TextInput
                        id="company"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.company"
                        required
                        autofocus
                    />

                    <InputError class="mt-2" :message="form.errors.company" />
                </div>

                <div class="mt-4">
                    <InputLabel for="contactPerson" value="Contact Person" />

                    <TextInput
                        id="contactPerson"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.contactPerson"
                        required
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.contactPerson"
                    />
                </div>

                <div class="mt-4">
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="contactPerson"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="phone" value="Phone" />

                    <TextInput
                        id="phone"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.phone"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <div class="mt-4">
                    <InputLabel for="companyAddress" value="Company Address" />

                    <TextInput
                        id="companyAddress"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.companyAddress"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.companyAddress" />
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
                        Add
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
