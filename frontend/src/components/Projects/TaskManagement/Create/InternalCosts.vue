<template>
    <div>
        <h3>{{ translate('message.internal_costs') }}</h3>
        <div v-for="(item, index) in value.items">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <select-field
                                :title="item.name || translate('label.description')"
                                :options="projectUsersForSelect"
                                @input="onItemUpdate('name', index, $event)"/>
                        <error :message="getValidationMessages(index, 'name')"/>
                    </div>
                    <div class="col-md-2">
                        <money-field
                                :label="translate('label.daily_rate')"
                                :currency="projectCurrencySymbol"
                                :value="item.rate"
                                @input="onItemUpdate('rate', index, $event)"/>
                    </div>
                    <div class="col-md-2">
                        <input-field
                                type="number"
                                :label="translate('label.qty')"
                                :value="item.quantity"
                                :content="item.quantity"
                                @input="onItemUpdate('quantity', index, $event)"/>
                    </div>
                    <div class="col-md-2">
                        <input-field
                                type="number"
                                :label="translate('label.days')"
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
                            {{ translate('label.internal_cost_subtotal') }} <b>{{ itemTotal(item) | money({symbol: projectCurrencySymbol}) }}</b>
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
                        <a @click="onAdd" class="btn-rounded btn-auto">{{ translate('button.add_internal_cost') }} +</a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group last-form-group">
                <div class="col-md-4">
                    <span class="title">{{ translate('message.total') }} <b>{{ baseTotal | money({symbol: projectCurrencySymbol}) }}</b></span>
                </div>
                <div class="col-md-4">
                    <money-field
                            :label="translate('label.forecast_total')"
                            :currency="projectCurrencySymbol"
                            :value="value.forecast"
                            @input="onUpdate('forecast', $event)"/>
                </div>
                <div class="col-md-4">
                    <money-field
                            :label="translate('label.actual_total')"
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
        created() {
            this.getProjectDepartments({project: this.$route.params.id});
        },
        computed: {
            ...mapGetters([
                'projectCurrencySymbol',
                'projectUsers',
            ]),
            baseTotal() {
                return this.value.items.reduce((prev, next) => {
                    return prev + this.itemTotal(next);
                }, 0);
            },
            projectUsersForSelect() {
                if (!this.projectUsers || !this.projectUsers.items) {
                    return [];
                }

                return this.projectUsers.items.filter(item => item.rate !== null).map(item => {
                    return {
                        'key': item.user,
                        'label': item.userFullName,
                        'hidden': item.userDeleted,
                        'rate': item.rate,
                    };
                });
            },
        },
        methods: {
            ...mapActions([
                'getProjectDepartments',
            ]),
            onAdd() {
                const data = Object.assign({}, this.value);
                data.items.push({
                    name: '',
                    quantity: 1,
                    duration: 1,
                    rate: 0,
                });

                this.$emit('input', data);
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
                if (property === 'name') {
                    data.items[index].name = value.label;
                    data.items[index].rate = value.rate;
                }

                this.$emit('input', data);
            },
            onUpdate(property, value) {
                let data = Object.assign({}, this.value);
                data[property] = value;

                this.$emit('input', data);
            },
            getValidationMessages(index, key) {
                if (!this.validationMessages[index] || !this.validationMessages[index][key]) {
                    return [];
                }
                return this.validationMessages[index][key];
            },
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
