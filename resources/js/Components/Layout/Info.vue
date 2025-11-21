<template>
    <div class="flex bg-yellow-50 p-3 mb-3 text-sm text-yellow-700 border rounded-lg">
        <template v-if="!title">
            <information-circle-icon class="h-5 w-5 flex-shrink-0 mr-3"></information-circle-icon>
            <slot></slot>
        </template>

        <template v-else>
            <div class="flex-col text-left">
                <div class="flex w-full">
                    <information-circle-icon class="h-5 w-5 flex-shrink-0 mr-1 mb-1"></information-circle-icon>
                    <p v-text="title"></p>
                </div>
                <slot></slot>
            </div>
        </template>
    </div>
</template>

<script>
import { InformationCircleIcon } from '@heroicons/vue/20/solid';

export default {
    props: { title: { type: String, required: false } },

    components: { InformationCircleIcon },
};
</script>
