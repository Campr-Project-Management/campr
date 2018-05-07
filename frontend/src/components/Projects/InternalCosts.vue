<template>
    <div class="page-section">
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>{{ translate('message.internal_costs') }}</h1>
            </div>
        </div>
        <chart
                title="message.by_phase"
                :data="internalCostsGraphData.byPhase | chartData"/>
        <hr class="double">
        <chart
                title="message.by_department"
                :data="internalCostsGraphData.byDepartment | chartData"/>
    </div>
</template>

<script>
    import Chart from './Charts/CostsChart.vue';
    import filters from './Charts/mixins/filters';
    import {mapGetters, mapActions} from 'vuex';

    export default {
        name: 'project-internal-costs',
        mixins: [filters],
        components: {
            Chart,
        },
        methods: {
            ...mapActions(['getProjectInternalCostsGraphData']),
        },
        created() {
            this.getProjectInternalCostsGraphData({id: this.$route.params.id});
        },
        computed: {
            ...mapGetters([
                'internalCostsGraphData',
            ]),
        },
    };
</script>
