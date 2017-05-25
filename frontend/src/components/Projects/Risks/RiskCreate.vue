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
                        <div class="ro-grid-item medium"></div>
                            <div class="ro-grid-item high"></div>
                            <div class="ro-grid-item very-high"></div>
                            <div class="ro-grid-item very-high"></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item low"></div>
                            <div class="ro-grid-item medium"></div>
                            <div class="ro-grid-item high"></div>
                            <div class="ro-grid-item very-high active"></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item very-low"></div>
                            <div class="ro-grid-item low"></div>
                            <div class="ro-grid-item medium"></div>
                            <div class="ro-grid-item high"></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item very-low"></div>
                            <div class="ro-grid-item very-low"></div>
                            <div class="ro-grid-item low"></div>
                            <div class="ro-grid-item medium"></div>
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
                        <h1>{{ translateText('message.create_new_risk') }}</h1>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <div class="form">
                    <!-- /// Opportunity Name /// -->
                    <input-field type="text" v-bind:label="translateText('placeholder.risk_title')" v-model="name" v-bind:content="name" />
                    <!-- /// End Opportunity Name /// -->

                    <!-- /// Opportunity Description /// -->
                    <div class="vueditor-holder">
                        <div class="vueditor-header">{{ translateText('placeholder.risk_description') }}</div>
                        <Vueditor ref="content" />
                    </div>
                    <!-- /// End Opportunity Description /// -->

                    <hr class="double">

                    <!-- /// Opportunity Impact /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                        v-bind:title="message.impact"
                        min="0"
                        max="100"
                        minSuffix=" %"
                        type="single"
                        v-model="riskImpact"
                        v-bind:value="riskImpact" />
                        <div class="slider-indicator">
                            <indicator-icon fill="middle-fill" position="77" title="Average Impact Value of all risks is"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Opportunity Impact /// -->

                    <!-- /// Opportunity Probability /// -->
                    <div class="range-slider-wrapper">
                        <range-slider
                        v-bind:title="message.probability"
                        min="0"
                        max="100"
                        minSuffix=" %"
                        type="single"
                        v-model="riskProbability"
                        v-bind:value="riskProbability" />
                        <div class="slider-indicator">
                            <indicator-icon fill="middle-fill" position="61" title="Average Probability Value of all risks is"></indicator-icon>
                        </div>
                    </div>
                    <!-- /// End Opportunity Probability /// -->

                    <hr class="double">

                    <!-- /// Opportunity Details  /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <input-field type="text" v-bind:label="translateText('placeholder.potential_cost')"/>
                            </div>
                            <div class="col-md-2">
                                <select-field 
                                    v-bind:title="'$'"
                                    v-bind:options="currencyLabel"
                                    v-model="details.currency"
                                    v-bind:currentOption="details.currency" />
                            </div>
                            <div class="col-md-4">
                                <input-field type="text" v-bind:label="translateText('placeholder.potential_time_delay')" v-model="timeDelay" v-bind:content="timeDelay" />
                            </div>
                            <div class="col-md-2">
                                <select-field 
                                    v-bind:title="'Hours'"
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
                                    {{translateText('message.budget')}}: <b>$0</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="'Budget is calculated as Probability times Potential Savings'">
                                        <tooltip-icon fill="light-fill"></tooltip-icon>
                                    </button>
                                </h4>
                            </div>
                            <div class="col-md-6">
                                <h4 class="light-color">
                                    {{translateText('message.delay')}}: <b>0 Days</b>
                                    <button type="button" class="btn btn-icon" v-tooltip.right-start="'Time Saved is calculated as Probability times Potential Time Savings'">
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
                                    v-bind:options="strategyLabel"
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
                                    v-bind:options="strategyLabel"
                                    v-model="details.status"
                                    v-bind:currentOption="details.status" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group last-form-group">
                            <div class="col-md-12">
                                <member-search v-model="gridList" v-bind:placeholder="translateText('placeholder.search_members')"></member-search>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Opportunity Details /// -->

                    <hr class="double">

                    <!-- /// Opportunity Measure /// -->
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="vueditor-holder measure-vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('placeholder.new_measure') }}</div>
                                    <Vueditor ref="content" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group last-form-group">
                            <div class="flex flex-space-between">
                                <div class="col-md-4">
                                    <input-field type="text" v-bind:label="translateText('placeholder.measure_cost')" v-model="measureCost" v-bind:content="measureCost" />
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="#" class="btn-rounded btn-auto">{{ translateText('button.add_new_measure') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Opportunity Measure /// -->

                    <hr class="double">

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <a href="javascript:void(0)" class="btn-rounded btn-auto disable-bg">{{ translateText('button.cancel') }}</a>
                        <a href="javascript:void(0)" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data: function() {
        const stepData = 2;

        return {
            name: '',
            content: '',
            timeSavings: '',
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
            message: {
                impact: Translator.trans('message.impact'),
                probability: Translator.trans('message.probability'),
            },
            riskImpact: stepData ? stepData.riskImpact : 0,
            riskProbability: stepData ? stepData.riskProbability : 0,
            currencyLabel: [{label: '$', key: 1}, {label: '€', key: 2}, {label: '₤', key: 3}],
            timeLabel: [{label: 'Hours', key: 1}, {label: 'Days', key: 2}, {label: 'Weeks', key: 3}, {label: 'Months', key: 4}],
            strategyLabel: [{label: 'Take', key: 1}, {label: 'Increase', key: 2}, {label: 'Skip', key: 3}],
            statusLabel: [{label: 'Initiated', key: 1}, {label: 'Ongoing', key: 2}, {label: 'Finished', key: 3}],
            model: {},
            currentOption: {},
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
    @import '../../../css/risks-and-opportunities/view';
</style>
