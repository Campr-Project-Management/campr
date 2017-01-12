<template>
    <div class="page-section projects">
        <div class="header">
            <h1>My Recent Projects</h1>
            <div class="full-filters">
                <project-filters></project-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'projects'}" class="btn-rounded">View all my Projects</router-link>
            </div>
        </div>
        <div class="content">
            <small-project-box v-for="project in projects" v-bind:project="project"></small-project-box>
            <a href="" class="new-box">New Project +</a>
        </div>
    </div>
</template>

<script>
import ProjectFilters from '../_common/ProjectFilters';
import SmallProjectBox from './SmallProjectBox';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        ProjectFilters,
        SmallProjectBox,
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

  .projects {
    margin-bottom: 32px;
  }
</style>
