<script setup>
import Phone from "@/Mixins/Phone.js"
import DateFormatter from "@/Components/DateFormatter.vue";
import {CheckIcon, XMarkIcon} from "@heroicons/vue/24/solid/index.js";

const {formatNumber} = Phone();

const props = defineProps({
    schedule: Object,
    even: Boolean,
    index: Number,
});

const isProcessed = props.schedule.attributes.processed;

</script>

<template>
    <div>
        <div class="border border-gray-100 xl:border-none rounded-lg px-0 text-sm"
             :class="{'mt-4 xl:mt-0' : index > 0}">
            <div class="xl:hidden flex justify-between bg-gray-100 p-2 rounded-t-lg">
                <p class="ml-auto text-gray-600 text-sm" v-text="'ID: ' + props.schedule.id"></p>
            </div>

            <div class="grid grid-cols-3 xl:grid-cols-12 gap-y-2 xl:gap-4 px-4 xl:px-2 py-4 xl:py-2"
                 :class="{'xl:bg-gray-100' : even}">

                <div class="hidden xl:flex flex-wrap gap-2 xl:justify-center">
                    <p class="text-sm" v-text="props.schedule.id"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400 text-left xl:text-center">Author ID:</div>
                <div class="col-span-2 xl:col-span-1 text-left xl:text-center">
                    {{ props.schedule.attributes.author_id ?? '-' }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Competition ID:</div>
                <div class="col-span-2 xl:col-span-1 xl:text-center">
                    {{ props.schedule.attributes.competition_id }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Phone Number:</div>
                <div class="col-span-2 xl:col-span-2 xl:text-center">
                    {{ formatNumber(props.schedule.attributes.competition_phone_number) }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Action At:</div>
                <div class="col-span-2 xl:col-span-3 xl:text-center">
                    <DateFormatter :date="props.schedule.attributes.action_at" format="do MMM yyyy HH:mm:ss">
                    </DateFormatter>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Processed:</div>
                <div class="col-span-2 xl:col-span-2 xl:text-center">
                    {{ props.schedule.attributes.processed ? 'YES' : 'NO' }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Success:</div>
                <div class="col-span-2 xl:col-span-1">
                    <template v-if="isProcessed">
                        <CheckIcon class="h-5 w-5 text-green-600 xl:mx-auto"
                                   v-if="props.schedule.attributes.success"></CheckIcon>
                        <XMarkIcon class="h-5 w-5 text-red-600 xl:mx-auto"
                                   v-if="!props.schedule.attributes.success"></XMarkIcon>
                    </template>

                    <template v-else>
                        <p class="xl:text-center text-gray-400">N/A</p>
                    </template>
                </div>


                <div class="col-span-3 xl:col-span-1 flex justify-end gap-2">
                    <Link :href="route('web.phone-line-schedule.show', {phoneLineSchedule: props.schedule.id})" as="button" class="btn btn--sm btn--blue">
                        View
                    </Link>
                </div>


            </div>
        </div>
    </div>
</template>
