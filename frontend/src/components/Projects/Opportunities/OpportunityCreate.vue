<template>
    <div class="row">        
        <div class="col-md-6 col-md-push-6">
            <!-- /// Project Opportunities /// -->
            <div class="ro-grid-wrapper clearfix">
                <!-- /// Project Opportunities Grid /// -->
                <div class="ro-grid">
                    <div class="ro-grid-header vertical-axis-header">
                        <div class="big-header">{{ translate('message.impact') }}</div>
                        <div class="small-headers clearfix">
                            <div class="small-header">{{ translate('message.very_low') }}</div>
                            <div class="small-header">{{ translate('message.low') }}</div>
                            <div class="small-header">{{ translate('message.high') }}</div>
                            <div class="small-header">{{ translate('message.very_high') }}</div>
                        </div>
                    </div> 
                    <div class="ro-grid-items clearfix">
                        <div v-for="item in gridData" class="ro-grid-item" :class="[{active: item.isActive}, item.type]"></div>
                    </div>
                    <div class="ro-grid-header horizontal-axis-header">                            
                        <div class="small-headers clearfix">
                            <div class="small-header">{{ translate('message.very_low') }}</div>
                            <div class="small-header">{{ translate('message.low') }}</div>
                            <div class="small-header">{{ translate('message.high') }}</div>
                            <div class="small-header">{{ translate('message.very_high') }}</div>
                        </div>
                        <div class="big-header">{{ translate('message.probability') }}</div>
                    </div>
                    <div class=""></div>
                </div>
                <!-- /// End Project Opportunities Grid /// -->
            </div>

            <!-- /// Project Risks Summary /// -->
            <div class="ro-summary">
                <div class="text-center flex flex-center">
                    <div class="text-right">
                        <p>{{ translate('message.priority') }}:</p>
                    </div>
                    <div class="text-left">
                        <p><b v-if="priority" :class="priority.color">{{ translate(priority.name) }}</b><b v-else>-</b></p>
                    </div>
                </div>
            </div>
            <!-- /// End Project Risks Summary /// -->
        </div>
        <div class="col-md-6 col-md-pull-6">
            <div class="create-phase page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_risks_and_opportunities') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translate('message.create_new_opportunity') }}</h1>
                        <h1 v-else>{{ translate('message.edit_opportunity') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Opportunity Name /// -->
                    <input-field type="text" :label="translate('placeholder.opportunity_title')" v-model="title" :content="title" />
                    <error
                        v-if="validationMessages.title && validationMessages.title.length"
                        v-for="message in validationMessages.title"
                        :message="message" />
                    <!-- /// End Opportunity Name /// -->

                    <!-- /// Opportunity Description /// -->

                    <editor
                        id="opportunity-description"
                        v-model="description"
                        :label="'placeholder.opportunity_description'"/>
                    <error
                        v-if="validationMessages.description && validationMessages.description.length"
                        v-for="message in validationMessages.description"
                        :message="message" />
                    <!-- /// End Opportunity Description /// -->

                    <hr class="double">

                    <!-- /// Opportunity Impact /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                                :title="translate('message.impact')"
                                minSuffix=" %"
                                :step="5"
                                v-model="opportunityImpact"/>
                        <div class="slider-indicator" v-if="risksOpportunitiesStats.opportunities">
                            <indicator-icon fill="middle-fill"
                                            v-if="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageImpact"
                                            :position="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageImpact"
                                            :title="translate('message.average_impact_opportunity')"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Opportunity Impact /// -->

                    <!-- /// Opportunity Probability /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                                :title="translate('message.probability')"
                                minSuffix=" %"
                                :step="5"
                                v-model="opportunityProbability"/>
                        <div class="slider-indicator" v-if="risksOpportunitiesStats.opportunities">
                            <indicator-icon fill="middle-fill"
                                            v-if="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageProbability"
                                            :position="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageProbability"
                                            :title="translate('message.average_probability_opportunity')"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Opportunity Probability /// -->

                    <hr class="double">

                    <!-- /// Opportunity Details  /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <money-field
                                        :currency="projectCurrencySymbol"
                                        :label="translate('placeholder.potential_savings')"
                                        v-model="costSavings" />
                            </div>
                            <div class="col-md-4">
                                <input-field
                                    type="text"
                                    :label="translate('placeholder.potential_time_savings')"
                                    v-model="timeSavings" :content="timeSavings"
                                />
                            </div>
                            <div class="col-md-4">
                                <select-field 
                                    :title="translate('label.time')"
                                    :options="timeLabel"
                                    v-model="details.time"
                                    :currentOption="details.time" />
                                <error
                                    v-if="validationMessages.timeUnit && validationMessages.timeUnit.length"
                                    v-for="message in validationMessages.timeUnit"
                                    :message="message" />
                            </div>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <h4 class="light-color">
                                    {{ translate('message.budget') }}: <b>{{ budget | money({symbol: projectCurrencySymbol}) }}</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="translate('message.budget_calculation_opportuntiy')">
                                        <tooltip-icon fill="light-fill"></tooltip-icon>
                                    </button>
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="light-color">
                                    {{ translate('message.time_saved') }}: <b>{{ timeSaved | formatNumber }} {{ timeSavedUnit }}</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="translate('message.time_calculation_opportunity')">
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
                                    :title="translate('label.opportunity_strategy')"
                                    :options="opportunityStrategiesForSelect"
                                    v-model="details.strategy"
                                    :currentOption="details.strategy" />
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder">
                                    <label class="active">{{ translate('label.due_date') }}</label>
                                    <datepicker v-model="schedule.dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select-field 
                                    :title="translate('label.opportunity_status')"
                                    :options="opportunityStatusesForSelect"
                                    v-model="details.status"
                                    :currentOption="details.status" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-12">
                                <member-search singleSelect="false" v-model="memberList" :placeholder="translate('placeholder.search_members')"></member-search>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Opportunity Details /// -->

                    <hr class="double">

                    <!-- /// Opportunity Measure /// -->
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
                                            v-model="measure.cost"
                                            :label="translate('placeholder.measure_cost')" />
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
                        <div class="form-group last-form-group">
                            <div class="col-md-12 text-right">
                                <a @click="addMeasure()" class="btn-rounded btn-auto">{{ translate('button.add_new_measure') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Opportunity Measure /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="btn-rounded btn-auto disable-bg">{{ translate('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="saveOpportunity()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
                        <a v-if="isEdit" @click="editOpportunity()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
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
import datepicker from '../../_common/_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import moment from 'moment';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';

export default {
    components: {
        InputField,
        RangeSlider,
        TooltipIcon,
        IndicatorIcon,
        MemberSearch,
        SelectField,
        datepicker,
        CalendarIcon,
        moment,
        Error,
        Editor,
        MoneyField,
    },
    methods: {
        ...mapActions([
            'getProjectRiskAndOpportunitiesStats',
            'getOpportunityStrategies',
            'getOpportunityStatuses',
            'createProjectOpportunity',
            'getProjectOpportunity',
            'editProjectOpportunity',
            'emptyValidationMessages',
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
                description: item.description,
                title: item.title,
                cost: item.cost,
                responsibility: this.memberList.length > 0 ? this.memberList[0] : null,
            }));

            return {
                title: this.title,
                description: this.description,
                impact: this.opportunityImpact,
                probability: this.opportunityProbability,
                costSavings: this.costSavings,
                timeSavings: this.timeSavings,
                timeUnit: this.details.time && this.details.time.key ? this.details.time.key : '',
                priority: this.priority ? this.priority.value : null,
                opportunityStrategy: this.details.strategy ? this.details.strategy.key : null,
                opportunityStatus: this.details.status ? this.details.status.key : null,
                dueDate: moment(this.schedule.dueDate).format('DD-MM-YYYY'),
                responsibility: this.memberList.length > 0 ? this.memberList[0] : null,
                measures,
            };
        },
        saveOpportunity: function() {
            let data = this.getFormData();
            data.project = this.$route.params.id;
            this.createProjectOpportunity(data);
        },
        editOpportunity: function() {
            let data = this.getFormData();
            data.id = this.$route.params.opportunityId;
            this.editProjectOpportunity(data);
        },
        updateGridView() {
            let index = 0;
            const riskImpact = this.opportunityImpact;
            const riskProbability = this.opportunityProbability;

            if (riskImpact < 25 || !riskImpact) {
                index += 12;
            }
            if (riskImpact >= 25 && riskImpact < 50) {
                index += 8;
            }
            if (riskImpact >= 50 && riskImpact < 75) {
                index += 4;
            }
            if (riskImpact >= 75) {
                index += 0;
            }

            if (riskProbability < 25 || !riskProbability) {
                index += 0;
            }
            if (riskProbability >= 25 && riskProbability < 50) {
                index += 1;
            }
            if (riskProbability >= 50 && riskProbability < 75) {
                index += 2;
            }
            if (riskProbability >= 75) {
                index += 3;
            }

            if (this.activeItem) {
                this.activeItem.isActive = false;
            }

            this.activeItem = this.gridData[index];
            this.activeItem.isActive = true;
            this.setPriority(this.gridData[index].type);
        },
        setPriority: function(type) {
            const priorityNames = {
                'very-low': {name: 'message.very_high', color: 'ro-very-low-priority', value: 0},
                'low': {name: 'message.high', color: 'ro-low-priority', value: 1},
                'medium': {name: 'message.medium', color: 'ro-medium-priority', value: 2},
                'high': {name: 'message.low', color: 'ro-high-priority', value: 3},
                'very-high': {name: 'message.very_low', color: 'ro-very-high-priority', value: 4},
            };

            this.priority = priorityNames[type];
        },
    },
    computed: {
        ...mapGetters([
            'risksOpportunitiesStats',
            'opportunityStrategiesForSelect',
            'opportunityStatusesForSelect',
            'validationMessages',
            'projectCurrencySymbol',
        ]),
        ...mapGetters({
            opportunity: 'currentOpportunity',
        }),
        budget() {
            let probability = _.toInteger(this.opportunityProbability) / 100;
            let costSavings = _.toFinite(this.costSavings);

            return (costSavings * probability).toFixed(2);
        },
        timeSaved() {
            // let unit = this.details.time && this.details.time.key ? this.details.time.label : '';
            let probability = _.toInteger(this.opportunityProbability) / 100;
            let timeSavings = _.toFinite(this.timeSavings);

            return (timeSavings * probability).toFixed(2);
        },
        timeSavedUnit() {
            return this.details.time && this.details.time.key ? this.details.time.label : '';
        },
    },
    created() {
        this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        this.getOpportunityStrategies(this.$route.params.id);
        this.getOpportunityStatuses(this.$route.params.id);
        if (this.$route.params.opportunityId) {
            this.getProjectOpportunity(this.$route.params.opportunityId);
        }

        this.$on('changeRangeSliderValue', valueObj => {
            this[valueObj.modelName] = valueObj.value;
        });
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
    watch: {
        opportunityImpact() {
            this.updateGridView();
        },
        opportunityProbability(value) {
            this.updateGridView();
        },
        opportunity(value) {
            this.title = this.opportunity.title;
            this.description = this.opportunity.description;
            this.opportunityImpact = this.opportunity.impact;
            this.opportunityProbability = this.opportunity.probability;
            this.costSavings = this.opportunity.costSavings;
            this.timeSavings = this.opportunity.timeSavings;
            this.details.time = this.opportunity.timeUnit
                ? {key: this.opportunity.timeUnit, label: this.translate(this.opportunity.timeUnit)}
                : null
            ;
            this.details.strategy = this.opportunity.opportunityStrategy
                ? {key: this.opportunity.opportunityStrategy, label: this.opportunity.opportunityStrategyName}
                : null
            ;
            this.details.status = this.opportunity.opportunityStatus
                ? {key: this.opportunity.opportunityStatus, label: this.opportunity.opportunityStatusName}
                : null
            ;
            this.memberList.push(this.opportunity.responsibility);
            this.measures = [];

            if (this.opportunity.measures.length > 0) {
                this.opportunity.measures.forEach((measure) => {
                    this.measures.push(Object.assign({}, measure));
                });
            }
        },
    },
    data: function() {
        return {
            priority: null,
            title: '',
            description: '',
            costSavings: '',
            timeSavings: '',
            days: '',
            schedule: {
                dueDate: new Date(),
            },
            measures: [],
            details: {
                time: null,
                strategy: null,
                status: null,
            },
            memberList: [],
            opportunityImpact: 0,
            opportunityProbability: 0,
            timeLabel: [
                {label: this.translate('choices.hours'), key: 'choices.hours'},
                {label: this.translate('choices.days'), key: 'choices.days'},
                {label: this.translate('choices.weeks'), key: 'choices.weeks'},
                {label: this.translate('choices.months'), key: 'choices.months'},
            ],
            isEdit: this.$route.params.opportunityId,
            gridData: [
                {type: 'medium'}, {type: 'low'}, {type: 'very-low'}, {type: 'very-low'},
                {type: 'high'}, {type: 'medium'}, {type: 'low'}, {type: 'very-low'},
                {type: 'very-high'}, {type: 'high'}, {type: 'medium'}, {type: 'low'},
                {type: 'very-high'}, {type: 'very-high'}, {type: 'high'}, {type: 'medium'},
            ],
            activeItem: null,
        };
    },
};
</script>

<style lang="scss">
    .tooltip {
        .tooltip-content {
            text-transform: none;
            letter-spacing: 0;
            font-size: 12px;
        }
    }
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/risks-and-opportunities/create';

    .ro-summary {
        .ro-very-high-priority {
            color: $dangerDarkColor;
        }
        .ro-high-priority {
            color: $dangerColor;
        }
        .ro-medium-priority {
            color: $warningColor;
        }
        .ro-low-priority {
            color: $secondColor;
        }
        .ro-very-low-priority {
            color: $secondDarkColor;
        }
    }
</style>
