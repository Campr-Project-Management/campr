<template>
    <div>
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
        </div>
        <p v-for="option in selectedOptions" class="multiselect-option">
            {{ option.label }}
            <a v-on:click="removeSelectedOption(option)"> <i class="fa fa-times"></i></a>
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
        letter-spacing: 0.1em;
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
            right: 20px;
            top: 18px;
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

    .multiselect-option {
        padding: 11px 20px 9px;
        background-color: $fadeColor;
        margin-top: 3px;
        color: $secondColor;
        position: relative;

        i.fa {
            position: absolute;
            right: 20px;
            top: 13px;
            color: $dangerColor;
            cursor: pointer;
            @include transition(opacity, 0.2s, ease-in);

            &:hover,
            &:active {
                @include opacity(0.8);
            }
        }
    }
</style>
