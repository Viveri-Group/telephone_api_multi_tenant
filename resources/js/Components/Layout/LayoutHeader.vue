<template>
    <h1 class="text-2xl font-semibold text-gray-700 dark:text-gray-200" :class="{ 'border-b mb-4': underline }">
        <slot></slot>
    </h1>
</template>

<script>
export default {
    props: { title: {}, underline: { type: Boolean, default: false } },
};
</script>
