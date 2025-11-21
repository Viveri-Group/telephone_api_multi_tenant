<script setup>
import DateFormatter from "@/Components/DateFormatter.vue";

const props = defineProps({
    maxCapacityCallLog: Object,
    even: Boolean,
    index: Number,
});

</script>

<template>
    <div>
        <div class="border border-gray-100 xl:border-none rounded-lg px-0 text-sm"
             :class="{'mt-4 xl:mt-0' : index > 0}">
            <div class="xl:hidden flex justify-between bg-gray-100 p-2 rounded-t-lg">
                <p class="text-gray-600 text-sm" v-text="'ID: '+props.maxCapacityCallLog.id"></p>
            </div>

            <div class="grid grid-cols-3 xl:grid-cols-12 gap-y-2 xl:gap-4 px-4 xl:px-2 py-4 xl:py-2"
                 :class="{'xl:bg-gray-100' : even}">

                <div class="hidden xl:flex flex-wrap gap-2 xl:col-span-3">
                    <p class="text-sm " v-text="props.maxCapacityCallLog.id"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Call ID:</div>
                <div class="col-span-2 xl:col-span-3 md:text-center">
                    <p v-text="props.maxCapacityCallLog.call_id"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Max # Lines:</div>
                <div class="col-span-2 xl:col-span-3 md:text-center">
                    <p v-text="props.maxCapacityCallLog.allowed_capacity"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Date:</div>
                <div class="col-span-2 xl:col-span-3 md:text-center text-left">
                    <DateFormatter :date="maxCapacityCallLog.created_at" format="do MMM yyyy HH:mm:ss"></DateFormatter>
                </div>
            </div>
        </div>
    </div>
</template>
