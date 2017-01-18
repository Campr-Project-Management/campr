<template>
    <div class="project-box box" v-bind:class="'border-color-' + task.id">
        <div class="header">
            <div>
                <h2>{{ task.name }}</h2>
                <p class="task-id">#{{ task.id }}</p>
            </div>
            <div>
                <eye-icon :link="{name: 'task', params: { id: task.id }}"></eye-icon>
            </div>
        </div>
        <div class="content">
            <div class="info">
                <p class="title">{{ task.projectName }}</p>
                <table>
                    <tr>
                        <th>Schedule</th>
                        <th>Start</th>
                        <th>Finish</th>
                        <th>Duration</th>
                    </tr>
                    <tr class="even" v-show="task.scheduledStartAt || task.scheduledFinishAt">
                        <td>Schedule Base</td>
                        <td>{{ task.scheduledStartAt }}</td>
                        <td>{{ task.scheduledFinishAt }}</td>
                        <td>{{ duration(task.scheduledStartAt, task.scheduledFinishAt) }}</td>
                    </tr>
                    <tr class="odd" v-show="task.forecastStartAt || task.forecastFinishAt">
                        <td>Schedule Forescast</td>
                        <td>{{ task.forecastStartAt }}</td>
                        <td>{{ task.forecastFinishAt }}</td>
                        <td>{{ duration(task.forecastStartAt, task.forecastFinishAt) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <bar-chart :percentage="task.progress" :status="task.colorStatusName" class="bar-chart" :title-left="task.colorStatusName"></bar-chart>
        <div class="nicescroll">
            {{ task.content }}
        </div>
        <div class="info bottom flex flex-space-between">
            <div>
                <p class="status">Status</p>
                <div class="status-boxes">
                    <span v-for="cs in colorStatuses" class="status-box" v-bind:style="{ background: task.colorStatusName === cs.name ? '#' + task.colorStatusColor : '' }"></span>
                </div>
            </div>
            <div class="icons">
                <div class="icon-holder">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px"
                       viewBox="0 0 17.3 24.3" style="enable-background:new 0 0 17.3 24.3;" xml:space="preserve">
                    <path id="XMLID_1674_" class="st0" d="M200.8,632.3v-6.8c0-0.5-0.1-1.5-1.1-1.5"/>
                    <g id="XMLID_1672_">
                        <g id="XMLID_1673_">
                            <path id="XMLID_1_" class="st0" d="M7.5,21.4c-2.2,0-3.7-1.7-3.7-4.1V8.7c0-3,2.1-5.3,4.9-5.3s4.9,2.3,4.9,5.3V15
                              c0,0.4-0.3,0.8-0.8,0.8c-0.4,0-0.8-0.3-0.8-0.8V8.7c0-2.2-1.4-3.8-3.4-3.8S5.3,6.5,5.3,8.7v8.6c0,1.3,0.7,2.6,2.3,2.6
                              s2.3-1.3,2.3-2.6v-6.8C9.8,10.1,9.7,9,8.6,9s-1.1,1-1.1,1.5v5.6c0,0.4-0.3,0.8-0.8,0.8c-0.4,0-0.8-0.3-0.8-0.8v-5.6
                              c0-1.8,1.1-3,2.6-3s2.6,1.2,2.6,3v6.8C11.3,19.7,9.7,21.4,7.5,21.4z"/>
                        </g>
                    </g>
                    </svg>
                    <span class="number">0</span>
                </div>

                <div class="icon-holder">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px"
                       viewBox="0 0 28.2 24.5" style="enable-background:new 0 0 28.2 24.5;" xml:space="preserve">
                    <path id="XMLID_1148_" class="st0" d="M23.8,3.4H4.1c-0.2,0-0.4,0.2-0.4,0.4v13.7c0,0.2,0.2,0.4,0.4,0.4h5.6v3
                      c0,0.4,0.5,0.6,0.7,0.3l3.3-3.3h10.1c0.2,0,0.4-0.2,0.4-0.4V3.9C24.3,3.6,24.1,3.4,23.8,3.4z M8.4,7.7h7.7c0.2,0,0.4,0.2,0.4,0.4
                      c0,0.2-0.2,0.4-0.4,0.4H8.4C8.2,8.6,8,8.4,8,8.1C8,7.9,8.2,7.7,8.4,7.7z M19.5,13.7H8.4c-0.2,0-0.4-0.2-0.4-0.4
                      c0-0.2,0.2-0.4,0.4-0.4h11.1c0.2,0,0.4,0.2,0.4,0.4C20,13.5,19.8,13.7,19.5,13.7z M19.5,11.1H8.4c-0.2,0-0.4-0.2-0.4-0.4
                      c0-0.2,0.2-0.4,0.4-0.4h11.1c0.2,0,0.4,0.2,0.4,0.4C20,10.9,19.8,11.1,19.5,11.1z"/>
                    </svg>
                    <span class="number">2</span>
                </div>
                <div class="icon-holder">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px"
                       viewBox="0 0 26.4 26.4" style="enable-background:new 0 0 26.4 26.4;" xml:space="preserve">
                    <g id="XMLID_493_">
                      <path id="XMLID_499_" class="st0" d="M22.3,4.2c-0.3-0.3-0.8-0.2-1.1,0.1L11.7,16l-3.4-3.4c-0.3-0.3-0.8-0.3-1.1,0
                        c-0.3,0.3-0.3,0.8,0,1.1l4,4c0.2,0.2,0.4,0.2,0.6,0.2c0.2,0,0.5-0.1,0.6-0.3l10-12.4C22.7,5,22.6,4.5,22.3,4.2z"/>
                      <path id="XMLID_496_" class="st0" d="M19.4,11.6c-0.4,0-0.8,0.4-0.8,0.8v8H5.8V7.6h9.6c0.4,0,0.8-0.4,0.8-0.8
                        c0-0.4-0.4-0.8-0.8-0.8H5C4.5,6,4.2,6.4,4.2,6.8v14.4C4.2,21.7,4.5,22,5,22h14.4c0.4,0,0.8-0.4,0.8-0.8v-8.8
                        C20.2,12,19.8,11.6,19.4,11.6z"/>
                    </g>
                    </svg>
                    <span class="number">2</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BarChart from '../_common/_charts/BarChart';
import EyeIcon from '../_common/_icons/EyeIcon';
import 'jquery.nicescroll/jquery.nicescroll.min.js';
import moment from 'moment';

export default {
    components: {
        BarChart,
        EyeIcon,
    },
    created() {
        window.$(document).ready(function() {
            window.$('.nicescroll').niceScroll({
                autohidemode: false,
            });
        });
    },
    methods: {
        duration: function(startDate, endDate) {
            let start = moment(startDate);
            let end = moment(endDate);
            return end.diff(start, 'days');
        },
    },
    props: ['task', 'colorStatuses'],
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common';
  @import '../../css/box';
  @import '../../css/box-task';

  h2 {
    line-height: 15px;
  }

  table {
    margin: 0 -10px;

    th, td {
      padding: 3px 9px;
    }
  }

  .task-id {
    color: $middleColor;
    font-size: 11px;
  }

  .st0 {
    fill: $middleColor;
  }

  .bar-chart {
    margin-top: 70px;
  }

  .title {
    display: none;
  }

  .nicescroll {
    margin-top: 20px;
    height: 180px;
    overflow: hidden;
  }

  .bullets {
    li {
      margin-bottom: 19px;
    }
  }

  .info.bottom {
    padding-top: 0;
  }
</style>

<style lang="scss">
  @import '../../css/_variables';

  .nicescroll-cursors {
    border: none !important;
    border-radius: 0 !important;
    width: 2px !important;
    background: $middleColor !important;
  }
</style>

