<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import TableRow from "@/Pages/Task/Components/TableRow.vue";
import TaskForm from "@/Pages/Task/Components/TaskForm.vue";
import {ref} from "vue";
import Modal from '@/Components/Modal.vue';
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const form = useForm({});
const showFormModal = ref(false);
const showDeleteConfirmationModal = ref(false);
const selectedTask = ref(null);

defineProps({
    tasks: Object
})

const openFormModal = (task) => {
    selectedTask.value = task;
    showFormModal.value = true;
}

const closeFormModal = () => {
    showFormModal.value = false;
    selectedTask.value = null;
    form.reset();
};

const openDeleteConfirmationModal = (task) => {
    selectedTask.value = task;
    showDeleteConfirmationModal.value = true;
}

const closeDeleteConfirmationModal = () => {
    showDeleteConfirmationModal.value = false;
    selectedTask.value = null;
};

const deleteTask = () => {
    form.delete(route('tasks.destroy', {'task': selectedTask.value.id}))
    closeDeleteConfirmationModal()
}

</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg pt-8">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-lg font-medium text-gray-900">Tasks</h1>
                                <p class="mt-1 text-sm text-gray-600">A list of all your tasks with the name, status, priority and due by date.</p>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <PrimaryButton @click="openFormModal({})">Add Task</PrimaryButton>
                            </div>
                        </div>
                        <div class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0 w-1/2">Name</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Priority</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Due By</th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                                    <span class="sr-only">Delete</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <table-row
                                                v-for="task in tasks"
                                                :key="task.id"
                                                :task="task"
                                                @editClicked="openFormModal(task)"
                                                @deleteClicked="openDeleteConfirmationModal(task)"
                                            />
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showFormModal">
            <TaskForm :task="selectedTask" @close="closeFormModal"/>
        </Modal>
        <Modal :show="showDeleteConfirmationModal" @close="closeDeleteConfirmationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this task?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your task is deleted, all of it's data will be permanently deleted. Please
                    click the delete button to confirm.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeDeleteConfirmationModal"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteTask"
                    >
                        Delete Task
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
