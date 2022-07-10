<template>
    <div class="page-section">
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>{{ translate('message.external_costs') }}</h1>
            </div>

            <VideoLink module="external_costs" />
        </div>
        <chart
            title="message.by_phase"
            :data="externalCostsGraphData.byPhase | chartData"/>
        <hr class="double">

        <chart
                title="message.by_department"
                :data="externalCostsGraphData.byDepartment | chartData"/>
    </div>
</template>

<script>
    import Chart from './Charts/CostsChart.vue';
    import filters from './Charts/mixins/filters';
    import {mapGetters, mapActions} from 'vuex';
    import VideoLink from '../_common/VideoLink';

    export default {
        name: 'project-external-costs',
        mixins: [filters],
        components: {
            VideoLink,
            Chart,
        },
        methods: {
            ...mapActions(['getProjectExternalCostsGraphData']),
        },
        created() {
            this.getProjectExternalCostsGraphData({id: this.$route.params.id});
        },
        computed: {
            ...mapGetters([
                'externalCostsGraphData',
            ]),
        },
    };
</script>
