<template>
    <div class="project-duration-slider">
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

    let values = [];
    for (let i = 1; i <= 37; i++) {
        values.push(i);
    }

    export default {
        name: 'ProjectDurationSlider',
        props: {
            value: {
                type: Number,
                required: false,
                default: 1,
                validator(value) {
                    return values.indexOf(value) >= 0;
                },
            },
            title: {
                type: String,
                required: false,
                default: 'message.project_duration',
            },
        },
        components: {
            RangeSlider,
        },
        methods: {
            onChange(value) {
                this.$emit('input', value);
            },
        },
        data() {
            let labels = [
                this.translate('message.project_duration_slider.label.lte', {'number': values[0]}),
            ];

            for (let i = 1; i < values.length - 1; i++) {
                labels.push(this.translate('message.project_duration_slider.label.months', {'number': values[i]}));
            }
            labels.push(this.translate('message.project_duration_slider.label.gt', {'number': values[values.length - 2]}));

            return {
                values: values,
                labels: labels,
            };
        },
    };
</script>

