<template>
    <div class="row">        
        <div class="col-lg-5 col-lg-push-7">
            <div class="ro-grid-wrapper clearfix">
                <risk-matrix
                        :impact="riskImpact"
                        :probability="riskProbability"
                        v-model="priority"/>
            </div>
        </div>
        <div class="col-lg-7 col-lg-pull-5">
            <div class="create-phase page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_risks_and_opportunities') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translate('message.create_new_risk') }}</h1>
                        <h1 v-else>{{ translate('message.edit_risk') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Risk Name /// -->
                    <input-field type="text" :label="translate('placeholder.risk_title')" v-model="title" :content="title" />
                    <error
                        v-if="validationMessages.title && validationMessages.title.length"
                        v-for="message in validationMessages.title"
                        :message="message" />
                    <!-- /// End Risk /// -->

                    <!-- /// Risk Description /// -->
                    <editor
                        id="risk-description"
                        v-model="description"
                        :label="'placeholder.risk_description'"/>
                    <error
                        v-if="validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <!-- /// End Risk Description /// -->

                    <hr class="double">

                    <!-- /// Risk Impact /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                                :title="translate('message.impact')"
                                minSuffix=" %"
                                :step="5"
                                v-model="riskImpact"/>
                        <div class="slider-indicator" v-if="risksOpportunitiesStats.risks">
                            <indicator-icon fill="middle-fill"
                                            v-if="risksOpportunitiesStats.risks.risk_data.averageData.averageImpact"
                                            :position="risksOpportunitiesStats.risks.risk_data.averageData.averageImpact"
                                            :title="translate('message.average_impact_risk')"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Risk Impact /// -->

                    <!-- /// Risk Probability /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                                :title="translate('message.probability')"
                                minSuffix=" %"
                                :step="5"
                                v-model="riskProbability"/>
                        <div class="slider-indicator" v-if="risksOpportunitiesStats.risks">
                            <indicator-icon fill="middle-fill"
                                            v-if="risksOpportunitiesStats.risks.risk_data.averageData.averageProbability"
                                            :position="risksOpportunitiesStats.risks.risk_data.averageData.averageProbability"
                                            :title="translate('message.average_probability_risk')"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Risk Probability /// -->

                    <hr class="double">

                    <!-- /// Risk Details  /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <money-field
                                        v-model.number="cost"
                                        :label="translate('placeholder.potential_cost')"
                                        :currency="projectCurrencySymbol"/>
                                <error
                                    v-if="validationMessages.cost && validationMessages.cost.length"
                                    v-for="message in validationMessages.cost"
                                    :message="message" />
                            </div>
                            <div class="col-md-4">
                                <input-field
                                    class="time-delay"
                                    type="number"
                                    v-bind:css="{marginBottom: 0}"
                                    v-model.number="timeDelay"
                                    :content="timeDelay"
                                    :label="translate('placeholder.potential_time_delay')" />
                                <error
                                    v-if="validationMessages.delay && validationMessages.delay.length"
                                    v-for="message in validationMessages.delay"
                                    :message="message" />
                            </div>
                            <div class="col-md-4">
                                <select-field
                                    :title="translate('label.time')"
                                    :options="timeLabel"
                                    v-model="details.time"
                                    :currentOption="details.time" />
                                <error
                                    v-if="validationMessages.delayUnit && validationMessages.delayUnit.length"
                                    v-for="message in validationMessages.delayUnit"
                                    :message="message" />
                            </div>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <h4 class="light-color">
                                    {{ translate('message.budget' )}}:
                                    <b>{{ potentialCost | money({symbol: projectCurrencySymbol}) }}</b>
                                    <button type="button" class="btn btn-icon"
                                            v-tooltip.right-start="translate('message.budget_calculation_risk')">
                                        <tooltip-icon fill="light-fill"></tooltip-icon>
                                    </button>
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="light-color">
                                    {{ translate('message.delay') }}:
                                    <b>{{ potentialDelay | formatNumber }} {{ timeUnit }}</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="translate('message.time_calculation_risk')">
                                        <tooltip-icon fill="light-fill"></tooltip-icon>
                                    </button>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <select-field 
                                    :title="translate('placeholder.risk_strategy')"
                                    :options="riskStrategiesForSelect"
                                    v-model="details.strategy"
                                    :currentOption="details.strategy" />
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder">
                                    <label class="active">{{ translate('label.due_date') }}</label>
                                    <date-field v-model="schedule.dueDate"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select-field 
                                    :title="translate('placeholder.risk_status')"
                                    :options="riskStatusesForSelect"
                                    v-model="details.status"
                                    :currentOption="details.status" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-12 member-search-container">
                                <member-search singleSelect="false" v-model="memberList" :placeholder="translate('placeholder.responsible')"></member-search>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Risk Details /// -->

                    <hr class="double">

                    <!-- /// Risk Measure /// -->
                    <div class="row" v-for="(measure, index) in measures">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input-field type="text" :label="translate('placeholder.measure_title')" v-model="measure.title" :content="measure.title" />
                                <span v-if="validationMessages.measures && validationMessages.measures[index]">
                                    <error
                                        v-if="validationMessages.measures[index].title.length"
                                        v-for="message in validationMessages.measures[index].title"
                                        :message="message" />
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <editor
                                    class="measure-vueditor-holder"
                                    :id="`measure-${index}`"
                                    v-model="measure.description"
                                    :label="'placeholder.new_measure'"/>
                                <span v-if="validationMessages.measures && validationMessages.measures[index]">
                                    <error
                                        v-if="validationMessages.measures[index].description.length"
                                        v-for="message in validationMessages.measures[index].description"
                                        :message="message" />
                                </span>
                            </div>
                        </div>
                        <div class="form-group last-form-group">
                            <div class="flex flex-space-between">
                                <div class="col-md-4">
                                    <money-field
                                            :currency="projectCurrencySymbol"
                                            :label="translate('placeholder.measure_cost')"
                                            v-model="measure.cost"/>
                                    <span v-if="validationMessages.measures && validationMessages.measures[index]">
                                        <error
                                            v-if="validationMessages.measures[index].cost.length"
                                            v-for="message in validationMessages.measures[index].cost"
                                            :message="message" />
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr class="double">
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group btn-row">
                            <div class="col-md-12 text-right">
                                <a @click="addMeasure()" class="btn-rounded btn-auto">{{ translate('button.add_new_measure') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Risk Measure /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="btn-rounded btn-auto disable-bg">{{ translate('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="saveRisk()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
                        <a v-if="isEdit" @click="editRisk()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import InputField from '../../_common/_form-components/InputField';
import MoneyField from '../../_common/_form-components/MoneyField';
import RangeSlider from '../../_common/_form-components/RangeSlider';
import TooltipIcon from '../../_common/_icons/TooltipQuestionMark';
import IndicatorIcon from '../../_common/_icons/IndicatorIcon';
import MemberSearch from '../../_common/MemberSearch';
import SelectField from '../../_common/_form-components/SelectField';
import moment from 'moment';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';
import RiskMatrix from '../RiskManagement/RiskMatrix';
import DateField from '../../_common/_form-components/DateField';

export default {
    components: {
        DateField,
        RiskMatrix,
        InputField,
        RangeSlider,
        TooltipIcon,
        IndicatorIcon,
        MemberSearch,
        SelectField,
        moment,
        Error,
        Editor,
        MoneyField,
    },
    methods: {
        ...mapActions([
            'getProjectRiskAndOpportunitiesStats', 'getRiskStrategies', 'getRiskStatuses',
            'createProjectRisk', 'getProjectRisk', 'editProjectRisk', 'emptyValidationMessages',
        ]),
        addMeasure: function() {
            let measure = {
                title: '',
                description: '',
                cost: '',
                responsibility: this.memberList.length > 0 ? this.memberList[0] : null,
            };

            this.measures.push(measure);
        },
        getFormData: function() {
            let measures = this.measures.map((item) => ({
                cost: item.cost,
                description: item.description,
                responsibility: this.memberList.length > 0 ? this.memberList[0] : null,
                title: item.title,
            }));

            return {
                title: this.title,
                description: this.description,
                impact: this.riskImpact,
                probability: this.riskProbability,
                cost: this.cost,
                currency: this.details.currency && this.details.currency.key ? this.details.currency.key : '',
                delay: this.timeDelay,
                delayUnit: this.details.time && this.details.time.key ? this.details.time.key : '',
                priority: this.priority,
                riskStrategy: this.details.strategy ? this.details.strategy.key : null,
                riskStatus: this.details.status ? this.details.status.key : null,
                dueDate: moment(this.schedule.dueDate).format('DD-MM-YYYY'),
                responsibility: this.memberList.length > 0 ? this.memberList[0] : null,
                measures,
            };
        },
        saveRisk: function() {
            let data = this.getFormData();
            data.project = this.$route.params.id;
            this.createProjectRisk(data);
        },
        editRisk: function() {
            let data = this.getFormData();
            data.id = this.$route.params.riskId;
            this.editProjectRisk(data);
        },
    },
    computed: {
        ...mapGetters({
            risksOpportunitiesStats: 'risksOpportunitiesStats',
            riskStrategiesForSelect: 'riskStrategiesForSelect',
            riskStatusesForSelect: 'riskStatusesForSelect',
            risk: 'currentRisk',
            validationMessages: 'validationMessages',
            projectCurrencySymbol: 'projectCurrencySymbol',
        }),
        potentialCost: function() {
            let probability = _.toFinite(this.riskProbability) / 100;
            let cost = _.toFinite(this.cost);

            return (probability * cost).toFixed(2);
        },
        timeUnit() {
            return this.details.time && this.details.time.key ? this.details.time.label : null;
        },
        potentialDelay() {
            let probability = _.toFinite(this.riskProbability) / 100;
            let delay = _.toFinite(this.timeDelay);

            return (delay * probability).toFixed(2);
        },
    },
    created() {
        this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        this.getRiskStrategies(this.$route.params.id);
        this.getRiskStatuses(this.$route.params.id);
        if (this.$route.params.riskId) {
            this.getProjectRisk(this.$route.params.riskId);
        }

        this.$on('changeRangeSliderValue', valueObj => {
            this[valueObj.modelName] = valueObj.value;
        });
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    data: function() {
        return {
            priority: null,
            title: '',
            description: '',
            cost: 0,
            timeDelay: 0,
            days: '',
            measureCost: '',
            schedule: {
                dueDate: new Date(),
            },
            details: {
                currency: null,
                time: null,
                strategy: null,
                status: null,
            },
            memberList: [],
            measures: [],
            riskImpact: 0,
            riskProbability: 0,
            timeLabel: [
                {label: this.translate('choices.hours'), key: 'choices.hours'},
                {label: this.translate('choices.days'), key: 'choices.days'},
                {label: this.translate('choices.weeks'), key: 'choices.weeks'},
                {label: this.translate('choices.months'), key: 'choices.months'},
            ],
            model: {},
            currentOption: {},
            isEdit: this.$route.params.riskId,
            activeItem: null,
        };
    },
    watch: {
        risk(value) {
            this.title = this.risk.title;
            this.description = this.risk.description;
            this.riskImpact = this.risk.impact;
            this.riskProbability = this.risk.probability;
            this.cost = this.risk.cost;
            this.timeDelay = this.risk.delay;
            this.schedule.dueDate = this.risk.dueDate ? moment(this.risk.dueDate).toDate() : null;
            this.details.time = this.risk.delayUnit
                ? {key: this.risk.delayUnit, label: this.translate(this.risk.delayUnit)}
                : null
            ;
            this.details.strategy = this.risk.riskStrategy
                ? {key: this.risk.riskStrategy, label: this.risk.riskStrategyName}
                : null
            ;
            this.details.status = this.risk.status
                ? {key: this.risk.status, label: this.risk.statusName}
                : null
            ;
            this.memberList.push(this.risk.responsibility);
            if (this.risk.measures.length > 0) {
                this.measures = this.risk.measures;
            } else {
                this.measures = [];
            }
        },
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/risks-and-opportunities/view';

    .ro-grid-wrapper {
        margin-top: 90px;

        @media (max-width: 1199px) {
            margin-top: 30px;
        }
    }

    .member-search-container {
        padding-top: 20px;
        padding-bottom: 25px;
    }

    .btn-row {
        padding-bottom: 15px;
    }
    
    .time-delay {
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
