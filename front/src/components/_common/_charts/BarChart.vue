<template>
    <div class="progress-line" :id="'chart' + _uid" :data-number="percentage" v-bind:class="{ 'danger-col-bg': status === 'Not started' }">
        <div class="progress-area right flex-end">
            <p class="title-right">{{ titleRight }}</p>
            <p class="percentage">
                <span class="number">0</span>
                <span class="percentage-sign">%</span>
            </p>
        </div>
        <p v-show="titleLeft" class="title-left">{{ titleLeft }}</p>
        <div class="filled" v-bind:class="{ finished: status === 'Finished' }"></div>
    </div>
</template>

<script>
export default {
    props: ['percentage', 'status', 'titleLeft', 'titleRight'],
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
    top: -60px;

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

  .progress-line {
    margin-top: 13px;
    width: 100%;
    height: 8px;
    background: $mainColor;
    position: relative;

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
    top: -32px;
    font-size: 9px;
    text-transform: uppercase;
  }
</style>
