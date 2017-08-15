<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <dropdown v-bind:title="translateText('message.customer')" v-bind:options="customers" :selectedValue="selectCustomer"></dropdown>
            <dropdown v-bind:title="translateText('message.programme')" v-bind:options="programmes" :selectedValue="selectProgramme"></dropdown>
            <dropdown v-bind:title="translateText('message.status')" v-bind:options="statuses" :selectedValue="selectStatus"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../_common/Dropdown';
import {mapActions, mapGetters} from 'vuex';

export default {
    props: ['updateFilters'],
    components: {
        Dropdown,
    },
    methods: {
        ...mapActions(['getProjectStatuses', 'getCustomers', 'getProgrammes']),
        translateText: function(text) {
            return this.translate(text);
        },
        selectCustomer: function(value) {
            this.updateFilters('customer', value);
        },
        selectProgramme: function(value) {
            this.updateFilters('programme', value);
        },
        selectStatus: function(value) {
            this.updateFilters('status', value);
        },
    },
    watch: {
        user: function() {
            this.getCustomers();
            this.getProjectStatuses();
            this.getProgrammes();
        },
    },
    computed: {
        ...mapGetters({
            statuses: 'projectStatusesForFilter',
            customers: 'customersForFilter',
            programmes: 'programmesForFilter',
            user: 'user',
        }),
    },

};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/filters';
</style>
