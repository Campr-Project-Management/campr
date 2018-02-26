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
                <!-- /// End Project Risk Grid /// -->
            </div>

            <!-- /// Project Risks Summary /// -->
            <div class="ro-summary">
                <div class="text-center flex flex-center">
                    <div class="text-right">
                        <p>{{ translateText('message.priority') }}:</p>
                    </div>
                    <div class="text-left">
                        <p><b v-if="priority" v-bind:class="priority.color">{{ translateText(priority.name) }}</b><b v-else>-</b></p>
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
                        <h1 v-if="!isEdit">{{ translateText('message.create_new_risk') }}</h1>
                        <h1 v-else>{{ translateText('message.edit_risk') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Risk Name /// -->
                    <input-field type="text" v-bind:label="translateText('placeholder.risk_title')" v-model="title" v-bind:content="title" />
                    <error
                        v-if="validationMessages.title && validationMessages.title.length"
                        v-for="message in validationMessages.title"
                        :message="message" />
                    <!-- /// End Risk /// -->

                    <!-- /// Risk Description /// -->
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ translateText('placeholder.risk_description') }}</div>
                        <Vueditor ref="description" />
                        <error
                            v-if="validationMessages.description && validationMessages.description.length"
                            v-for="message in validationMessages.description"
                            :message="message" />
                    </div>
                    <!-- /// End Risk Description /// -->

                    <hr class="double">

                    <!-- /// Risk Impact /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                                :title="translateText('message.impact')"
                                minSuffix=" %"
                                :step="5"
                                v-model="riskImpact"/>
                        <div class="slider-indicator" v-if="risksOpportunitiesStats.risks">
                            <indicator-icon fill="middle-fill"
                                            v-if="risksOpportunitiesStats.risks.risk_data.averageData.averageImpact"
                                            :position="risksOpportunitiesStats.risks.risk_data.averageData.averageImpact"
                                            :title="translateText('message.average_impact_risk')"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Risk Impact /// -->

                    <!-- /// Risk Probability /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                                :title="translateText('message.probability')"
                                minSuffix=" %"
                                :step="5"
                                v-model="riskProbability"/>
                        <div class="slider-indicator" v-if="risksOpportunitiesStats.risks">
                            <indicator-icon fill="middle-fill"
                                            v-if="risksOpportunitiesStats.risks.risk_data.averageData.averageProbability"
                                            :position="risksOpportunitiesStats.risks.risk_data.averageData.averageProbability"
                                            :title="translateText('message.average_probability_risk')"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Risk Probability /// -->

                    <hr class="double">

                    <!-- /// Risk Details  /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <input-field
                                    type="text"
                                    v-model="cost" v-bind:content="cost"
                                    v-bind:label="translateText('placeholder.potential_cost')" />
                                <error
                                    v-if="validationMessages.cost && validationMessages.cost.length"
                                    v-for="message in validationMessages.cost"
                                    :message="message" />
                            </div>
                            <div class="col-md-2">
                                <select-field
                                    v-bind:title="translateText('label.currency')"
                                    v-bind:options="currencyLabel"
                                    v-model="details.currency"
                                    v-bind:currentOption="details.currency" />
                                <error
                                    v-if="validationMessages.currency && validationMessages.currency.length"
                                    v-for="message in validationMessages.currency"
                                    :message="message" />
                            </div>
                            <div class="col-md-4">
                                <input-field
                                    class="time-delay"
                                    type="text"
                                    v-model="timeDelay" v-bind:content="timeDelay"
                                    v-bind:label="translateText('placeholder.potential_time_delay')" />
                                <error
                                    v-if="validationMessages.delay && validationMessages.delay.length"
                                    v-for="message in validationMessages.delay"
                                    :message="message" />
                            </div>
                            <div class="col-md-2">
                                <select-field
                                    v-bind:title="translateText('label.time')"
                                    v-bind:options="timeLabel"
                                    v-model="details.time"
                                    v-bind:currentOption="details.time" />
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
                                    {{ translateText('message.budget' )}}: <b>{{ calculatedBudget }}</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="translateText('message.budget_calculation_risk')">
                                        <tooltip-icon fill="light-fill"></tooltip-icon>
                                    </button>
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="light-color">
                                    {{ translateText('message.delay') }}: <b>{{ calculatedTime }}</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="translateText('message.time_calculation_risk')">
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
                                    v-bind:title="translateText('placeholder.risk_strategy')"
                                    v-bind:options="riskStrategiesForSelect"
                                    v-model="details.strategy"
                                    v-bind:currentOption="details.strategy" />
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder">
                                    <label class="active">{{ translateText('label.due_date') }}</label>
                                    <datepicker v-model="schedule.dueDate" format="dd-MM-yyyy" />
                                    <calendar-icon fill="middle-fill"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select-field 
                                    v-bind:title="translateText('placeholder.risk_status')"
                                    v-bind:options="riskStatusesForSelect"
                                    v-model="details.status"
                                    v-bind:currentOption="details.status" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-12 member-search-container">
                                <member-search singleSelect="false" v-model="memberList" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Risk Details /// -->

                    <hr class="double">

                    <!-- /// Risk Measure /// -->
                    <div class="row" v-for="(measure, index) in measures">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input-field type="text" v-bind:label="translateText('placeholder.measure_title')" v-model="measure.title" v-bind:content="measure.title" />
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
                                <div class="vueditor-holder measure-vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('placeholder.new_measure') }}</div>
                                    <Vueditor :id="'measure.description'+index" :ref="'measure.description'+index" />
                                    <span v-if="validationMessages.measures && validationMessages.measures[index]">
                                        <error
                                            v-if="validationMessages.measures[index].description.length"
                                            v-for="message in validationMessages.measures[index].description"
                                            :message="message" />
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group last-form-group">
                            <div class="flex flex-space-between">
                                <div class="col-md-4">
                                    <input-field type="text" v-bind:label="translateText('placeholder.measure_cost')" v-model="measure.cost" v-bind:content="measure.cost" />
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
                                <a @click="addMeasure()" class="btn-rounded btn-auto">{{ translateText('button.add_new_measure') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Risk Measure /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="btn-rounded btn-auto disable-bg">{{ translateText('button.cancel') }}</router-link>
                        <a v-if="!isEdit" @click="saveRisk()" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
                        <a v-if="isEdit" @click="editRisk()" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
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
import datepicker from '../../_common/_form-components/Datepicker';
import CalendarIcon from '../../_common/_icons/CalendarIcon';
import moment from 'moment';
import Error from '../../_common/_messages/Error.vue';
import {createEditor} from 'vueditor';
import vueditorConfig from '../../_common/vueditorConfig';

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
    },
    methods: {
        ...mapActions([
            'getProjectRiskAndOpportunitiesStats', 'getRiskStrategies', 'getRiskStatuses',
            'createProjectRisk', 'getProjectRisk', 'editProjectRisk', 'emptyValidationMessages',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        addMeasure: function() {
            let measure = {
                title: '',
                description: this.$refs['measure.description'+this.measures.length],
                cost: '',
                responsibility: this.memberList.length > 0 ? this.memberList[0] : null,
            };

            let thisRef = 'measure.description'+this.measures.length;
            setTimeout(() => {
                measure.element = createEditor(document.getElementById(thisRef), {...vueditorConfig, id: thisRef});
            }, 1000);
            this.measures.push(measure);
        },
        getFormData: function() {
            this.measures.map((item, index) => {
                item.description = item.element.getContent();
                item.responsibility = this.memberList.length > 0 ? this.memberList[0] : null;
                delete item.element;
            });
            let data = {
                title: this.title,
                description: this.$refs.description.getContent(),
                impact: this.riskImpact,
                probability: this.riskProbability,
                budget: this.calculateBudget(),
                cost: this.cost,
                currency: this.details.currency && this.details.currency.key ? this.details.currency.key : '',
                delay: this.timeDelay,
                delayUnit: this.details.time && this.details.time.key ? this.details.time.key : '',
                priority: this.priority ? this.priority.value : null,
                riskStrategy: this.details.strategy ? this.details.strategy.key : null,
                status: this.details.status ? this.details.status.key : null,
                dueDate: moment(this.schedule.dueDate).format('DD-MM-YYYY'),
                responsibility: this.memberList.length > 0 ? this.memberList[0] : null,
                measures: this.measures,
            };

            return data;
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
        calculateBudget: function() {
            let currency = this.details.currency && this.details.currency.key ? this.details.currency.label : '';
            let riskVal = parseInt(this.riskProbability ? this.riskProbability : 0);
            let costVal = parseFloat(this.cost ? this.cost : 0);
            this.calculatedBudget = currency + ' ' + (riskVal * costVal).toFixed(2);

            return (riskVal * costVal).toFixed(2);
        },
        calculateTime: function() {
            let unit = this.details.time && this.details.time.key ? this.details.time.label : '';
            let riskVal = parseInt(this.riskProbability ? this.riskProbability : 0);
            let timeVal = parseFloat(this.timeDelay ? this.timeDelay : 0);
            this.calculatedTime = (riskVal * timeVal).toFixed(2) + ' ' + unit;
        },
        updateGridView() {
            let index = 0;
            const riskImpact = this.riskImpact;
            const riskProbability = this.riskProbability;

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
        getCurrencySymbol: function(label) {
            let symbols = {
                'USD': '$',
                'EUR': '€',
                'GBP': '₤',
            };

            return symbols[label];
        },
        setPriority: function(type) {
            const priorityNames = {
                'very-low': {name: 'message.very_low', color: 'ro-very-low-priority', value: 0},
                'low': {name: 'message.low', color: 'ro-low-priority', value: 1},
                'medium': {name: 'message.medium', color: 'ro-medium-priority', value: 2},
                'high': {name: 'message.high', color: 'ro-high-priority', value: 3},
                'very-high': {name: 'message.very_high', color: 'ro-very-high-priority', value: 4},
            };

            this.priority = priorityNames[type];
        },
    },
    computed: {
        ...mapGetters({
            risksOpportunitiesStats: 'risksOpportunitiesStats',
            riskStrategiesForSelect: 'riskStrategiesForSelect',
            riskStatusesForSelect: 'riskStatusesForSelect',
            risk: 'currentRisk',
            validationMessages: 'validationMessages',
        }),
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
            calculatedBudget: '0.00',
            calculatedTime: '0.00',
            title: '',
            description: '',
            cost: '',
            timeDelay: '',
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
            currencyLabel: [
                {label: '$', key: 'USD'},
                {label: '€', key: 'EUR'},
                {label: '₤', key: 'GBP'},
            ],
            timeLabel: [
                {label: this.translateText('choices.hours'), key: 'choices.hours'},
                {label: this.translateText('choices.days'), key: 'choices.days'},
                {label: this.translateText('choices.weeks'), key: 'choices.weeks'},
                {label: this.translateText('choices.months'), key: 'choices.months'},
            ],
            model: {},
            currentOption: {},
            isEdit: this.$route.params.riskId,
            gridData: [
                {type: 'medium'}, {type: 'high'}, {type: 'very-high'}, {type: 'very-high'},
                {type: 'low'}, {type: 'medium'}, {type: 'high'}, {type: 'very-high'},
                {type: 'very-low'}, {type: 'low'}, {type: 'medium'}, {type: 'high'},
                {type: 'very-low'}, {type: 'very-low'}, {type: 'low'}, {type: 'medium'},
            ],
            activeItem: null,
        };
    },
    watch: {
        cost(value) {
            this.calculateBudget();
        },
        timeDelay(value) {
            this.calculateTime();
        },
        details: {
            handler: function(value) {
                this.calculateBudget();
                this.calculateTime();
            },
            deep: true,
        },
        risk(value) {
            this.title = this.risk.title;
            this.$refs.description.setContent(this.risk.description);
            this.riskImpact = this.risk.impact;
            this.riskProbability = this.risk.probability;
            this.cost = this.risk.cost;
            this.details.currency = this.risk.currency
                ? {key: this.translateText(this.risk.currency), label: this.getCurrencySymbol(this.risk.currency)}
                : null
            ;
            this.timeDelay = this.risk.delay;
            this.details.time = this.risk.delayUnit
                ? {key: this.risk.delayUnit, label: this.translateText(this.risk.delayUnit)}
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
                this.measures = this.risk.measures.map((item, index) => {
                    let val = {
                        title: item.title,
                        description: item.description,
                        cost: item.cost,
                    };
                    setTimeout(() => {
                        const thisRef = 'measure.description'+index;
                        val.element = createEditor(document.getElementById(thisRef), {...vueditorConfig, id: thisRef});
                        val.element.setContent(item.description);
                    }, 1000);

                    return val;
                });
            }
        },
        riskProbability: function(value) {
            this.calculateBudget();
            this.calculateTime();
            this.updateGridView();
            this.riskProbability = value;
            return value;
        },
        riskImpact: function(value) {
            this.updateGridView();
            this.riskImpact = value;
            return value;
        },
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
    @import '../../../css/risks-and-opportunities/view';

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
        overflow: hidden;
    }
</style>
