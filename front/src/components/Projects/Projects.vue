<template>
    <div>
        <navigation></navigation>
        <div class="page-section">
            <div class="header">
                <h1>My Projects</h1>
                <project-filters></project-filters>
            </div>
            <div class="content">
                <project-box v-for="project in projects" v-bind:project="project"></project-box>
                <a href="" class="new-box">New Project +</a>
            </div>
        </div>
    </div>
</template>

<script>
import Navigation from '../_layout/Navigation';
import ProjectFilters from '../_common/ProjectFilters';
import ProjectBox from './ProjectBox';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        Navigation,
        ProjectFilters,
        ProjectBox,
    },
    methods: mapActions(['getProjects']),
    created() {
        this.getProjects();
        window.$(document).ready(function() {

        });
    },
    computed: mapGetters({
        projects: 'projects',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/page-section';
</style>
