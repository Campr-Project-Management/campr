<template>
    <div class="dropdown">
        <button
            ref="btn-dropdown"
            class="btn btn-primary dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            @click="updateScrollbarTop()"
        >
            {{ placeholder }}
            <span class="caret"></span>
        </button>
        <scrollbar v-show="options.length > 0" :style="{height: scrollbarHeight + 'px', top: scrollbarTop + 'px'}" class="dropdown-menu dropdown-menu-right">
            <ul ref="ul">
                <li v-for="option in options" :style="{height: itemHeight + 'px'}">
                    <a href="javascript:void(0)" v-on:click="updateValue(option)">{{ translateText(option.label) }}</a>
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
            default: [],
        },
        currentOption: {
            required: false,
        },
        maxItems: {
            type: Number,
            default: 3,
        },
    },
    computed: {
        placeholder() {
            let option = this.currentOption;
            if (this.value) {
                option = _.find(this.options, (o) => {
                    return o.key === this.value.key;
                });
            }

            if (option) {
                return this.translateText(option.label);
            }

            return this.title;
        },
        scrollbarHeight() {
            const itemsToShow = this.options.length < this.maxItems
                ? this.options.length
                : this.maxItems
            ;

            return (itemsToShow * this.itemHeight)
                + (this.marginBottom + this.marginTop)
                + (this.paddingBottom + this.paddingTop);
        },
    },
    methods: {
        updateValue(value) {
            this.$emit('input', value);
        },
        translateText(text) {
            return this.translate(text);
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
</style>
