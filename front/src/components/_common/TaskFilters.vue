<template>
    <div class="filters">
        <span class="title">Filter By</span>
        <div class="dropdowns">
            <dropdown title="Schedule" item="task" filter="schedule"></dropdown>
            <dropdown title="Status" v-bind:options="statuses" item="task" filter="status"></dropdown>
            <dropdown title="Project" v-bind:options="projectsForFilter" item="task" filter="project"></dropdown>
        </div>
    </div>
</template>

<script>
import Dropdown from '../_common/Dropdown';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        Dropdown,
    },
    methods: mapActions(['getProjects']),
    created() {
        this.getProjects();
    },
    computed: {
        ...mapGetters({
            projectsForFilter: 'projectsForFilter',
        }),
    },
    data() {
        return {
            statuses: [
                {
                    'key': '',
                    'label': 'All Statuses',
                },
                {
                    'key': 'NOT_STARTED',
                    'label': 'Not started',
                },
                {
                    'key': 'IN_PROGRESS',
                    'label': 'In progress',
                },
                {
                    'key': 'FINISHED',
                    'label': 'Finished',
                },
            ],
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/filters';
</style>
