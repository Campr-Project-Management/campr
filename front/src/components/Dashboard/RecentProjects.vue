<template>
    <div class="page-section projects">
        <div class="header">
            <h1>{{ message.recent_projects }}</h1>
            <div class="full-filters">
                <project-filters></project-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'projects'}" class="btn-rounded">{{ message.all_projects }}</router-link>
                <div class="pagination" v-if="count > 0">
                    <span v-for="page in count/projects.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                </div>
            </div>
        </div>
        <div class="content">
            <small-project-box v-for="project in projects" v-bind:project="project"></small-project-box>
            <router-link :to="{name: 'projects-create-1'}">
                <a href="" class="new-box">{{ message.new_project }} +</a>
            </router-link>
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
    methods: {
        ...mapActions(['getProjects']),
        changePage(page) {
            this.getProjects(page);
            this.activePage = page;
        },
    },
    created() {
        if (!this.$store.state.project || this.$store.state.project.items.length === 0) {
            this.getProjects(this.activePage);
        }
    },
    computed: mapGetters({
        projects: 'projects',
        user: 'user',
    }),
    data() {
        return {
            activePage: 1,
            message: {
                recent_projects: window.Translator.trans('message.recent_projects'),
                all_projects: window.Translator.trans('message.all_projects'),
                new_project: window.Translator.trans('message.new_project'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_common';
    @import '../../css/page-section';

  .projects {
    margin-bottom: 32px;
  }
</style>
