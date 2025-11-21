import _ from 'lodash';
import Tip from "@/Components/Tip/Tip.vue";

export default {
    components: { Tip },

    props: {
        type: String,
        name: String,
        label: String,
        modelValue: String | Number,
        tip: String,
        required: Boolean,
        value: {
            type: String,
            default: '',
        },
    },

    computed: {

        // hasErrors() {
        //     return false;
        //     // return _.has(this.errors, this.errorKey);
        // },

        errorKey() {
            if (this.name) {
                return this.name;
            }

            return _.camelCase(this.label);
        },
    },
};
