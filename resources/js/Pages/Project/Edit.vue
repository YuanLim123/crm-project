<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import ClientSection from './Partials/ClientSection.vue';
import UserSection from './Partials/UserSection.vue';
import SelectClientModal from './Modals/SelectClientModal.vue';
import SelectUserModal from './Modals/SelectUserModal.vue';
import AddTaskModal from './Modals/AddTaskModal.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';


const showSelectClientModal = ref(false);
const showSelectUserModal = ref(false);
const showAddTaskModal = ref(false);

const props = defineProps({
    project: {
        type: Object,
        required: true,
    },
    clients: {
        type: Array,
        default: () => [],
    },
    users: {
        type: Array,
        default: () => [],
    },
    endDate: {
        type: String,
        default: '',
    },

});

const selectedClient = ref(props.project.client);
const selectedUser = ref(props.project.user);

const form = useForm({
    title: props.project.title,
    description: props.project.description,
    status: props.project.status,
    endDate: props.endDate,
    client: props.project.client_id,
    assignedUser: props.project.user_id,
    tasks: props.project.tasks,
});

const submit = function () {
    console.log(form.tasks)
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to update this project?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        form.tasks.forEach((task) => {
            task.user = task.user ? task.user.id : ''; // store only the ID
        });
        if (result.isConfirmed) {
            form.put(route('projects.update', { project: props.project}), {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success',
                        text: 'Project update successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                    });
                },
                onError: (error) => {
                    console.log(error);
                    Swal.fire({
                        title: 'Error',
                        text: 'A problem occurred while updating the project.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                },
                onFinish: () => {
                    form.reset();
                    selectedClient.value = '';
                    selectedUser.value = '';
                },
            });
        }
    });
};

const goBack = function () {
    router.get(route('projects.index'));
};

const openSelectClientModal = function () {
    showSelectClientModal.value = true;
};

const closeSelectClientModal = function () {
    showSelectClientModal.value = false;
};

const openSelectUserModal = function () {
    showSelectUserModal.value = true;
};

const closeSelectUserModal = function () {
    showSelectUserModal.value = false;
};

const openAddTaskModal = function () {
    showAddTaskModal.value = true;
};

const closeAddTaskModal = function () {
    showAddTaskModal.value = false;
};

const selectClient = function (client) {
    form.client = client.id; // only store the ID and get the rest from the backend
    showSelectClientModal.value = false;
    selectedClient.value = client;
};

const removeSelectedClient = function () {
    selectedClient.value = null;
    form.client = '';
};

const removeSelectedUser = function () {
    selectedUser.value = null;
    form.assignedUser = '';
};

const selectUser = function (user) {
    form.assignedUser = user.id; // only store the ID and get the rest from the backend
    showSelectUserModal.value = false;
    selectedUser.value = user;
};

const addTask = function (task) {
    form.tasks.push(task);
    showAddTaskModal.value = false;
};

const removeTask = function (index) {
    form.tasks.splice(index, 1);
};

const STATUS = [
    { value: 'pending', text: 'Pending' },
    { value: 'completed', text: 'Completed' },
    { value: 'cancelled', text: 'Cancelled' },
];
</script>

<template>
    <Head title="Add Project" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="border-b-2 border-gray-200 px-2 py-4 text-gray-900"
                    >
                        Edit Project
                    </div>
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="title" value="Title" />

                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-1/3"
                                    v-model="form.title"
                                    required
                                    autofocus
                                    autocomplete="title"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.title"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="description"
                                    value="Description"
                                />

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
                                    :status="STATUS"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.status"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="endDate"
                                    value="End Date"
                                    class="mt-4"
                                />

                                <TextInput
                                    id="endDate"
                                    type="date"
                                    class="mt-1 block w-1/3"
                                    v-model="form.endDate"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.endDate"
                                />
                            </div>
                            <div class="mt-4 flex flex-row gap-16">
                                <div class="w-1/3 grow">
                                    <a
                                        @click.prevent="openSelectClientModal"
                                        href="#"
                                        class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                        >Click here to select a client</a
                                    >
                                    <span class="pl-2">
                                        <InputError
                                            :message="form.errors.client"
                                        />
                                    </span>

                                    <p class="text-gray-400">
                                        {{
                                            selectedClient
                                                ? selectedClient.company
                                                : 'No client selected'
                                        }}
                                        <button
                                            v-if="selectedClient"
                                            @click="removeSelectedClient"
                                            type="button"
                                            class="ml-2 text-red-500 hover:text-red-700"
                                            title="Remove selected client"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </p>

                                    <div v-if="selectedClient" class="mt-4">
                                        <ClientSection
                                            :client="selectedClient"
                                            class="h-80"
                                        />
                                    </div>
                                </div>

                                <div class="w-1/3 grow">
                                    <a
                                        @click.prevent="openSelectUserModal"
                                        href="#"
                                        class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                        >Click here to select a user</a
                                    >
                                    <span class="pl-2">
                                        <InputError
                                            :message="form.errors.assignedUser"
                                        />
                                    </span>
                                    <p class="text-gray-400">
                                        {{
                                            selectedUser
                                                ? selectedUser.name
                                                : 'No user selected'
                                        }}
                                        <button
                                            v-if="selectedUser"
                                            @click="removeSelectedUser"
                                            type="button"
                                            class="ml-2 text-red-500 hover:text-red-700"
                                            title="Remove selected user"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </p>

                                    <div v-if="selectedUser" class="mt-4">
                                        <UserSection
                                            :user="selectedUser"
                                            class="h-80"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a
                                    @click.prevent="openAddTaskModal"
                                    href="#"
                                    class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                    >Click here to add task</a
                                >
                                <div v-if="form.tasks.length > 0" class="mt-2">
                                    <table
                                        class="w-full text-left text-sm rtl:text-right"
                                    >
                                        <thead
                                            class="bg-gray-50 text-xs uppercase text-gray-700"
                                        >
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3"
                                                >
                                                    Title
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3"
                                                >
                                                    Description
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3"
                                                >
                                                    Assigned User
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3"
                                                >
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    task, index
                                                ) in form.tasks"
                                                :key="index"
                                                class="border-b bg-white"
                                            >
                                                <th
                                                    scope="row"
                                                    class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                                                >
                                                    {{ task.title }}
                                                </th>
                                                <td class="px-6 py-4">
                                                    {{ task.description }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    {{
                                                        task.user
                                                            ? task.user
                                                                  .name
                                                            : 'TBA'
                                                    }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <a
                                                        @click.prevent="
                                                            removeTask(index)
                                                        "
                                                        class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                                        >Remove
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-end">
                                <SecondaryButton @click="goBack">
                                    Back
                                </SecondaryButton>

                                <PrimaryButton class="ms-4" type="submit">
                                    Add
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <SelectClientModal
            :show="showSelectClientModal"
            @close="closeSelectClientModal"
            :clients="props.clients"
            @select="selectClient"
        />
        <SelectUserModal
            :show="showSelectUserModal"
            @close="closeSelectUserModal"
            :users="props.users"
            @select="selectUser"
        />
        <AddTaskModal
            :show="showAddTaskModal"
            @close="closeAddTaskModal"
            :users="props.users"
            :status="STATUS"
            @submit="addTask"
        />
    </AuthenticatedLayout>
</template>
