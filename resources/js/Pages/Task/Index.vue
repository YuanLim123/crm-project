<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddTaskModal from './Modals/AddTaskModal.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tasks: {
        type: Array,
    },
    users: {
        type: Array,
        default: () => [],
    },
    projects: {
        type: Array,
        default: () => [],
    },
    status: {
        type: Array,
        default: () => [],
    },
});

const showAddTaskModal = ref(false);

const openAddTaskModal = function () {
    showAddTaskModal.value = true;
};
const closeAddTaskModal = function () {
    showAddTaskModal.value = false;
};
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="border-b-2 border-gray-200 px-2 py-4 text-gray-900"
                    >
                        Tasks
                    </div>
                    <div class="flex flex-col gap-2 px-2 py-4">
                        <PrimaryButton class="w-40" @click="openAddTaskModal"
                            >Add Task</PrimaryButton
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
                                    <th scope="col" class="px-6 py-3">Title</th>
                                    <th scope="col" class="px-6 py-3">
                                        Under Project
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Assigned User
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="task in props.tasks"
                                    :key="task.id"
                                    class="border-b bg-white"
                                >
                                    <th
                                        scope="row"
                                        class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                                    >
                                        {{ task.title }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ task.project.title }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ task.user ? task.user.name : 'TBA' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a
                                            href="#"
                                            class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                            >View</a
                                        >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <AddTaskModal
            :show="showAddTaskModal"
            @close="closeAddTaskModal"
            :users="props.users"
            :projects="props.projects"
            :status="props.status"
        />
    </AuthenticatedLayout>
</template>
