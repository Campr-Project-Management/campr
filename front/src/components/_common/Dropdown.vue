<template>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">{{ title }}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li v-for="option in options"><a href="javascript:void(0)" v-on:click="filterItems([filter, option.key, item]), title = option.label">{{ option.label }}</a></li>
        </ul>
    </div>
</template>

<script>
import {mapActions} from 'vuex';

export default {
    props: ['title', 'filter', 'options', 'item'],
    methods: mapActions(['filterItems']),
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables';

  .btn-primary {
    background: $darkColor;
    color: $lightColor;
    border: none;
    width: 200px;
    text-transform: uppercase;
    height: 40px;
    font-size: 10px;
    border-radius: 1px;
    text-align: left;
    padding-right: 22px;
    position: relative;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;

    @media screen and (max-width: 1440px) {
      width: 120px;
    }

    .caret {
      right: 8px;
      margin-top: 4px;
      position: absolute;
    }
  }

  .btn-primary.active, .btn-primary:active, .open > .dropdown-toggle.btn-primary {
    background-color: $darkColor;
    border-color: $darkColor;
  }
</style>
