<template>
    <vue-perfect-scrollbar>
        <slot/>
    </vue-perfect-scrollbar>
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar';

export default {
    name: 'scrollbar',
    components: {
        VuePerfectScrollbar,
    },
};
</script>
