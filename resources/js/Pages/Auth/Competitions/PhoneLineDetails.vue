<script setup>
import Phone from "@/Mixins/Phone.js";

const {formatNumber} = Phone();

const props = defineProps({
    phoneLine: Object,
    even: Boolean,
    index: Number,
});

</script>

<template>
    <div>
        <div class="border border-gray-100 xl:border-none rounded-lg px-0 text-sm"
             :class="{'mt-4 xl:mt-0' : index > 0}">
            <div class="xl:hidden flex justify-between bg-gray-100 p-2 rounded-t-lg">
                <p class="ml-auto text-gray-600 text-sm" v-text="'ID: ' + props.phoneLine.id"></p>
            </div>

            <div class="grid grid-cols-3 xl:grid-cols-12 gap-y-2 xl:gap-4 px-4 xl:px-2 py-4 xl:py-2"
                 :class="{'xl:bg-gray-100' : even}">

                <div class="hidden xl:flex flex-wrap gap-2">
                    <p class="text-sm " v-text="props.phoneLine.id"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Phone Number:</div>
                <div class="col-span-2 xl:col-span-11">
                    <p v-text="formatNumber(props.phoneLine.attributes.number)"></p>
                </div>
            </div>
        </div>
    </div>
</template>
