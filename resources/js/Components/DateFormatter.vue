<template>
    <span v-text="dateFormatted" :class="styling"></span>
</template>

<script>
import {format} from 'date-fns';

export default {
    props: {
        date: {type: String},
        format: {type: String},
        styling: {type: String, default: ''}
    },

    computed: {
        dateFormatted() {
            if (this.date) return format(new Date(this.date), this.format);
        },
    },
};
</script>
