<template>
    <div class="filters">
        <span class="title">Filter By</span>
        <div class="dropdowns">
            <dropdown title="Customer"></dropdown>
            <dropdown title="Programme"></dropdown>
            <dropdown title="Status" v-bind:options="statuses" item="project" filter="status"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../_common/Dropdown';

export default {
    components: {
        Dropdown,
    },
    data() {
        return {
            statuses: [
                {
                    'key': '',
                    'label': 'All Statuses',
                },
                {
                    'key': 'NOT_STARTED',
                    'label': 'Not started',
                },
                {
                    'key': 'IN_PROGRESS',
                    'label': 'In progress',
                },
                {
                    'key': 'FINISHED',
                    'label': 'Finished',
                },
            ],
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/filters';
</style>
