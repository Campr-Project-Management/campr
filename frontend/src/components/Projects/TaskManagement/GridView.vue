<template>
    <div>
        <div class="grid-view">
            <task-box v-bind:task="task" user="user" :colorStatuses="colorStatuses" v-for="task in tasks">
            </task-box>
        </div>
        <div class="pagination flex flex-center" v-if="tasks && count > 0">
            <span v-for="page in count/tasks.length" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
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
    },
    methods: {
        ...mapActions(['getColorStatuses', 'getTasks']),
    },
};
</script>

<style scoped lang="scss">
</style>
