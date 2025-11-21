<script setup>

const props = defineProps({
    data: Object,
    even: Boolean,
    index: Number,
});

const audioTypeStyling = () => {
    if(props.data.supplied_by === 'phone line'){
        return 'text-blue-700 bg-blue-200 border-blue-500';
    }

    if(props.data.supplied_by === 'competition'){
        return 'text-green-700 bg-green-200 border-green-500';
    }

    if(props.data.supplied_by === 'default'){
        return 'text-gray-400 bg-gray-200 border-gray-400';
    }
}
</script>

<template>
    <div>
        <div class="border border-gray-100 xl:border-none rounded-lg px-0 text-sm mt-4 xl:mt-0">
            <div class="xl:hidden flex justify-between bg-gray-100 p-2 rounded-t-lg">
                <p class="text-gray-600 text-sm" v-text="'Shout ID: ' + props.data.external_id"></p>
                <p class="ml-auto text-gray-600 text-sm" v-text="'' + props.data.audio_type"></p>
            </div>

            <div class="hidden xl:grid grid-cols-3 xl:grid-cols-12 gap-y-2 xl:gap-4 px-4 xl:px-2 py-4 xl:py-2"
                 :class="{'xl:bg-gray-100' : even}">

                <div class="block xl:hidden font-bold text-gray-400">File Number:</div>
                <div class="col-span-2 xl:col-span-2 text-xs xl:ml-2" v-text="props.data.external_id">
                </div>

                <div class="block xl:hidden font-bold text-gray-400">File Type:</div>
                <div class="col-span-2 xl:col-span-3">
                    {{ props.data.audio_type }}
                </div>

                <div class="block xl:hidden font-bold text-gray-400">Foo:</div>
                <div class="col-span-2 xl:col-span-7 flex justify-end gap-2">
                    <span
                        class="border rounded-md p-1 uppercase text-xs font-bold"
                        :class="audioTypeStyling()"
                        v-text="props.data.supplied_by + ' audio'"
                    ></span>
                </div>
            </div>
        </div>
    </div>
</template>
