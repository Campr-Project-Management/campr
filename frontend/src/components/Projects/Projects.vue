<template>
    <div>
        <div class="page-section projects">
            <div class="header">
                <h1>{{ translate('message.my_projects') }}</h1>
                <div class="flex filters-container">
                    <project-filters :updateFilters="applyFilters"></project-filters>
                </div>
            </div>
            <div class="grid-view">
                <project-box v-for="project in projects" v-bind:project="project"></project-box>
                <div class="new-box">
                    <router-link :to="{name: 'projects-create-1'}">
                        <span>{{ translate('message.new_project') }} +</span>
                    </router-link>
                </div>
            </div>
        </div>

        <pagination
                v-model.number="activePage"
                :number-of-pages="pages"
                @input="onPageChange"/>
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
        ...mapActions(['getProjects', 'setProjectFilters', 'clearProjects']),
        onPageChange(page) {
            this.page = page;
            this.getProjectsData();
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
                    page: this.page,
                    favorites: this.favorites,
                },
            });
        },
    },
    created() {
        this.setProjectFilters({clear: true});
        this.clearProjects();
        this.getProjectsData();
    },
    computed: {
        ...mapGetters([
            'projects',
            'user',
            'localUser',
            'projectsCount',
            'projectsPerPage',
        ]),
        pages: function() {
            return Math.ceil(this.projectsCount / this.projectsPerPage);
        },
        localUserIsAdmin() {
            return !! (this.localUser && this.localUser.roles && (
                this.localUser.roles.indexOf('ROLE_ADMIN') !== -1 ||
                this.localUser.roles.indexOf('ROLE_SUPER_ADMIN') !== -1
            ));
        },
    },
    data() {
        return {
            page: 1,
            favorites: true,
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
