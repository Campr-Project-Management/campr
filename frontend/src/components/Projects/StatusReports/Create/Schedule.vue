<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <schedule-dates-table
                        :base-start-at="baseStartAt"
                        :base-finish-at="baseFinishAt"
                        :base-duration-days="baseDurationDays"
                        :forecast-start-at="forecastStartAt"
                        :forecast-finish-at="forecastFinishAt"
                        :forecast-duration-days="forecastDurationDays"
                        :actual-start-at="actualStartAt"
                        :actual-finish-at="actualFinishAt"
                        :actual-duration-days="actualDurationDays"/>
            </div>
            <div class="col-md-4">
                <div class="range-slider-legend">
                    <div class="legend-item">
                        <span>{{ translate('message.base_schedule') }}</span>
                        <div class="legend-bar darker-bg"></div>
                    </div>
                    <div class="legend-item">
                        <span>{{ translate('message.forecast_schedule') }}</span>
                        <div class="legend-bar middle-bg"></div>
                    </div>
                    <div class="legend-item">
                        <span>{{ translate('message.actual_schedule') }}</span>
                        <div class="legend-bar second-bg"></div>
                    </div>
                    <div class="legend-item">
                        <span>{{ translate('message.warning') }}</span>
                        <div class="legend-bar warning-bg"></div>
                    </div>
                    <div class="legend-item">
                        <span>{{ translate('message.danger') }}</span>
                        <div class="legend-bar danger-bg"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <schedule-dates-bar
                        :base-start-at="baseStartAt"
                        :base-finish-at="baseFinishAt"
                        :base-duration-days="baseDurationDays"
                        :forecast-start-at="forecastStartAt"
                        :forecast-finish-at="forecastFinishAt"
                        :forecast-duration-days="forecastDurationDays"
                        :actual-start-at="actualStartAt"
                        :actual-finish-at="actualFinishAt"
                        :actual-duration-days="actualDurationDays"
                        :show-schedule-tooltip="false"
                        :show-pins="false"/>
            </div>
        </div>
    </div>
</template>

<script>
    import ScheduleDatesBar from '../../../_common/ScheduleDatesBar';
    import ScheduleDatesTable from '../../../_common/ScheduleDatesTable';

    export default {
        name: 'status-report-schedule',
        components: {ScheduleDatesTable, ScheduleDatesBar},
        props: {
            baseStartAt: {
                type: [String, Date],
            },
            baseFinishAt: {
                type: [String, Date],
            },
            baseDurationDays: {
                type: Number,
                required: true,
                default: 0,
            },
            forecastStartAt: {
                type: [String, Date],
            },
            forecastFinishAt: {
                type: [String, Date],
            },
            forecastDurationDays: {
                type: Number,
                required: true,
                default: 0,
            },
            actualStartAt: {
                type: [String, Date],
            },
            actualFinishAt: {
                type: [String, Date],
            },
            actualDurationDays: {
                type: Number,
                required: true,
                default: 0,
            },
        },
    };
</script>

<style lang="scss" scoped>
    .range-slider-legend {
        text-align: right;
        font-size: 10px;
        text-transform: uppercase;

        .legend-item {
            margin-bottom: 10px;
            line-height: 1em;

            span {
                display: block;
                text-align: right;
            }

            .legend-bar {
                display: inline-block;
                width: 50%;
                height: 5px;
            }
        }
    }
</style>
