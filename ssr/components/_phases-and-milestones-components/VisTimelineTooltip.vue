<template>
    <div>
        <div class="task-box box">
            <div class="box-header">
                <div class="user-info flex flex-v-center">
                    <img class="user-avatar" src="http://dev.campr.biz/uploads/avatars/60.jpg" alt="Phase responsable: Sandy Fanning-Choi"/>
                    <p>Sandy Fanning-Choi</p>
                </div>
                <h2><router-link to="" class="simple-link">Phase 1</router-link></h2>
                <p class="task-id">#1</p>
            </div>
            <div class="content">
                <table class="table table-small">
                    <thead>
                        <tr>
                            <th>Schedule</th>
                            <th>Start</th>
                            <th>Finish</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Base</td>
                            <td>24.01.2017</td>
                            <td>26.01.2017</td>
                            <td>2</td>
                        </tr>
                        <tr class="column-warning">
                            <td>Forecast</td>
                            <td>24.01.2017</td>
                            <td>27.01.2017</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>Actual</td>
                            <td>24.01.2017</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="status">
                <p><span>Status:</span> In Progress</p>
                <bar-chart position="right" :percentage="85" :color="Green" v-bind:title-right="green"></bar-chart>
            </div>
        </div>
    </div>
</template>

<script>
import BarChart from '../_charts/BarChart';

export default {
    components: {
        BarChart,
    },
    props: ['task', 'colorStatuses', 'user'],
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss">
  @import '../../../frontend/src/css/_variables';
  @import '../../../frontend/src/css/_mixins';
  @import '../../../frontend/src/css/box';
  @import '../../../frontend/src/css/box-task';

  h2 {
    line-height: 15px;
  }

  .table-small {
    margin: 0;

    > thead {
        > tr {
            > th {
                height: 30px;
                padding: 8px 15px;
            }
        }
    }

    > tbody {
        > tr {
            > td {
                padding: 8px 15px;
            }
        }
    }

    td {
      font-size: 10px;
    }
  }

  .progress-line.bar-chart {
    margin-top: 40px;
  }
</style>
