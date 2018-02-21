<template>
    <div class="page-section projects">
        <div class="header">
            <h1>{{ translateText('message.recent_projects') }}</h1>
            <div class="full-filters">
                <project-filters :updateFilters="applyFilters"></project-filters>
                <div class="separator"></div>
                <router-link :to="{name: 'projects'}" class="btn-rounded btn-auto">{{ translateText('message.all_projects') }}</router-link>
            </div>
        </div>
        <div class="grid-view">
            <small-project-box v-for="project in projects" v-bind:project="project"></small-project-box>
            <router-link
                :to="{name: 'projects-create-1'}"
                class="new-box"
                v-if="localUserIsAdmin"
            >
                {{ translateText('message.new_project') }} +
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
        ...mapActions(['getProjects', 'setProjectFilters']),
        translateText: function(text) {
            return this.translate(text);
        },
        applyFilters: function(key, value) {
            let filterObj = {};
            filterObj[key] = value;
            this.setProjectFilters(filterObj);
            this.getRecentProjectsData();
        },
        getRecentProjectsData: function() {
            this.getProjects({
                queryParams: {
                    page: this.activePage,
                },
            });
        },
    },
    created() {
        this.setProjectFilters({clear: true});
        this.getRecentProjectsData();
    },
    computed: {
        ...mapGetters({
            projects: 'projects',
            user: 'user',
            localUser: 'localUser',
        }),
        localUserIsAdmin() {
            return !! (this.localUser && this.localUser.roles && (
                this.localUser.roles.indexOf('ROLE_SUPER_ADMIN') !== -1 ||
                this.localUser.roles.indexOf('ROLE_SUPER_ADMIN') !== -1
            ));
        },
    },
    data() {
        return {
            activePage: 1,
        };
    },
};
</script>

<style scoped lang="scss">
</style>
