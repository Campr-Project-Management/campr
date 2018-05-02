<template>
    <transition type="modal">
        <div class="modal modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-inner">
                        <slot name="header" v-if="header">
                            <a href="javascript:void(0)" class="modal-close" @click="$emit('close')">
                                <svg
                                    version="1.1"
                                    width="32px"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 28.8 28.8">
                                    <g>
                                        <line id="XMLID_530_" class="st0" x1="3.1" y1="2.9" x2="26.1" y2="25.9"/>
                                        <line id="XMLID_529_" class="st0" x1="26.1" y1="2.9" x2="3.1" y2="25.9"/>
                                    </g>
                                </svg>
                            </a>
                            <p class="modal-title danger-color">{{ translateText(header) }}</p>
                        </slot>
                        <slot name="body" v-if="body">
                            <a href="javascript:void(0)" class="modal-close" @click="$emit('close')" v-if="!header">
                                <svg
                                    version="1.1"
                                    width="32px"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 28.8 28.8">
                                    <g>
                                        <line class="st0" x1="3.1" y1="2.9" x2="26.1" y2="25.9"/>
                                        <line class="st0" x1="26.1" y1="2.9" x2="3.1" y2="25.9"/>
                                    </g>
                                </svg>
                            </a>
                            <h3 class="text-center">{{ translateText(body) }}</h3>
                        </slot>
                        <br>
                        <slot name="footer">
                            <div class="flex flex-center">
                                <button class="btn-rounded btn-empty btn-auto danger-color danger-border" @click="$emit('close')">
                                    {{ translateText(buttonText) }}
                                </button>
                            </div>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import './Modal.vue';

export default {
    props: {
        header: {
            type: String,
            default() {
                return 'message.alert';
            },
        },
        body: {
            type: String,
            default() {
                return '';
            },
        },
        buttonText: {
            type: String,
            default() {
                return 'message.close';
            },
        },
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<style scoped lang="scss">
    @import '../../css/_variables';

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
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        width: 60%;
        margin: 0 auto;
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
