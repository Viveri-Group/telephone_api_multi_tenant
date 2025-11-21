<script setup>
const props = defineProps({
    record: Boolean,
    handleClick: Function,
})
</script>

<template>
    <div class="border rounded-xl px-2 py-1 cursor-pointer"
         :class="{'border-red-500' : props.record , 'border-gray-200' : !props.record}"
         @click="props.handleClick">
                        <span class="mx-auto relative flex h-3 w-3 my-1">
                          <span class="absolute inline-flex h-full w-full rounded-full opacity-75"
                                :class="{'bg-red-500 animate-ping' : props.record , 'bg-gray-300' : !props.record}"
                          ></span>
                          <span class="relative inline-flex rounded-full h-3 w-3"
                                :class="{'bg-red-500' : props.record , 'bg-gray-300' : !props.record}">
                          </span>
                        </span>
        <p class="mx-auto uppercase text-xs font-bold"
           :class="{'text-red-500' : props.record , 'text-gray-400' : !props.record}">
            Record
        </p>
    </div>
</template>
