<script setup>
import Errors from "../../Composables/Error/Errors.js"

const props = defineProps({
    errorKey: String,
})

const {showError} = Errors();
</script>

<template>
    <p class="text-red-600 mt-2" v-text="showError(props.errorKey)"></p>
</template>
