<script setup>
import VueJsonPretty from "vue-json-pretty";

const props = defineProps({
    data: {type: [Object,String]},
    showLineNumber: {type: Boolean, default: true},
    showIcon: {type: Boolean, default: true},
})
</script>

<template>
    <template v-if="data">
        <div class="bg-gray-100 px-2 py-4 rounded-lg border">
            <vue-json-pretty
                :data="data"
                :show-line-number="showLineNumber"
                :show_icon="showIcon">
            </vue-json-pretty>
        </div>
    </template>

    <template v-else>
        <p class="text-sm text-gray-400 bg-gray-100 text-center p-2 rounded-lg border">No data to display.</p>
    </template>
</template>
