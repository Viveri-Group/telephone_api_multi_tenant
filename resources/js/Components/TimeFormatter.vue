<script setup>
import {computed} from "vue";
import DateFormatter from "@/Components/DateFormatter.vue";

const props = defineProps({
    time: {type: String, required: true},
    format: {type: String, default: 'HH:mmaaa'},
})

const time = computed(() => {
    const { hours, minutes, seconds } = splitTime(props.time);

    if (hours && minutes && seconds) {
        const date = new Date();

        date.setHours(hours);
        date.setMinutes(minutes);
        date.setSeconds(seconds);

        return date.toString();
    }
})

const splitTime = (timeString) => {
    const timeParts = timeString.split(':');

    const hours = timeParts[0];

    const minutes = timeParts[1];

    const seconds = timeParts.length === 3 ? timeParts[2] : '00';

    return { hours, minutes, seconds };
}
</script>

<template>
    <DateFormatter :date="time" :format="format"></DateFormatter>
</template>
