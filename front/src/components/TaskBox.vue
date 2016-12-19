<template>
    <div class="project-box box">
        <div class="header">
            <h2>TASK</h2>
            <div>
                <svg class="eye" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                  width="16px" height="9.334px" viewBox="0 0 16 9.334" enable-background="new 0 0 16 9.334" xml:space="preserve">
                    <path fill="#636EA0" d="M0.073,4.874c0.144,0.181,3.582,4.46,7.928,4.46c4.345,0,7.782-4.279,7.927-4.46
                      c0.096-0.122,0.096-0.293,0-0.414C15.783,4.276,12.346,0,8.001,0C3.655,0,0.217,4.276,0.073,4.46
                      C-0.024,4.581-0.024,4.752,0.073,4.874 M8.001,1.667c1.654,0,3,1.345,3,3c0,1.654-1.346,3-3,3c-1.654,0-3-1.346-3-3
                      C5.001,3.012,6.347,1.667,8.001,1.667"/>
                    <path fill="#636EA0" d="M8.001,7c1.286,0,2.333-1.047,2.333-2.333c0-1.287-1.047-2.334-2.333-2.334
                      c-1.287,0-2.333,1.047-2.333,2.334C5.668,5.953,6.714,7,8.001,7"/>
                </svg>
            </div>
        </div>
        <div class="content">
            <div class="info">
                <p class="title">DigitlWall > Phase 2 > Millestone 2.2</p>
                <p class="status">Status</p>
                <div class="status-boxes">
                    <span class="status-box" v-bind:class="{ 'second-col-bg': task.status === 'FINISHED' }"></span>
                    <span class="status-box" v-bind:class="{ 'warning-col-bg': task.status === 'IN_PROGRESS' }"></span>
                    <span class="status-box" v-bind:class="{ 'danger-col-bg': task.status === 'NOT_STARTED' }"></span>
                </div>
            </div>
            <div class="progress-area">
                <p class="title">Progress</p>
                <p class="percentage"><span class="number">0</span>%</p>
            </div>
        </div>
        <div class="progress-bar" v-bind:class="{ 'danger-col-bg': task.status === 'NOT_STARTED' }">
            <div class="filled" :data-number="task.percentage" v-bind:class="{ finished: task.status === 'FINISHED' }"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['task'],
    data() {
        return {
            title: 'CAMPR',
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../css/_variables.scss';
  @import '../css/dashboard/box.scss';

  .info {
    padding-top: 18px;

    .title {
      font-size: 11px;
      margin-bottom: 13px;
      height: 15px;
      line-height: 15px;
    }

    .status {
      text-transform: uppercase;
      margin-bottom: 8px;
      font-size: 8px;
      height: 12px;
      line-height: 12px;
    }
  }
  .status-boxes {
    display: flex;

    .status-box {
      width: 20px;
      height: 20px;
      margin-right: 2px;
      border-radius: 50%;
      background: $mainColor;
    }
  }
</style>
