<template>
    <div class="page-section">
        <div class="header flex flex-space-between">
            <div class="flex">
                <h1>{{ translateText('message.internal_costs') }}</h1>
            </div>
        </div>
        <chart title="message.by_phase" :data="internalCostsGraphData.byPhase"/>
        <hr class="double">
        <chart title="message.by_department" :data="internalCostsGraphData.byDepartment"/>
    </div>
</template>

<script>
    import Chart from './Charts/CostsChart.vue';
    import {mapGetters, mapActions} from 'vuex';

    export default {
        name: 'project-internal-costs',
        components: {
            Chart,
        },
        methods: {
            ...mapActions(['getProjectInternalCostsGraphData']),
            translateText: function(text) {
                return this.translate(text);
            },
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

<style scoped lang="scss">
    @import '../../css/_common';
    @import '../../css/_mixins';
    @import '../../css/_variables';
    @import '../../css/page-section';
</style>
