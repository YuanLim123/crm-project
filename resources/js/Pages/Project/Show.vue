<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProjectSection from './Partials/ProjectSection.vue';
import ClientSection from './Partials/ClientSection.vue';
import UserSection from './Partials/UserSection.vue';
import TaskSection from './Partials/TaskSection.vue';
import AddressSection from './Partials/AddressSection.vue';
import ProjectTasksSection from './Partials/ProjectTasksSection.vue';
import AttachmentSection from './Partials/AttachmentSection.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

defineProps({
    project: {
        type: Object,
    },
    files: {
        type: Array,
        default: () => [],
    },
});

const removeProject = function (projectId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('projects.destroy', projectId), {
                // Remove the data object
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Your project has been deleted.',
                        icon: 'success',
                    });
                },
            });
        }
    });
};
</script>

<template>
    <Head title="Project" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex flex-row items-center justify-between">
                        <div class="p-6 text-gray-900">
                            {{ project.title }}
                        </div>
                        <div
                            class="mr-6 flex flex-row items-center justify-end gap-4"
                        >
                            <a
                                @click.prevent="removeProject(project.id)"
                                href="#"
                                class="flex h-8 items-center rounded-lg bg-red-500 px-2 text-white hover:bg-red-600"
                            >
                                <span class="material-symbols-outlined">
                                    delete
                                </span></a
                            >
                            <Link
                                :href="
                                    route('projects.edit', { project: project })
                                "
                                class="flex h-8 items-center rounded-lg bg-blue-500 px-2 text-white hover:bg-blue-600"
                            >
                                Edit Project</Link
                            >
                            <Link
                                :href="route('projects.index')"
                                class="flex h-8 items-center rounded-lg border-2 border-solid px-2"
                                >Back to Projects
                            </Link>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 p-6">
                        <ProjectSection :project="project" />
                        <UserSection :user="project.user" />
                        <ClientSection :client="project.client" />
                        <TaskSection :tasks="project.tasks" />
                        <AddressSection
                            :address="project.client.company_address"
                        />
                        <ProjectTasksSection :tasks="project.tasks" />
                        <AttachmentSection :files="files" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
