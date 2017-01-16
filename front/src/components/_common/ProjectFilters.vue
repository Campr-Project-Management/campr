<template>
    <div class="filters">
        <span class="title">Filter By</span>
        <div class="dropdowns">
            <dropdown title="Customer" v-bind:options="customers" item="project" filter="company"></dropdown>
            <dropdown title="Programme"></dropdown>
            <dropdown title="Status" v-bind:options="statuses" item="project" filter="status"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../_common/Dropdown';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        Dropdown,
    },
    methods: mapActions(['getProjectStatuses', 'getCustomers']),
    created() {
        this.getProjectStatuses();
        this.getCustomers();
    },
    computed: {
        ...mapGetters({
            statuses: 'projectStatusesForFilter',
            customers: 'customersForFilter',
        }),
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/filters';
</style>
