<template>
    <div class="filters">
        <span class="title">{{ message.filter_by }}</span>
        <div class="dropdowns">
            <dropdown v-bind:title="message.customer" v-bind:options="customers" item="project" filter="company"></dropdown>
            <dropdown v-bind:title="message.programme"></dropdown>
            <dropdown v-bind:title="message.status" v-bind:options="statuses" item="project" filter="status"></dropdown>
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
    watch: {
        user: function() {
            this.getCustomers();
            this.getProjectStatuses();
        },
    },
    computed: {
        ...mapGetters({
            statuses: 'projectStatusesForFilter',
            customers: 'customersForFilter',
            user: 'user',
        }),
    },
    data: function() {
        return {
            message: {
                filter_by: window.Translator.trans('message.filter_by'),
                customer: window.Translator.trans('message.customer'),
                programme: window.Translator.trans('message.programme'),
                status: window.Translator.trans('message.status'),
            },
        };
    },

};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/filters';
</style>
