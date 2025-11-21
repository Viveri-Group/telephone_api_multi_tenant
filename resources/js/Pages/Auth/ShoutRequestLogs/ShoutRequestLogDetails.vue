<script setup>
import DateFormatter from "@/Components/DateFormatter.vue";
import HTTPMixin from "@/Mixins/HTTPMixin.js";

const {attemptsBadge, responseBadge, responseSideBorder, responseText} = HTTPMixin();

const props = defineProps({
    shoutRequestLog: Object,
    even: Boolean,
    index: Number,
});

</script>

<template>
    <div
        class="border-l-[.4rem] my-2"
        :class="responseSideBorder(props.shoutRequestLog.status_code)"
    >
        <div class="border border-gray-100 xl:border-none rounded-lg px-0 text-sm"
             :class="{'mt-4 xl:mt-0' : index > 0}">
            <div class="xl:hidden flex justify-between bg-gray-100 p-2 rounded-t-lg">
                <p class="text-gray-600 text-sm" v-text="props.shoutRequestLog.identifier"></p>
                <div class="flex gap-1">
                    <div class="ml-auto text-gray-600 mt-1 rounded px-1 text-xs"
                       :class="responseBadge(props.shoutRequestLog.status_code)"
                       v-text="'Response '+props.shoutRequestLog.status_code"></div>
                    <div class="ml-auto text-gray-600 mt-1 rounded px-1 text-xs ml-2"
                       :class="attemptsBadge(props.shoutRequestLog.status_code, props.shoutRequestLog.attempts)"
                       v-text="'Attempts '+props.shoutRequestLog.attempts"></div>
                </div>
            </div>

            <div class="grid grid-cols-3 xl:grid-cols-12 gap-y-2 xl:gap-4 px-4 xl:px-2 py-4 xl:py-2"
                 :class="{'xl:bg-gray-100' : even}">

                <div class="hidden xl:block col-span-2 xl:col-span-2">
                    <p v-text="props.shoutRequestLog.identifier"></p>
                    <p class="text-gray-400 text-xs" v-text="'ID: '+props.shoutRequestLog.id"></p>
                </div>

                <div class="hidden xl:block col-span-2 xl:col-span-4">
                    <div>
                        <div class="inline-block">
                            <span
                                class="mr-1.5 text-xs w-[1rem] h-[1rem] rounded-full flex justify-center items-center"
                                :class="attemptsBadge(props.shoutRequestLog.status_code, props.shoutRequestLog.attempts)">{{
                                    props.shoutRequestLog.attempts
                                }}
                            </span>
                        </div>
                        <span class="inline-block ml-auto break-all" v-text="props.shoutRequestLog.url"></span>
                    </div>

                    <p class="text-gray-400 text-xs break-all mt-1" v-text="props.shoutRequestLog.request_type"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">URL:</div>
                <div class="col-span-2 xl:col-span-2 break-all block xl:hidden">
                    {{ props.shoutRequestLog.url }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">HTTP Method:</div>
                <div class="col-span-2 xl:col-span-1 xl:text-center">
                    {{ props.shoutRequestLog.http_method }}
                </div>

                <div class="col-span-2 xl:col-span-1 xl:text-center hidden xl:block font-bold"
                :class="responseText(props.shoutRequestLog.status_code)">
                    {{ props.shoutRequestLog.status_code }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Request Type:</div>
                <div class="col-span-2 xl:col-span-1 break-all block xl:hidden">
                    {{ props.shoutRequestLog.request_type }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Date:</div>
                <div class="col-span-2 xl:col-span-2 xl:text-center text-left">
                    <DateFormatter :date="shoutRequestLog.created_at" format="do MMM yyyy HH:mm:ss"></DateFormatter>
                </div>
                <div class="col-span-3 xl:col-span-2 flex justify-end">
                    <Link :href="route('web.shout-request-logs.show', {shoutRequestLog: props.shoutRequestLog.id})" as="button" class="btn btn--sm btn--blue">View</Link>
                </div>
            </div>
        </div>
    </div>
</template>
