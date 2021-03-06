<template>
    <div :class="{disabled: disabled}">
        <div class="dropdown">
            <button
                    class="btn btn-primary dropdown-toggle"
                    type="button"
                    data-toggle="dropdown"
                    @click="updateScrollbarTop($event)"
                    ref="dropdownButton"
                    :title="title">

                <span class="multi-select-field-title">{{ title }}</span>
                <span class="caret"></span>
            </button>
            <scrollbar
                    v-show="visibleOptions.length > 0"
                    :style="{height: scrollbarHeight + 5 + 'px', top: scrollbarTop + 'px'}"
                    v-if="!disabled"
                    class="dropdown-menu dropdown-menu-right customScrollbar">
                <ul ref="ul">
                    <li v-for="option in visibleOptions" :key="option.key">
                        <a href="javascript:void(0)" @click="onSelect(option)">{{ option.label }}</a>
                    </li>
                </ul>
            </scrollbar>
        </div>
        <scrollbar class="multiselect-content customScrollbar">
            <div>
                <p v-for="option in selection"
                   :key="option.key"
                   class="multiselect-option">
                    {{ option.label }}
                    <a v-if="!disabled"
                       @click="onRemove(option)"
                       :title="translate('message.clear_selection')"> <i class="fa fa-times"></i></a>
                </p>
            </div>
        </scrollbar>
    </div>
</template>

<script>
    import $ from 'jquery';
    import _ from 'lodash';

    export default {
        props: {
            value: {
                type: Array,
                required: false,
                default: () => [],
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
            disabled: {
                type: Boolean,
                required: false,
                default: false,
            },
        },
        computed: {
            selection() {
                if (!this.value) {
                    return [];
                }

                return _.uniqBy(this.options.filter((o) => _.find(this.value, ['key', o.key])), 'key');
            },
            visibleOptions() {
                return this.availableOptions.filter(option => !option.hidden);
            },
            availableOptions() {
                let options = _.uniqBy(this.options, 'key');

                return options.filter((option) => {
                    return !_.find(this.value, ['key', option.key]);
                });
            },
            scrollbarHeight() {
                const count = Math.min(this.visibleOptions.length, this.maxItems);

                return (count * this.itemHeight)
                    + (this.marginBottom + this.marginTop)
                    + (this.paddingBottom + this.paddingTop);
            },
        },
        methods: {
            onSelect(option) {
                if (this.disabled) {
                    return;
                }

                let value = _.uniqBy([...this.value, option], 'key');

                this.$emit('input', value);
            },
            onRemove(option) {
                if (this.disabled) {
                    return;
                }

                let value = this.value.filter((selected) => selected.key !== option.key);

                this.$emit('input', value);
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

                if (windowInnerHeight - currentElementOffset < (this.scrollbarHeight + (this.itemHeight / 1.1))) {
                    this.scrollbarTop = -1 * this.scrollbarHeight;
                } else {
                    this.scrollbarTop = this.itemHeight / 1.1;
                }
            },
        },
        mounted() {
            let $ul = $(this.$refs.ul);
            let buttonHeight = $(this.$refs.dropdownButton).outerHeight();

            this.itemHeight = buttonHeight * 1.1;
            this.marginTop = parseInt($ul.css('margin-top'), 10);
            this.marginBottom = parseInt($ul.css('margin-bottom'), 10);
            this.paddingTop = parseInt($ul.css('padding-top'), 10);
            this.paddingBottom = parseInt($ul.css('padding-bottom'), 10);
            this.scrollbarTop = buttonHeight;
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
    @import '~theme/variables';
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
            .multi-select-field-title {
                display: inline-block;
                width: 90%;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                min-height: 100%;
                line-height: 2.5em;
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
        border-radius: 1px;
        text-align: left;
        padding-left: 20px;
        padding-right: 22px;
        position: relative;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: 1.5px;
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

    .multiselect-option {
        padding: 11px 20px 9px;
        background-color: $fadeColor;
        margin-top: 3px;
        color: $secondColor;
        position: relative;
        text-transform: uppercase;

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
