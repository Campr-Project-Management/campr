<template>
    <div>
        <div class="page-section">
            <div class="header">
                <h1>{{ message.my_tasks }}</h1>
                <div class="flex filters-container">
                    <task-filters></task-filters>
                    <div class="separator"></div>
                    <div class="pagination flex flex-v-center" v-if="count > 0">
                        <span v-for="page in count/tasks.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                    </div>
                </div>
            </div>
            <div class="grid-view">
                <task-box v-for="task in tasks" v-bind:task="task" v-bind:colorStatuses="colorStatuses"></task-box>
                <router-link :to="{name: 'task-new'}">
                    <a href="" class="new-box">{{ message.new_project }} +</a>
                </router-link>
            </div>
            <div class="pagination flex flex-center" v-if="count > 0">
                <span v-for="page in count/tasks.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import TaskFilters from '../_common/TaskFilters';
import TaskBox from './TaskBox';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        TaskFilters,
        TaskBox,
    },
    methods: {
        ...mapActions(['getTasks', 'getColorStatuses']),
        changePage(page) {
            this.getTasks(page);
            this.activePage = page;
        },
    },
    created() {
        if (!this.$store.state.task || this.$store.state.task.items.length === 0) {
            this.getTasks(this.activePage);
        }
        if (!this.$store.state.colorStatus || this.$store.state.colorStatus.items.length === 0) {
            this.getColorStatuses();
        }
    },
    computed: mapGetters({
        user: 'user',
        tasks: 'tasks',
        count: 'count',
        colorStatuses: 'colorStatuses',
    }),
    data() {
        return {
            activePage: 1,
            message: {
                my_tasks: Translator.trans('message.my_tasks'),
                new_task: Translator.trans('message.new_task'),
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
