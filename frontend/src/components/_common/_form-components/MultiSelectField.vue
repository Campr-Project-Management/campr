<template>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
            {{ title }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li v-for="option in processedOptions">
                <a href="javascript:void(0)" v-on:click="updateValue(option)">
                    {{ option.label }}
                </a>
            </li>
        </ul>
        <p v-for="option in selectedOptions">
            {{ option.label }}
            <a v-on:click="removeSelectedOption(option)">x</a>
        </p>
    </div>
</template>

<script>
export default {
    props: ['title', 'options', 'selectedOptions'],
    computed: {
        processedOptions: function() {
            if (!this.selectedOptions || !this.options.length) {
                return this.options;
            }

            let selectedOptions = new Set(this.selectedOptions);
            return [...new Set([...this.options].filter(option => !selectedOptions.has(option)))];
        },
    },
    methods: {
        updateValue: function(value) {
            this.$emit('input', [...this.selectedOptions, value]);
        },
        removeSelectedOption: function(value) {
            this.$emit('input', this.selectedOptions.filter(option => option.key !== value.key));
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables.scss';
    @import '../../../css/_mixins.scss';

    .btn-primary {
        background: $darkColor;
        color: $lightColor;
        border: none;
        width: 100%;
        text-transform: uppercase;
        height: 40px;
        font-size: 11px;
        border-radius: 1px;
        text-align: left;
        padding-left: 20px;
        padding-right: 22px;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 11px;
        letter-spacing: 1.5px;
        @include transition(all, 0.2s, ease-in);

        @media screen and (max-width: 1440px) {
            width: 120px;
        }

        .caret {
            right: 8px;
            margin-top: 4px;
            position: absolute;
        }

        &:focus {
            background: $middleColor;
            color: $lighterColor;
            outline: 0;
        }
        
    }

    .btn-primary.active, .btn-primary:active, .open > .dropdown-toggle.btn-primary {
        background: $middleColor;
        color: $lighterColor;
    }
</style>
