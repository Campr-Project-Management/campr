<template>
    <div class="project-budget-slider">
        <range-slider
                :title="translate(title)"
                :values="values"
                :value="value"
                :labels="labels"
                @input="onChange"/>
    </div>
</template>

<script>
    import RangeSlider from '../../_common/_form-components/RangeSlider';

    let values = [
        10,
        50,
        100,
        150,
        200,
        300,
        400,
        500,
        750,
        1000,
        2000,
        5000,
        7500,
        10000,
        25000,
        50000,
        75000,
        100000,
        200000,
        300000,
        500000,
    ];

    values.forEach((value, i) => values[i] = value * 1000);

    export default {
        name: 'ProjectBudgetSlider',
        props: {
            value: {
                type: Number,
                required: false,
                default: 10000,
                validator(value) {
                    return values.indexOf(value) >= 0;
                },
            },
            title: {
                type: String,
                required: false,
                default: 'message.project_budget',
            },
            currency: {
                type: String,
                required: true,
            },
        },
        components: {
            RangeSlider,
        },
        methods: {
            onChange(value) {
                this.$emit('input', value);
            },
            formatAmount(amount) {
                return this.formatMoney(amount, {
                    symbol: this.currency,
                    precision: 0,
                });
            },
        },
        data() {
            let labels = [
                this.translate('message.project_budget_slider.label.lte',
                    {'amount': `${this.formatAmount(values[0] / 1000)}K`}),
            ];

            for (let i = 1; i < values.length - 1; i++) {
                let amount = values[i] / 1000;
                labels.push(`${this.formatAmount(amount)}K`);
            }
            labels.push(this.translate('message.project_budget_slider.label.gt',
                {'amount': `${this.formatAmount(values[values.length - 1] / 1000)}K`}));

            return {
                values: values,
                labels: labels,
            };
        },
    };
</script>
