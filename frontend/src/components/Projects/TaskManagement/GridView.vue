<template>
    <vue-scrollbar class="categories-scroll">
        <div class="grid-view">
            <task-box v-bind:task="task" user="user" :colorStatuses="colorStatuses" v-for="task in tasks">
            </task-box>
        </div>
        <div class="pagination flex flex-center" v-if="count > 0">
            <span v-for="page in [1,2]" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
        </div>
    </vue-scrollbar>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import TaskBox from '../../Tasks/TaskBox';
import VueScrollbar from 'vue2-scrollbar';

export default {
    components: {
        TaskBox,
        VueScrollbar,
    },
    created() {
        if (!this.$store.state.colorStatus || this.$store.state.colorStatus.items.length === 0) {
            this.getColorStatuses();
        }
        if (!this.tasks || this.tasks.items.length === 0) {
            this.getTasks();
        }
    },
    computed: mapGetters({
        colorStatuses: 'colorStatuses',
        tasks: 'tasks',

    }),
    methods: {
        ...mapActions(['getColorStatuses', 'getTasks']),
    },
    data() {
        return {
            count: 2,
        };
    },
};
</script>

<style>
    .grid-view {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        margin-right: -15px;
        margin-left: -15px;
    }

    .task-box-wrapper {
        width: 25%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .categories-scroll {
        width: 100%;
        padding-bottom: 30px;
    }
</style>
