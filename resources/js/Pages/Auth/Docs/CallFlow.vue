<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import LayoutBox from "@/Components/Layout/LayoutBox.vue";

const props = defineProps({
    diagram: String
})
</script>

<template>
    <Head title="Call Flow"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">Call Flow</span>
            </h2>
        </template>

        <LayoutBox>
            <div class="max-w-5xl mx-auto border rounded-xl">
                <img src="../../../../images/call_flow_diagram.svg" alt="Call Flow Diagram">
            </div>
        </LayoutBox>
    </AuthenticatedLayout>
</template>
