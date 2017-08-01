<template>
    <div class="project-status-report page-section">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="header">
                    <h1>
                        Tesla - SpaceX Mars Project
                        <span>week 12</span>
                    </h1>
                </div>

                <div class="hero-text">
                    {{ translateText('message.status_report') }}
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="widget same-height">
                            <h3>{{ translateText('message.overal_status') }}</h3>
                            <div class="flex flex-center">
                                <div class="status-boxes flex flex-v-center">
                                    <div class="status-box" style="background-color:#5FC3A5"></div>
                                    <div class="status-box" style=""></div>
                                    <div class="status-box" style=""></div>
                                </div>
                            </div>

                            <h4>{{ translateText('message.tasks_status') }}</h4>
                            <div class="status-bar clearfix flex">
                                <!-- bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) -->
                                <div class="bar middle-bg flex-v-center" data-number="245" style="width:66.40%;">Open: 245</div>
                                <div class="bar main-bg flex-v-center" data-number="124" style="width:33.60%;">Closed: 124</div>
                            </div>

                            <h4>{{ translateText('message.tasks_condition') }}</h4>
                            <div class="status-bar clearfix flex">
                                <!-- bar width calculated as (data-number * 100)/(bar1_data-number + bar2_data-number + ...) -->
                                <div class="bar second-bg flex-v-center" data-number="285" style="width:77.23%;">285</div>
                                <div class="bar warning-bg flex-v-center" data-number="55" style="width:14.90%;">55</div>
                                <div class="bar danger-bg flex-v-center" data-number="29" style="width:7.87%;">29</div>
                            </div>

                            <div class="checkbox-input clearfix">
                                <input id="action_needed" type="checkbox" name="" value="">
                                <label class="no-margin-bottom" for="action_needed">{{ translateText('message.action_needed') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="widget same-height">
                            <h3>{{ translateText('message.project_trend') }}</h3>
                            <h4>{{ translateText('message.project_trend') }}: 12.08.2017</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form">
                            <!-- /// Project Status Comment /// -->
                            <div class="form-group">
                                <div class="vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('placeholder.comment') }}</div>
                                    <Vueditor ref="content" />
                                </div>
                            </div>
                            <!-- /// End Project Staus Comment /// -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <vue-scrollbar class="table-wrapper">
                            <table class="table table-small">
                                <thead>
                                    <tr>
                                        <th>{{ translateText('table_header_cell.schedule') }}</th>
                                        <th>{{ translateText('table_header_cell.start') }}</th>
                                        <th>{{ translateText('table_header_cell.finish') }}</th>
                                        <th>{{ translateText('table_header_cell.duration') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Base</td>
                                        <td>10.02.2016</td>
                                        <td>30.05.2018</td>
                                        <td>841</td>
                                    </tr>
                                    <tr>
                                        <td>Forecast</td>
                                        <td>01.04.2016</td>
                                        <td class="cell-warning">30.06.2018</td>
                                        <td>721</td>
                                    </tr>
                                    <tr>
                                        <td>Actual</td>
                                        <td>01.04.2016</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </vue-scrollbar>
                    </div>
                    <div class="col-md-4">
                        <div class="range-slider-legend">
                            <div class="legend-item">
                                Base Schedule
                                <div class="legend-bar dark-bg"></div>
                            </div>
                            <div class="legend-item">
                                Forecast Schedule
                                <div class="legend-bar middle-bg"></div>
                            </div>
                            <div class="legend-item">
                                Actual Schedule
                                <div class="legend-bar second-bg"></div>
                            </div>
                            <div class="legend-item">
                                Warning
                                <div class="legend-bar warning-bg"></div>
                            </div>
                            <div class="legend-item">
                                Alert
                                <div class="legend-bar danger-bg"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="task-range-slider">
                            <div class="task-range-slider-title">Schedule</div>
                            <task-range-slider class="base" id="scheduleBase" message="Base" min="10.02.2016" max="30.06.2018" v-bind:from="'10.02.2016'" v-bind:to="'30.05.2018'" type="double"></task-range-slider>
                            <task-range-slider class="forecast" id="scheduleForecast" message="Forecast" min="10.02.2016" max="30.06.2018" v-bind:from="'01.04.2016'" v-bind:to="'30.06.2018'" type="double"></task-range-slider>
                            <task-range-slider class="actual" id="scheduleActual" message="Actual" min="10.02.2016" max="30.06.2018" v-bind:from="'01.04.2016'" v-bind:to="'24.07.2017'" type="double"></task-range-slider>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import 'jquery-match-height/jquery.matchHeight.js';
import VueScrollbar from 'vue2-scrollbar';
import TaskRangeSlider from '../_common/_task-components/TaskRangeSlider';

export default {
    components: {
        VueScrollbar,
        TaskRangeSlider,
    },
    created() {
        window.$(document).ready(function() {
            window.$('.same-height').matchHeight();
        });
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .page-section {
        .header {
            justify-content: center;
            text-align: center;

            h1 {
                padding-bottom: 20px;

                span {
                    font-size: 0.75em;
                    display: block;
                    margin-top: 10px;
                }
            }
        }
    }

    .hero-text {
        font-size: 3em;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 30px; 
    }

    .widget {
        background-color: $darkColor;
        padding: 25px 20px;

        h3, h4 {
            margin: 0 0 10px;
            text-align: center;
        }
    }

    .status-boxes {
        margin-bottom: 30px;

        .status-box {
            width: 56px;
            height: 56px;
            margin-right: 5px;
            background-color:$fadeColor;
        }
    }

    .status-bar {
        margin-bottom: 30px;

        .bar {
            height: 30px;
            line-height: 30px;
            text-align: center;
            color: $whiteColor;
            font-weight: 500;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
    }

    .range-slider-legend {
        text-align: right;
        font-size: 10px;
        text-transform: uppercase;

        .legend-item {
            margin-bottom: 10px;
            line-height: 1em;

            .legend-bar {
                display: inline-block;
                width: 90%;
                height: 5px;
            }
        }
    }
</style>
