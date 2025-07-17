<script setup>

defineProps({
    tasks: {
        type: Array,
        required: true
    }
});

</script>

<template>
    <section class="flex flex-col grow border-2 border-solid border-gray-300 rounded-lg basis-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 bg-gray-100 p-2 border-b-2 border-gray-200">
                Project Tasks
            </h2>
            <div class=" overflow-x-auto shadow-md">
                <table class="w-full text-sm text-left rtl:text-right">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-100 border-b-2 border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Task
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Assigned To
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Deadline
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="task in tasks" :key="task.id" class="bg-white border-b">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ task.title }}
                            </th>
                            <td class="px-6 py-4">
                                {{ task.user.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ task.status}}
                            </td>
                            <td class="px-6 py-4">
                                {{ task.end_date}}
                            </td>
                            <td class="px-6 py-4">
                                <a href="#"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </header>
    </section>
</template>
