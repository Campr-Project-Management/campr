<template>
    <div class="dropdown" :class="{disabled: disabled}">
        <button
            ref="btn-dropdown"
            class="btn btn-primary dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            @click="updateScrollbarTop($event)"
            :title="title">

            <span class="select-field-placeholder">{{ placeholder }}</span>

            <i
                    v-if="allowClear && value"
                    class="fa fa-times select-field-clear"
                    :title="translate('message.clear_selection')"
                    @click="onClear"></i>

            <span class="caret"></span>
        </button>
        <scrollbar
                v-if="!disabled"
                v-show="availableOptions.length > 0"
                :style="{height: scrollbarHeight + 5 +'px', top: scrollbarTop + 'px'}"
                class="dropdown-menu dropdown-menu-right customScrollbar">
            <ul ref="ul">
                <li v-for="option in availableOptions" :style="{height: itemHeight + 'px'}">
                    <a href="javascript:void(0)" @click="onChange(option)">{{ translate(option.label) }}</a>
                </li>
            </ul>
        </scrollbar>
    </div>
</template>

<script>
import $ from 'jquery';
import _ from 'lodash';

export default {
    name: 'select-field',
    props: {
        value: {
            type: Object,
            required: false,
            default: null,
        },
        title: {
            type: String,
            required: false,
            default: null,
        },
        options: {
            type: Array,
            required: true,
            default: () => [],
        },
        maxItems: {
            type: Number,
            default: 3,
        },
        allowClear: {
            type: Boolean,
            default: false,
            required: false,
        },
        disabled: {
            type: Boolean,
            required: false,
        },
    },
    computed: {
        availableOptions() {
            return _.uniqBy(this.options, 'key');
        },
        placeholder() {
            let option = null;
            if (this.value) {
                option = _.find(this.availableOptions, (o) => {
                    return o.key === this.value.key;
                });
            }

            if (option) {
                return this.translate(option.label);
            }

            return this.title;
        },
        scrollbarHeight() {
            const itemsToShow = this.availableOptions.length < this.maxItems
                ? this.availableOptions.length
                : this.maxItems
            ;

            return (itemsToShow * this.itemHeight)
                + (this.marginBottom + this.marginTop)
                + (this.paddingBottom + this.paddingTop);
        },
    },
    methods: {
        onChange(value) {
            if (this.disabled) {
                return;
            }

            this.$emit('input', value);
        },
        onClear(event) {
            if (this.disabled) {
                return;
            }

            event.stopPropagation();
            this.$emit('input', null);
        },
        updateScrollbarTop(event) {
            if (this.disabled) {
                event.stopPropagation();
                return;
            }

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

    .disabled *, .disabled a {
        cursor: not-allowed;
    }

    .dropdown {
        .dropdown-menu {
            position: absolute;

            ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
        }

        button {
            .select-field-clear {
                position: absolute;
                right: 3em;
                top: 1.3em;
                color: $dangerColor;
                cursor: pointer;
                font-style: normal;
            }

            .select-field-placeholder {
                display: inline-block;
                width: 90%;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                min-height: 100%;
                line-height: 3.5em;
            }
        }
    }

    .btn-primary {
        background: $darkColor;
        color: $lightColor;
        border: none;
        width: 100% !important;
        text-transform: uppercase;
        height: 40px;
        font-size: 11px;
        line-height: 43px;
        letter-spacing: 0.1em;
        border-radius: 1px;
        text-align: left;
        padding: 0 35px 0 20px;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        @include transition(all, 0.2s, ease-in);

        .caret {
            right: 20px;
            top: 18px;
            position: absolute;
        }

        &:focus {
            outline: 0;
        }

        &:hover,
        &:focus {
            background: $middleColor;
            border-color: $darkColor;
        }
    }

    .btn-primary.active, .btn-primary:active,
    .open > .dropdown-toggle.btn-primary {
        background-color: $middleColor;
        border-color: $darkColor;

        &:hover,
        &:focus {
            background-color: $middleColor;
            border-color: $darkColor;
        }
    }
</style>
