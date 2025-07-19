<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },

    clients: {
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
                Select a client
            </h2>
            <div class="overflow-x-auto shadow-md">
                <table class="w-full text-left text-sm rtl:text-right">
                    <thead
                        class="border-b-2 border-gray-200 bg-gray-100 text-xs uppercase text-gray-500"
                    >
                        <tr>
                            <th scope="col" class="px-6 py-3">Company</th>
                            <th scope="col" class="px-6 py-3">
                                Contact Person
                            </th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="client in clients"
                            :key="client.id"
                            class="border-b bg-white"
                        >
                            <th
                                scope="row"
                                class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                            >
                                {{ client.company }}
                            </th>
                            <td class="px-6 py-4">
                                {{ client.contact_person }}
                            </td>
                            <td class="px-6 py-4">
                                {{ client.email }}
                            </td>
                            <td class="px-6 py-4">
                                <a
                                    @click.prevent="emit('select', client)"
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
