<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DeleteUserModal from './Modals/DeleteUserModal.vue';
import AddUserModal from './Modals/AddUserModal.vue';
import EditUserModal from './Modals/EditUserModal.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

defineProps({
    users: {
        type: Array,
    },
});

let selectedUser;
const selectedUserId = ref(null);
const showAddUserModal = ref(false);
const showDeleteUserModal = ref(false);
const showEditUserModal = ref(false);

const page = usePage()

const can = computed(() => page.props.auth.can);

const openAddUserModal = function () {
    showAddUserModal.value = true;
};

const closeAddUserModal = function () {
    showAddUserModal.value = false;
};

const openDeletedUserModal = function (userId) {
    selectedUserId.value = userId ?? null;
    showDeleteUserModal.value = true;
};

const closeDeletedUserModal = function () {
    showDeleteUserModal.value = false;
};

const openEditUserModal = function (user) {
    selectedUser = user ?? null;
    showEditUserModal.value = true;
};

const closeEditUserModal = function () {
    showEditUserModal.value = false;
};

const showAllUsers = function () {
    router.get(route('users.index'), {}, { preserveState: true });
};

const showDeletedUsersOnly = function () {
    router.get(
        route('users.index'),
        { showDeleted: true },
        { preserveState: true },
    );
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="border-b-2 border-gray-200 px-2 py-4 text-gray-900"
                    >
                        User Page
                    </div>
                    <div class="flex flex-col gap-2 px-2 py-4">
                        <PrimaryButton class="w-32" @click="openAddUserModal"
                            >Add User</PrimaryButton
                        >
                        <div class="flex flex-row gap-2">
                            <PrimaryButton @click="showAllUsers" class="w-48"
                                >Show All User</PrimaryButton
                            >
                            <PrimaryButton
                                @click="showDeletedUsersOnly"
                                class="w-48"
                                >Show Deleted User</PrimaryButton
                            >
                        </div>
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
                                    <td class="flex gap-2 px-6 py-4">
                                        <a
                                            v-if="can.edit_user"
                                            @click.prevent="
                                                openEditUserModal(user)
                                            "
                                            href="#"
                                            class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                            :class="{
                                                'pointer-events-none opacity-50':
                                                    user.deleted_at,
                                            }"
                                            >Edit</a
                                        >
                                        <a
                                            v-if="can.delete_user"
                                            @click.prevent="
                                                openDeletedUserModal(user.id)
                                            "
                                            href="#"
                                            class="font-medium text-red-600 hover:underline dark:text-red-500"
                                            :class="{
                                                'pointer-events-none opacity-50':
                                                    user.deleted_at,
                                            }"
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
        <DeleteUserModal
            :show="showDeleteUserModal"
            :userId="selectedUserId"
            @close="closeDeletedUserModal"
        />
        <EditUserModal
            :show="showEditUserModal"
            :user="selectedUser"
            @close="closeEditUserModal"
        />
        <AddUserModal :show="showAddUserModal" @close="closeAddUserModal" />
    </AuthenticatedLayout>
</template>
