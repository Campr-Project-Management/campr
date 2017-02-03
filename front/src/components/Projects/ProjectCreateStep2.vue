<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-2">
            <h1>Project Creation Wizard</h1>
            <h2>Step 2: Project Worthiness Analysis</h2>

            <range-slider title="Project Duration" min="0" max="10" minSuffix=" Months" type="single" v-model="projectDuration"></range-slider>
            <range-slider title="Project Budget" min="0" max="30000" minPrefix="$" type="single"></range-slider>
            <range-slider title="People Involved" min="0" max="20" type="double"></range-slider>
            <range-slider title="Departments Involved" min="0" max="20" type="double"></range-slider>

            <range-slider title="Strategical Meaning" min="few" values="few, medium, high" type="single"></range-slider>
            <range-slider title="Risks" min="few" values="few, medium, high" type="single"></range-slider>

            <div class="dropdowns">
                <select-field title="Category" v-bind:options="category"></select-field>
                <select-field title="Scope" v-bind:options="scope"></select-field>
            </div>

            <div class="flex flex-space-between actions">
                <router-link :to="{name: 'projects-create-1'}" title="Previous Step" class="btn-rounded">
                    < Previous Step
                </router-link>
                <router-link :to="{name: 'projects-create-3'}" title="Analyse" class="btn-rounded second-bg">
                    Analyse
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import SelectField from '../_common/_form-components/SelectField';
import RangeSlider from '../_common/_form-components/RangeSlider';

export default {
    components: {
        SelectField,
        RangeSlider,
    },
    data: function() {
        return {
            projectDuration: 0,
        };
    },
};
</script>

<style lang="scss">
    @import '../../css/project-create';
</style>

<style lang="scss">
@import '../../css/_common';

    input[type=text] {
        margin-bottom: 0 !important;
    }

    #app.bg{
        background: url('../../assets/bg-1.jpg');
        background-size: cover;

        .page {
            background: none !important;
        }
    }

    .slider-holder {
        text-transform: uppercase;

        .title {
            letter-spacing: 1.9px;
        }

        .value {
            letter-spacing: 1.6px;
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
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common';
  @import '../../css/project-create';

  .avatar {
      margin: 28px auto;
      display: block;
  }

  .st0 {
      fill:none;
      stroke:#666FA1;
      stroke-miterlimit:10;
  }

  .part-of {
      display: none;
  }

  .input-holder {
      margin-top: 30px;
  }

  .btn-empty {
      margin: 0 auto 30px;
      width: 140px;
      font-size: 9px;
  }

  .btn-rounded {
      padding: 0;
  }

  .checkbox-input {
      margin-bottom: 23px;
  }

  .dropdown {
      margin-bottom: 30px;
  }

  .btn-right {
      margin: 10px 0 30px;
  }

  .dropdowns {
      padding-top: 30px;
  }
</style>
