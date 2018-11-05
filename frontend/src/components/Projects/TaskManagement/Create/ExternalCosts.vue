<template>
    <div>
        <h3>{{ translate('message.external_costs') }}</h3>
        <div v-for="(item, index) in value.items">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-10">
                        <input-field
                            type="text"
                            :label="translate('label.cost_description')"
                            :value="item.name"
                            :content="item.name"
                            @input="onItemUpdate(index, 'name', $event)" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="number"
                            :label="translate('label.external_cost_qty')"
                            :value="item.quantity"
                            :content="item.quantity"
                            @input="onItemUpdate(index, 'quantity', $event)"
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
                            :options="projectUnitsForSelect"
                            :currentOption="(item.unit && item.unit.id) || 'custom'"
                            @input="onItemUpdate(index, 'unit', $event)"/>
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            :label="translate('label.custom')"
                            :value="item.customUnit"
                            :content="item.customUnit"
                            :disabled="item.unit && item.unit !== 'custom'"
                            @input="onItemUpdate(index, 'customUnit', $event)"/>
                        <error
                            v-if="getValidationMessages(index, 'unit').length"
                            v-for="message in getValidationMessages(index, 'unit')"
                            :message="message" />
                    </div>
                    <div class="col-md-2">
                        <money-field
                            :label="translate('label.external_cost_unit_rate')"
                            :currency="projectCurrencySymbol"
                            :value="item.rate"
                            @input="onItemUpdate(index, 'rate', $event)"/>
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
                            {{ translate('message.total') }} <b>{{ itemTotal(item) | money({symbol: projectCurrencySymbol}) }}</b>
                        </span>
                    </div>
                </div>
            </div>
            <hr>
            <h4>{{ translate('message.capex') }}</h4>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-10">
                        <div class="flex flex-v-center">
                            <switch-field
                                    :true-value="0"
                                    :false-value="1"
                                    :value="item.expenseType"
                                    @input="onItemUpdate(index, 'expenseType', $event)"/>
                            <span class="note no-margin-bottom"><b>{{ translate('message.note') }}</b> {{ translate('message.opex_capex_note') }}</span>
                            <error
                                    v-if="getValidationMessages(index, 'expenseType').length"
                                    v-for="message in getValidationMessages(index, 'expenseType')"
                                    :message="message" />
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
            <a @click="onAdd" class="btn-rounded btn-auto">{{ translate('button.add_external_cost') }} +</a>
        </div>
        <hr>
        <div class="row">
            <div class="form-group">
                <div class="col-md-4">
                    <span class="title">
                        {{ translate('message.capex_subtotal') }} <b>{{ capexSubtotal | money({symbol: projectCurrencySymbol}) }}</b>
                    </span>
                </div>
                <div class="col-md-4">
                    <span class="title">
                        {{ translate('message.opex_subtotal') }} <b>{{ opexSubtotal | money({symbol: projectCurrencySymbol}) }}</b>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-4">
                    <span class="title">
                        {{ translate('message.total') }} <b>{{ baseTotal | money({symbol: projectCurrencySymbol}) }}</b>
                    </span>
                </div>
                <div class="col-md-4">
                    <money-field
                        :label="translate('label.forecast')"
                        :value="value.forecast"
                        :currency="projectCurrencySymbol"
                        @input="onUpdate('forecast', $event)"/>
                </div>
                <div class="col-md-4">
                    <money-field
                        :label="translate('label.actual')"
                        :value="value.actual"
                        :currency="projectCurrencySymbol"
                        @input="onUpdate('actual', $event)"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../../../_common/_form-components/InputField';
import MoneyField from '../../../_common/_form-components/MoneyField';
import SelectField from '../../../_common/_form-components/SelectField';
import RadioField from '../../../_common/_form-components/RadioField';
import Switches from '../../../3rdparty/vue-switches';
import DeleteIcon from '../../../_common/_icons/DeleteIcon';
import Error from '../../../_common/_messages/Error.vue';
import {mapActions, mapGetters} from 'vuex';
import SwitchField from '../../../_common/_form-components/SwitchField';
import _ from 'lodash';

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
        SwitchField,
        MoneyField,
    },
    methods: {
        ...mapActions(['getProjectUnits']),
        onAdd() {
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
            return _.toFinite(item.rate * item.quantity);
        },
        getValidationMessages(index, key) {
            if (this.validationMessages[index] && this.validationMessages[index][key]) {
                return this.validationMessages[index][key];
            }
            return [];
        },
        onItemUpdate(index, property, value) {
            let data = Object.assign({}, this.value);
            data.items[index][property] = value;

            this.$emit('input', data);
        },
        onUpdate(property, value) {
            let data = Object.assign({}, this.value);
            data[property] = value;

            this.$emit('input', data);
        },
        isOPEX(item) {
            return item.expenseType === 1;
        },
        isCAPEX(item) {
            return !this.isOPEX(item);
        },
    },
    computed: {
        ...mapGetters([
            'projectUnitsForSelect',
            'projectCurrencySymbol',
        ]),
        baseTotal() {
            return this.value.items.reduce((total, item) => {
                return total + this.itemTotal(item);
            }, 0);
        },
        opexSubtotal() {
            return this.value.items.reduce((total, item) => {
                return this.isOPEX(item) ? total + this.itemTotal(item) : total;
            }, 0);
        },
        capexSubtotal() {
            return this.value.items.reduce((total, item) => {
                return this.isCAPEX(item) ? total + this.itemTotal(item) : total;
            }, 0);
        },
    },
    created() {
        this.getProjectUnits(this.$route.params.id);
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
