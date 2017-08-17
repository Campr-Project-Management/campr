<template>
    <div>
        <div class="page-section projects">
            <div class="header">
                <h1>{{ translateText('message.my_projects') }}</h1>
                <div class="flex filters-container">
                    <project-filters :updateFilters="applyFilters"></project-filters>
                </div>
            </div>
            <div class="grid-view">
                <project-box v-for="project in projects" v-bind:project="project"></project-box>
                <router-link :to="{name: 'projects-create-1'}" class="new-box">
                    {{ translateText('message.new_project') }} +
                </router-link>
            </div>
        </div>
        <pagination
            :current-page="activePage"
            :number-of-pages="pages"
            v-on:change-page="changePage"/>
    </div>
</template>

<script>
import ProjectFilters from '../_common/ProjectFilters';
import ProjectBox from './ProjectBox';
import {mapActions, mapGetters} from 'vuex';
import Pagination from '../_common/Pagination.vue';

export default {
    components: {
        ProjectFilters,
        ProjectBox,
        Pagination,
    },
    methods: {
        ...mapActions(['getProjects', 'setProjectFilters']),
        changePage(page) {
            this.activePage = page;
            this.getProjectsData();
        },
        translateText: function(text) {
            return this.translate(text);
        },
        applyFilters: function(key, value) {
            let filterObj = {};
            filterObj[key] = value;
            this.setProjectFilters(filterObj);
            this.getProjectsData();
        },
        getProjectsData: function() {
            this.getProjects({
                queryParams: {
                    page: this.activePage,
                },
            });
        },
    },
    created() {
        this.setProjectFilters({clear: true});
        this.getProjectsData();
    },
    computed: {
        ...mapGetters({
            projects: 'projects',
            user: 'user',
            projectsCount: 'projectsCount',
            projectsPerPage: 'projectsPerPage',
        }),
        pages: function() {
            return Math.ceil(this.projectsCount / this.projectsPerPage);
        },
    },
    data() {
        return {
            activePage: 1,
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
