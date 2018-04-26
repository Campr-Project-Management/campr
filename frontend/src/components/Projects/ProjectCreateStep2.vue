<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-2">
            <h1>{{ translateText('message.project_create_wizard') }}</h1>
            <h2>{{ translateText('message.project_create_step2') }}</h2>

            <range-slider
                    :title="translateText('message.project_duration')"
                    :max="durationMaxValue"
                    minSuffix="Months"
                    v-model="projectDuration"/>
            <range-slider
                    :title="translateText('message.project_budget')"
                    :max="budgetMaxValue"
                    :values="budgetValues"
                    :min-prefix="currencySymbol"
                    v-model="projectBudget"/>
            <range-slider
                    :title="translateText('message.team_members_involved')"
                    :min="1"
                    :max="20"
                    type="double"
                    v-model="projectInvolved"/>
            <range-slider
                    :title="translateText('message.departments_involved')"
                    :min="1"
                    :max="20"
                    type="double"
                    v-model="departmentsInvolved"/>
            <range-slider
                    :title="translateText('message.strategical_meaning')"
                    min="none"
                    :values="['none', 'few', 'medium', 'high']"
                    v-model="strategicalMeaning"/>
            <range-slider
                    :title="translateText('message.risks')"
                    min="few"
                    :values="['none', 'few', 'medium', 'high']"
                    v-model="risks"/>

            <div class="dropdowns">
                <select-field
                        :title="translateText('message.category')"
                        :options="projectCategories"
                        v-model="selectedCategory">
                </select-field>
                <select-field
                        :title="translateText('message.scope')"
                        :options="projectScopes"
                        v-model="selectedScope">
                </select-field>
            </div>

            <div class="flex flex-space-between actions">
                <a href="#" @click="previousStep" class="btn-rounded"
                   :title="translateText('button.previous_step')">
                    < {{ translateText('button.previous_step') }}
                </a>
                <a href="#" @click="nextStep" class="btn-rounded second-bg"
                   :title="translateText('button.analyze')">
                    {{ translateText('button.analyze') }} >
                </a>
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
            ...mapActions([
                'getProjectCategories',
                'getProjectScopes',
                'getCurrencies',
            ]),
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
            getFirstStepData() {
                return JSON.parse(localStorage.getItem(FIRST_STEP_LOCALSTORAGE_KEY));
            },
        },
        computed: {
            ...mapGetters({
                localUser: 'localUser',
                projectCategories: 'projectCategoriesForSelect',
                projectCategoriesLoading: 'projectCategoriesLoading',
                projectScopes: 'projectScopesForSelect',
                projectScopesLoading: 'projectScopesLoading',
                currencyById: 'currencyById',
                currencies: 'currencies',
            }),
            currencySymbol() {
                const stepData = this.getFirstStepData();
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
            durationMaxValue: function() {
                return this.projectHasProgrammeAndPortofolio() ? 36 : 12;
            },
            budgetMaxValue: function() {
                return this.projectHasProgrammeAndPortofolio() ? 2000000 : 500000;
            },
            budgetValues: function() {
                let values = [];
                for (let i = 0; i < 20; i++) {
                    values.push(i * 10000);
                }

                if (!this.projectHasProgrammeAndPortofolio()) {
                    for (let i = 0; i < 6; i++) {
                        values.push(200000 + (i * 50000));
                    }

                    values.push(500000);
                    return values;
                }

                for (let i = 0; i < 16; i++) {
                    values.push(200000 + (i * 50000));
                }
                for (let i = 0; i < 10; i++) {
                    values.push(1000000 + (i * 100000));
                }

                values.push(2000000);

                return values;
            },
        },
        created() {
            this.getProjectCategories();
            this.getProjectScopes();

            if (this.currencies.length === 0) {
                this.getCurrencies();
            }
        },
        data: function() {
            const stepData = JSON.parse(localStorage.getItem(SECOND_STEP_LOCALSTORAGE_KEY));

            return {
                projectDuration: stepData ? stepData.projectDuration : 0,
                projectBudget: stepData ? stepData.projectBudget : 0,
                projectInvolved: stepData ? stepData.projectInvolved : [0, 0],
                departmentsInvolved: stepData ? stepData.departmentsInvolved : [0, 0],
                strategicalMeaning: stepData ? stepData.strategicalMeaning : 'none',
                risks: stepData ? stepData.risks : 'none',
                selectedCategory: stepData ? stepData.selectedCategory : {},
                selectedScope: stepData ? stepData.selectedScope : {},
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
</style>
