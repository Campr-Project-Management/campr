<template>
    <div>
        <span class="title pull-left">{{ title }}</span>
        <div class="radio-input radio-list clearfix">
            <div v-for="option in options">
                <input
                    type="radio"
                    v-bind:name="name + '_' + _uid"
                    v-bind:id="name + '_' + option.key + '_' + _uid"
                    v-bind:value="option.key"
                    v-bind:checked="isChecked(option)"
                    v-on:change="updateValue($event.target.value)">
                <label v-bind:for="name + '_' + option.key + '_' + _uid">
                    {{ option.label }}
                </label>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['title', 'options', 'currentOption', 'name'],
    methods: {
        updateValue: function(value) {
            this.$emit('input', value);
        },
        isChecked(option) {
            return option.key === this.currentOption ||
                (this.currentOption && option.key.toString() === this.currentOption.toString());
        },
    },
};
</script>

<style scoped lang="scss">
    .radio-list {
        label {
            margin: 10px 10px 10px 0;
        }
    }
</style> 
