<script setup>

defineProps({
    user: {
        type: Object,
    }
});

</script>

<template>
    <section class="flex flex-col grow border-2 border-solid border-gray-300 rounded-lg basis-1/3">
        <header>
            <h2 class="text-lg font-medium text-gray-900 bg-gray-100 p-2">
                Assigned User
            </h2>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Name
                </p>
                <p class="text-sm">
                    {{ user ? user.name : 'TBA' }}
                </p>
            </div>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Email
                </p>
                <p class="text-sm"> 
                    {{ user ? user.email : 'TBA' }}
                </p>
                
            </div>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Phone
                </p>
                <p class="text-sm">
                    {{ user ? user.phone : 'TBA' }}
                </p>
            </div> 
        </header>
    </section>
</template>
