<script setup>
import Input from "@/Components/Form/Input.vue";
import SearchContainer from "@/Components/Layout/SearchContainer.vue";
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import Select from "@/Components/Form/Select.vue"
import {useForm, usePage} from "@inertiajs/vue3";
import {onMounted} from "vue";
import OrderBy from "@/Components/Form/OrderBy.vue";
import useOrganisationSelect from "@/Composables/useOrganisationSelect.js";

const { organisationSelectData } = useOrganisationSelect();

const props = defineProps({
    defaultSearchFormOptions: Object,
    updateSearchCriteria: Function,
});

const form = useForm({
    organisation_id: '',
    competition_id: '',
    call_id: '',
    caller_phone_number: '',
    phone_number:'',
    status: '',
    date_to: '',
    date_from: '',
    order_by: '',
    order_by_direction: '',
});

const submit = () => {
    props.updateSearchCriteria(form.data());

    form.get(route('web.active-calls.index'), {
        preserveScroll: true
    });
};

onMounted(() => {
    (new URLSearchParams(window.location.search)).forEach((value, key) => {
        form[key] = value;
    });

    props.updateSearchCriteria(form.data());
});

const clearForm = () => {
    form.reset();
    form.order_by = props.defaultSearchFormOptions.orderBy.column;
    form.order_by_direction = props.defaultSearchFormOptions.orderBy.direction;
    submit();
};

</script>

<template>
    <LayoutBox>
        <SearchContainer heading="Search Active Calls" storage-key="hide_active_search_search_panel">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-12 gap-x-4 gap-y-1">
                    <Select label="Organisation"
                            name="organisation"
                            tip="The organisation the call belongs to."
                            :has-default="true"
                            :options="organisationSelectData"
                            v-model="form.organisation_id"
                            class="col-span-12 sm:col-span-6 xl:col-span-4"
                    ></Select>

                    <Input label="Competition ID"
                           name="competition_id"
                           tip="The ID of the competition."
                           v-model="form.competition_id"
                           class="col-span-12 sm:col-span-6 xl:col-span-2"
                    ></Input>

                    <Input label="Call ID"
                           name="call_id"
                           tip="The call ID from the shout switch."
                           v-model="form.call_id"
                           class="col-span-12 sm:col-span-6 xl:col-span-2"
                    ></Input>

                    <Select label="Competition Phone Number"
                            name="phone_number"
                            tip="The competition phone number."
                            :has-default="true"
                            :options="props.defaultSearchFormOptions.phone_book"
                            v-model="form.phone_number"
                            class="col-span-12 sm:col-span-6 xl:col-span-4"
                    ></Select>

                    <Input label="Caller Phone Number"
                           name="caller_phone_number"
                           tip="The callers phone number."
                           v-model="form.caller_phone_number"
                           class="col-span-12 sm:col-span-6 xl:col-span-4"
                    ></Input>

                    <Input label="Date From"
                           type="datetime-local"
                           name="date-from"
                           tip="Date from range."
                           v-model="form.date_from"
                           class="col-span-6 lg:col-span-3 xl:col-span-4"
                    ></Input>

                    <Input label="Date To"
                           type="datetime-local"
                           name="date-to"
                           tip="Date to range."
                           v-model="form.date_to"
                           class="col-span-6 lg:col-span-3 xl:col-span-4"
                    ></Input>

                    <div class="col-span-12">
                        <div class="flex justify-between items-center">
                            <template v-if="props.defaultSearchFormOptions.orderBy">
                                <OrderBy
                                    :order-by-config="props.defaultSearchFormOptions.orderBy"
                                    :form="form"
                                ></OrderBy>
                            </template>

                            <div v-else></div>

                            <div class="flex-col xl:flex-row flex justify-between gap-2">
                                <button class="btn btn--gray" type="reset" @click.prevent="clearForm">Clear</button>
                                <input type="submit" value="Submit" class="btn btn--blue ml-auto inline-block"></input>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </SearchContainer>
    </LayoutBox>
</template>
