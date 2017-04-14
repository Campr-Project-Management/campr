<template>
    <div class="page-section projects">
        <div class="header">
            <h1>{{ message.recent_projects }}</h1>
            <div class="full-filters">
                <project-filters></project-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'projects'}" class="btn-rounded btn-auto">{{ message.all_projects }}</router-link>
            </div>
        </div>
        <div class="grid-view">
            <small-project-box v-for="project in projects" v-bind:project="project"></small-project-box>
            <router-link :to="{name: 'projects-create-1'}" class="new-box">
                {{ message.new_project }} +
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
                recent_projects: Translator.trans('message.recent_projects'),
                all_projects: Translator.trans('message.all_projects'),
                new_project: Translator.trans('message.new_project'),
            },
        };
    },
};
</script>

<style scoped lang="scss">
</style>
