<template>
    <div>
        <div class="page-section projects">
            <div class="header">
                <h1>My Projects</h1>
                <div class="flex">
                    <project-filters></project-filters>
                    <div class="pagination" v-if="count > 0">
                        <span v-for="page in count/projects.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                    </div>
                </div>
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
    methods: {
        ...mapActions(['getProjects']),
        changePage(page) {
            this.getProjects(page);
            this.activePage = page;
        },
    },
    watch: {
        user: function() {
            this.getProjects(this.activePage);
        },
    },
    computed: mapGetters({
        projects: 'projects',
        user: 'user',
    }),
    data() {
        return {
            activePage: 1,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/page-section';
</style>
