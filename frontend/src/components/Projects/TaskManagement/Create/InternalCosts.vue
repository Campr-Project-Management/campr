<template>
    <div>
        <h3>{{ message.internal_costs }}</h3>
        <span class="note blue-note">{{ message.project_currency }} <i class="fa fa-dollar"></i> USD</span>
        <div v-for="(cost, index) in processedInternalCosts">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <select-field
                            v-bind:title="label.resource"
                            v-bind:options="resourcesForSelect"
                            v-model="cost.resource"
                            v-bind:currentOption="cost.resource" />
                    </div>
                    <div class="col-md-2">
                        <input-field type="text" v-bind:label="label.daily_rate" v-bind:content="cost.rate" disabled />
                    </div>
                    <div class="col-md-2">
                        <input-field type="text" v-bind:label="label.qty" v-model="cost.qty" v-bind:content="cost.qty" />
                    </div>
                    <div class="col-md-2">
                        <input-field type="text" v-bind:label="label.days" v-model="cost.days" v-bind:content="cost.days" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-6">                    
                        <span class="title">
                            {{ label.internal_cost_subtotal }} <b><i class="fa fa-dollar"></i> {{ cost.total }}</b>
                        </span>
                    </div>
                    <div class="col-md-6" v-if="index === (processedInternalCosts.length - 1)">
                        <div class="pull-right">
                            <a v-on:click="addInternalCost" class="btn-rounded btn-auto">{{ button.add_internal_cost }} +</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group last-form-group">                
                <div class="col-md-4">                    
                    <span class="title">{{ message.base_total }} <b><i class="fa fa-dollar"></i> {{ baseTotal }}</b></span>        
                </div>
                <div class="col-md-4">
                    <input-field
                        type="text"
                        v-bind:label="label.forecast_total"
                        v-bind:content="internalCosts.forecast"
                        v-model="internalCosts.forecast" />
                </div>
                <div class="col-md-4">
                    <input-field
                        type="text"
                        v-bind:label="label.actual_total"
                        v-bind:content="internalCosts.actual"
                        v-model="internalCosts.actual" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../../_common/_form-components/InputField';
import SelectField from '../../../_common/_form-components/SelectField';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        InputField,
        SelectField,
    },
    methods: {
        ...mapActions(['getProjectDepartments']),
        addInternalCost: function() {
            this.internalCosts.items.push({
                resource: '',
                qty: 1,
                days: 1,
            });
        },
    },
    created() {
        if (!this.internalCosts.length) {
            this.addInternalCost();
        }

        this.getProjectDepartments(this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            resources: 'projectDepartments',
            resourcesForSelect: 'projectDepartmentsForSelect',
        }),
        baseTotal: function() {
            return this.processedInternalCosts.reduce((prev, next) => {
                return prev + next.total;
            }, 0);
        },
        processedInternalCosts: function() {
            return this.internalCosts.items.map(item => {
                item.total = item.resource ? item.resource.rate * item.qty * item.days : 0;
                item.rate = item.resource ? item.resource.rate : 0;
                return item;
            });
        },
    },
    watch: {
        internalCosts: {
            handler: function(value) {
                this.$emit('input', value);
            },
            deep: true,
        },
    },
    data() {
        return {
            message: {
                internal_costs: this.translate('message.internal_costs'),
                project_currency: this.translate('message.project_currency'),
                base_total: this.translate('message.total'),
            },
            label: {
                resource: this.translate('label.resource'),
                daily_rate: this.translate('label.daily_rate'),
                qty: this.translate('label.qty'),
                days: this.translate('label.days'),
                base_start_date: this.translate('label.base_start_date'),
                forecast_total: this.translate('label.forecast_total'),
                actual_total: this.translate('label.actual_total'),
                internal_cost_subtotal: this.translate('label.internal_cost_subtotal'),
            },
            button: {
                add_internal_cost: this.translate('button.add_internal_cost'),
                cancel: this.translate('button.cancel'),
                create_task: this.translate('button.create_task'),
            },
            internalCosts: {
                items: [],
                forecast: 0,
                actual: 0,
            },
        };
    },
};
</script>
