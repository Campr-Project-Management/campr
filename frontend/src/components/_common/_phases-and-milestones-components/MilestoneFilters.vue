<template>
    <div class="filters">
        <span class="title">Filter by</span>
        <div class="dropdowns">
            <dropdown v-bind:title="'Phase'" item="milestone" filter="phase"></dropdown>
            <dropdown v-bind:title="'Due Date'" item="milestone" filter="due_date"></dropdown>
            <dropdown v-bind:title="'Status'" item="milestone" filter="status"></dropdown>
            <dropdown v-bind:title="'Responsible'" item="milestone" filter="responsible"></dropdown>
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
