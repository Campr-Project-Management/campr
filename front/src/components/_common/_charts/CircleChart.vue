<template>
  <div :data-percentage="percentage" class="chart relative">
      <svg viewBox="0 0 100 100">
          <circle cx="50" cy="50" r="49" class="empty" stroke-width="1" fill="transparent"/>
          <circle cx="50" cy="50" r="49" class="full" stroke-width="1" fill="transparent"/>
      </svg>
      <div class="text">
          <p class="title">Task Status</p>
          <p class="flex">
              <span class="percentage">0</span>
              <span class="percentage-sign flex-end">%</span>
          </p>
      </div>
  </div>
</template>

<script>
export default {
    props: ['percentage'],
    mounted() {
        const progressBar = window.$('.chart');
        let speed = 1000;

        progressBar.each(function(i, elem) {
            const $this = window.$(elem);
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
        });
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/_variables.scss';

  .btn-primary {
    background: $darkColor;
    color: $lightColor;
    border: none;
    width: 200px;
    text-transform: uppercase;
    height: 40px;
    font-size: 10px;
    border-radius: 1px;
    text-align: left;

    @media screen and (max-width: 1440px) {
      width: 120px;
    }

    .caret {
      float: right;
      margin-top: 4px;
    }
  }

  .btn-primary.active, .btn-primary:active, .open > .dropdown-toggle.btn-primary {
    background-color: $darkColor;
    border-color: $darkColor;
  }
</style>
