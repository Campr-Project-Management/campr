<template>
    <div class="slider-holder">
        <div class="heading flex flex-space-between">
            <span class="title">{{ title }}</span>
            <span class="value">
                <span class="text">{{ minPrefix}}</span><span class="from number">{{ from }}</span><span class="text">{{ minSuffix }}</span>
                <span v-show="type == 'double'">
                - <span class="text">{{ maxPrefix }}</span><span class="to number">{{ to }}</span><span class="text">{{ maxSuffix }}</span>
                </span>
            </span>
        </div>

        <input type="text" class="range" v-bind:id="'slider' + _uid" ref="slider" />
    </div>
</template>

<script>
import 'ion-rangeslider/js/ion.rangeSlider.js';
import 'ion-rangeslider/css/ion.rangeSlider.css';
import 'ion-rangeslider/css/ion.rangeSlider.skinHTML5.css';

export default {
    props: ['title', 'min', 'max', 'type', 'minPrefix', 'minSuffix', 'maxPrefix', 'maxSuffix', 'values', 'value'],
    computed: {
        from: function() {
            if (!this.value) {
                return this.min;
            }
            const values = this.value.split(';');
            return values[0];
        },
        to: function() {
            if (!this.value) {
                return this.from;
            }
            const values = this.value.split(';');
            return values.length > 1 ? values[1] : this.max;
        },
    },
    mounted() {
        const $this = window.$('#slider' + this._uid);
        const values = this.values ? this.values.split(',') : '';
        const vm = this;

        $this.ionRangeSlider({
            type: this.type,
            min: this.min,
            max: this.max,
            from: isNaN(parseFloat(this.from, 10)) ? values.indexOf(this.from) : this.from,
            to: isNaN(parseFloat(this.to, 10)) ? values.indexOf(this.to) : this.to,
            values: values,
        });

        $this.on('change', function(e) {
            vm.updateValue(e.target.value);
        });
    },
    methods: {
        updateValue: function(value) {
            this.$emit('input', value);
        },
    },
};
</script>

<style lang="scss">
    @import '../../../css/_variables.scss';

    .irs-min, .irs-max, .irs-from, .irs-to, .irs-single {
        display: none !important;
        visibility: hidden !important;
    }

    .irs-line {
        background: $darkColor !important;
        border: none !important;
    }

    .irs-bar {
        background: $middleColor !important;
        border: none !important;
    }

    .irs-bar-edge {
        background: $middleColor !important;
        border: none !important;
    }

    .irs-slider {
        font-size: 0 !important;
        background: $secondColor !important;
        border: 2px solid $secondDarkColor !important;
    }

    .task-sidebar {
        .irs-line {
            background: $middleColor !important;
            border: none !important;
        }

        .irs-bar,
        .irs-bar-edge {
            background: $secondColor !important;
            border: none !important;
        }
    }
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../../css/_variables.scss';

  .slider-holder {
      text-transform: uppercase;
      color: $lightColor;
      position: relative;
      margin-bottom: 35px;

      .heading {
          position: absolute;
          width: 100%;
      }

      .title {
          letter-spacing: 1.9px;
      }

      .value {
          letter-spacing: 1.6px;
      }

      .number {
          color: $secondColor;
      }

      .slider {
          margin-top: 9px;
          width: 100%;
          height: 11px;
          padding: 0;
      }

      .range-slider-rail, .range-slider-fill {
          height: 10px;
          border-radius: 5px;
      }

      .range-slider-rail {
          background: $darkColor;
      }

      .range-slider-fill {
          background: $middleColor;
      }

      .range-slider-knob {
          background: $secondColor;
          border: 2px solid $secondDarkColor;
      }
  }
</style>
