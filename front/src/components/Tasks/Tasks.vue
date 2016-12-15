<template>
    <div>
        <navigation></navigation>
        <div>Tasks</div>
    </div>
</template>

<script>
import Navigation from '../Navigation.vue';

export default {
    components: {
        Navigation,
    },
    data() {
        return {
            title: 'CAMPR',
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
</style>
