<script setup>
import DateFormatter from "@/Components/DateFormatter.vue";

const props = defineProps({
    audioFile: Object,
    even: Boolean,
    index: Number,
});

</script>

<template>
    <div>
        <div class="border border-gray-100 xl:border-none rounded-lg px-0 text-sm"
             :class="{'mt-4 xl:mt-0' : index > 0}">
            <div class="xl:hidden flex justify-between bg-gray-100 p-2 rounded-t-lg">
                <p class="text-gray-600 text-sm" v-text="'Shout ID: ' + props.audioFile.attributes.external_id"></p>
                <p class="ml-auto text-gray-600 text-sm" v-text="'ID: ' + props.audioFile.id"></p>
            </div>

            <div class="grid grid-cols-3 xl:grid-cols-12 gap-y-2 xl:gap-4 px-4 xl:px-2 py-4 xl:py-2"
                 :class="{'xl:bg-gray-100' : even}">

                <div class="hidden xl:flex flex-wrap gap-2">
                    <p class="text-xs xl:ml-2" v-text="'ID: ' + props.audioFile.id"></p>
                    <p class="text-xs xl:ml-2" v-text="'Shout ID: '+props.audioFile.attributes.external_id"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Type:</div>
                <div class="col-span-2 xl:col-span-1 md:text-center">
                    <p v-text="props.audioFile.attributes.type"></p>
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Shout Filename:</div>
                <div class="col-span-2 xl:col-span-2 md:text-center">
                    {{ props.audioFile.attributes.shout_name}}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Filename:</div>
                <div class="col-span-2 xl:col-span-2 md:text-center">
                    {{ props.audioFile.attributes.filename}}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Size:</div>
                <div class="col-span-2 xl:col-span-2 md:text-center">
                    {{ props.audioFile.attributes.size}}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Extension:</div>
                <div class="col-span-2 xl:col-span-2 md:text-center">
                    {{ props.audioFile.attributes.extension}}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Uploaded:</div>
                <div class="col-span-2 xl:col-span-2 md:text-center text-left">
                    <DateFormatter :date="audioFile.attributes.created_at" format="do MMM yyyy HH:mmaaa"></DateFormatter>
                </div>
            </div>
        </div>
    </div>
</template>
