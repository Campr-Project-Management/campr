<template>
    <div class="row">        
        <div class="col-md-6 col-md-push-6">
            <!-- /// Project Opportunities /// -->
            <div class="ro-grid-wrapper clearfix">
                <!-- /// Project Opportunities Grid /// -->
                <div class="ro-grid">
                    <div class="ro-grid-header vertical-axis-header">
                        <div class="big-header">{{ translateText('message.impact') }}</div>
                        <div class="small-headers clearfix">
                            <div class="small-header">{{ translateText('message.very_low') }}</div>
                            <div class="small-header">{{ translateText('message.low') }}</div>
                            <div class="small-header">{{ translateText('message.high') }}</div>
                            <div class="small-header">{{ translateText('message.very_high') }}</div>
                        </div>
                    </div> 
                    <div class="ro-grid-items clearfix">
                        <div v-for="item in gridData" class="ro-grid-item" :class="[{active: item.isActive}, item.type]"></div>
                    </div>
                    <div class="ro-grid-header horizontal-axis-header">                            
                        <div class="small-headers clearfix">
                            <div class="small-header">{{ translateText('message.very_low') }}</div>
                            <div class="small-header">{{ translateText('message.low') }}</div>
                            <div class="small-header">{{ translateText('message.high') }}</div>
                            <div class="small-header">{{ translateText('message.very_high') }}</div>
                        </div>
                        <div class="big-header">{{ translateText('message.probability') }}</div>
                    </div>
                    <div class=""></div>
                </div>
                <!-- /// End Project Opportunities Grid /// -->
            </div>

            <!-- /// Project Risks Summary /// -->
            <div class="ro-summary">
                <div class="text-center flex flex-center">
                    <div class="text-right">
                        <p>{{ translateText('message.priority') }}:</p>
                    </div>
                    <div class="text-left">
                        <p><b>{{ translateText('message.very_high') }}</b></p>
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
                            {{ translateText('message.back_to_risks_and_opportunities') }}
                        </router-link>
                        <h1 v-if="!isEdit">{{ translateText('message.create_new_opportunity') }}</h1>
                        <h1 v-else>{{ translateText('message.edit_opportunity') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Opportunity Name /// -->
                    <input-field type="text" v-bind:label="translateText('placeholder.opportunity_title')" v-model="title" v-bind:content="title" />
                    <!-- /// End Opportunity Name /// -->

                    <!-- /// Opportunity Description /// -->
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ translateText('placeholder.opportunity_description') }}</div>
                        <Vueditor ref="description" />
                    </div>
                    <!-- /// End Opportunity Description /// -->

                    <hr class="double">

                    <!-- /// Opportunity Impact /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                        v-bind:title="translateText('message.impact')"
                        min="0"
                        max="100"
                        minSuffix=" %"
                        type="single"
                        v-model="opportunityImpact"
                        v-bind:value="opportunityImpact" />
                        <div class="slider-indicator" v-if="risksOpportunitiesStats.opportunities">
                            <indicator-icon fill="middle-fill" :position="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageImpact" :title="translateText('message.average_impact_opportunity')"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Opportunity Impact /// -->

                    <!-- /// Opportunity Probability /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                        v-bind:title="translateText('message.probability')"
                        min="0"
                        max="100"
                        minSuffix=" %"
                        type="single"
                        v-model="opportunityProbability"
                        v-bind:value="opportunityProbability" />
                        <div class="slider-indicator" v-if="risksOpportunitiesStats.opportunities">
                            <indicator-icon fill="middle-fill" :position="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageProbability" :title="translateText('message.average_probability_opportunity')"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Opportunity Probability /// -->

                    <hr class="double">

                    <!-- /// Opportunity Details  /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('placeholder.potential_savings')"
                                    v-model="costSavings" v-bind:content="costSavings"
                                />
                            </div>
                            <div class="col-md-2">
                                <select-field 
                                    v-bind:title="translateText('label.currency')"
                                    v-bind:options="currencyLabel"
                                    v-model="details.currency"
                                    v-bind:currentOption="details.currency" />
                            </div>
                            <div class="col-md-4">
                                <input-field
                                    type="text"
                                    v-bind:label="translateText('placeholder.potential_time_savings')"
                                    v-model="timeSavings" v-bind:content="timeSavings"
                                />
                            </div>
                            <div class="col-md-2">
                                <select-field 
                                    v-bind:title="translateText('label.time')"
                                    v-bind:options="timeLabel"
                                    v-model="details.time"
                                    v-bind:currentOption="details.time" />
                            </div>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <h4 class="light-color">
                                    {{ translateText('message.budget') }}: <b>{{ calculatedBudget }}</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="translateText('message.budget_calculation_opportuntiy')">
                                        <tooltip-icon fill="light-fill"></tooltip-icon>
                                    </button>
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="light-color">
                                    {{ translateText('message.time_saved') }}: <b>{{ calculatedTime }}</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="translateText('message.time_calculation_opportunity')">
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
                                    v-bind:title="translateText('label.opportunity_strategy')"
                                    v-bind:options="opportunityStrategiesForSelect"
                                    v-model="details.strategy"
                                    v-bind:currentOption="details.strategy" />
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="schedule.dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill" stroke="middle-stroke" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select-field 
                                    v-bind:title="translateText('label.opportunity_status')"
                                    v-bind:options="opportunityStatusesForSelect"
                                    v-model="details.status"
                                    v-bind:currentOption="details.status" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-12">
                                <member-search singleSelect="false" v-model="memberList" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Opportunity Details /// -->

                    <hr class="double">

                    <!-- /// Opportunity Measure /// -->
                    <div class="row" v-for="(measure, index) in measures">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input-field type="text" v-bind:label="translateText('placeholder.measure_title')" v-model="measure.title" v-bind:content="measure.title" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="vueditor-holder measure-vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('placeholder.new_measure') }}</div>
                                    <Vueditor :ref="'measure.description'+index" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group last-form-group">
                            <div class="flex flex-space-between">
                                <div class="col-md-4">
                                    <input-field type="text" v-bind:label="translateText('placeholder.measure_cost')" v-model="measure.cost" v-bind:content="measure.cost" />
                                </div>
                            </div>
                        </div>
                        <hr class="double">
                    </div>
                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-12 text-right">
                                <a @click="addMeasure()" class="btn-rounded btn-auto">{{ translateText('button.add_new_measure') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Opportunity Measure /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="btn-rounded btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="saveOpportunity()" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
                        <a v-if="isEdit" @click="editOpportunity()" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
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
import RangeSlider from '../../_common/_form-components/RangeSlider';
import TooltipIcon from '../../_common/_icons/TooltipQuestionMark';
import IndicatorIcon from '../../_common/_icons/IndicatorIcon';
import MemberSearch from '../../_common/MemberSearch';
import SelectField from '../../_common/_form-components/SelectField';
import datepicker from 'vuejs-datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import moment from 'moment';

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
    },
    methods: {
        ...mapActions([
            'getProjectRiskAndOpportunitiesStats', 'getOpportunityStrategies', 'getOpportunityStatuses',
            'createProjectOpportunity', 'getProjectOpportunity', 'editProjectOpportunity',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        addMeasure: function() {
            this.measures.push({
                title: '',
                description: this.$refs['measure.description'+this.measures.length],
                cost: '',
            });
        },
        getFormData: function() {
            this.measures.map((item, index) => {
                item.description = this.$refs['measure.description'+index][0].getContent();
            });
            let data = {
                title: this.title,
                description: this.$refs.description.getContent(),
                impact: this.opportunityImpact,
                probability: this.opportunityProbability,
                budget: this.calculatedBudget,
                costSavings: this.costSavings,
                currency: this.details.currency && this.details.currency.key ? this.details.currency.label : '',
                timeSavings: this.timeSavings,
                timeUnit: this.details.time && this.details.time.key ? this.details.time.key : '',
                priority: 'PRIORITY', // TODO: determine the priority calulation method
                opportunityStrategy: this.details.strategy ? this.details.strategy.key : null,
                opportunityStatus: this.details.status ? this.details.status.key : null,
                dueDate: moment(this.schedule.dueDate).format('DD-MM-YYYY'),
                responsibility: this.memberList.length > 0 ? this.memberList[0] : null,
                measures: this.measures,
            };

            return data;
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
        calculateBudget: function() {
            let currency = this.details.currency && this.details.currency.key ? this.details.currency.label : '';
            let opportunityVal = parseInt(this.opportunityProbability ? this.opportunityProbability : 0);
            let savingsVal = parseFloat(this.costSavings ? this.costSavings : 0);
            this.calculatedBudget = currency + ' ' + (opportunityVal * savingsVal).toFixed(2);
        },
        calculateTime: function() {
            let unit = this.details.time && this.details.time.key ? this.details.time.label : '';
            let opportunityVal = parseInt(this.opportunityProbability ? this.opportunityProbability : 0);
            let timeVal = parseFloat(this.timeSavings ? this.timeSavings : 0);
            this.calculatedTime = (opportunityVal * timeVal).toFixed(2) + ' ' + unit;
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
        },
    },
    computed: {
        ...mapGetters({
            risksOpportunitiesStats: 'risksOpportunitiesStats',
            opportunityStrategiesForSelect: 'opportunityStrategiesForSelect',
            opportunityStatusesForSelect: 'opportunityStatusesForSelect',
            opportunity: 'currentOpportunity',
        }),
    },
    created() {
        this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        this.getOpportunityStrategies();
        this.getOpportunityStatuses();
        if (this.$route.params.opportunityId) {
            this.getProjectOpportunity(this.$route.params.opportunityId);
        }
    },
    watch: {
        opportunityImpact: function(value) {
            this.updateGridView();
            this.opportunityImpact = value;
            return value;
        },
        opportunityProbability(value) {
            this.calculateBudget();
            this.calculateTime();
            this.updateGridView();
            this.opportunityProbability = value;
            return value;
        },
        costSavings(value) {
            this.calculateBudget();
        },
        timeSavings(value) {
            this.calculateTime();
        },
        details: {
            handler: function(value) {
                this.calculateBudget();
                this.calculateTime();
            },
            deep: true,
        },
        opportunity(value) {
            this.title = this.opportunity.title;
            this.$refs.description.setContent(this.opportunity.description);
            this.opportunityImpact = this.opportunity.impact.toString();
            this.opportunityProbability = this.opportunity.probability.toString();
            this.costSavings = this.opportunity.costSavings;
            this.details.currency = this.opportunity.currency
                ? {key: this.opportunity.currency, label: this.opportunity.currency}
                : null
            ;
            this.timeSavings = this.opportunity.timeSavings;
            this.details.time = this.opportunity.timeUnit
                ? {key: this.opportunity.timeUnit, label: this.translateText(this.opportunity.timeUnit)}
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
            if (this.opportunity.measures.length > 0) {
                this.measures = this.opportunity.measures.map((item, index) => {
                    return {
                        title: item.title,
                        description: item.description,
                        cost: item.cost,
                    };
                });
            }
        },
    },
    data: function() {
        return {
            calculatedBudget: '0.00',
            calculatedTime: '0.00',
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
                currency: null,
                time: null,
                strategy: null,
                status: null,
            },
            memberList: [],
            opportunityImpact: 0,
            opportunityProbability: 0,
            currencyLabel: [{label: this.translateText('label.currency'), key: null}, {label: '$', key: 1}, {label: '€', key: 2}, {label: '₤', key: 3}],
            timeLabel: [
                {label: this.translateText('label.time'), key: null},
                {label: this.translateText('choices.hours'), key: 'choices.hours'},
                {label: this.translateText('choices.days'), key: 'choices.days'},
                {label: this.translateText('choices.weeks'), key: 'choices.weeks'},
                {label: this.translateText('choices.months'), key: 'choices.months'},
            ],
            isEdit: this.$route.params.opportunityId,
            message: {
                impact: Translator.trans('message.impact'),
                probability: Translator.trans('message.probability'),
            },
            strategyLabel: [{label: 'Take', key: 1}, {label: 'Increase', key: 2}, {label: 'Skip', key: 3}],
            statusLabel: [{label: 'Initiated', key: 1}, {label: 'Ongoing', key: 2}, {label: 'Finished', key: 3}],
            model: {},
            currentOption: {},
            gridData: [
                {type: 'medium'}, {type: 'high'}, {type: 'very-high'}, {type: 'very-high'},
                {type: 'low'}, {type: 'medium'}, {type: 'high'}, {type: 'very-high'},
                {type: 'very-low'}, {type: 'low'}, {type: 'medium'}, {type: 'high'},
                {type: 'very-low'}, {type: 'very-low'}, {type: 'low'}, {type: 'medium'},
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
</style>
