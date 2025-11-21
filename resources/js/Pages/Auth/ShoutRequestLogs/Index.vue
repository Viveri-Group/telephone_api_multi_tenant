<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import Tip from "@/Components/Tip/Tip.vue";
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import Pagination from "@/Components/Layout/Pagination.vue";
import ShoutRequestLogDetails from "@/Pages/Auth/ShoutRequestLogs/ShoutRequestLogDetails.vue";
import ShoutRequestLogSearch from "@/Pages/Auth/ShoutRequestLogs/ShoutRequestLogSearch.vue";
import CountDownTimer from "@/Components/Timer/CountDownTimer.vue";
import Info from "@/Components/Layout/Info.vue";

const props = defineProps({
    shoutRequestLogs: Object,
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
        only: ['shoutRequestLogs'],
    });
};
</script>

<template>
    <Head title="Shout Server Request Logs"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">Shout Server Request Logs</span>
            </h2>
        </template>

        <Info class="mt-4">
            All requests made to the Shout API can be viewed here - this typically deals with audio file change requests.
        </Info>

        <ShoutRequestLogSearch :default-search-form-options="props.defaultSearchFormOptions"
                        :update-search-criteria="updateSearchCriteria"></ShoutRequestLogSearch>

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
            <template v-if="props.shoutRequestLogs.data.data.length > 0">
                <Pagination :data="props.shoutRequestLogs"></Pagination>

                <div>
                    <div class="hidden xl:block">
                        <div
                            class="grid grid-cols-12 gap-4 p-3 text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800"
                        >
                            <p class="col-span-2">
                                Description <tip description="Server description."></tip> <br />
                                ID <tip description="Internal ID of the api request."></tip>
                            </p>

                            <p class="col-span-4">
                                Request Details <tip description="The details of the request such as number of attempts, url & request type."></tip>
                            </p>

                            <p class="text-center">
                                Method
                                <tip description="The http method used."></tip>
                            </p>

                            <p class="text-center">
                                Response
                                <tip description="The response code returned."></tip>
                            </p>

                            <p class="col-span-2 text-center">
                                Date
                                <tip description="The date the request was made."></tip>
                            </p>
                        </div>
                    </div>

                    <template v-for="(shoutRequestLog, index) in props.shoutRequestLogs.data.data">
                        <ShoutRequestLogDetails :shoutRequestLog="shoutRequestLog" :even="!!(index % 2)"
                                         :index="index"></ShoutRequestLogDetails>
                    </template>
                </div>

                <Pagination :data="props.shoutRequestLogs"></Pagination>
            </template>

            <p v-else class="bg-gray-100 p-4 mt-4 text-center rounded text-gray-500 text-sm">There are no entries to display.</p>
        </LayoutBox>
    </AuthenticatedLayout>
</template>
