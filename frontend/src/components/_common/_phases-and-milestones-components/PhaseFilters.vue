<template>
    <div class="filters">
        <span class="title">Filter by</span>
        <div class="dropdowns">
            <dropdown v-bind:title="'Start Date'" item="phase" filter="start_date"></dropdown>
            <dropdown v-bind:title="'End Date'" item="phase" filter="end_date"></dropdown>
            <dropdown v-bind:title="'Status'" item="phase" filter="status"></dropdown>
            <dropdown v-bind:title="'Responsible'" item="phase" filter="responsible"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../Dropdown';

export default {
    components: {
        Dropdown,
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
