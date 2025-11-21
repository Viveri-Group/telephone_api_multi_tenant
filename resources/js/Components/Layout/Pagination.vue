<template>
    <template v-if="data">
        <div v-if="data.last_page !== 1" class="bg-white py-3 flex items-center justify-between ">
            <div class="flex-1 flex justify-between sm:hidden">
                <a
                    :href="data.prev_page_url"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Previous
                </a>
                <a
                    :href="data.next_page_url"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                    Next
                </a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        {{ ' ' }}
                        <span class="font-medium" v-text="data.from"></span>
                        {{ ' ' }}
                        to
                        {{ ' ' }}
                        <span class="font-medium" v-text="data.to"></span>
                        {{ ' ' }}
                        of
                        {{ ' ' }}
                        <span class="font-medium" v-text="data.total"></span>
                        {{ ' ' }}
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <a
                            v-if="data.current_page !== 1"
                            @click="pageButtonChange(data.current_page - 1)"
                            :href="data.prev_page_url"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        >
                            <span class="sr-only">Previous</span>
                            <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                        </a>

                        <template v-for="(page, key) in pageRange" :key="key">
                            <a
                                v-if="!isNaN(page) && Number.isInteger(page)"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer"
                                :href="data.first_page_url.replace('page=1','page='+(page))"
                                @click="pageButtonChange(page)"
                                :class="{
                                    'z-10 bg-green-50 border-green-500 text-green-600': page === data.current_page,
                                    'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 ': page === data.current_page,
                                }"
                            >
                                {{ page }}
                            </a>

                            <a
                                v-else
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium cursor-pointer"
                                :class="{
                                    'z-10 bg-green-50 border-green-500 text-green-600': page === data.current_page,
                                    'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 ': page === data.current_page,
                                }"
                            >
                                {{ page }}
                            </a>
                        </template>

                        <a
                            v-if="data.current_page !== data.last_page"
                            :href="data.next_page_url"
                            @click="pageButtonChange(data.current_page + 1)"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                        >
                            <span class="sr-only">Next</span>
                            <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </template>
</template>

<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';
</script>

<script>
export default {
    props: ['data'],

    data() {
        return {
            limit: 2,
        };
    },

    emits: ['pagination-change-page'],

    computed: {
        pageRange() {
            if (!this.data) {
                return 0;
            }

            if (this.limit === -1) {
                return 0;
            }
            if (this.limit === 0) {
                return this.data.last_page;
            }
            let current = this.data.current_page;
            let last = this.data.last_page;
            let delta = this.limit;
            let left = current - delta;
            let right = current + delta + 1;
            let range = [];
            let pages = [];
            let l;
            for (let i = 1; i <= last; i++) {
                if (i === 1 || i === last || (i >= left && i < right)) {
                    range.push(i);
                }
            }
            range.forEach(function (i) {
                if (l) {
                    if (i - l === 2) {
                        pages.push(l + 1);
                    } else if (i - l !== 1) {
                        pages.push('...');
                    }
                }
                pages.push(i);
                l = i;
            });
            return pages;
        },
    },

    methods: {
        pageButtonChange(page) {
            this.$emit('pagination-change-page', page);
        },
    },
};
</script>
