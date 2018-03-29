<template>
    <div class="dropdown">
        <button
            class="btn btn-primary dropdown-toggle"
            type="button"
            data-toggle="dropdown"
            @click="updateScrollbarTop()"
        >
            {{ translateText(activeTitle) }}
            <span class="caret"></span>
        </button>
        <scrollbar v-show="options.length > 0" :style="{height: scrollbarHeight + 'px', top: scrollbarTop + 'px'}" class="dropdown-menu dropdown-menu-right">
            <ul ref="ul">
                <li v-for="option in options">
                    <a
                        href="javascript:void(0)"
                        v-on:click="customTitle = option.label, selectedValue(option.key)">
                        {{ translateText(option.label) }}
                    </a>
                </li>
            </ul>
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
        filter: {},
        options: {
            type: Array,
            required: true,
            default: [],
        },
        item: {},
        selectedValue: {},
        maxItems: {
            type: Number,
            default: 3,
        },
    },
    methods: {
        translateText(text) {
            return this.translate(text);
        },
        resetCustomTitle() {
            this.customTitle = null;
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
            customTitle: null,
            itemHeight: 0,
            paddingTop: 0,
            paddingBottom: 0,
            marginTop: 0,
            marginBottom: 0,
            scrollbarTop: 0,
        };
    },
    computed: {
        activeTitle() {
            return this.customTitle ? this.customTitle : this.title;
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
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables';
  @import '../../css/_mixins';

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
    width: 200px;
    text-transform: uppercase;
    height: 40px;
    padding: 0 35px 0 20px;
    font-size: 11px;
    line-height: 43px;
    letter-spacing: 0.1em;
    text-align: left;
    position: relative;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    @include border-radius(0);

    @media screen and (max-width: 1440px) {
      width: 120px;
    }

    .caret {
      right: 20px;
      top: 18px;
      position: absolute;
    }

    &:focus {
      outline: 0;
    }
  }

  .btn-primary.active, .btn-primary:active, .open > .dropdown-toggle.btn-primary {
    background-color: $darkColor;
    border-color: $darkColor;
  }
</style>
