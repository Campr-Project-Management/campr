<template>
  <div :data-percentage="percentage" class="chart relative" :id="'chart-' + _uid">
      <svg viewBox="0 0 100 100">
          <circle cx="50" cy="50" r="49" class="empty" stroke-width="1" fill="transparent"/>
          <circle cx="50" cy="50" r="49" class="full" stroke-width="1" fill="transparent"/>
      </svg>
      <div class="text">
          <p class="title">{{ title }}</p>
          <p class="flex">
              <span class="percentage">0</span>
              <span class="percentage-sign flex-end">%</span>
          </p>
      </div>
  </div>
</template>

<script>
export default {
    props: ['percentage', 'title'],
    mounted() {
        const $this = window.$('#chart-' + this._uid);
        let speed = 1000;

        const $percentageNumber = $this.find('.percentage');
        const animatedCircle = $this.find('.full');
        const percentage = $this.data('percentage');
        const c = Math.PI*(animatedCircle.attr('r')*2);

        let strokeDasharray = 0;
        let pct = percentage/100*c;
        let animation = setInterval(function() {
            strokeDasharray++;

            animatedCircle[0]
            .style.strokeDasharray = strokeDasharray + ', 10000';
            if (strokeDasharray >= pct) {
                clearInterval(animation);
            };
        }, 1);

        window.$({Counter: 0})
        .animate({Counter: $this.data('percentage')}, {
            duration: speed,
            step: function() {
                $percentageNumber.text(this.Counter.toFixed(2));
            },
        });
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/_common';
  @import '../../../css/_variables.scss';

  .chart {
    font-size: 22px;

    svg {
      display: block;
      width: 100%;
      transform: rotate(-90deg);
    }

    .empty {
      stroke: $mainColor;
    }

    .full {
      stroke: $secondColor;
      stroke-dasharray: 0, 10000;
    }

    .text {
      position: absolute;
      left: 50%;
      top: 50%;
      padding: 0;
      margin: 0;
      transform: translate(-50%, -50%);
      color: rgb(216, 218, 229);

      .title {
        text-transform: uppercase;
        font-size: 9px;
        text-align: center;
      }

      .percentage {
        font-weight: 700;
        font-size: 34px;
        line-height: 38px;
        height: 33px;
      }

      .percentage-sign {
        color: $middleColor;
        font-size: 12px;
      }
    }
  }
</style>
