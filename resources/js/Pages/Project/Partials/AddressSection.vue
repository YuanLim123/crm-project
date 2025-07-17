<script setup>

defineProps({
    address: {
        required: true
    }
});

</script>

<template>
    <section class="flex flex-col grow border-2 border-solid border-gray-300 rounded-lg basis-full">
        <header>
            <h2 class="text-lg font-medium text-gray-900 bg-gray-100 p-2">
                Company Address
            </h2>
            <div class="mt-2 px-2 py-4">
                <p class="text-sm">
                    {{ address }}
                </p>
            </div>
        </header>
    </section>
</template>
