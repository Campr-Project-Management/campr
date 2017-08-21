<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <dropdown ref="customers" v-bind:title="translateText('message.customer')" v-bind:options="customers" :selectedValue="selectCustomer"></dropdown>
            <dropdown ref="programmes" v-bind:title="translateText('message.programme')" v-bind:options="programmes" :selectedValue="selectProgramme"></dropdown>
            <dropdown ref="statuses" v-bind:title="translateText('message.status')" v-bind:options="statuses" :selectedValue="selectStatus"></dropdown>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
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
        clearFilters: function() {
            this.updateFilters('clear', true);
            this.$refs.customers.resetCustomTitle();
            this.$refs.programmes.resetCustomTitle();
            this.$refs.statuses.resetCustomTitle();
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
