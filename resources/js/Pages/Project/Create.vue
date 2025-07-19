<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextAreaInput from '@/Components/TextAreaInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import ClientSection from './Partials/ClientSection.vue';
import SelectClientModal from './Modals/SelectClientModal.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const showSelectClientModal = ref(false);

const selectedClient = ref(null);

const form = useForm({
    title: '',
    description: '',
    status: '',
    endDate: '',
    client: '',
    assignedUser: '',
    tasks: [],
});

const props = defineProps({
    clients: {
        type: Array,
        default: () => [],
    },
});

const submit = function () {
    console.log(form);
};

const goBack = function () {
    router.get(route('projects.index'));
};

const openSelectClientModal = function () {
    showSelectClientModal.value = true;
};

const closeSelectClientModal = function () {
    showSelectClientModal.value = false;
};

const selectClient = function (client) {
    form.client = client.id;
    showSelectClientModal.value = false;
    selectedClient.value = client;

    console.log('Selected client:', selectedClient.value);
    console.log('Form client:', form.client);
};

const STATUS = [
    { value: 'pending', text: 'Pending' },
    { value: 'in_progress', text: 'In Progress' },
    { value: 'completed', text: 'Completed' },
];
</script>

<template>
    <Head title="Add Project" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="border-b-2 border-gray-200 px-2 py-4 text-gray-900"
                    >
                        Add Project
                    </div>
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="title" value="Title" />

                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-1/3"
                                    v-model="form.title"
                                    required
                                    autofocus
                                    autocomplete="title"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.title"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="description"
                                    value="Description"
                                />

                                <TextAreaInput
                                    id="description"
                                    class="mt-1 block h-24 w-1/2 resize-none"
                                    v-model="form.description"
                                    required
                                    autocomplete="description"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="status" value="Status" />

                                <SelectInput
                                    id="status"
                                    class="mt-1 block w-1/3"
                                    v-model="form.status"
                                    :status="STATUS"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.status"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="endDate"
                                    value="End Date"
                                    class="mt-4"
                                />

                                <TextInput
                                    id="endDate"
                                    type="date"
                                    class="mt-1 block w-1/3"
                                    v-model="form.endDate"
                                    required
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.endDate"
                                />
                            </div>

                            <div class="mt-4">
                                <a
                                    @click.prevent="openSelectClientModal"
                                    href="#"
                                    class="font-medium text-blue-600 hover:underline dark:text-blue-500"
                                    >Choose a client</a
                                >

                                <div v-if="selectedClient" class="mt-4">
                                    <ClientSection :client="selectedClient" class="flex-none"/>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-end">
                                <SecondaryButton @click="goBack">
                                    Back
                                </SecondaryButton>

                                <PrimaryButton class="ms-4" type="submit">
                                    Add
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <SelectClientModal
            :show="showSelectClientModal"
            @close="closeSelectClientModal"
            :clients="props.clients"
            @select="selectClient"
        />
    </AuthenticatedLayout>
</template>
