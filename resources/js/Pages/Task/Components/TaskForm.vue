<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import VueTailwindDatepicker from 'vue-tailwind-datepicker';

const props = defineProps({
    task: Object
})

const form = useForm({
    id: props.task.id,
    name: props.task.name || '',
    priority: props.task.priority || '',
    status: props.task.status || '',
    due_by: props.task.due_by || ''
})

const emit = defineEmits(['close']);

const closeModal = () => {
    form.reset();
    emit('close');
}

const store = () => {
    form.post(route('tasks.store'), {
        onSuccess: () => closeModal(),
    });
}

const update = () => {
    form.put(route('tasks.update', {'task': form.id}), {
        onSuccess: () => closeModal(),
    });
}

const editing = () => {
    return Object.keys(props.task).length
}

</script>

<template>
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            {{ editing() ? 'Edit' : 'Create '}} a task.
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Enter the details below for your task and click enter to create.
        </p>

        <div class="mt-6">
            <InputLabel for="name" value="Name"  />

            <TextInput
                id="name"
                ref="nameInput"
                v-model="form.name"
                type="text"
                class="mt-1 block w-3/4"
                placeholder="Name"
            />

            <InputError :message="form.errors.name" class="mt-2" />
        </div>

        <div class="mt-6">
            <InputLabel for="status" value="Status"  />

            <select
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                id="status"
                name="status"
                v-model="form.status"
            >
                <option>Not started</option>
                <option>In progress</option>
                <option>Completed</option>
            </select>

            <InputError :message="form.errors.status" class="mt-2" />
        </div>

        <div class="mt-6">
            <InputLabel for="priority" value="Priority"  />

            <select
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                name="status"
                id="status"
                v-model="form.priority"
            >
                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
            </select>

            <InputError :message="form.errors.priority" class="mt-2" />
        </div>

        <div class="mt-6">
            <InputLabel for="due_by" value="Due By" />

            <vue-tailwind-datepicker
                as-single
                v-model="form.due_by"
                placeholder="Due by"
                no-input
                name="due_by"
            />

            <InputError :message="form.errors.due_by" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

            <PrimaryButton
                v-if="editing()"
                class="ml-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="update"
            >
                Save Changes
            </PrimaryButton>

            <PrimaryButton
                v-else
                class="ml-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="store"
            >
                Create Task
            </PrimaryButton>
        </div>
    </div>
</template>
