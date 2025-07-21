<script setup>

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    status: {
        type: Array,
    },
});
</script>

<template>
    <select
        v-model="model"
        class="mt-1 block w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option
            v-for="option in status"
            :key="option.value"
            :value="option.value"
        >
            {{ option.text }}
        </option>
    </select>
</template>
