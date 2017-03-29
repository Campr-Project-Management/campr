<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-2">
            <h1>{{ message.project_create_wizard }}</h1>
            <h2>{{ message.project_create_step2 }}</h2>

            <range-slider v-bind:title="message.project_duration" min="0" max="10" minSuffix=" Months" type="single" v-model="projectDuration"></range-slider>
            <range-slider v-bind:title="message.project_budget" min="0" max="30000" minPrefix="$" type="single"></range-slider>
            <range-slider v-bind:title="message.project_involved" min="0" max="20" type="double"></range-slider>
            <range-slider v-bind:title="message.departments_involved" min="0" max="20" type="double"></range-slider>

            <range-slider v-bind:title="message.strategical_meaning" min="few" values="few, medium, high" type="single"></range-slider>
            <range-slider v-bind:title="message.risks" min="few" values="few, medium, high" type="single"></range-slider>

            <div class="dropdowns">
                <select-field v-bind:title="message.category" v-bind:options="category"></select-field>
                <select-field v-bind:title="message.scope" v-bind:options="scope"></select-field>
            </div>

            <div class="flex flex-space-between actions">
                <router-link :to="{name: 'projects-create-1'}" v-bind:title="button.previous_step" class="btn-rounded">
                    < {{ button.previous_step }}
                </router-link>
                <router-link :to="{name: 'projects-create-3'}" v-bind:title="button.analyze" class="btn-rounded second-bg">
                    {{ button.analyze }}
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
            message: {
                project_create_wizard: Translator.trans('message.project_create_wizard'),
                project_create_step2: Translator.trans('message.project_create_step2'),
                project_duration: Translator.trans('message.project_duration'),
                project_budget: Translator.trans('message.project_budget'),
                project_involved: Translator.trans('message.project_involved'),
                departments_involved: Translator.trans('message.departments_involved'),
                strategical_meaning: Translator.trans('message.strategical_meaning'),
                risks: Translator.trans('message.risks'),
                category: Translator.trans('message.category'),
                scope: Translator.trans('message.scope'),
            },
            button: {
                previous_step: Translator.trans('button.previous_step'),
                analyze: Translator.trans('button.analyze'),
            },
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
