<template>
    <div class="progress-line" :id="'chart' + _uid" :data-number="percentage" v-bind:class="{ 'right': position === 'right' }">
        <div class="progress-area right flex-end">
            <p class="title-right">{{ titleRight }}</p>
            <p class="percentage">
                <span class="number">0</span>
                <span class="percentage-sign">%</span>
            </p>
        </div>
        <p v-show="titleLeft" class="title-left"><span>Status: </span> {{ titleLeft }}</p>
        <div class="filled" v-bind:style="{ background: color }" v-show="percentage != 0"></div>
        <div class="zero-fill" v-bind:style="{ background: color }" v-show="percentage == 0"></div>
    </div>
</template>

<script>
export default {
    props: ['percentage', 'color', 'titleLeft', 'titleRight', 'position'],
    mounted() {
        const $this = window.$('#chart' + this._uid);
        let speed = 1000;

        const $percentageNumber = $this.find('.number');

        $this.find('.filled')
        .animate({'width': $this.data('number') + '%'}, speed);

        window.$({Counter: 0})
            .animate({Counter: $this.data('number')}, {
                duration: speed,
                step: function() {
                    $percentageNumber.text(Math.ceil(this.Counter));
                },
            });
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/_common';
  @import '../../../css/_variables.scss';

  .progress-area {
    letter-spacing: 0.7px;
    right: 0;
    position: absolute;
    bottom: 16px;

    &.right {
      width: 60px;
    }

    .title-right {
      font-size: 8px;
      text-transform: uppercase;
      font-weight: 600;
      min-height: 11px;
    }

    .percentage {
      color: $middleColor;
      margin-top: 2px;
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
    position: relative;

    &.right {
      width: 75%;

      .progress-area {
        top: -30px;
        right: -87px;
      }
    }

    .filled {
      width: 0;
      background: $middleColor;
      height: 100%;

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
</style>
