<template>
    <ul class="rasci-select" v-bind:class="{
        default: !responsibilityValue,
        responsible: responsibilityValue === 'responsible',
        accountable: responsibilityValue === 'accountable',
        support: responsibilityValue === 'support',
        consulted: responsibilityValue === 'consulted',
        informed: responsibilityValue === 'informed',
        disabled: disabled,
        active: active,
        last: isLast,
        'second-to-last': isSecondToLast,
    }" @click="!disabled && (active = !active)">
        <li class="rasci-default">
            <span></span>
        </li>
        <li class="rasci-r" @click="responsibilityValue = 'responsible'">
            <span>R</span>
        </li>
        <li class="rasci-a" @click="responsibilityValue = 'accountable'">
            <span>A</span>
        </li>
        <li class="rasci-s" @click="responsibilityValue = 'support'">
            <span>S</span>
        </li>
        <li class="rasci-c" @click="responsibilityValue = 'consulted'">
            <span>C</span>
        </li>
        <li class="rasci-i" @click="responsibilityValue = 'informed'">
            <span>I</span>
        </li>
    </ul>
</template>

<script>
import {mapActions} from 'vuex';

export default {
    props: {
        isLast: {},
        isSecondToLast: {},
        project: {},
        user: {},
        workPackage: {},
        responsibility: {},
        disabled: {
            default() {
                return false;
            },
        },
    },
    methods: {
        ...mapActions(['setRaci']),
    },
    computed: {
        getClass() {
            return this.responsibility;
        },
    },
    watch: {
        responsibilityValue(value) {
            if (value === this.responsibility) {
                return;
            }
            this.$emit('value', value);
        },
    },
    data() {
        return {
            active: false,
            responsibilityValue: null,
        };
    },
    created() {
        this.responsibilityValue = this.responsibility;
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';

    ul.rasci-select {
        list-style: none;
        margin: 0;
        padding: 3px;
        text-align: center;
        position: relative;
        display: inline-block;
        width: 30px;
        height: 30px;
        box-sizing: content-box;

        li {
            position: absolute;
            top: 3px;
            left: 3px;
            z-index: -1;
            @include translate(0, 0);
            @include opacity(0);
            @include transition(all, 0.3s, ease-in);

            span {
                display: block;
                width: 30px;
                height: 30px;
                color: $whiteColor;
                text-transform: uppercase;
                line-height: 30px;
                font-size: 16px;
                background-color: rgba($lightColor,.2);
                @include border-radius(50px);
                cursor: pointer;
                @include transition(background-color, 0.2s, ease-in);
            }

            &.rasci-r {
                span {
                    background-color: $responsibleColor;
                }

                &:hover {
                    span {
                        background-color: saturate($responsibleColor, 50%);
                    }
                }
            }

            &.rasci-a {
                span {
                    background-color: $accountableColor;
                }

                &:hover {
                    span {
                        background-color: saturate($accountableColor, 50%);
                    }
                }
            }

            &.rasci-s {
                span {
                    background-color: $supportColor;
                }

                &:hover {
                    span {
                        background-color: saturate($supportColor, 50%);
                    }
                }
            }

            &.rasci-c {
                span {
                    background-color: $consultedColor;
                }

                &:hover {
                    span {
                        background-color: saturate($consultedColor, 50%);
                    }
                }
            }

            &.rasci-i {
                span {
                    background-color: $informedColor;
                }

                &:hover {
                    span {
                        background-color: saturate($informedColor, 50%);
                    }
                }
            }

            &:hover {
                span {
                    background-color: rgba($lightColor,.6);
                }
            }
        }

        &.default {
            li{
                &.rasci-default {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.responsible {
            li{
                &.rasci-r {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.accountable {
            li{
                &.rasci-a {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.support {
            li{
                &.rasci-s {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.consulted {
            li{
                &.rasci-c {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.informed {
            li{
                &.rasci-i {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.disabled {
            pointer-events: none;

            li {
                span {
                    cursor: default;
                }
            }
        }

        &:after {
            content: '';
            position: absolute;
            z-index: 1;
            top: 0;
            width: 0;
            height: 0;
            @include transition(all, 0.2s, ease-in);
        }

        &.active {
            &.last {
                left: -80px;
            }

            &.second-to-last {
                left: -40px;
            }

            li {
                z-index: 2;
                @include opacity(1);

                &.rasci-r {
                    @include translate(-80px, 0);
                    z-index: 2;
                }

                &.rasci-a {
                    @include translate(-40px, 0);
                    z-index: 2;
                }

                &.rasci-s {
                    @include translate(0, 0);
                    z-index: 2;
                }

                &.rasci-c {
                    @include translate(40px, 0);
                    z-index: 2;
                }

                &.rasci-i {
                    @include translate(80px, 0);
                    z-index: 2;
                }
            }

            &:after {
                width: 210px;
                left: 50%;
                margin-left: -105px;
                height: 100%;                
                background-color: rgba($darkerColor,.8);
                @include border-radius(4px);
            }
        }
    }
</style>
