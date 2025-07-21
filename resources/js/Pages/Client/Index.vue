<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddClientModal from './Modals/AddClientModal.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    clients: {
        type: Array,
    },
});

const showAddClientModal = ref(false);

const openAddClientModal = function () {
    showAddClientModal.value = true;
};
const closeAddClientModal = function () {
    showAddClientModal.value = false;
};
</script>

<template>
    <Head title="Clients" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="border-b-2 border-gray-200 px-2 py-4 text-gray-900"
                    >
                        Client Page
                    </div>
                    <div class="flex flex-col gap-2 px-2 py-4">
                        <PrimaryButton class="w-40" @click="openAddClientModal"
                            >Add Client</PrimaryButton
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
                                        Project
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="client in clients"
                                    :key="client.id"
                                    class="border-b bg-white"
                                >
                                    <th
                                        scope="row"
                                        class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                                    >
                                        {{ client.contact_person }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ client.email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{
                                            client.project
                                                ? client.project.title
                                                : 'TBA'
                                        }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a
                                            href="#"
                                            class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                            >Edit</a
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <AddClientModal
            :show="showAddClientModal"
            @close="closeAddClientModal"
        />
    </AuthenticatedLayout>
</template>
