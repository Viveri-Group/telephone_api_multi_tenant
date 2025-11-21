<script setup>
import Input from "@/Components/Form/Input.vue";
import SearchContainer from "@/Components/Layout/SearchContainer.vue";
import LayoutBox from "@/Components/Layout/LayoutBox.vue";
import Select from "@/Components/Form/Select.vue"
import {useForm} from "@inertiajs/vue3";
import {onMounted} from "vue";

const props = defineProps({
    defaultSearchFormOptions: Object,
    updateSearchCriteria: Function,
});

const form = useForm({
    identifier: '',
    http_method: '',
    url: '',
    request_type:'',
    status_code:'',
    attempts:'',
    date_to: '',
    date_from: '',
});

const submit = () => {
    props.updateSearchCriteria(form.data());

    form.get(route('web.shout-request-logs.index'), {
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
        <SearchContainer heading="Search Shout Server Request Logs" storage-key="hide_shout_server_request_log_search_panel">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-12 gap-x-4 gap-y-1">
                    <Input label="Server Description"
                           name="identifier"
                           tip="Description of the server."
                           v-model="form.identifier"
                           class="col-span-12 sm:col-span-6 xl:col-span-3"
                    ></Input>

                    <Input label="URL"
                           name="url"
                           tip="The url the request was sent to."
                           v-model="form.url"
                           class="col-span-12 sm:col-span-6 xl:col-span-3"
                    ></Input>

                    <Select label="Method"
                            name="http_method"
                            tip="The http method used."
                            :has-default="true"
                            :options="[{label:'DELETE', value:'DELETE'},{label:'GET', value:'GET'},{label:'PATCH', value:'PATCH'},{label:'POST', value:'POST'},{label:'PUT', value:'PUT'}]"
                            v-model="form.http_method"
                            class="col-span-4 sm:col-span-4 xl:col-span-2"
                    ></Select>

                    <Input label="Response Code"
                           name="status_code"
                           tip="The http response status code."
                           v-model="form.status_code"
                           class="col-span-4 sm:col-span-4 xl:col-span-2"
                    ></Input>

                    <Select label="Attempts"
                            name="attempts"
                            tip="The number of attempts made."
                            :has-default="true"
                            :options="[{label:'1 Attempt', value:'1'},{label:'2 or greater', value:'2'},{label:'3 or greater', value:'3'},{label:'4 or greater', value:'4'},{label:'5 or greater', value:'5'},{label:'6 or greater', value:'6'},{label:'7 or greater', value:'7'},{label:'8 or greater', value:'8'},{label:'9 or greater', value:'9'}]"
                            v-model="form.attempts"
                            class="col-span-4 sm:col-span-4 xl:col-span-2"
                    ></Select>

                    <Input label="Request Type"
                           name="request_type"
                           tip="The type of request sent."
                           v-model="form.request_type"
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
