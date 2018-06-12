<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <dropdown ref="statuses" v-bind:title="translateText('message.status')" v-bind:options="statusesOptions" :selectedValue="selectStatus"></dropdown>
            <dropdown ref="colorStatuses" v-bind:title="translateText('message.condition')" v-bind:options="colorStatuses" :selectedValue="selectColorStatus"></dropdown>
            <dropdown ref="projects" v-bind:title="translateText('message.project')" v-bind:options="projectsForFilter" :selectedValue="selectProject"></dropdown>
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
        ...mapActions(['getProjectsForDropdown', 'setProjectFilters', 'clearProjects', 'getTaskStatuses']),
        translateText: function(text) {
            return this.translate(text);
        },
        selectColorStatus: function(value) {
            this.updateFilters('colorStatus', value);
        },
        selectStatus: function(value) {
            this.updateFilters('status', value);
        },
        selectProject: function(value) {
            this.updateFilters('project', value);
        },
        clearFilters: function() {
            this.updateFilters('clear', true);
            this.$refs.colorStatuses.resetCustomTitle();
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
        ...mapGetters({
            projectsForFilter: 'projectsForFilter',
            colorStatuses: 'colorStatusesForSelect',
            taskStatuses: 'taskStatuses',
            user: 'user',
        }),
        statusesOptions() {
            return this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
        },
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
