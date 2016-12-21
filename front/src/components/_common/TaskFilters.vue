<template>
    <div class="filters">
        <span class="title">Filter By</span>
        <div class="dropdowns">
            <dropdown title="Schedule"></dropdown>
            <dropdown title="Status"></dropdown>
            <dropdown title="Project"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../_common/Dropdown';

export default {
    components: {
        Dropdown,
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/filters';
</style>
