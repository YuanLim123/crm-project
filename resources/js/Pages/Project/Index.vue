<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

defineProps({
    projects: {
        type: Array,
    }
});
</script>

<template>

    <Head title="Users" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg ">
                    <div class="px-2 py-4 text-gray-900 border-b-2 border-gray-200">
                        Project Page
                    </div>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Title
                                    </th>
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
                                <tr v-for="project in projects" :key="project.id" class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ project.title }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ project.client ? project.client.contact_person : '' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ project.end_date ? project.end_date : '' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <Link :href="route('projects.show', project.id)"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
