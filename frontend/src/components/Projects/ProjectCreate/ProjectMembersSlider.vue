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
        3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30, 35, 40, 50, 60, 70, 80, 90, 100, 110, 120, 121,
    ];

    export default {
        name: 'ProjectMembersSlider',
        props: {
            value: {
                type: Number,
                required: false,
                default: 3,
                validator(value) {
                    return values.indexOf(value) >= 0;
                },
            },
            title: {
                type: String,
                required: false,
                default: 'message.project_members',
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
                this.translate('message.project_members_slider.label.lte',
                    {'count': `${values[0]}`}),
            ];

            for (let i = 1; i < values.length - 1; i++) {
                labels.push(values[i]);
            }
            labels.push(this.translate('message.project_members_slider.label.gt',
                {'count': `${values[values.length - 2]}`}));

            return {
                values: values,
                labels: labels,
            };
        },
    };
</script>
