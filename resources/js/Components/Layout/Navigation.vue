<script setup>
import NavigationList from "@/Composables/Navigation/NavigationList.js";

const props = defineProps({
    section: {type: String, default: 'main'},
    heading: {type: String, default: ''},
})

const {NavList, isNavActive} = NavigationList();
</script>

<template>
    <section>
        <div class="text-xs/6 font-semibold text-gray-700 -ml-3" v-if="heading" v-text="heading"></div>
        <li>
            <ul role="list" class="-mx-2 space-y-1">
                <template v-for="item in NavList[props.section]" :key="item.name">
                    <li v-if="item.show ? item.show() : true">
                        <a
                            class="text-gray-700 hover:text-gray-700 hover:bg-yellow-300 group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold"
                            :href="item.path"
                            :class="[isNavActive(item) ? 'bg-yellow-300 text-gray-700 group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold' : 'text-gray-700 hover:bg-yellow-300 hover:text-gray-700', 'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6']"
                            :target="item.target ?? '_self'"
                        >
                            <component :is="item.icon" class="h-6 w-6 shrink-0 text-gray-700" aria-hidden="true"/>
                            {{ item.name }}
                        </a>
                    </li>
                </template>
            </ul>
        </li>
    </section>
</template>
