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
                <task-box v-for="task in tasks" v-bind:task="task"></task-box>
            </div>
            <div class="pagination flex flex-center" v-if="count > 0">
                <span v-for="page in pageCount" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
            </div>
        </div>
        <pagination
                :value="activePage"
                :number-of-pages="pages"
                @input="changePage"/>
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
            ...mapActions(['getTasks', 'setFilters']),
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
                        userRasci: this.userRasci,
                    },
                });
            },
        },
        created() {
            this.setFilters({clear: true});
            this.getTasksData();
        },
        computed: {
            ...mapGetters({
                user: 'user',
                tasks: 'tasks',
                count: 'tasksCount',
                tasksPerPage: 'tasksPerPage',
            }),
            pages: function() {
                return Math.ceil(this.count / this.tasksPerPage);
            },
        },
        data() {
            return {
                items: [],
                activePage: 1,
                userRasci: true,
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
