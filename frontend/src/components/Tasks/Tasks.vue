<template>
    <div>
        <div class="page-section">
            <div class="header">
                <h1>{{ translateText('message.my_tasks') }}</h1>
                <div class="flex filters-container">
                    <task-filters :updateFilters="applyFilters"></task-filters>
                </div>
            </div>
            <div class="grid-view">
                <task-box v-for="task in tasks" v-bind:task="task" v-bind:colorStatuses="colorStatuses"></task-box>
            </div>
            <div class="pagination flex flex-center" v-if="count > 0">
                <span v-for="page in pageCount" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
            </div>
        </div>
        <pagination
            :current-page="activePage"
            :number-of-pages="pages"
            :value="activePage"
            v-on:change-page="changePage"/>
    </div>
</template>

<script>
import TaskFilters from '../_common/TaskFilters';
import TaskBox from './TaskBox';
import {mapActions, mapGetters} from 'vuex';
import Pagination from '../_common/Pagination.vue';

export default {
    components: {
        TaskFilters,
        TaskBox,
        Pagination,
    },
    methods: {
        ...mapActions(['getTasks', 'getColorStatuses', 'setFilters']),
        changePage(page) {
            this.activePage = page;
            this.getTasksData();
        },
        translateText: function(text) {
            return this.translate(text);
        },
        applyFilters: function(key, value) {
            let filterObj = {};
            filterObj[key] = value;
            this.setFilters(filterObj);
            this.getTasksData();
        },
        getTasksData: function() {
            this.getTasks({
                queryParams: {
                    page: this.activePage,
                },
            });
        },
    },
    created() {
        this.setFilters({clear: true});
        this.getTasksData();
        this.getColorStatuses();
    },
    computed: {
        ...mapGetters({
            user: 'user',
            tasks: 'tasks',
            count: 'tasksCount',
            tasksPerPage: 'tasksPerPage',
            colorStatuses: 'colorStatuses',
        }),
        pages: function() {
            return Math.ceil(this.count / this.tasksPerPage);
        },
    },
    data() {
        return {
            items: [],
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
