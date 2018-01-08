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
                            v-bind:currentOption="cost.selectedResource"
                            v-on:input="setCostProperty('resource', index, $event)" />
                        <error
                            v-if="getValidationMessages(index, 'resource').length"
                            v-for="message in getValidationMessages(index, 'resource')"
                            :message="message" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="label.daily_rate"
                            v-model="cost.rate"
                            v-bind:content="cost.rate" />
                        <!--v-on:input="setCostProperty('rate', index, $event)"-->
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="label.qty"
                            v-model="cost.quantity"
                            v-bind:content="cost.quantity" />
                        <!--v-on:input="setCostProperty('quantity', index, $event)"-->
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="label.days"
                            v-model="cost.duration"
                            v-bind:content="cost.duration" />
                        <!--v-on:input="setCostProperty('duration', index, $event)"-->
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
                    <div class="col-md-6">
                        <div class="pull-right">
                            <span v-on:click="deleteInternalCost(index);"><delete-icon /></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a v-on:click="addInternalCost" class="btn-rounded btn-auto">{{ button.add_internal_cost }} +</a>
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
                        v-bind:content="forecastContent"
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
import DeleteIcon from '../../../_common/_icons/DeleteIcon';
import Error from '../../../_common/_messages/Error.vue';
import {mapActions, mapGetters} from 'vuex';

export default {
    props: ['validationMessages'],
    components: {
        InputField,
        SelectField,
        DeleteIcon,
        Error,
    },
    methods: {
        ...mapActions(['getProjectDepartments', 'getProjectResources']),
        addInternalCost: function() {
            this.internalCosts.items.push({
                resource: '',
                quantity: 1,
                duration: 1,
                rate: 0,
            });
        },
        deleteInternalCost: function(index) {
            this.internalCosts.items = [
                ...this.internalCosts.items.slice(0, index),
                ...this.internalCosts.items.slice(index + 1),
            ];
        },
        itemTotal(item) {
            return item.rate * item.quantity * item.duration;
        },
        setCostProperty(property, index, value) {
            this.internalCosts.items[index][property] = value;
            if (property === 'resource') {
                this.internalCosts.items[index]['rate'] = value.rate;
                this.internalCosts.items[index].selectedResource = value;
            }
            this.$emit('input', this.internalCosts);
        },
        getValidationMessages(index, key) {
            if (this.validationMessages[index] && this.validationMessages[index][key]) {
                return this.validationMessages[index][key];
            }
            return [];
        },
    },
    created() {
        this.getProjectDepartments({project: this.$route.params.id});
        this.getProjectResources(this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            resources: 'projectDepartments',
            resourcesForSelect: 'projectResourcesForSelect',
        }),
        baseTotal: function() {
            return this.processedInternalCosts.reduce((prev, next) => {
                return prev + this.itemTotal(next);
            }, 0);
        },
        processedInternalCosts: function() {
            return this.internalCosts.items.map(item => {
                item.total = this.itemTotal(item);
                return item;
            });
        },
        forecastContent: function() {
            if (this.$route.params.taskId) {
                return this.internalCosts.forecast;
            }
            return this.baseTotal;
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

<style scoped lang="scss">
    .note {
        padding-left: 10px;
        margin: 0;

        &.blue-note {
            margin: -10px 0 20px;
            padding-left: 0;
        }
    }

    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }
</style> 
