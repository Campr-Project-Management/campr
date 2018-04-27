<template>
    <div class="bar-chart">
      <div class="progress-line" :id="'chart' + _uid" :data-number="percentage" v-bind:class="{ 'right': position === 'right' }">
          <div class="progress-area">
              <p class="title-right">{{ titleRight }}</p>
              <p class="percentage">
                  <span class="number">{{ percentage }}</span>
                  <span class="percentage-sign">%</span>
              </p>
          </div>
          <p v-show="titleLeft" class="title-left"><span>Status: </span> {{ titleLeft }}</p>
          <div class="filled" v-bind:style="{ background: color, width: percentage + '%' }" v-if="!isZeroFill"></div>
          <div class="zero-fill" v-if="isZeroFill"></div>
      </div>
    </div>
</template>

<script>
export default {
    props: ['percentage', 'color', 'titleLeft', 'titleRight', 'position'],
    computed: {
        isZeroFill: function() {
            return this.percentage == 0;
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/_variables.scss';

  .bar-chart {
      position: relative;
  }

  .progress-area {
    letter-spacing: 0.7px;
    position: absolute;
    text-align: right;
    right: 0;
    bottom: 13px;
    margin-bottom: 0;

    .title-right {
      font-size: 8px;
      text-transform: uppercase;
      font-weight: 600;
      min-height: 11px;
      margin-bottom: 0;
    }

    .percentage {
      color: $middleColor;
      margin: 0;
      display: flex;
    }

    .number {
      color: $lighterColor;
      font-size: 32px;
      height: 30px;
      line-height: 35px;
      min-width: 55px;
      text-align: right;
      font-weight: 700;
    }

    .percentage-sign {
      align-self: flex-end;
      font-size: 10px; 
      margin-left: 5px;
      position: relative;
      top: 5px;
    }
  }

  .recent-tasks {
    .progress-area {
      bottom: -2px;
    }
  }

  .column {
    .progress-area {
      bottom: -2px;
    }
  }

  .zero-fill {
      width: 100%;
      height: 100%;
  }

  .progress-line {
    margin-top: 13px;
    width: 100%;
    height: 8px;
    background: $mainColor;

    &.right {
      width: 75%;
    }

    .filled {
      width: 0;
      background: $middleColor;
      height: 100%;

      -webkit-transition: all ease 0.75s;
      -moz-transition: all ease 0.75s;
      -ms-transition: all ease 0.75s;
      -o-transition: all ease 0.75s;
      transition: all ease 0.75s;

      &.finished {
        background: $secondColor;
      }
    }
  }

  .title-left {
    position: absolute;
    bottom: 14px;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 1.9px;
    font-weight: 900;

    span {
      font-weight: 300;
      color: $lightColor;
    }
  }

  .widget {
    .progress-line {
      background-color: $darkColor;
    }
  }
</style>
