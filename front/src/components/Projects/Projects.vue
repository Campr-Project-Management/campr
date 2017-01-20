<template>
    <div>
        <div class="page-section projects">
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
import ProjectFilters from '../_common/ProjectFilters';
import ProjectBox from './ProjectBox';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        ProjectFilters,
        ProjectBox,
    },
    methods: mapActions(['getProjects']),
    created() {
        this.getProjects();
    },
    computed: mapGetters({
        projects: 'filteredProjects',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/page-section';
</style>
