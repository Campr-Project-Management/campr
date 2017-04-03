<template>
    <div id="app" v-bind:class="{ bg: bgRoutes.indexOf(this.$route.name) >= 0 }">
        <pulse-loader :loading="loading" :color="color" :size="size" v-show="loader"></pulse-loader>
        <sidebar></sidebar>
        <div class="page">
            <navigation v-bind:user="user"></navigation>
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import Navigation from './components/_layout/Navigation';
import Sidebar from './components/_layout/Sidebar';
import {mapActions, mapGetters} from 'vuex';
import {PulseLoader} from 'vue-spinner/dist/vue-spinner.min.js';

export default {
    name: 'app',
    components: {
        PulseLoader,
        Navigation,
        Sidebar,
    },
    methods: mapActions(['getUserInfo']),
    created() {
        this.getUserInfo();
    },
    computed: mapGetters({
        user: 'user',
        loader: 'loader',
    }),
    data: function() {
        return {
            bgRoutes: ['projects-create-1', 'projects-create-2', 'projects-create-3'],
        };
    },
};
</script>

<style lang="scss">
  @import 'css/_variables.scss';

  html, body {
    margin: 0;
    min-height: 100vh;
  }

  body {
    font-size: 12px;
  }
  .v-spinner {
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: $mainColor;
    z-index: 99;
  }
  .tablet {
    display: none;
  }

  ul {
    list-style-type: none;
    padding: 0;
    text-align: left;
  }

  p {
    margin: 0;
  }

  a {
    color: inherit;
    text-decoration: none;

    &:hover, &:active, &:focus {
      text-decoration: none;
      color: inherit;
    }
  }

  .notification-balloon {
    display: block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: $secondColor;
    color: $mainColor;
    position: absolute;
    text-align: center;
    line-height: 20px;

    @media screen and (max-width: 768px) {
      width: 16px;
      height: 16px;
      line-height: 16px;
    }
  }

  .dropdown-menu {
    color: $lightColor;
    background: $darkColor;

    li a {
      color: inherit;
    }

    a:hover {
      color: $lighterColor;
      background: $mainColor;
    }
  }

  .new-box {
    height: 189px;
    width: 394px;
    border: 1px dashed #5FC3A5;
    text-align: center;
    line-height: 189px;
    text-transform: uppercase;
    display: block;
    color: $secondColor;
    margin: 0 12.5px 30px;

    &:hover {
      border: 1px dashed #fff;
    }
  }

  .second-col-bg {
    background: $secondColor !important;
  }

  .warning-col-bg {
    background: $warningColor !important;
  }

  .danger-col-bg {
    background: $dangerColor !important;
  }

  #app {
    font-family: 'Poppins', sans-serif;
    color: #D8DAE5;
    height: 100%;
    padding: 0 0 0 210px;
    min-height: 100vh;

    @media screen and (max-width: 768px) {
      padding: 0 0 0 75px;
    }

    .page {
      padding: 0 20px;
      background: #232D4B;
      min-height: 100vh;
    }
  }
</style>
