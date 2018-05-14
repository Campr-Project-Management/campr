<template>
    <div class="modal-mask modal" v-bind:class="{'specific-modal': hasSpecificClass}">
        <div class="modal-wrapper">
            <scrollbar class="modal-container customScrollbar">
                <div class="modal-inner">
                    {{mclass}}
                    <a href="javascript:void(0)" class="modal-close" @click="$emit('close')">
                        <svg version="1.1" width="32px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 28.8 28.8">
                            <g>
                                <line class="st0" x1="3.1" y1="2.9" x2="26.1" y2="25.9"/>
                                <line class="st0" x1="26.1" y1="2.9" x2="3.1" y2="25.9"/>
                            </g>
                        </svg>
                    </a>
                    <slot></slot>
                </div>
            </scrollbar>
        </div>
    </div>
</template>

<script>
import {mapActions} from 'vuex';
export default {
    props: {
        hasSpecificClass: {
            type: Boolean,
            required: false,
        },
    },
    methods: {
        ...mapActions([
            'emptyValidationMessages',
        ]),
    },
    beforeDestroy() {
        this.emptyValidationMessages();
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_variables';

    .specific-modal {
        .customScrollbar.ps {
            overflow: initial !important;
        }
    }
    
    .st0 {
        stroke: $secondColor;
    }

    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: block;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: block;
        vertical-align: middle;
    }

    .modal-container {
        width: 60%;
        max-height: 80vh;
        margin: 10vh auto 0;
        overflow: auto;
        padding: 56px 30px;
        background-color: $mainColor;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        font-family: Helvetica, Arial, sans-serif;
        position: relative;
    }

    .modal-inner {
        margin: 0 auto;
    }

    .modal-close {
        position: absolute;
        right: 27px;
        top: 27px;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
        margin: 20px 0;
    }

    .modal-default-button {
        float: right;
    }

    /*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
     */

    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
</style>
