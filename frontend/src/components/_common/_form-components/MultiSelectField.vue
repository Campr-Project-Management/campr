<template>
    <div>
        <div class="dropdown">
            <button
                class="btn btn-primary dropdown-toggle"
                type="button"
                data-toggle="dropdown"
                @click="updateScrollbarTop()"
                ref="btn-dropdown"
                :title="title">

                <span class="multi-select-field-title">{{ title }}</span>
                <span class="caret"></span>
            </button>
            <scrollbar v-show="availableOptions.length !== 0"
                       :style="{height: scrollbarHeight + 'px', top: scrollbarTop + 'px'}"
                       class="dropdown-menu dropdown-menu-right">
                <ul ref="ul">
                    <li v-for="option in availableOptions" :key="option.key">
                        <a href="javascript:void(0)" @click="onSelect(option)">{{ option.label }}</a>
                    </li>
                </ul>
            </scrollbar>
        </div>
        <scrollbar :style="{height: (3 * itemHeight) + 'px'}" class="multiselect-content">
            <div>
                <p
                        v-for="option in selection"
                        :key="option.key"
                        class="multiselect-option">
                    {{ option.label }}
                    <a
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
    },
    computed: {
        selection() {
            if (!this.value) {
                return [];
            }

            return _.uniqBy(this.options.filter((o) => _.find(this.value, ['key', o.key])), 'key');
        },
        availableOptions() {
            let options = _.uniqBy(this.options, 'key');

            return options.filter((option) => {
                return !_.find(this.value, ['key', option.key]);
            });
        },
        scrollbarHeight() {
            const count = Math.min(this.availableOptions.length, this.maxItems);

            return (count * this.itemHeight)
                + (this.marginBottom + this.marginTop)
                + (this.paddingBottom + this.paddingTop);
        },
    },
    methods: {
        onSelect(option) {
            let value = _.uniqBy([...this.value, option], 'key');

            this.$emit('input', value);
        },
        onRemove(option) {
            let value = this.value.filter((selected) => selected.key !== option.key);

            this.$emit('input', value);
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
