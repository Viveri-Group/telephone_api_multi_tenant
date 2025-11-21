<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import {Tabs, Tab} from "vue3-tabs-component";
import VueJsonPretty from 'vue-json-pretty';
import 'vue-json-pretty/lib/styles.css';
import DateFormatter from "@/Components/DateFormatter.vue";
import HTTPMixin from "@/Mixins/HTTPMixin.js";
import JsonBox from "@/Components/Layout/JsonBox.vue";

const {responseText, requestDuration} = HTTPMixin();

const props = defineProps({
    apiRequestLog: Object,
});

</script>

<template>
    <Head title="API Request Log"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">API Request Log</span>
            </h2>
        </template>

        <LayoutBox>
            <div class="flex justify-end">
                <Link :href="route('web.api-request-logs.index')" as="button" class="btn btn--sm btn--blue">Back</Link>
            </div>
        </LayoutBox>

        <LayoutBox>
            <div class="grid grid-cols-12 text-sm gap-2">
                <p class="col-span-3 xl:col-span-1">Call ID:</p>
                <div class="col-span-9 xl:col-span-5"
                   >
                    <span v-if="props.apiRequestLog.data.request_input?.call_id"
                          v-text="props.apiRequestLog.data.request_input?.call_id"
                    ></span>
                    <span v-else>N/A</span>
                </div>

                <p class="col-span-3 xl:col-span-1">Created:</p>
                <div class="col-span-9 xl:col-span-5">
                    <DateFormatter :date="props.apiRequestLog.data.created_at" format="do MMM yyyy HH:mm:ss">
                    </DateFormatter>
                </div>

                <p class="col-span-3 xl:col-span-1">Response:</p>
                <p class="col-span-9 xl:col-span-5" v-text="props.apiRequestLog.data.response_status"></p>

                <p class="col-span-3 xl:col-span-1">UUID:</p>
                <p class="col-span-9 xl:col-span-5" v-text="props.apiRequestLog.data.uuid"></p>

                <p class="col-span-3 xl:col-span-1">User:</p>
                <p class="col-span-9 xl:col-span-5" v-text="props.apiRequestLog.data.user.name"></p>

                <p class="col-span-3 xl:col-span-1">Duration:</p>
                <div class="col-span-9 xl:col-span-5">
                    <span v-if="props.apiRequestLog.data.duration"
                          v-text="props.apiRequestLog.data.duration + 'ms'"
                          :class="requestDuration(props.apiRequestLog.data.duration)"
                    ></span>

                    <span v-else class="text-gray-400">-</span>
                </div>
            </div>

            <div class="grid grid-cols-12 text-sm gap-2">
                <p class="col-span-3 xl:col-span-1 mt-4">Request Type:</p>
                <p class="col-span-9 xl:col-span-11 break-all text-gray-600 mt-2 bg-gray-100 px-3 py-1.5 border rounded" v-text="props.apiRequestLog.data.request_type"></p>
            </div>
        </LayoutBox>

        <LayoutBox>
            <Tabs>
                <Tab name="Request Input">
                    <JsonBox :data="props.apiRequestLog.data.request_input"></JsonBox>
                </Tab>

                <Tab name="Response" :suffix="'<span class=\'ml-2 ' + responseText(props.apiRequestLog.data.response_status) + '\'>('+ props.apiRequestLog.data.response_status +')</span>'">
                    <JsonBox :data="props.apiRequestLog.data.response_data"></JsonBox>
                </Tab>
            </Tabs>
        </LayoutBox>
    </AuthenticatedLayout>
</template>
