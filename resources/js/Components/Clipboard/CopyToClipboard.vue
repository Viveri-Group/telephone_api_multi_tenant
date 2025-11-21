<template>
    <button @click.prevent="copyToClipboard" :class="styling">
        <span class="text-bold bg-white" v-if="showCopied">COPIED!</span>
        <clipboard-document-icon class="w-[1.2rem] mt-[.1rem]" v-if="enableIcon && !showCopied"></clipboard-document-icon>
        <span v-if="!showCopied" v-text="buttonTitle"></span>
    </button>
</template>

<script>
import { ClipboardDocumentIcon } from '@heroicons/vue/20/solid';

export default {
    components: { ClipboardDocumentIcon },

    props: {
        copy: {},
        buttonTitle: { type: String, default: 'Copy to Clipboard' },
        enableIcon: {type: Boolean, default: false},
        styling: {type: String, default: 'btn btn--gray flex w-auto'}
    },

    data(){
        return {
            showCopied: false,
        }
    },

    methods: {
        copyToClipboard() {
            this.copyContent();
            this.showCopied = true;

            setTimeout(() => this.showCopied = false, 1000)
        },

        async copyContent() {
            await navigator.clipboard.writeText(this.copy);
        },
    },
};
</script>
