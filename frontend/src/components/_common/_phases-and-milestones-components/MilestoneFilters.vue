<template>
    <div class="filters">
        <span class="title">Filter by</span>
        <div class="dropdowns">
            <dropdown :selectedValue="selectPhase" v-if="projectPhases.items && projectPhases.items.length" v-bind:title="'Phase'" item="milestone" filter="phase" :options="projectPhasesForSelect"></dropdown>
            <dropdown :selectedValue="selectDueData" v-bind:title="'Due Date'" item="milestone" filter="due_date"></dropdown>
            <dropdown :selectedValue="selectStatus" v-if="!boardView" title="Status" :options="statusesLabel"></dropdown>
            <dropdown :selectedValue="selectResponsible" v-bind:title="'Responsible'" item="phase" filter="responsible" :options="projectUsersForSelect"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../Dropdown2';
import {mapActions, mapGetters} from 'vuex';

export default {
    props: ['selectStatus', 'selectResponsible', 'selectPhase', 'selectDueData'],
    created() {
        this.getTaskStatuses();
        this.getProjectUsers({id: this.$route.params.id});
        if (!this.projectPhases.items || this.projectPhases.items.length === 0) {
            this.getProjectPhases({projectId: this.$route.params.id});
        }
    },
    components: {
        Dropdown,
    },
    computed: {
        ...mapGetters({
            taskStatuses: 'taskStatuses',
            projectUsersForSelect: 'projectUsersForSelect',
            projectPhases: 'projectPhases',
            projectPhasesForSelect: 'projectPhasesForSelect',
        }),
        statusesLabel: function() {
            let statuses = this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
            statuses.unshift({label: 'Status', key: null});
            return statuses;
        },
    },
    methods: {
        ...mapActions(['getTaskStatuses', 'getProjectUsers', 'getProjectPhases']),
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/filters';
</style>
