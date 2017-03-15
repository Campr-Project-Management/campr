<template>
    <div class="filters">
        <span class="title">{{ message.filter_by }}</span>
        <div class="dropdowns">
            <dropdown v-bind:title="message.schedule" item="task" filter="schedule"></dropdown>
            <dropdown v-bind:title="message.status" v-bind:options="statuses" item="task" filter="colorStatusName"></dropdown>
            <dropdown v-bind:title="message.project" v-bind:options="projectsForFilter" item="task" filter="project"></dropdown>
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
            statuses: 'colorStatusesForFilter',
            user: 'user',
        }),
    },
    data: function() {
        return {
            message: {
                filter_by: Translator.trans('message.filter_by'),
                schedule: Translator.trans('message.schedule'),
                status: Translator.trans('message.status'),
                project: Translator.trans('message.project'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/filters';
</style>
