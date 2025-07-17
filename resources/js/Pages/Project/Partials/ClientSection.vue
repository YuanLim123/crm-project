<script setup>

defineProps({
    client: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <section class="flex flex-col grow border-2 border-solid border-gray-300 rounded-lg basis-1/3">
        <header>
            <h2 class="text-lg font-medium text-gray-900 bg-gray-100 p-2">
                Client Information
            </h2>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Company
                </p>
                <p class="text-sm">
                    {{ client.company }}
                </p>
            </div>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Contact Person
                </p>
                <p class="text-sm"> 
                    {{ client.contact_person }}
                </p>
                
            </div>
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Email
                </p>
                <p class="text-sm">
                    {{ client.email }}
                </p>
            </div> 
            <div class="mt-2 p-2">
                <p class="mt-1 text-sm text-gray-600">
                    Phone
                </p>
                <p class="text-sm">
                    {{ client.phone }}
                </p>
            </div>
        </header>
    </section>
</template>
