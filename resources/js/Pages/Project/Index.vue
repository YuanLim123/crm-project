<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, Head, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddProjectModal from './Modals/AddProjectModal.vue';
import { ref } from 'vue';

defineProps({
    projects: {
        type: Array,
    },
});

const showAddProjectModal = ref(false);

const openAddProjectModal = function () {
    showAddProjectModal.value = true;
};
const closeAddProjectModal = function () {
    showAddProjectModal.value = false;
};

const goToCreateProject = function () {
    router.get(route('projects.create'));
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
                        Project Page
                    </div>
                    <div class="flex flex-col gap-2 px-2 py-4">
                        <PrimaryButton class="w-40" @click="goToCreateProject"
                            >Add Project</PrimaryButton
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
                                        Client
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        End Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="project in projects"
                                    :key="project.id"
                                    class="border-b bg-white"
                                >
                                    <th
                                        scope="row"
                                        class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                                    >
                                        {{ project.title }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{
                                            project.client
                                                ? project.client.contact_person
                                                : ''
                                        }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{
                                            project.end_date
                                                ? project.end_date
                                                : ''
                                        }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link
                                            :href="
                                                route(
                                                    'projects.show',
                                                    project.id,
                                                )
                                            "
                                            class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                            >View
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <AddProjectModal
            :show="showAddProjectModal"
            @close="closeAddProjectModal"
        />
    </AuthenticatedLayout>
</template>
