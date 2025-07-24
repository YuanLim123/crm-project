<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Swal from 'sweetalert2';
import { useForm } from '@inertiajs/vue3';
import Multiselect from 'vue-multiselect';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    users: {
        type: Array,
        default: () => [],
    },
    status: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['close', 'submit']);

const form = useForm({
    title: '',
    description: '',
    status: '',
    endDate: '',
    user: '',
});

const nameWithEmail = function (user) {
    return `${user.name} - [${user.email}]`;
};

const submit = function() {
    emit('submit', { ...form });
    form.reset();
};

</script>

<template>
    <Modal :show="props.show" @close="emit('close')">
        <div class="p-6 overflow-auto">
            <h2 class="py-2 text-lg font-medium text-gray-900">Add Task</h2>
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

                    <TextAreaInput
                        id="description"
                        class="mt-1 block h-24 w-1/2 resize-none"
                        v-model="form.description"
                        required
                        autocomplete="description"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.description"
                    />
                </div>

                <div class="mt-4">
                    <InputLabel for="status" value="Status" />

                    <SelectInput
                        id="status"
                        class="mt-1 block w-1/3"
                        v-model="form.status"
                        :status="status"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.status" />
                </div>

                <div>
                    <InputLabel for="endDate" value="End Date" class="mt-4" />

                    <TextInput
                        id="endDate"
                        type="date"
                        class="mt-1 block w-1/3"
                        v-model="form.endDate"
                        required
                    />

                    <InputError class="mt-2" :message="form.errors.endDate" />
                </div>

                <div class="mt-4">
                    <InputLabel for="user" value="User" />
                    <multiselect
                        id="single-select-search"
                        v-model="form.user"
                        :options="users"
                        :custom-label="nameWithEmail"
                        placeholder="Select one"
                        label="name"
                        track-by="name"
                        aria-label="pick a value"
                        required
                    ></multiselect>
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>