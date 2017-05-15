<template>
    <div class="filters">
        <span class="title">Filter by</span>
        <div class="dropdowns">
            <dropdown :selectedValue="selectStartDate" v-bind:title="'Start Date'" item="phase" filter="start_date"></dropdown>
            <dropdown :selectedValue="selectEndDate" v-bind:title="'End Date'" item="phase" filter="end_date"></dropdown>
            <dropdown :selectedValue="selectedStatusValue" filter="status" item="phase" :title="'Status'" :options="statusesLabel"></dropdown>
            <dropdown :selectedValue="selectResponsible" v-bind:title="'Responsible'" item="phase" filter="responsible" :options="projectUsersForSelect"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../Dropdown2';
import {mapActions, mapGetters} from 'vuex';

export default {
    props: ['selectStatus', 'selectResponsible', 'selectStartDate', 'selectEndDate'],
    created() {
        this.getTaskStatuses();
        this.getProjectUsers({id: this.$route.params.id});
    },
    components: {
        Dropdown,
    },
    computed: {
        ...mapGetters({
            taskStatuses: 'taskStatuses',
            projectUsersForSelect: 'projectUsersForSelect',
        }),
        statusesLabel: function() {
            let statuses = this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
            statuses.unshift({label: 'Status', key: null});
            return statuses;
        },
    },
    methods: {
        ...mapActions(['getTaskStatuses', 'getProjectUsers']),
        selectedStatusValue: function(value) {
            this.selectStatus(value);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
