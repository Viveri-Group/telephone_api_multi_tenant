<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import {Tabs, Tab} from "vue3-tabs-component";
import 'vue-json-pretty/lib/styles.css';
import DateFormatter from "@/Components/DateFormatter.vue";
import JsonBox from "@/Components/Layout/JsonBox.vue";
import HTTPMixin from "@/Mixins/HTTPMixin.js";

const {attemptsBadge, responseText} = HTTPMixin();

const props = defineProps({
    shoutRequestLog: Object,
});

</script>

<template>
    <Head title="Shout Request Log"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <span class="text-gray-500 font-normal">Shout Request Log</span>
            </h2>
        </template>

        <LayoutBox>
            <div class="flex justify-end">
                <Link :href="route('web.shout-request-logs.index')" as="button" class="btn btn--sm btn--blue">Back</Link>
            </div>
        </LayoutBox>

        <LayoutBox>
            <div class="grid grid-cols-12 text-sm gap-2">
                <p class="col-span-3 xl:col-span-1">Description:</p>
                <p class="col-span-9 xl:col-span-5" v-text="props.shoutRequestLog.data.identifier"></p>

                <p class="col-span-3 xl:col-span-1">Response:</p>
                <p class="col-span-9 xl:col-span-5 font-bold" v-text="props.shoutRequestLog.data.status_code"
                :class="responseText(props.shoutRequestLog.data.status_code)"></p>

                <p class="col-span-3 xl:col-span-1">HTTP Method:</p>
                <p class="col-span-9 xl:col-span-5" v-text="props.shoutRequestLog.data.http_method"></p>

                <p class="col-span-3 xl:col-span-1">Request Type:</p>
                <p class="col-span-9 xl:col-span-5" v-text="props.shoutRequestLog.data.request_type"></p>

                <p class="col-span-3 xl:col-span-1">Response Type:</p>
                <p class="col-span-9 xl:col-span-5" v-text="props.shoutRequestLog.data.response_class"></p>

                <p class="col-span-3 xl:col-span-1"></p>
                <p class="col-span-9 xl:col-span-5"></p>

                <p class="col-span-3 xl:col-span-1">URL:</p>
                <p class="col-span-9 xl:col-span-11 break-all text-gray-600 mt-2 bg-gray-100 px-3 py-1.5 border rounded" v-text="props.shoutRequestLog.data.url"></p>
            </div>
        </LayoutBox>

        <LayoutBox>
            <Tabs>
                <Tab name="Request">
                    <div class="grid grid-cols-12 text-sm gap-x-4 mb-4 mt-4 md:mt-0">
                        <div class="col-span-12 xl:col-span-3 xl:border rounded xl:p-2 flex justify-between">
                            <p>Started:</p>
                            <DateFormatter :date="props.shoutRequestLog.data.request_start" format="do MMM yyyy HH:mm:ss">
                            </DateFormatter>
                        </div>

                        <div class="col-span-12 xl:col-span-3 xl:border rounded xl:p-2 flex justify-between">
                            <p>Ended:</p>
                            <DateFormatter :date="props.shoutRequestLog.data.request_end" format="do MMM yyyy HH:mm:ss">
                            </DateFormatter>
                        </div>

                        <div class="col-span-12 xl:col-span-3 xl:border rounded xl:p-2 flex justify-between">
                            <p>Time:</p>
                            {{props.shoutRequestLog.data.response_time + 'ms'}}
                        </div>

                        <div class="col-span-12 xl:col-span-3 xl:border rounded xl:p-2 flex justify-between">
                            <p>Attempts:</p>
                            <p
                                class="mr-1.5 text-xs w-[1rem] h-[1rem] rounded-full flex justify-center items-center"
                                :class="attemptsBadge(props.shoutRequestLog.data.status_code, props.shoutRequestLog.data.attempts)">
                                {{props.shoutRequestLog.data.attempts}}
                            </p>
                        </div>
                    </div>

                    <JsonBox :data="props.shoutRequestLog.data.request_input"></JsonBox>
                </Tab>

                <Tab name="Response" :suffix="'<span class=\'ml-2 ' + responseText(props.shoutRequestLog.data.status_code) + '\'>('+ props.shoutRequestLog.data.status_code +')</span>'">
                    <JsonBox :data="props.shoutRequestLog.data.response"></JsonBox>
                </Tab>
            </Tabs>
        </LayoutBox>
    </AuthenticatedLayout>
</template>
