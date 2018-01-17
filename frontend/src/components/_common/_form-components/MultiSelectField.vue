<template>
    <div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" @click="dropdownToggle()" ref="btn-dropdown">
                {{ title }}
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right nicescroll">
                <li v-for="(option, index) in processedOptions" :key="index">
                    <a href="javascript:void(0)" @click="updateValue(option)">
                        {{ option.label }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="multiselect-content nicescroll" ref="multiselect-content">
            <p v-for="(option, index) in selectedOptions" :key="index" class="multiselect-option">
                {{ option.label }}
                <a @click="removeSelectedOption(option)"> <i class="fa fa-times"></i></a>
            </p>
        </div>
    </div>
</template>

<script>
import 'jquery.nicescroll/jquery.nicescroll.js';
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
        dropdownToggle: function() {
            let scrollTop = $(window).scrollTop();
            let elementOffset = $(this.$el).offset().top;
            let currentElementOffset = (elementOffset - scrollTop);

            let windowInnerHeight = window.innerHeight;

            if (windowInnerHeight - currentElementOffset < 3*this.dropdownItemHeight) {
                $(this.$el).find('.dropdown-menu').css('top', -3*this.dropdownItemHeight + 'px');
            } else {
                $(this.$el).find('.dropdown-menu').css('top', this.dropdownItemHeight + 'px');
            }
        },
        setDropdownMenuHeight() {
            $(this.$el).find('.dropdown-menu').css('height', 3*this.dropdownItemHeight + 'px');
            $(this.$el).find('.multiselect-content').css('height', 3*this.multiSelectItemHeight + 'px');
        },
        addNiceSCrollEvent() {
            window.$(document).ready(function() {
                window.$('.nicescroll').niceScroll({
                    autohidemode: false,
                });
            });
        },
    },
    mounted() {
        this.setDropdownMenuHeight();
        this.addNiceSCrollEvent();
    },
    data() {
        return {
            dropdownItemHeight: 42,
            multiSelectItemHeight: 42,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables.scss';
    @import '../../../css/_mixins.scss';

    .dropdown-menu{
        &.nicescroll{
             max-height : 200px;
         }
    }

    .multiselect-content{
        &.nicescroll{
             max-height : 200px;
         }
    }

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
