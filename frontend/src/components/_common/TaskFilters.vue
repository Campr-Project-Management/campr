<template>
    <div class="filters">
        <span class="title">{{ translate('message.filter_by') }}</span>
        <div class="dropdowns">
            <dropdown ref="statuses" v-bind:title="translate('message.status')" v-bind:options="taskStatusesForSelect" :selectedValue="selectStatus"></dropdown>
            <dropdown
                    ref="trafficLights"
                    :title="translate('message.condition')"
                    :options="trafficLightsForSelect"
                    :selectedValue="selectTrafficLight"/>
            <dropdown ref="projects" v-bind:title="translate('message.project')" v-bind:options="projectsForFilter" :selectedValue="selectProject"></dropdown>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translate('button.clear_filters') }}</a>
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
        ...mapActions(['getProjectsForDropdown', 'setProjectFilters', 'clearProjects', 'getTaskStatuses']),
        selectTrafficLight(value) {
            this.updateFilters('trafficLight', value);
        },
        selectStatus: function(value) {
            this.updateFilters('status', value);
        },
        selectProject: function(value) {
            this.updateFilters('project', value);
        },
        clearFilters: function() {
            this.updateFilters('clear', true);
            this.$refs.trafficLights.resetCustomTitle();
            if (this.$refs.statuses) {
                this.$refs.statuses.resetCustomTitle();
            }
            this.$refs.projects.resetCustomTitle();
        },
    },
    created() {
        this.clearProjects();
        this.setProjectFilters({clear: true});
        this.getProjectsForDropdown();
        if (!this.statuses || !this.statuses.length) {
            this.getTaskStatuses();
        }
    },
    computed: {
        ...mapGetters([
            'projectsForFilter',
            'taskStatuses',
            'user',
            'taskStatusesForSelect',
            'trafficLightsForSelect',
        ]),
    },
    data: function() {
        return {
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/filters';
</style>
