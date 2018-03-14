<template>
    <div>
        <h3>{{ message.external_costs }}</h3>
        <span class="note blue-note">{{ message.project_currency }} <i class="fa fa-dollar"></i> USD</span>
        <div v-for="(item, index) in value.items">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-10">
                        <input-field
                            type="text"
                            v-bind:label="label.external_cost_description"
                            :value="item.name"
                            v-bind:content="item.name"
                            v-on:input="onItemUpdate('name', index, $event)" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="number"
                            v-bind:label="label.external_cost_qty"
                            :value="item.quantity"
                            v-bind:content="item.quantity"
                            v-on:input="onItemUpdate('quantity', index, $event)"
                        />
                        <error
                            v-if="getValidationMessages(index, 'quantity').length"
                            v-for="message in getValidationMessages(index, 'quantity')"
                            :message="message" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-8">
                        <radio-field
                            name="units"
                            v-bind:options="projectUnitsForSelect"
                            v-bind:currentOption="(item.unit && item.unit.id) || 'custom'"
                            v-on:input="onItemUpdate('unit', index, $event)"/>
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="label.custom"
                            :value="item.customUnit"
                            v-bind:content="item.customUnit"
                            v-bind:disabled="item.unit && item.unit !== 'custom'"
                            v-on:input="onItemUpdate('customUnit', index, $event)"/>
                        <error
                            v-if="getValidationMessages(index, 'unit').length"
                            v-for="message in getValidationMessages(index, 'unit')"
                            :message="message" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="number"
                            v-bind:label="label.external_cost_unit_rate"
                            :value="item.rate"
                            v-bind:content="item.rate"
                            v-on:input="onItemUpdate('rate', index, $event)"/>
                        <error
                            v-if="getValidationMessages(index, 'rate').length"
                            v-for="message in getValidationMessages(index, 'rate')"
                            :message="message" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-12">
                        <span class="title">
                            {{ label.external_cost_total }} <b><i class="fa fa-dollar"></i> {{ baseTotal }}</b>
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
                            <switches
                                    :value="item.capex"
                                    v-bind:selected="item.capex"
                                    v-on:input="onItemUpdate('capex', index, $event)"/>
                            <span class="note no-margin-bottom"><b>{{ message.note }}</b> {{ message.opex_capex_note }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="flex flex-direction-reverse">
                            <button
                                    @click="onDelete(index)"
                                    type="button"
                                    class="btn-icon">
                                <delete-icon fill="danger-fill"/>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <div class="flex flex-direction-reverse">
            <a v-on:click="add" class="btn-rounded btn-auto">{{ button.add_external_cost }} +</a>
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
                        type="number"
                        v-bind:label="label.external_cost_forecast"
                        v-bind:content="value.forecast"
                        :value="value.forecast"
                        v-on:input="onUpdate('forecast', $event)"/>
                </div>
                <div class="col-md-4">
                    <input-field
                        type="number"
                        v-bind:label="label.external_cost_actual"
                        v-bind:content="value.actual"
                        :value="value.actual"
                        v-on:input="onUpdate('actual', $event)"/>
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
import Error from '../../../_common/_messages/Error.vue';
import {mapActions, mapGetters} from 'vuex';

export default {
    props: {
        validationMessages: {
            type: [Object, Array],
            required: false,
        },
        value: {
            type: Object,
            required: false,
            default: {},
        },
    },
    components: {
        InputField,
        SelectField,
        RadioField,
        Switches,
        DeleteIcon,
        Error,
    },
    methods: {
        ...mapActions(['getProjectUnits']),
        add: function() {
            this.$emit('add');
        },
        onDelete(index) {
            let data = Object.assign({}, this.value);
            data.items = [
                ...data.items.slice(0, index),
                ...data.items.slice(index + 1),
            ];

            this.$emit('input', data);
        },
        itemTotal(item) {
            return !isNaN(item.rate * item.quantity) ? item.rate * item.quantity : 0;
        },
        getValidationMessages(index, key) {
            if (this.validationMessages[index] && this.validationMessages[index][key]) {
                return this.validationMessages[index][key];
            }
            return [];
        },
        onItemUpdate(property, index, value) {
            let data = Object.assign({}, this.value);
            data.items[index][property] = value;

            this.$emit('input', data);
        },
        onUpdate(property, value) {
            let data = Object.assign({}, this.value);
            data[property] = value;

            this.$emit('input', data);
        },
    },
    computed: {
        ...mapGetters({
            projectUnitsForSelect: 'projectUnitsForSelect',
        }),
        baseTotal: function() {
            return this.value.items.reduce((prev, next) => {
                return prev + this.itemTotal(next);
            }, 0);
        },
        opexSubtotal: function() {
            return this.value.items.reduce((prev, next) => {
                return next.opex ? prev + next.total : prev;
            }, 0);
        },
        capexSubtotal: function() {
            return this.value.items.reduce((prev, next) => {
                return next.capex ? prev + next.total : prev;
            }, 0);
        },
    },
    created() {
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
                external_cost_total: this.translate('message.total'),
                external_cost_forecast: this.translate('label.forecast'),
                external_cost_actual: this.translate('label.actual'),
            },
            button: {
                add_external_cost: this.translate('button.add_external_cost'),
            },
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../../css/_common';

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
