<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">Dashboard</span>
            </h2>
        </template>

        <LayoutBox>
            Welcome to the Dashboard!
        </LayoutBox>
    </AuthenticatedLayout>
</template>
