<template>
    <div class="my-recent projects">
        <div class="header">
            <h1>My Recent Projects</h1>
            <project-filters></project-filters>
        </div>
        <div class="content">
            <project-box v-for="project in projects" v-bind:project="project"></project-box>
            <a href="" class="new-box">New Project +</a>
        </div>
    </div>
</template>

<script>
import ProjectFilters from './ProjectFilters';
import ProjectBox from '../ProjectBox';
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
        projects: 'projects',
    }),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/dashboard/my-recent-sections';

  .projects {
    margin-bottom: 32px;
  }
</style>
