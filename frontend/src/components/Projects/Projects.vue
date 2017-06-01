<template>
    <div>
        <div class="page-section projects">
            <div class="header">
                <h1>{{ message.my_projects }}</h1>
                <div class="flex filters-container">
                    <project-filters></project-filters>
                    <div class="separator" v-if="count > 0"></div>
                    <div class="pagination flex flex-v-center" v-if="count > 0">
                        <span v-for="page in count/projects.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                    </div>
                </div>
            </div>
            <div class="grid-view">
                <project-box v-for="project in projects" v-bind:project="project"></project-box>
                <router-link :to="{name: 'projects-create-1'}" class="new-box">
                    {{ message.new_project }} +
                </router-link>
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
    created() {
        if (!this.$store.state.project || this.$store.state.project.projects.length === 0) {
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
                my_projects: Translator.trans('message.my_projects'),
                new_project: Translator.trans('message.new_project'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    .filters-container {
        margin: 20px 0;

        .pagination {
            margin: 0;
        }
    }
</style>
