<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import Tip from "@/Components/Tip/Tip.vue";
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import Pagination from "@/Components/Layout/Pagination.vue";
import MaxCapacityCallLogDetails from "@/Pages/Auth/MaxCapacityCallLogs/MaxCapacityCallLogDetails.vue";
import MaxCapacityCallLogSearch from "@/Pages/Auth/MaxCapacityCallLogs/MaxCapacityCallLogSearch.vue";
import CountDownTimer from "@/Components/Timer/CountDownTimer.vue";
import Info from "@/Components/Layout/Info.vue";

const props = defineProps({
    maxCapacityCallLogs: Object,
    defaultSearchFormOptions: Object
});

const form = useForm({
    competition_id: '',
    call_id:'',
    phone_number: '',
    caller_phone_number: '',
    date_from: '',
    date_to: '',
});

const updateSearchCriteria = (searchParams) => {
    Object.entries(searchParams).forEach(([key, value]) => {
        form[key] = value
    });
};

const handleAutoReload = () => {
    router.reload({
        only: ['maxCapacityCallLogs'],
    });
};
</script>

<template>
    <Head title="Max Capacity Call Logs"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">Max Capacity Call Logs</span>
            </h2>
        </template>

        <Info class="mt-4">
            Max Capacity Logs are created when an entrant calls in but the maximum capacity has been hit.
        </Info>

        <MaxCapacityCallLogSearch :default-search-form-options="props.defaultSearchFormOptions"
                        :update-search-criteria="updateSearchCriteria"></MaxCapacityCallLogSearch>

        <LayoutBox>
            <div class="flex justify-end">
                <CountDownTimer
                    count-down-value="30"
                    :auto-start="false"
                    :auto-restart="true"
                    :final-stop-after="60 * 30"
                    turn-amber-at="15"
                    turn-red-at="5"
                    @count-down-ended="handleAutoReload"
                >
                </CountDownTimer>
                <tip class="hidden lg:inline-block" description="Automatically reload page data."></tip>
            </div>
        </LayoutBox>

        <LayoutBox>
            <template v-if="props.maxCapacityCallLogs.data.data.length > 0">
                <Pagination :data="props.maxCapacityCallLogs"></Pagination>

                <div>
                    <div class="hidden xl:block">
                        <div
                            class="grid grid-cols-12 gap-4 p-3 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                        >
                            <p class="col-span-3">
                                ID
                                <tip description="Internal ID."></tip>
                            </p>

                            <p class="col-span-3 text-center">
                                Call ID
                                <tip description="The unique call id from the shout switch."></tip>
                            </p>

                            <p class="col-span-3 text-center">
                                Max # Lines
                                <tip description="The maximum number of allowed phone lines."></tip>
                            </p>

                            <p class="col-span-3 text-center">
                                Created
                                <tip description="The date the request was made."></tip>
                            </p>
                        </div>
                    </div>

                    <template v-for="(maxCapacityCallLog, index) in props.maxCapacityCallLogs.data.data">
                        <MaxCapacityCallLogDetails :maxCapacityCallLog="maxCapacityCallLog" :even="!!(index % 2)"
                                         :index="index"></MaxCapacityCallLogDetails>
                    </template>
                </div>

                <Pagination :data="props.maxCapacityCallLogs"></Pagination>
            </template>

            <p v-else class="bg-gray-100 p-4 mt-4 text-center rounded text-gray-500 text-sm">There are no entries to display.</p>
        </LayoutBox>
    </AuthenticatedLayout>
</template>
