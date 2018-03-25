<template>
    <div>
        <div class="dropdown">
            <button
                class="btn btn-primary dropdown-toggle"
                type="button"
                data-toggle="dropdown"
                @click="updateScrollbarTop()"
                ref="btn-dropdown"
            >
                {{ title }}
                <span class="caret"></span>
            </button>
            <scrollbar v-if="processedOptions.length !== 0" :style="{height: scrollbarHeight + 'px', top: scrollbarTop + 'px'}" class="dropdown-menu dropdown-menu-right">
                <ul ref="ul">
                    <li v-for="(option, index) in processedOptions" :key="index">
                        <a href="javascript:void(0)" @click="updateValue(option)">
                            {{ option.label }}
                        </a>
                    </li>
                </ul>
            </scrollbar>
        </div>
        <scrollbar :style="{height: (3 * itemHeight) + 'px'}" class="multiselect-content">
            <div>
                <p v-for="(option, index) in selectedOptions" :key="index" class="multiselect-option">
                    {{ option.label }}
                    <a @click="removeSelectedOption(option)"> <i class="fa fa-times"></i></a>
                </p>
            </div>
        </scrollbar>
    </div>
</template>

<script>
import $ from 'jquery';

export default {
    props: {
        title: {
            type: String,
            required: false,
            default: null,
        },
        options: {
            type: Array,
            required: true,
            default: [],
        },
        selectedOptions: {
            type: Array,
            required: true,
            default: [],
        },
        maxItems: {
            type: Number,
            default: 3,
        },
    },
    computed: {
        processedOptions() {
            if (!this.selectedOptions || !this.options.length) {
                return this.options;
            }

            let selectedOptions = new Set(this.selectedOptions);
            return [...new Set([...this.options].filter(option => !selectedOptions.has(option)))];
        },
        scrollbarHeight() {
            const itemsToShow = this.processedOptions.length < this.maxItems
                ? this.processedOptions.length
                : this.maxItems
            ;

            return (itemsToShow * this.itemHeight)
                + (this.marginBottom + this.marginTop)
                + (this.paddingBottom + this.paddingTop);
        },
    },
    methods: {
        updateValue(value) {
            this.$emit('input', [...this.selectedOptions, value]);
        },
        removeSelectedOption(value) {
            this.$emit('input', this.selectedOptions.filter(option => option.key !== value.key));
        },
        updateScrollbarTop() {
            let scrollTop = $(window).scrollTop();
            let elementOffset = $(this.$el).offset().top;
            let currentElementOffset = (elementOffset - scrollTop);
            let windowInnerHeight = window.innerHeight;

            if (windowInnerHeight - currentElementOffset < (this.scrollbarHeight + this.itemHeight)) {
                this.scrollbarTop = -1 * this.scrollbarHeight;
            } else {
                this.scrollbarTop = this.itemHeight;
            }
        },
    },
    mounted() {
        let $ul = $(this.$refs.ul);

        this.itemHeight = $(this.$el).height();
        this.marginTop = parseInt($ul.css('margin-top'), 10);
        this.marginBottom = parseInt($ul.css('margin-bottom'), 10);
        this.paddingTop = parseInt($ul.css('padding-top'), 10);
        this.paddingBottom = parseInt($ul.css('padding-bottom'), 10);
        this.scrollbarTop = this.itemHeight;
    },
    data() {
        return {
            itemHeight: 0,
            paddingTop: 0,
            paddingBottom: 0,
            marginTop: 0,
            marginBottom: 0,
            scrollbarTop: 0,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables.scss';
    @import '../../../css/_mixins.scss';

    .dropdown {
        .dropdown-menu {
            position: absolute;

            ul {
                list-style: none;
                margin: 0;
                padding: 5px;
            }
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
