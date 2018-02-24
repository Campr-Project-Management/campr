<template>
    <div id="app" v-bind:class="{ bg: bgRoutes.indexOf(this.$route.name) >= 0 }">
        <pulse-loader :loading="loading" :color="color" :size="size" v-show="loader"></pulse-loader>
        <sidebar></sidebar>
        <div class="page">
            <navigation v-bind:user="localUser"></navigation>
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
        localUser: 'localUser',
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
    @import 'css/_mixins.scss';

    html, body {
        margin: 0;
        min-height: 100vh;
    }

    body {
        font-size: 12px;
        line-height: 1.5em;
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
        line-height: 22px;

        @media screen and (max-width: 768px) {
            width: 16px;
            height: 16px;
            line-height: 16px;
        }
    }

    .dropdown-menu {
        color: $lightColor;
        background: $darkColor;
        width: 100%;
        margin: 0;
        @include border-radius(0);
        border-top: 1px solid $fadeColor;
        box-shadow: 0 0 8px -2px $blackColor;
        padding: 0;

        li {
            a {
                display: block;
                color: inherit;
                text-transform: uppercase;
                font-size: 11px;
                letter-spacing: 0.1em;
                padding: 14px 12px 11px;

                &:hover {
                    color: $lighterColor;
                    background-color: $middleColor;
                }
            }
        }
    }

    .new-box {    
        width: 25%;  
        padding: 0 15px 30px;

        a {
            text-transform: uppercase; 
            text-align: center;
            min-height: 200px;
            border: 1px dashed $secondColor;
            color: $secondColor;
            display: table;
            width: 100%;
            height: 100%;
            @include transition(all, 0.2s, ease);

            span {
                display: table-cell;
                vertical-align: middle;
            }
        }

        &:hover {
            a {
                color: $whiteColor;
                border-color: $whiteColor;
            }
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

    .no-select{
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
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
