<template>
    <component v-bind:is="type">
        <div class="flex justify-between">
            <LayoutHeader v-text="heading"></LayoutHeader>
            <chevron-down-icon
                class="w-5 h-5 text-gray-400 cursor-pointer mt-2"
                @click.prevent="hidePanel"
                v-if="showPanel"
            ></chevron-down-icon>
            <chevron-left-icon
                class="w-5 h-5 text-gray-400 cursor-pointer mt-2"
                @click.prevent="unhidePanel"
                v-else
            ></chevron-left-icon>
        </div>

        <div v-if="showPanel">
            <slot></slot>
        </div>
    </component>
</template>

<script>
import { ChevronDownIcon, ChevronLeftIcon } from '@heroicons/vue/20/solid';
import LayoutHeader from "@/Components/Layout/LayoutHeader.vue";

export default {
    props: {
        heading: {},
        collapsed: { type: Boolean, default: false },
        storageKey: { type: String, required: false },
        type: { type: String, default: 'layout-box' },
    },

    components: {LayoutHeader, ChevronDownIcon, ChevronLeftIcon },

    data() {
        return {
            refreshHiddenCheck: 0,
            fallbackShowPanel: !this.collapsed,
        };
    },

    computed: {
        showPanel() {
            if (this.storageKey) {
                this.refreshHiddenCheck;
                return !localStorage.getItem(this.storageKey);
            }

            return this.fallbackShowPanel;
        },
    },

    methods: {
        hidePanel() {
            if (this.storageKey) {
                localStorage.setItem(this.storageKey, true);
                this.refreshHiddenCheck++;
            } else {
                this.fallbackShowPanel = false;
            }
        },
        unhidePanel() {
            if (this.storageKey) {
                localStorage.removeItem(this.storageKey);
                this.refreshHiddenCheck++;
            } else {
                this.fallbackShowPanel = true;
            }
        },
    },
};
</script>
