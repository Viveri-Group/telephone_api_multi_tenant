<script setup>
import Select from "@/Components/Form/Select.vue";

const props = defineProps({
    orderByConfig: Object,
    form: Object,
});

const orderByOptions = Object.entries(props.orderByConfig.availableColumns).map(([key, value]) => ({
    label: key,
    value: value,
}));
</script>

<template>
    <div class="grid grid-cols-12 gap-4 border border-blue-400 bg-blue-100 rounded-xl px-2 pt-2">
        <Select name="order_by"
                :options="orderByOptions"
                v-model="props.form.order_by"
                class="col-span-12 sm:col-span-6 xl:col-span-6"
        ></Select>

        <Select name="order_by_direction"
                :has-default="true"
                :options="[{value:'asc', label: 'ASC'},{value:'desc', label: 'DESC'}]"
                v-model="props.form.order_by_direction"
                class="col-span-12 sm:col-span-6 xl:col-span-6"
        ></Select>
    </div>
</template>
