<script setup>

defineProps({
    project: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <section class="flex flex-col grow border-2 border-solid border-gray-300 rounded-lg">
        <header>
            <h2 class="text-lg font-medium text-gray-900 bg-gray-100 p-2">
                Project Information
            </h2>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Description
                </p>
                <p class="text-sm">
                    {{ project.description }}
                </p>
            </div>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Deadline
                </p>
                <p class="text-sm"> 
                    {{ project.end_date }}
                </p>
                
            </div>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Status
                </p>
                <p class="text-sm">
                    {{ project.status }}
                </p>
            </div> 
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Created At
                </p>
                <p class="text-sm">
                    {{ project.created_at }}
                </p>
            </div>
        </header>
    </section>
</template>
