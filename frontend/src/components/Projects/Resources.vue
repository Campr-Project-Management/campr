<template>
    <div class="page-section">
        <div class="row">
            <div class="header flex flex-space-between">
                <div class="flex">
                    <h1>{{ translateText('message.resources') }}</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <vue-chart
                chart-type="ColumnChart"
                :columns="columns"
                :rows="rowsByPhase"
                :options="optionsByPhase">
            </vue-chart>
        </div>
        <hr class="double">
        <div class="row">
            <vue-chart
                chart-type="ColumnChart"
                :columns="columns"
                :rows="rowsByDepartment"
                :options="optionsByDepartment">
            </vue-chart>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';

export default {
    methods: {
        ...mapActions(['getProjectResourcesGraphData']),
        translateText: function(text) {
            return this.translate(text);
        },
    },
    created() {
        this.getProjectResourcesGraphData({id: this.$route.params.id});
    },
    computed: mapGetters({
        resourceData: 'resourceData',
    }),
    watch: {
        resourceData(value) {
            Object.entries(this.resourceData.byPhase).map(([key, value]) => {
                this.rowsByPhase.push([
                    key,
                    value.base ? parseInt(value.base) : 0,
                    value.actual ? parseInt(value.actual) : 0,
                    value.forecast ? parseInt(value.forecast) : 0,
                    value.base && value.actual ? parseInt(value.base) - parseInt(value.actual) : 0,
                ]);
            });
            Object.entries(this.resourceData.byDepartment).map(([key, value]) => {
                this.rowsByDepartment.push([
                    key,
                    value.base ? parseInt(value.base) : 0,
                    value.actual ? parseInt(value.actual) : 0,
                    value.forecast ? parseInt(value.forecast) : 0,
                    value.base && value.actual ? parseInt(value.base) - parseInt(value.actual) : 0,
                ]);
            });
        },
    },
    data: function() {
        const defaultOptions = {
            hAxis: {
                textStyle: {
                    color: '#D8DAE5',
                },
            },
            vAxis: {
                title: '',
                minValue: 0,
                maxValue: 0,
                textStyle: {
                    color: '#D8DAE5',
                },
            },
            width: '100%',
            height: 350,
            curveType: 'function',
            colors: ['#5FC3A5', '#A05555', '#646EA0', '#2E3D60', '#D8DAE5'],
            backgroundColor: '#191E37',
            titleTextStyle: {
                color: '#D8DAE5',
            },
            legend: {
                position: 'bottom',
                maxLines: 5,
            },
            legendTextStyle: {
                color: '#D8DAE5',
            },
        };
        const optionsByPhase = Object.assign({}, optionsByPhase, defaultOptions);
        const optionsByDepartment = Object.assign({}, optionsByDepartment, defaultOptions);
        optionsByPhase.title = Translator.trans('message.resources_chart_by_phase');
        optionsByDepartment.title = Translator.trans('message.resources_chart_by_department');
        return {
            columns: [{
                'type': 'string',
                'label': Translator.trans('message.total'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.base'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.actual'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.forecast'),
            }, {
                'type': 'number',
                'label': Translator.trans('label.remaining'),
            }],
            rowsByPhase: [
                ['', 0, 0, 0, 0],
            ],
            rowsByDepartment: [
                ['', 0, 0, 0, 0],
            ],
            optionsByPhase: optionsByPhase,
            optionsByDepartment: optionsByDepartment,
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../css/_common';
    @import '../../css/_mixins';
    @import '../../css/_variables';
    @import '../../css/page-section';
</style>
