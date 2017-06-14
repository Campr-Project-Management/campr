<template>
    <div class="page-section">
        <div class="row">
            <div class="header flex flex-space-between">
                <div class="flex">
                    <h1>{{ translateText('message.costs') }}</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <vue-chart
                chart-type="ColumnChart"
                :columns="columns"
                :rows="rowsByPhase"
                :options="options">
            </vue-chart>
        </div>
        <hr class="double">
        <div class="row">
            <vue-chart
                chart-type="ColumnChart"
                :columns="columns"
                :rows="rowsByDepartment"
                :options="options">
            </vue-chart>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';

export default {
    methods: {
        ...mapActions(['getProjectCostsResources']),
        translateText: function(text) {
            return this.translate(text);
        },
    },
    created() {
        this.getProjectCostsResources({id: this.$route.params.id, type: 1});
    },
    computed: mapGetters({
        projectCostsAndResources: 'projectCostsAndResources',
    }),
    watch: {
        projectCostsAndResources(value) {
            Object.entries(this.projectCostsAndResources.byPhase).map(([key, value]) => {
                this.rowsByPhase.push([
                    key,
                    value.base ? parseInt(value.base) : 0,
                    value.actual ? parseInt(value.actual) : 0,
                    value.forecast ? parseInt(value.forecast) : 0,
                    value.base && value.actual ? parseInt(value.base) - parseInt(value.actual) : 0,
                ]);
            });
            Object.entries(this.projectCostsAndResources.byDepartment).map(([key, value]) => {
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
            options: {
                title: Translator.trans('message.costs_chart'),
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
            },
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
