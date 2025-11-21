<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import DateFormatter from "@/Components/DateFormatter.vue";
import Phone from "@/Mixins/Phone.js";
import {CheckIcon, XMarkIcon} from "@heroicons/vue/24/solid/index.js";

const {formatNumber} = Phone();

const props = defineProps({
    schedule: Object
});

const isProcessed = props.schedule.data.attributes.processed
</script>

<template>
    <Head title="Phone Line Schedule"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">Phone Line Schedule</span>
            </h2>
        </template>

        <div class="mt-6">
            <Link
                :href="route('web.phone-line-schedule.index', {competitionPhoneNumber: props.schedule.data.attributes.competition_phone_number})"
                as="button" class="btn btn--sm btn--blue">Back to Schedules
            </Link>
        </div>

        <LayoutBox>
            <div class="grid grid-cols-6 xl:grid-cols-10 gap-x-4 gap-y-0.5">
                <p class="col-span-2 xl:col-span-2 text-gray-500">ID:</p>
                <p class="col-span-4 xl:col-span-3" v-text="props.schedule.data.id"></p>

                <p class="col-span-2 xl:col-span-2 text-gray-500">Author ID:</p>
                <p class="col-span-4 xl:col-span-3" v-text="props.schedule.data.attributes.author_id"></p>

                <p class="col-span-2 xl:col-span-2 text-gray-500">Competition ID:</p>
                <p class="col-span-4 xl:col-span-3" v-text="props.schedule.data.attributes.competition_id"></p>

                <p class="col-span-2 xl:col-span-2 text-gray-500">Phone Number:</p>
                <p class="col-span-4 xl:col-span-3">
                    {{ formatNumber(props.schedule.data.attributes.competition_phone_number) }}
                </p>

                <p class="col-span-2 xl:col-span-2 text-gray-500">Processed:</p>
                <p class="col-span-4 xl:col-span-3">
                    <CheckIcon class="h-5 w-5 text-green-600"
                               v-if="props.schedule.data.attributes.processed"></CheckIcon>
                    <XMarkIcon class="h-5 w-5 text-red-600"
                               v-if="!props.schedule.data.attributes.processed"></XMarkIcon>
                </p>

                <template v-if="isProcessed">
                    <p class="col-span-2 xl:col-span-2 text-gray-500">Completed At:</p>
                    <p class="col-span-4 xl:col-span-3">
                        <DateFormatter :date="props.schedule.data.attributes.completed_at"
                                       format="do MMM yyyy HH:mm:ss">
                        </DateFormatter>
                    </p>

                    <p class="col-span-2 xl:col-span-2 text-gray-500">Success:</p>
                    <p class="col-span-4 xl:col-span-8" v-text="props.schedule.data.attributes.success"></p>

                    <div class="col-span-6 xl:col-span-10 p-4 border rounded-lg mt-6 flex gap-2">
                        <p class="text-gray-500">Notes:</p>
                        <p class="" v-text="props.schedule.data.attributes.notes"></p>
                    </div>
                </template>
            </div>
        </LayoutBox>
    </AuthenticatedLayout>
</template>
