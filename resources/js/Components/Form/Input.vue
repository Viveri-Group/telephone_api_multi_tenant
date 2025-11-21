<template>
    <div>
        <div class="" :class="{ 'flex justify-between space-x-2': inline }">
            <div class="flex flex-shrink-0 items-center justify-between">
                <div class="flex flex-shrink-0 space-x-1">
                    <label :class="labelStyle" :for="name" v-text="label"></label>
                    <span v-if="required" class="text-red-600"> *</span>
                </div>

                <tip v-if="tip" :description="tip"></tip>
            </div>

            <template v-if="this.type === 'textarea'">
            <textarea
                class="input"
                v-bind="$attrs"
                v-text="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                :required="required"
            ></textarea>
            </template>

            <template v-else-if="this.type === 'checkbox'">
                <input
                    v-bind="$attrs"
                    :checked="typeof checked === 'undefined' ? (modelValue === 'on' || modelValue === true) : checked"
                    @change="$emit('update:modelValue', $event.target.checked ? this.value || 'on' : null)"
                    class="cursor-pointer focus:ring-0"
                    type="checkbox"
                    :required="required"
                />

            </template>

            <template v-else-if="this.type === 'display'">
                <input class="input" v-bind="$attrs" :value="value" readonly disabled/>
            </template>

            <template v-else>
                <input
                    class="input"
                    v-bind="$attrs"
                    :value="this.type !== 'file' ? modelValue : null"
                    @input="$emit('update:modelValue', $event.target.value)"
                    :type="this.type || 'text'"
                    :required="required"
                />
            </template>
        </div>
        <ErrorInput :error-key="errorKey"></ErrorInput>
    </div>
</template>

<script>
import FormField from "@/Mixins/FormField.js";
import ErrorInput from "@/Components/Error/ErrorInput.vue";
import Tip from "@/Components/Tip/Tip.vue";

export default {
    components: {Tip, ErrorInput},
    mixins: [FormField],

    props: {
        labelStyle: {
            type: String,
            default: 'text-gray-700 text-sm font-bold',
        },

        multiple: {
            type: Boolean,
            required: false,
        },

        checked: {
            required: false,
        },

        inline: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
