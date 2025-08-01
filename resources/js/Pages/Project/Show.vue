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

defineProps({
    project: {
        type: Object,
    },
    files: {
        type: Array,
        default: () => [],
    }
});

</script>

<template>

    <Head title="Project" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-9xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex flex-row justify-between items-center">
                        <div class="p-6 text-gray-900">
                            {{ project.title }}
                        </div>
                        <div class="flex flex-row justify-end items-center gap-4 mr-6">
                            <Link :href="route('projects.edit', { project: project })"
                                class="px-2  bg-blue-500 h-8 text-white rounded-lg hover:bg-blue-600 flex items-center">
                            Edit Project</Link>
                            <Link :href="route('projects.index')"
                                class="px-2 border-2 border-solid rounded-lg h-8 flex items-center">Back to Projects
                            </Link>
                        </div>
                    </div>
                    <div class="flex gap-4 p-6 flex-wrap">
                        <ProjectSection :project="project" />
                        <UserSection :user="project.user" />
                        <ClientSection :client="project.client" />
                        <TaskSection :tasks="project.tasks" />
                        <AddressSection :address="project.client.company_address" />
                        <ProjectTasksSection :tasks="project.tasks" />
                        <AttachmentSection :files="files" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
