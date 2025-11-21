<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import Tip from "@/Components/Tip/Tip.vue";
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import Info from "@/Components/Layout/Info.vue";
import PhoneLineScheduleDetails from "@/Pages/Auth/PhoneLineSchedule/PhoneLineScheduleDetails.vue";

const props = defineProps({
    schedules: Object,
    competitionPhoneNumber: String,
    processed: Boolean,
});

const isProcessed = props.processed;

</script>

<template>
    <Head title="Phone Line Schedules"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">Phone Line Schedules
                    <span class="italic text-sm" v-if="isProcessed">(Processed)</span>
                    <span class="italic text-sm" v-if="!isProcessed">(Not Processed)</span>
                </span>
            </h2>
        </template>

        <Info class="mt-4" v-if="!isProcessed">
            This page shows the scheduled changes (that haven't happened yet) that will attempt to assign the number to a competition.
        </Info>

        <Info class="mt-4" v-if="isProcessed">
            This page shows schedules that have been processed - that attempted to assign the number to a competition.
        </Info>

        <div>
            <Link :href="route('web.phone-line-schedule.index', {competitionPhoneNumber: props.competitionPhoneNumber}) + '?processed=true'" as="button" class="btn btn--sm btn--blue" v-if="!isProcessed">Show Processed Schedules</Link>
            <Link :href="route('web.phone-line-schedule.index', {competitionPhoneNumber: props.competitionPhoneNumber})" as="button" class="btn btn--sm btn--blue" v-if="isProcessed">Show Upcoming Schedules</Link>
        </div>

        <LayoutBox>
            <template v-if="props.schedules.data.length > 0">

                <div>
                    <div class="hidden xl:block">
                        <div
                            class="grid grid-cols-12 gap-4 p-3 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                        >
                            <p class="xl:text-center">
                                ID
                                <tip description="Internal ID of the entry."></tip>
                            </p>

                            <p class="xl:text-center">
                                Author ID
                                <tip description="External ID of the creator."></tip>
                            </p>

                            <p class="xl:text-center">
                                Comp ID
                                <tip description="The ID of the competition this number will be moved to."></tip>
                            </p>

                            <p class="col-span-2 xl:text-center">
                                Phone Number
                                <tip description="The target phone number."></tip>
                            </p>

                            <p class="col-span-3 xl:text-center">
                                Action At
                                <tip description="When the telephone number will be moved."></tip>
                            </p>

                            <p class="col-span-2 xl:text-center">
                                Processed
                                <tip description="Has the schedule been processed."></tip>
                            </p>

                            <p class="col-span-1 xl:text-center">
                                Success
                                <tip description="Was the schedule a success?"></tip>
                            </p>
                        </div>
                    </div>

                    <template v-for="(schedule, index) in props.schedules.data">
                        <PhoneLineScheduleDetails
                            :schedule="schedule" :even="!!(index % 2)"
                            :index="index"
                        ></PhoneLineScheduleDetails>
                    </template>
                </div>
            </template>

            <p v-else class="bg-gray-100 p-4 mt-4 text-center rounded text-gray-500 text-sm">There are no entries to
                display.</p>
        </LayoutBox>
    </AuthenticatedLayout>
</template>
