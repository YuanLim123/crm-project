<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    users: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['close', 'select']);
</script>

<template>
    <Modal :show="props.show" @close="emit('close')">
        <div class="p-6">
            <h2 class="py-2 text-lg font-medium text-gray-900">
                Select a User
            </h2>
            <div class="overflow-x-auto overflow-y-auto shadow-md">
                <table class="w-full text-left text-sm rtl:text-right">
                    <thead
                        class="border-b-2 border-gray-200 bg-gray-100 text-xs uppercase text-gray-500"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="border-b bg-white"
                        >
                            <th
                                scope="row"
                                class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                            >
                                {{ user.name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4">
                                <a
                                    @click.prevent="emit('select', user)"
                                    href="#"
                                    class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                    >Select</a
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex items-center justify-end">
                <SecondaryButton @click="emit('close')">
                    Cancel
                </SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
