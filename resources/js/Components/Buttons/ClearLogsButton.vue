<script setup>
import {Link, router, usePage} from "@inertiajs/vue3";
import ConfirmModal from "@/Components/Modal/ConfirmModal.vue";
import {computed, ref} from "vue";

const isModalOpen = ref(false);

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const confirmAction = () => {
    router.visit(route('web.log-clear-down'));
    closeModal();
};

const show = computed(() => usePage().props.auth.environment !== 'production');

</script>

<template>
    <button v-if="show" @click="openModal" class="font-bold rounded-md bg-blue-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-blue-700 focus:shadow-none active:bg-blue-700 hover:bg-blue-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2">
        Clear Logs
    </button>

    <ConfirmModal
        v-if="show"
        :isOpen="isModalOpen"
        title="Delete Logs?"
        @confirm="confirmAction"
        @cancel="closeModal"
        action-button="Delete"
    >
        <p>The following logs will be deleted.</p>
        <ul class="list-disc ml-10 mt-2">
            <li>Active Call</li>
            <li>Active Call Orphan</li>
            <li>API Request Log</li>
            <li>Entrant Round Count</li>
            <li>Non Entry</li>
            <li>Max Capacity Call Log</li>
        </ul>

        <p class="text-red-600 mt-2">This action CANNOT be undone!</p>

    </ConfirmModal>
</template>

