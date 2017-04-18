<template>
    <div>
        <div class="grid-view" v-if="tasks">
            <task-box v-bind:task="task" user="user" :colorStatuses="colorStatuses" v-for="task in tasksPage">
            </task-box>
        </div>
        <div class="pagination flex flex-center" v-if="tasks && count > 0">
            <span v-for="page in pages" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import TaskBox from '../../Tasks/TaskBox';

export default {
    components: {
        TaskBox,
    },
    created() {
        if (!this.$store.state.colorStatus || this.$store.state.colorStatus.items.length === 0) {
            this.getColorStatuses();
        };
        if (!this.tasks || this.tasks.length === 0) {
            this.getTasks();
        };
    },
    computed: {
        ...mapGetters({
            colorStatuses: 'colorStatuses',
            tasks: 'tasks',
        }),
        count: function() {
            return this.tasks ? this.tasks.length : 0;
        },
        tasksPage: function() {
            const begin = (this.activePage-1)*this.tasksPerPage;
            const end = begin + this.tasksPerPage;
            return this.tasks.slice(begin, end);
        },
        pages: function() {
            return Math.ceil(this.tasks.length/this.tasksPerPage);
        },
    },
    methods: {
        ...mapActions(['getColorStatuses', 'getTasks']),
        changePage: function(page) {
            this.activePage = page;
        },
    },
    data() {
        return {
            activePage: 1,
            tasksPerPage: 4,
        };
    },
};
</script>

<style scoped lang="scss">
</style>
