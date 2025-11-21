<script setup>
import DateFormatter from "@/Components/DateFormatter.vue";

const props = defineProps({
    competition: {type: Object, required: true},
});
</script>

<template>
    <div class="grid grid-cols-12 gap-x-4 gap-y-0.5 text-xs border border-gray-300 rounded-md py-1">

        <div class="col-span-4 text-gray-400 text-right">START:</div>
        <span class="col-span-8 text-left">
            <DateFormatter :date="competition.start" format="do MMM yyyy HH:mmaaa" styling="w-full break-all"></DateFormatter>
        </span>

        <div class="col-span-4 text-gray-400 text-right">END:</div>
        <span class="col-span-8 text-left">
            <DateFormatter :date="competition.end" format="do MMM yyyy HH:mmaaa" styling="w-full break-all"></DateFormatter>
        </span>
    </div>
</template>
