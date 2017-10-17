<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <dropdown ref="statuses" v-bind:title="translateText('message.status')" v-bind:options="statuses" :selectedValue="selectStatus"></dropdown>
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
        ...mapActions(['getProjectsForDropdown', 'setProjectFilters', 'clearProjects']),
        translateText: function(text) {
            return this.translate(text);
        },
        selectStatus: function(value) {
            this.updateFilters('status', value);
        },
        selectProject: function(value) {
            this.updateFilters('project', value);
        },
        clearFilters: function() {
            this.updateFilters('clear', true);
            this.$refs.statuses.resetCustomTitle();
            this.$refs.projects.resetCustomTitle();
        },
    },
    created() {
        this.clearProjects();
        this.setProjectFilters({clear: true});
        this.getProjectsForDropdown();
    },
    computed: {
        ...mapGetters({
            projectsForFilter: 'projectsForFilter',
            statuses: 'colorStatusesForSelect',
            user: 'user',
        }),
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
