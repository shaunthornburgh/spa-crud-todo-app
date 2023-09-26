<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Red from "@/Components/Badges/Red.vue";
import Yellow from "@/Components/Badges/Yellow.vue";
import Green from "@/Components/Badges/Green.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    task: Object
})

const emit = defineEmits(['editClicked', 'deleteClicked'])

const handleEditClick = () => {
    emit('editClicked', props.task);
}

const handleDeleteClick = () => {
    emit('deleteClicked', props.task);
}
</script>

<template>
    <tr>
        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ props.task.name }}</td>
        <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
            <red v-if="props.task.priority === 'High'">{{ props.task.priority }}</red>
            <yellow v-if="props.task.priority === 'Medium'">{{ props.task.priority }}</yellow>
            <green v-if="props.task.priority === 'Low'">{{ props.task.priority }}</green>
        </td>
        <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
            <red v-if="props.task.status === 'Not started'">{{ props.task.status }}</red>
            <yellow v-if="props.task.status === 'In progress'">{{ props.task.status }}</yellow>
            <green v-if="props.task.status === 'Completed'">{{ props.task.status }}</green>
        </td>
        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ props.task.due_by }}</td>
        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
            <SecondaryButton @click="handleEditClick">Edit</SecondaryButton>
        </td>
        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
            <DangerButton @click="handleDeleteClick">Delete</DangerButton>
        </td>
    </tr>
</template>
