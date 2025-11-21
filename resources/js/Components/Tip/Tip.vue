<template>
    <span v-tippy="`${description}`">
        <question-mark-circle-icon class="-mt-1 w-5 h-5 fill-blue-300 cursor-pointer inline-block">
        </question-mark-circle-icon>
    </span>
</template>

<script>
import { QuestionMarkCircleIcon } from '@heroicons/vue/20/solid';

export default {
    components: { QuestionMarkCircleIcon },
    props: ['description'],
};
</script>
