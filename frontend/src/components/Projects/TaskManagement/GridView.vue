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
    },
    computed: mapGetters({
        colorStatuses: 'colorStatuses',
    }),
    methods: {
        ...mapActions(['getColorStatuses']),
    },
    data() {
        return {
            tasks: [
                {
                    'id': 1,
                    'name': 'Tesla3 SpaceX Mars Project',
                    'progress': 33,
                    'phase': 'Phase 1',
                    'milestone': 'Milestone 1.2',
                    'content': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sit amet felis blandit eros dapibus aliquam.',
                    'status': 'todo',
                }, {
                    'id': 2,
                    'name': 'Tesla4 SpaceX Mars Project',
                    'progress': 75,
                    'phase': 'Phase 3',
                    'milestone': 'Milestone 3.2',
                    'content': 'Suspendisse tempus efficitur posuere. Phasellus laoreet neque ligula, sed laoreet neque lacinia et.',
                    'status': 'inprogress',
                }, {
                    'id': 3,
                    'name': 'Tesla SpaceX Mars Project',
                    'progress': 10,
                    'phase': 'Phase 3',
                    'milestone': 'Milestone 3.3',
                    'content': 'Mauris sapien nisi, placerat at elit ut, gravida auctor eros.',
                    'status': 'codereview',
                }, {
                    'id': 4,
                    'name': 'Tesla SpaceX Mars Project',
                    'progress': 100,
                    'phase': 'Phase 2',
                    'milestone': 'Milestone 2.3.5',
                    'content': '',
                    'status': 'todo',
                }, {
                    'id': 4,
                    'name': 'Tesla SpaceX Mars Project',
                    'progress': 68,
                    'phase': 'Phase 5',
                    'milestone': 'Milestone 5.1.2',
                    'content': 'Praesent rutrum libero nec lectus ultrices, ac rhoncus risus rhoncus.',
                    'status': 'todo',
                },
            ],
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
