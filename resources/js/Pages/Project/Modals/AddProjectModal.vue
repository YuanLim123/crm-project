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
    title: '',
    description: '',
    status: '',
    endDate: '',
    client: '',
    assignedUser: '',
    tasks: [],
});
</script>

<template>
    <Modal :show="props.show" @close="emit('close')">
        <div class="p-6">
            <h2 class="py-2 text-lg font-medium text-gray-900">Add Project</h2>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="title" value="Title" />

                    <TextInput
                        id="title"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.title"
                        required
                        autofocus
                        autocomplete="title"
                    />

                    <InputError class="mt-2" :message="form.errors.title" />
                </div>

                <div class="mt-4">
                    <InputLabel for="description" value="Description" />

                    <TextInput
                        id="description"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.description"
                        required
                        autocomplete="description"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.description"
                    />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <SecondaryButton @click="emit('close')">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton class="ms-4" type="submit">
                        Add
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
