<script setup>
import Input from "@/Components/Form/Input.vue";
import SearchContainer from "@/Components/Layout/SearchContainer.vue";
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import {useForm} from "@inertiajs/vue3";
import {onMounted} from "vue";
import useOrganisationSelect from "@/Composables/useOrganisationSelect.js";
import Select from "@/Components/Form/Select.vue";

const { organisationSelectData } = useOrganisationSelect();

const props = defineProps({
    defaultSearchFormOptions: Object,
    updateSearchCriteria: Function,
});

const form = useForm({
    organisation_id: '',
    id: '',
    name: '',
    type: '',
    date_from: '',
    date_to: '',
});

const submit = () => {
    props.updateSearchCriteria(form.data());

    form.get(route('web.competition.index'), {
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
    form.reset()
    form.date_from = props.defaultSearchFormOptions.date_from;
    form.date_to = props.defaultSearchFormOptions.date_to;
    submit();
};
</script>

<template>
    <LayoutBox>
        <SearchContainer heading="Search Competitions" storage-key="hide_competition_search_panel">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-12 gap-x-4 gap-y-1">
                    <Select label="Organisation"
                            name="organisation"
                            tip="The organisation the competition belongs to."
                            :has-default="true"
                            :options="organisationSelectData"
                            v-model="form.organisation_id"
                            class="col-span-12 sm:col-span-6 xl:col-span-4"
                    ></Select>

                    <Input label="Name"
                           name="name"
                           tip="The name of the competition."
                           v-model="form.name"
                           class="col-span-12 sm:col-span-6 xl:col-span-8"
                    ></Input>

                    <Input label="Date From"
                           type="datetime-local"
                           name="date-from"
                           tip="Date from range."
                           v-model="form.date_from"
                           class="col-span-6 lg:col-span-3 xl:col-span-3"
                    ></Input>

                    <Input label="Date To"
                           type="datetime-local"
                           name="date-to"
                           tip="Date to range."
                           v-model="form.date_to"
                           class="col-span-6 lg:col-span-3 xl:col-span-3"
                    ></Input>

                    <div class="col-span-12 flex justify-end">
                        <div class="flex gap-2">
                            <button class="btn btn--gray" type="reset" @click.prevent="clearForm">Clear</button>
                            <input type="submit" value="Submit" class="btn btn--blue ml-auto inline-block"></input>
                        </div>
                    </div>
                </div>
            </form>
        </SearchContainer>
    </LayoutBox>
</template>
