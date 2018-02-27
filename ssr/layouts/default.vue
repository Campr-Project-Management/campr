<template>
    <div id="app">
        <div class="page">
            <nuxt/>
        </div>
    </div>
</template>


<style lang="scss">
    @import '../../frontend/src/css/_variables.scss';
    @import '../../frontend/src/css/_mixins.scss';

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
        height: 198px;
        width: 25%;
        border: 1px dashed #5FC3A5;
        text-align: center;
        line-height: 198px;
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

    .no-select {
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
        padding: 5px;
        min-height: 100vh;

        .page {
            padding: 0 20px;
            background: #232D4B;
            min-height: 100vh;
        }
    }

    @media print {
        #app {
            background: none !important;

            .page {
                background: none !important;
            }
        }
    }
</style>
