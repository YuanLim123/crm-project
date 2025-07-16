<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'flex items-center px-4 py-2 bg-gray-200 text-gray-900 text-sm rounded-md transition'
        : 'flex items-center px-4 py-2 text-gray-700 text-sm hover:bg-gray-100 hover:text-gray-900 rounded-md transition'
);
</script>

<template>
    <Link
        :href="href"
        :class="classes"
        :aria-current="active ? 'page' : undefined"
    >
        <slot />
    </Link>
</template>