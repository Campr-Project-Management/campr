<template>
    <div>
        <div class="page-section projects">
            <div class="header">
                <h1>{{ message.my_projects }}</h1>
                <div class="flex">
                    <project-filters></project-filters>
                    <div class="pagination" v-if="count > 0">
                        <span v-for="page in count/projects.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                    </div>
                </div>
            </div>
            <div class="content">
                <project-box v-for="project in projects" v-bind:project="project"></project-box>
                <router-link :to="{name: 'projects-create-1'}">
                    <a href="" class="new-box">{{ message.new_project }} +</a>
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
            message: {
                my_projects: window.Translator.trans('message.my_projects'),
                new_project: window.Translator.trans('message.new_project'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/page-section';
</style>
