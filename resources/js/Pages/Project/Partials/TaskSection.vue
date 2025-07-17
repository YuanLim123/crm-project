<script setup>

defineProps({
    tasks: {
        type: Array,
        required: true
    }
});

</script>

<template>
    <section class="flex flex-col grow border-2 border-solid border-gray-300 rounded-lg basis-1/3">
        <header>
            <h2 class="text-lg font-medium text-gray-900 bg-gray-100 p-2">
                Tasks Information
            </h2>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Total Tasks
                </p>
                <p class="text-sm">
                    {{ tasks.length }} tasks
                </p>
            </div>
            <div class="mt-2 p-2">
                <p class="mt-1 mb-2 text-sm text-gray-600">
                    Tasks List
                </p>
                <ol class="list-none pl-2">
                    <li v-for="task in tasks" :key="task.id" class="text-sm mb-1 flex justify-between">
                        <p>{{ task.title }}</p>
                        <p>{{ task.status }}</p>
                    </li>
                </ol>
            </div>
        </header>
    </section>
</template>
