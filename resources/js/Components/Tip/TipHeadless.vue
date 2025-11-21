<template>
    <div class="clear">
        <span v-tippy="`${description}`" class="cursor-pointer">
            <slot></slot>
        </span>
    </div>
</template>

<script>
import { QuestionMarkCircleIcon } from '@heroicons/vue/20/solid';

export default {
    components: { QuestionMarkCircleIcon },
    props: ['description'],
};
</script>
