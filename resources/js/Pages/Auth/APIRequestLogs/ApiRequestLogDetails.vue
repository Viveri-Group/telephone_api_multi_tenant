<script setup>
import DateFormatter from "@/Components/DateFormatter.vue";
import HTTPMixin from "@/Mixins/HTTPMixin.js";
import CopyToClipboard from "@/Components/Clipboard/CopyToClipboard.vue";

const {responseSideBorder, responseText, requestDuration} = HTTPMixin();

const props = defineProps({
    apiRequestLog: Object,
    even: Boolean,
    index: Number,
});

</script>

<template>
    <div
        class="border-l-[.4rem] my-2"
        :class="responseSideBorder(props.apiRequestLog.response_status)"
    >
        <div class="border border-gray-100 xl:border-none rounded-lg px-0 text-sm"
             :class="{'mt-4 xl:mt-0' : index > 0}">
            <div class="xl:hidden flex justify-between bg-gray-100 p-2 rounded-t-lg">
                <p class="text-gray-600 text-sm" v-text="props.apiRequestLog.uuid"></p>
                <p class="ml-auto text-gray-600 text-sm" v-text="'ID: ' + props.apiRequestLog.id"></p>
            </div>

            <div class="grid grid-cols-3 xl:grid-cols-12 gap-y-2 xl:gap-4 px-4 xl:px-2 py-4 xl:py-2"
                 :class="{'xl:bg-gray-100' : even}">

                <div class="xl:col-span-3 hidden xl:block text-gray-400 text-xs">
                    <p v-text="'ID: '+props.apiRequestLog.id"></p>
                    <p v-text="props.apiRequestLog.uuid"></p>

                    <template v-if="props.apiRequestLog.request_input?.call_id">
                        <CopyToClipboard
                            styling="mt-1 border border-blue-500 rounded-md text-blue-500 hover:border-blue-800 hover:text-blue-800 px-1"
                            :button-title="'Call ID: '+props.apiRequestLog.request_input.call_id"
                            :copy="props.apiRequestLog.request_input.call_id">
                        </CopyToClipboard>
                    </template>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Call ID:</div>
                <div class="block xl:hidden col-span-2 xl:col-span-4">
                    <p v-if="props.apiRequestLog.request_input?.call_id" v-text="props.apiRequestLog.request_input.call_id"></p>
                    <p v-else class="text-gray-400">-</p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">User:</div>
                <div class="col-span-2 xl:col-span-4">
                    <p class="text-gray-400 text-xs" v-text="props.apiRequestLog.user.name + ' (' + props.apiRequestLog.user.id + ')'"></p>
                    <p class="hidden xl:block truncate" v-text="props.apiRequestLog.request_type"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Request Type:</div>
                <div class="block xl:hidden col-span-2 xl:col-span-2 truncate">
                    {{ props.apiRequestLog.request_type }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">HTTP Response:</div>
                <div class="col-span-2 xl:col-span-1 font-bold xl:text-center"
                :class="responseText(props.apiRequestLog.response_status)">
                    {{ props.apiRequestLog.response_status }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Request Duration:</div>
                <div class="col-span-2 xl:col-span-1 font-bold xl:text-center"
                >
                    <span v-if="props.apiRequestLog.duration"
                          v-text="props.apiRequestLog.duration + 'ms'"
                          :class="requestDuration(props.apiRequestLog.duration)"
                    ></span>

                    <span v-else class="text-gray-400">-</span>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Date:</div>
                <div class="col-span-2 xl:col-span-2 xl:text-center">
                    <DateFormatter :date="apiRequestLog.created_at" format="do MMM yyyy HH:mm:ss"></DateFormatter>
                </div>

                <div class="col-span-3 xl:col-span-1 text-right">
                    <Link :href="route('web.api-request-logs.show', {apiRequestLog: apiRequestLog.id})" as="button" class="btn btn--sm btn--blue">View</Link>
                </div>
            </div>
        </div>
    </div>
</template>
