<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-2">
            <h1>{{ translateText('message.project_create_wizard') }}</h1>
            <h2>{{ translateText('message.project_create_step2') }}</h2>

            <range-slider
                v-bind:title="translateText('message.project_duration')"
                min="0"
                v-bind:max="durationMaxValue"
                minSuffix="Months"
                type="single"
                :model="projectDuration"
                :modelName="'projectDuration'"
                @onRangeSliderUpdate="updateSlider"/>
            <range-slider
                v-bind:title="translateText('message.project_budget')"
                min="0"
                v-bind:max="budgetMaxValue"
                v-bind:values="budgetValues"
                minPrefix="€"
                type="single"
                :model="projectBudget"
                :modelName="'projectBudget'"
                @onRangeSliderUpdate="updateSlider"/>
            <range-slider
                v-bind:title="translateText('message.team_members_involved')"
                min="1"
                max="20"
                type="double"
                :model="projectInvolved"
                :modelName="'projectInvolved'"
                @onRangeSliderUpdate="updateSlider"/>
            <range-slider
                v-bind:title="translateText('message.departments_involved')"
                min="1"
                max="20"
                type="double"
                :model="departmentsInvolved"
                :modelName="'departmentsInvolved'"
                @onRangeSliderUpdate="updateSlider"/>
            <range-slider
                v-bind:title="translateText('message.strategical_meaning')"
                min="few"
                values="few,medium,high"
                type="single"
                :model="strategicalMeaning"
                :modelName="'strategicalMeaning'"
                @onRangeSliderUpdate="updateSlider"/>
            <range-slider
                v-bind:title="translateText('message.risks')"
                min="few"
                values="few,medium,high"
                type="single"
                :model="risks"
                :modelName="'risks'"
                @onRangeSliderUpdate="updateSlider"/>

            <div class="dropdowns">
                <select-field
                    v-bind:title="translateText('message.category')"
                    v-bind:options="projectCategories" 
                    v-model="selectedCategory"
                    v-bind:currentOption="selectedCategory">
                </select-field>
                <select-field
                    v-bind:title="translateText('message.scope')"
                    v-bind:options="projectScopes"
                    v-model="selectedScope"
                    v-bind:currentOption="selectedScope">
                </select-field>
            </div>

            <div class="flex flex-space-between actions">
                <a href="#" v-on:click="previousStep" class="btn-rounded" v-bind:title="translateText('button.previous_step')">
                    < {{ translateText('button.previous_step') }}
                </a>
                <a href="#" v-on:click="nextStep" class="btn-rounded second-bg" v-bind:title="translateText('button.analyze')">
                    {{ translateText('button.analyze') }} >
                </a>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectField from '../_common/_form-components/SelectField';
import RangeSlider from '../_common/_form-components/RangeSlider';
import {mapActions, mapGetters} from 'vuex';
import {FIRST_STEP_LOCALSTORAGE_KEY, SECOND_STEP_LOCALSTORAGE_KEY} from '../../helpers/project';

export default {
    components: {
        SelectField,
        RangeSlider,
    },
    methods: {
        ...mapActions(['getProjectCategories', 'getProjectScopes']),
        translateText(text) {
            return this.translate(text);
        },
        nextStep: function(e) {
            e.preventDefault();
            this.saveStepState();
            this.$router.push({name: 'projects-create-3'});
        },
        previousStep: function(e) {
            e.preventDefault();
            this.saveStepState();
            this.$router.push({name: 'projects-create-1'});
        },
        saveStepState: function() {
            const stepData = {
                projectDuration: this.projectDuration,
                projectBudget: this.projectBudget,
                projectInvolved: this.projectInvolved,
                departmentsInvolved: this.departmentsInvolved,
                selectedCategory: this.selectedCategory,
                selectedScope: this.selectedScope,
                strategicalMeaning: this.strategicalMeaning,
                risks: this.risks,
            };
            localStorage.setItem(SECOND_STEP_LOCALSTORAGE_KEY, JSON.stringify(stepData));
        },
        projectHasProgrammeAndPortofolio: function() {
            const step1Data = JSON.parse(localStorage.getItem(FIRST_STEP_LOCALSTORAGE_KEY));
            if (
                step1Data.visiblePortfolio &&
                step1Data.selectedPortfolio.key &&
                step1Data.visibleProgramme &&
                step1Data.selectedProgramme.key !== undefined
            ) {
                return true;
            }
            return false;
        },
        updateSlider(sliderResult) {
            this[sliderResult.modelName] = sliderResult.value;
        },
    },
    computed: {
        ...mapGetters({
            projectCategories: 'projectCategoriesForSelect',
            projectCategoriesLoading: 'projectCategoriesLoading',
            projectScopes: 'projectScopesForSelect',
            projectScopesLoading: 'projectScopesLoading',
        }),
        durationMaxValue: function() {
            return this.projectHasProgrammeAndPortofolio() ? '36' : '12';
        },
        budgetMaxValue: function() {
            return this.projectHasProgrammeAndPortofolio() ? '2000000' : '500000';
        },
        budgetValues: function() {
            let result = '';
            for (let i=0; i<20; i++) {
                result += i * 10000 + ',';
            }
            if(!this.projectHasProgrammeAndPortofolio()) {
                for (let i=0; i<6; i++) {
                    result += 200000 + (i * 50000) + ',';
                }
                result +='500000';
                return result;
            }

            for (let i=0; i<16; i++) {
                result += 200000 + (i * 50000) + ',';
            }
            for (let i=0; i<10; i++) {
                result += 1000000 + (i * 100000) + ',';
            }
            result +='2000000';

            return result;
        },
    },
    created() {
        this.getProjectCategories();
        this.getProjectScopes();
    },
    data: function() {
        const stepData = JSON.parse(localStorage.getItem(SECOND_STEP_LOCALSTORAGE_KEY));

        return {
            projectDuration: stepData ? stepData.projectDuration : 0,
            projectBudget: stepData ? stepData.projectBudget : 0,
            projectInvolved: stepData ? stepData.projectInvolved : 0,
            departmentsInvolved: stepData ? stepData.departmentsInvolved : 0,
            strategicalMeaning: stepData ? stepData.strategicalMeaning : '',
            risks: stepData ? stepData.risks : '',
            selectedCategory: stepData ? stepData.selectedCategory : '',
            selectedScope: stepData ? stepData.selectedScope : '',
        };
    },
};
</script>

<style lang="scss">
    @import '../../css/project-create';
    @import '../../css/_common';

    input[type=text] {
        margin-bottom: 0 !important;
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
