<template>
    <div :class="styleProp">
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: {
        styleProp: { default: 'bg-white p-4 rounded-lg shadow mt-4' },
    },
};
</script>
