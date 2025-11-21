<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm, router} from '@inertiajs/vue3';
import Tip from "@/Components/Tip/Tip.vue";
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import Pagination from "@/Components/Layout/Pagination.vue";
import ApiRequestLogDetails from "@/Pages/Auth/APIRequestLogs/ApiRequestLogDetails.vue";
import APIRequestLogSearch from "@/Pages/Auth/APIRequestLogs/APIRequestLogSearch.vue";
import CountDownTimer from "@/Components/Timer/CountDownTimer.vue";
import Info from "@/Components/Layout/Info.vue";
import RecordButton from "@/Components/Buttons/RecordButton.vue";
import {computed, ref, watch} from "vue";
import {format} from "date-fns";
import LineChart from "@/Components/Charts/LineChart.vue";

const props = defineProps({
    apiRequestLogs: Object,
    defaultSearchFormOptions: Object
});

const interval = 30;
const captureGraphData = ref(false);
let graphDataPoints = ref([]);
let graphDataLabels = [];
let graphPointsTitle = '';

watch(() => props.apiRequestLogs, (newVal, oldVal) => {
    if (captureGraphData.value) {
        if (captureGraphData.value) {
            makeGraphDataEntry();
        }
    }
});

const chartData = computed(() => ({
    labels: graphDataLabels,
    datasets: [{
        data: graphDataPoints.value,
        label: graphPointsTitle,
        fill: true,
        borderColor: '#649cea',
    }]
}));

const form = useForm({
    competition_id: '',
    call_id:'',
    phone_number: '',
    caller_phone_number: '',
    date_from: '',
    date_to: '',
});

const toggleCaptureGraphData = () => {
    captureGraphData.value = !captureGraphData.value;

    if (!captureGraphData.value) {
        graphDataLabels = [];
        graphDataPoints.value = [];
    }

    if (captureGraphData.value) {
        graphPointsTitle = `API Requests Duration Monitor - ${format(new Date(), "do MMM yyyy HH:mm:ss aa")}`;
        makeGraphDataEntry();
    }
};

const updateSearchCriteria = (searchParams) => {
    Object.entries(searchParams).forEach(([key, value]) => {
        form[key] = value
    });
};

const makeGraphDataEntry = () => {
    graphDataLabels.push(format(new Date(), 'HH:mm:ss'));

    const maxDuration = Math.max(...props.apiRequestLogs.data.data.map(item => item.duration));

    const data = graphDataPoints.value;
    graphDataPoints.value = [...data, maxDuration]
}

const handleAutoReload = () => {
    router.reload({
        only: ['apiRequestLogs'],
    });
};
</script>

<template>
    <Head title="API Request Logs"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">API Request Logs</span>
            </h2>
        </template>

        <Info class="mt-4">
            All requests sent to the API can be viewed here.
        </Info>

        <APIRequestLogSearch :default-search-form-options="props.defaultSearchFormOptions"
                        :update-search-criteria="updateSearchCriteria"></APIRequestLogSearch>

        <LayoutBox>
            <div class="flex justify-end gap-4 items-center">
                <RecordButton
                    :record="captureGraphData"
                    :handle-click="toggleCaptureGraphData">
                </RecordButton>

                <div class="flex justify-end">
                    <CountDownTimer
                        :count-down-value="interval"
                        :auto-start="false"
                        :auto-restart="true"
                        :final-stop-after="60 * interval"
                        turn-amber-at="10"
                        turn-red-at="5"
                        @count-down-ended="handleAutoReload"
                    >
                    </CountDownTimer>
                    <tip class="hidden lg:inline-block" description="Automatically reload page data."></tip>
                </div>
            </div>
        </LayoutBox>

        <LayoutBox v-if="captureGraphData">
            <div class="flex-grow overflow-hidden">
                <LineChart
                    :key="graphDataPoints.length"
                    :data="chartData"
                    :options="{ responsive: true, maintainAspectRatio: false }">
                </LineChart>
            </div>
        </LayoutBox>

        <LayoutBox>
            <template v-if="props.apiRequestLogs.data.data.length > 0">
                <Pagination :data="props.apiRequestLogs"></Pagination>

                <div>
                    <div class="hidden xl:block">
                        <div
                            class="grid grid-cols-12 gap-4 p-3 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                        >
                            <p class="col-span-3">
                                ID <tip description="Internal ID of the api request."></tip> <br>
                                UUID <tip description="UUID of the request."></tip> <br>
                                Call ID <tip description="Call ID if applicable."></tip>
                            </p>

                            <p class="col-span-4">
                                User (ID) <tip description="The user who made the request."></tip> <br>
                                Request Type <tip description="The type of request."></tip>
                            </p>

                            <p class="col-span-1 text-center">
                                HTTP <br>Response
                                <tip description="The http response status code."></tip>
                            </p>

                            <p class="text-center">
                                Duration
                                <tip description="Time took to complete the request (in milliseconds)."></tip>
                            </p>

                            <p class="col-span-2 text-center">
                                Created
                                <tip description="The date the request was made."></tip>
                            </p>
                        </div>
                    </div>

                    <template v-for="(apiRequestLog, index) in props.apiRequestLogs.data.data">
                        <ApiRequestLogDetails
                            :apiRequestLog="apiRequestLog"
                            :even="!!(index % 2)"
                            :index="index">
                        </ApiRequestLogDetails>
                    </template>
                </div>

                <Pagination :data="props.apiRequestLogs"></Pagination>
            </template>

            <p v-else class="bg-gray-100 p-4 mt-4 text-center rounded text-gray-500 text-sm">There are no entries to display.</p>
        </LayoutBox>
    </AuthenticatedLayout>
</template>
