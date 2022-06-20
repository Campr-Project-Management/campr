<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-2">
            <h1>{{ translate('message.project_create_wizard') }}</h1>
            <h2>{{ translate('message.project_create_step2') }}</h2>

            <VideoLink module="setup" style="margin-left: auto; margin-right: auto; display: block; margin-bottom: 20px;" />

            <project-duration-slider v-model="projectDuration"/>
            <project-budget-slider
                    v-model="projectBudget"
                    :currency="currencySymbol"/>
            <project-members-slider v-model="projectMembers"/>
            <project-strategical-meaning-slider v-model="projectStrategicalMeaning"/>
            <project-innovation-degree-slider v-model="projectInnovationDegree"/>
            <project-technology-complexity-slider v-model="projectTechnologyComplexity"/>

            <div class="dropdowns">
                <select-field
                        :title="translate('message.category')"
                        :options="projectCategoriesForSelect"
                        v-model="selectedCategory">
                </select-field>
                <select-field
                        :title="translate('message.scope')"
                        :options="projectScopesForSelect"
                        v-model="selectedScope">
                </select-field>
            </div>

            <div class="flex flex-space-between actions">
                <a href="#" @click="previousStep" class="btn-rounded"
                   :title="translate('button.previous_step')">
                    < {{ translate('button.previous_step') }}
                </a>

                <a href="#" @click="nextStep" class="btn-rounded second-bg"
                   :title="translate('button.analyze')">
                    {{ translate('button.analyze') }} >
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectField from '../_common/_form-components/SelectField';
    import RangeSlider from '../_common/_form-components/RangeSlider';
    import VideoLink from '../_common/VideoLink';
    import {mapActions, mapGetters} from 'vuex';
    import ProjectDurationSlider from './ProjectCreate/ProjectDurationSlider';
    import ProjectBudgetSlider from './ProjectCreate/ProjectBudgetSlider';
    import ProjectStrategicalMeaningSlider from './ProjectCreate/ProjectStrategicalMeaningSlider';
    import ProjectInnovationDegreeSlider from './ProjectCreate/ProjectInnovationDegreeSlider';
    import ProjectTechnologyComplexitySlider from './ProjectCreate/ProjectTechnologyComplexitySlider';
    import ProjectMembersSlider from './ProjectCreate/ProjectMembersSlider';

    export default {
        components: {
            VideoLink,
            ProjectMembersSlider,
            ProjectTechnologyComplexitySlider,
            ProjectInnovationDegreeSlider,
            ProjectStrategicalMeaningSlider,
            ProjectBudgetSlider,
            ProjectDurationSlider,
            SelectField,
            RangeSlider,
        },
        methods: {
            ...mapActions([
                'getProjectCategories',
                'getProjectScopes',
                'getCurrencies',
                'setProjectCreateWizardStep2',
            ]),
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
                const data = {
                    projectDuration: this.projectDuration,
                    projectBudget: this.projectBudget,
                    projectMembers: this.projectMembers,
                    projectStrategicalMeaning: this.projectStrategicalMeaning,
                    projectInnovationDegree: this.projectInnovationDegree,
                    projectTechnologyComplexity: this.projectTechnologyComplexity,
                    selectedCategory: this.selectedCategory,
                    selectedScope: this.selectedScope,
                };
                this.setProjectCreateWizardStep2(data);
            },
            init() {
                let stepData = this.projectCreateWizardStep2;

                this.projectDuration = stepData.projectDuration > 0 ? stepData.projectDuration : 1;
                this.projectBudget = stepData.projectBudget >= 10000 ? stepData.projectBudget : 10000;
                this.projectMembers = stepData.projectMembers >= 3 ? stepData.projectMembers : 3;
                this.projectStrategicalMeaning = Number(
                    stepData.projectStrategicalMeaning ? stepData.projectStrategicalMeaning : 0);
                this.projectInnovationDegree = Number(
                    stepData.projectInnovationDegree ? stepData.projectInnovationDegree : 0);
                this.projectTechnologyComplexity = Number(
                    stepData.projectTechnologyComplexity ? stepData.projectTechnologyComplexity : 0);
            },
        },
        computed: {
            ...mapGetters([
                'localUser',
                'currencyById',
                'currencies',
                'projectCreateWizardStep1',
                'projectCreateWizardStep2',
                'projectCategoriesForSelect',
                'projectScopesForSelect',
            ]),
            currencySymbol() {
                const stepData = this.projectCreateWizardStep1;
                if (!stepData) {
                    return '';
                }

                let id = stepData.selectedCurrency && stepData.selectedCurrency.key;
                if (!id) {
                    return '';
                }

                let currency = this.currencyById(id);
                if (!currency) {
                    return '';
                }

                return currency.symbol;
            },
        },
        created() {
            this.getProjectCategories();
            this.getProjectScopes();

            if (this.currencies.length === 0) {
                this.getCurrencies();
            }

            this.init();
        },
        data() {
            return {
                projectDuration: 1,
                projectBudget: 10000,
                projectMembers: 3,
                projectStrategicalMeaning: 0,
                projectInnovationDegree: 0,
                projectTechnologyComplexity: 0,
                selectedCategory: null,
                selectedScope: null,
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
        fill: none;
        stroke: #666FA1;
        stroke-miterlimit: 10;
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

    .actions {
        .btn-rounded {
            margin-left: 0;
        }
    }
</style>
