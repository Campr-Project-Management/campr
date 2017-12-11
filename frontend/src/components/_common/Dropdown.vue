<template>
    <div class="dropdown">
        <button ref="btn-dropdown" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" @click="dropdownToggle()">{{ translateText(activeTitle) }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right nicescroll">
            <li v-for="option in options">
                <a
                    href="javascript:void(0)"
                    v-on:click="filterItems([filter, option.key, item]), customTitle = option.label, selectedValue(option.key)">
                    {{ translateText(option.label) }}
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
import {mapActions} from 'vuex';
import 'jquery.nicescroll/jquery.nicescroll.js';

export default {
    props: ['title', 'filter', 'options', 'item', 'selectedValue'],
    methods: {
        ...mapActions(['filterItems']),
        translateText: function(text) {
            return this.translate(text);
        },
        resetCustomTitle: function() {
            this.customTitle = null;
        },
        dropdownToggle: function() {
            let scrollTop = $(window).scrollTop();
            let elementOffset = $(this.$el).offset().top;
            let currentElementOffset = (elementOffset - scrollTop);

            let windowInnerHeight = window.innerHeight;

            if ((windowInnerHeight - currentElementOffset) < (3 * this.dropdownItemHeight)) {
                $(this.$el).find('.dropdown-menu').css('top', (-3 * this.dropdownItemHeight) + 'px');
            } else {
                $(this.$el).find('.dropdown-menu').css('top', this.dropdownItemHeight + 'px');
            }
        },
    },
    mounted() {
        this.dropdownItemHeight = this.$refs['btn-dropdown'].clientHeight;
        $(this.$el).find('.dropdown-menu').css('height', (3 * this.dropdownItemHeight) + 'px');
        window.$(document).ready(function() {
            window.$('.nicescroll').niceScroll({
                autohidemode: false,
            });
        });
    },
    data: function() {
        return {
            customTitle: null,
            dropdownItemHeight: null,
        };
    },
    computed: {
        activeTitle: function() {
            return this.customTitle ? this.customTitle : this.title;
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables';
  @import '../../css/_mixins';

  .dropdown-menu{
      &.nicescroll{
           max-height : 200px;
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
