<template>
    <div>
        <h3>{{ message.internal_costs }}</h3>
        <div v-for="(item, index) in value.items">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <select-field
                            :title="translate('label.cost_item')"
                            :options="resourcesForSelect"
                            :value="{key: item.resource}"
                            @input="onItemUpdate('resource', index, $event)" />
                        <error
                            v-if="getValidationMessages(index, 'resource').length"
                            v-for="message in getValidationMessages(index, 'resource')"
                            :message="message" />
                    </div>
                    <div class="col-md-2">
                        <money-field
                            :label="label.daily_rate"
                            :currency="projectCurrencySymbol"
                            :value="item.rate"
                            @input="onItemUpdate('rate', index, $event)" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="number"
                            :label="label.qty"
                            :value="item.quantity"
                            :content="item.quantity"
                            @input="onItemUpdate('quantity', index, $event)"/>
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="number"
                            :label="label.days"
                            :value="item.duration"
                            :content="item.duration"
                            @input="onItemUpdate('duration', index, $event)"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-6">
                        <span class="title">
                            {{ label.internal_cost_subtotal }} <b>{{ itemTotal(item) | money({symbol: projectCurrencySymbol}) }}</b>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
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
        </div>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a @click="onAdd" class="btn-rounded btn-auto">{{ button.add_internal_cost }} +</a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group last-form-group">                
                <div class="col-md-4">
                    <span class="title">{{ message.base_total }} <b>{{ baseTotal | money({symbol: projectCurrencySymbol}) }}</b></span>
                </div>
                <div class="col-md-4">
                    <money-field
                        :label="label.forecast_total"
                        :currency="projectCurrencySymbol"
                        :value="value.forecast"
                        @input="onUpdate('forecast', $event)"/>
                </div>
                <div class="col-md-4">
                    <money-field
                        :label="label.actual_total"
                        :currency="projectCurrencySymbol"
                        :value="value.actual"
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
        DeleteIcon,
        Error,
        MoneyField,
    },
    methods: {
        ...mapActions([
            'getProjectDepartments',
            'getProjectResources',
        ]),
        onAdd: function() {
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
            return item.rate * item.quantity * item.duration;
        },
        onItemUpdate(property, index, value) {
            let data = Object.assign({}, this.value);
            data.items[index][property] = value;

            if (property === 'resource') {
                data.items[index]['rate'] = value.rate;
                data.items[index].selectedResource = value;
            }

            this.$emit('input', data);
        },
        onUpdate(property, value) {
            let data = Object.assign({}, this.value);
            data[property] = value;

            this.$emit('input', data);
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
        ...mapGetters([
            'projectCurrencySymbol',
        ]),
        baseTotal() {
            return this.value.items.reduce((prev, next) => {
                return prev + this.itemTotal(next);
            }, 0);
        },
        forecastContent() {
            if (this.$route.params.taskId) {
                return this.value.forecast;
            }

            return this.baseTotal;
        },
    },
    data() {
        return {
            message: {
                internal_costs: this.translate('message.internal_costs'),
                base_total: this.translate('message.total'),
            },
            label: {
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
