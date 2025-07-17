<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Swal from 'sweetalert2';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    users: {
        type: Array,
    },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showAddUserModal = ref(false);

const addUser = function () {
    showAddUserModal.value = true;
};

const closeModal = function () {
    showAddUserModal.value = false;
};

const submit = function () {
    form.post(route('users.store'), {
        onSuccess: () => {
            Swal.fire({
                title: 'Success',
                text: 'User added successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
            });
            closeModal();
        },
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="border-b-2 border-gray-200 px-2 py-4 text-gray-900"
                    >
                        User Page
                    </div>
                    <div class="flex flex-col gap-2 px-2 py-4">
                        <PrimaryButton class="w-32" @click="addUser"
                            >Add User</PrimaryButton
                        >
                        <PrimaryButton class="w-48"
                            >Show Deleted User</PrimaryButton
                        >
                    </div>
                    <div
                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                    >
                        <table class="w-full text-left text-sm rtl:text-right">
                            <thead
                                class="bg-gray-50 text-xs uppercase text-gray-700"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">Name</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="user in users"
                                    :key="user.id"
                                    class="border-b bg-white"
                                >
                                    <th
                                        scope="row"
                                        class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                                    >
                                        {{ user.name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a
                                            href="#"
                                            class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                            >Edit</a
                                        >
                                        <a
                                            href="#"
                                            class="font-medium text-red-600 hover:underline dark:text-red-500"
                                            >Delete</a
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showAddUserModal" @close="closeModal">
            <div class="p-6">
                <h2 class="py-2 text-lg font-medium text-gray-900">
                    Add an User
                </h2>
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

                    <div class="mt-4">
                        <InputLabel for="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="password_confirmation"
                            value="Confirm Password"
                        />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <SecondaryButton @click="closeModal">
                            Cancel
                        </SecondaryButton>

                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            @click="submit"
                        >
                            Add
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
