<template>
    <div>
        <h3>{{ message.external_costs }}</h3>
        <span class="note blue-note">{{ message.project_currency }} <i class="fa fa-dollar"></i> USD</span>
        <div v-for="(cost, index) in processedExternalCosts">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-10">
                        <input-field
                            type="text"
                            v-bind:label="label.external_cost_description"
                            v-model="cost.description"
                            v-bind:content="cost.description" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="label.external_cost_qty"
                            v-model="cost.qty"
                            v-bind:content="cost.qty" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-8">
                        <radio-field
                            name="units"
                            v-bind:options="projectUnitsForSelect"
                            v-bind:currentOption="cost.selectedUnit"
                            v-model="cost.selectedUnit" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="label.custom"
                            v-model="cost.customUnit"
                            v-bind:content="cost.customUnit"
                            v-bind:disabled="cost.selectedUnit !== 'custom'" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="label.external_cost_unit_rate"
                            v-model="cost.unitRate"
                            v-bind:content="cost.unitRate" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-12">
                        <span class="title">
                            {{ label.external_cost_total }} <b><i class="fa fa-dollar"></i> {{ cost.total }}</b>
                        </span>
                    </div>
                </div>
            </div>
            <hr>
            <h4>{{ message.capex }}</h4>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-10">
                        <div class="flex flex-v-center">
                            <switches v-model="cost.capex" v-bind:selected="cost.capex"></switches>
                            <span class="note no-margin-bottom"><b>{{ message.note }}</b> {{ message.opex_capex_note }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="flex flex-direction-reverse">
                            <span v-on:click="deleteExternalCost(index);"><delete-icon /></span>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <div class="flex flex-direction-reverse">
            <a v-on:click="addExternalCost()" class="btn-rounded btn-auto">{{ button.add_external_cost }} +</a>
        </div>
        <hr>
        <div class="row">
            <div class="form-group">
                <div class="col-md-4">
                    <span class="title">
                        {{ message.capex_subtotal }} <b><i class="fa fa-dollar"></i> {{ capexSubtotal }}</b>
                    </span>
                </div>
                <div class="col-md-4">
                    <span class="title">
                        {{ message.opex_subtotal }} <b><i class="fa fa-dollar"></i> {{ opexSubtotal }}</b>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-4">
                    <span class="title">
                        {{ message.external_costs_total }} <b><i class="fa fa-dollar"></i> {{ baseTotal }}</b>
                    </span>
                </div>
                <div class="col-md-4">
                    <input-field
                        type="text"
                        v-bind:label="label.external_cost_forecast"
                        v-bind:content="externalCosts.forecast"
                        v-model="externalCosts.forecast" />
                </div>
                <div class="col-md-4">
                    <input-field
                        type="text"
                        v-bind:label="label.external_cost_actual"
                        v-bind:content="externalCosts.actual"
                        v-model="externalCosts.actual" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../../_common/_form-components/InputField';
import SelectField from '../../../_common/_form-components/SelectField';
import RadioField from '../../../_common/_form-components/RadioField';
import Switches from '../../../3rdparty/vue-switches';
import DeleteIcon from '../../../_common/_icons/DeleteIcon';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        InputField,
        SelectField,
        RadioField,
        Switches,
        DeleteIcon,
    },
    methods: {
        ...mapActions(['getProjectUnits']),
        addExternalCost: function() {
            this.externalCosts.items.push({
                description: '',
                qty: 1,
                unitRate: 1,
                capex: 0,
                opex: 1,
                customUnit: '',
                selectedUnit: 'custom',
            });
        },
        deleteExternalCost: function(index) {
            this.externalCosts.items = [
                ...this.externalCosts.items.slice(0, index),
                ...this.externalCosts.items.slice(index + 1),
            ];
        },
    },
    watch: {
        externalCosts: {
            handler: function(value) {
                this.$emit('input', value);
            },
            deep: true,
        },
    },
    computed: {
        ...mapGetters({
            projectUnitsForSelect: 'projectUnitsForSelect',
        }),
        baseTotal: function() {
            return this.processedExternalCosts.reduce((prev, next) => {
                return prev + next.total;
            }, 0);
        },
        opexSubtotal: function() {
            return this.processedExternalCosts.reduce((prev, next) => {
                return next.opex ? prev + next.total : prev;
            }, 0);
        },
        capexSubtotal: function() {
            return this.processedExternalCosts.reduce((prev, next) => {
                return next.capex ? prev + next.total : prev;
            }, 0);
        },
        processedExternalCosts: function() {
            return this.externalCosts.items.map(item => {
                item.total = item.qty * item.unitRate;
                item.unit = item.selectedUnit !== 'custom' ? item.selectedUnit : item.customUnit;
                item.opex = !item.capex;
                return item;
            });
        },
    },
    created() {
        if (!this.externalCosts.items.length) {
            this.addExternalCost();
        }

        this.getProjectUnits(this.$route.params.id);
    },
    data() {
        return {
            message: {
                external_costs: this.translate('message.external_costs'),
                project_currency: this.translate('message.project_currency'),
                note: this.translate('message.note'),
                capex: this.translate('message.capex'),
                opex_capex_note: this.translate('message.opex_capex_note'),
                capex_subtotal: this.translate('message.capex_subtotal'),
                opex_subtotal: this.translate('message.opex_subtotal'),
                external_costs_total: this.translate('message.total'),
            },
            label: {
                custom: this.translate('label.custom'),
                external_cost_description: this.translate('label.cost_description'),
                external_cost_qty: this.translate('label.external_cost_qty'),
                external_cost_unit_rate: this.translate('label.external_cost_unit_rate'),
                external_cost_total: this.translate('label.total'),
                external_cost_forecast: this.translate('label.forecast'),
                external_cost_actual: this.translate('label.actual'),
            },
            button: {
                add_external_cost: this.translate('button.add_external_cost'),
            },
            externalCosts: {
                items: [],
                forecast: 0,
                actual: 0,
            },
        };
    },
};
</script>

<style scoped lang="scss">
    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }

    .vue-switcher {
        margin: 3px 10px 0 0;
    }
</style>    
