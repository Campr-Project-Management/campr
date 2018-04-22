<template>
    <ul class="rasci-select" :class="{
        default: !value,
        responsible: value === 'responsible',
        accountable: value === 'accountable',
        support: value === 'support',
        consulted: value === 'consulted',
        informed: value === 'informed',
        disabled: disabled,
        active: activeElem === index,
        last: last,
        'second-to-last': secondToLast,
    }"  @click="handleClick" :key="index">
        <li class="rasci-default">
            <span></span>
        </li>
        <li class="rasci-r" @click="onClick('responsible')">
            <span>R</span>
        </li>
        <li class="rasci-a" @click="onClick('accountable')">
            <span>A</span>
        </li>
        <li class="rasci-s" @click="onClick('support')">
            <span>S</span>
        </li>
        <li class="rasci-c" @click="onClick('consulted')">
            <span>C</span>
        </li>
        <li class="rasci-i" @click="onClick('informed')">
            <span>I</span>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            value: {
                type: String,
                required: false,
                default: '',
            },
            last: {
                type: Boolean,
                required: false,
                default: false,
            },
            secondToLast: {
                type: Boolean,
                required: false,
                default: false,
            },
            disabled: {
                type: Boolean,
                required: false,
                default: false,
            },
            activeElem: {
                type: String,
                required: false,
                default: null,
            },
            index: {
                type: String,
                required: false,
                default: '',
            },
        },
        methods: {
            handleClick() {
                this.$emit('handleClick', this.index);
            },
            onClick(value) {
                this.$emit('input', value);
            },
        },
        data() {
            return {
                active: false,
            };
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
                background-color: rgba($lightColor, .2);
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
                    background-color: rgba($lightColor, .6);
                }
            }
        }

        &.default {
            li {
                &.rasci-default {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.responsible {
            li {
                &.rasci-r {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.accountable {
            li {
                &.rasci-a {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.support {
            li {
                &.rasci-s {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.consulted {
            li {
                &.rasci-c {
                    @include opacity(1);
                    z-index: 1;
                }
            }
        }

        &.informed {
            li {
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
                background-color: rgba($darkerColor, .8);
                @include border-radius(4px);
            }
        }
    }
</style>
