<template>
    <div>
        <date-picker
                :inline="inline"
                :monday-first="mondayFirst"
                :format="format"
                :value="value"
                :disabled="disabled"
                :clear-button="clearButton"
                :placeholder="placeholder"
                @input="onUpdate"
                @cleared="onCleared"
                @opened="onOpened"/>

        <calendar-icon fill="middle-fill" v-if="!inline"/>
    </div>
</template>

<script>
    import DatePicker from 'vuejs-datepicker';
    import CalendarIcon from './../_icons/CalendarIcon';

    export default {
        name: 'date-field',
        components: {DatePicker, CalendarIcon},
        props: {
            value: {
                required: false,
            },
            inline: {
                default: false,
            },
            mondayFirst: {
                type: Boolean,
                default: true,
            },
            format: {
                type: String,
                default: 'dd-MM-yyyy',
            },
            disabled: {
                type: Boolean,
                default: false,
            },
            clearButton: {
                type: Boolean,
                default: false,
            },
            placeholder: {
                type: String,
                required: false,
            },
        },
        methods: {
            onUpdate(value) {
                this.$emit('input', value);
            },
            onCleared(value) {
                this.$emit('cleared', value);
            },
            onOpened() {
                let el = this.$el.getElementsByClassName('vdp-datepicker__calendar')[0];
                arrangeHorizontally(el);
                arrangeVertically(el);

                this.$emit('opened');
            },
        },
    };

    /**
     * @param {HTMLElement} el
     */
    function arrangeHorizontally(el) {
        el.style.visibility = 'hidden';
        el.style.display = 'block';

        let screenRect = document.body.getBoundingClientRect();
        let elRect = el.getBoundingClientRect();

        if ((elRect.x + elRect.width) > screenRect.width) {
            el.style.right = '0px';
        }

        el.style.visibility = 'visible';
        el.style.display = 'none';
    }

    /**
     * @param {HTMLElement} el
     */
    function arrangeVertically(el) {
        el.style.visibility = 'hidden';
        el.style.display = 'block';
        el.style.top = '';

        let screenH = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        let elRect = el.getBoundingClientRect();

        let distanceToBottom = screenH - elRect.y;
        let distanceToTop = elRect.y;
        if (distanceToBottom < distanceToTop) {
            el.style.top = `${-1 * elRect.height}px`;
        }

        el.style.visibility = 'visible';
        el.style.display = 'none';
    }
</script>

<style lang="scss">
    @import '../../../css/_variables';

    .vdp-datepicker {
        input {
            color: $lightColor;
            text-transform: uppercase;
            font-size: 11px;
            text-align: left;

            &::placeholder {
                color: $lightColor;
            }
        }

        .vdp-datepicker__clear-button {
            color: $dangerColor;
            font-size: 1.5em;
            position: absolute;
            right: 35px;
            top: 13px;
            i {
                font-style: normal;
            }
        }

        .vdp-datepicker__calendar {
            color: $whiteColor;
            border: none;
            background-color: $middleColor;
            width: 350px;
            position: absolute;
            box-shadow: 0 0 8px -2px $blackColor;

            header {
                span {
                    background-color: $datePickerGreen;
                    font-size: 16px;
                    text-transform: uppercase;
                    font-weight: 400;
                    padding: 20px 0;

                    &.prev, &.next, &.up {
                        &:hover {
                            background-color: $datePickerDarkGreen !important;
                        }
                    }

                    &.prev {
                        &:after {
                            border-right: 10px solid $whiteColor;
                        }
                    }

                    &.next {
                        &:after {
                            border-left: 10px solid $whiteColor;
                        }
                    }
                }
            }

            .cell {
                color: $darkerColor;

                &.selected {
                    background: $datePickerGreen;
                    color: $whiteColor !important;

                    &:hover {
                        background-color: $datePickerGreen;
                        color: $datePickerGreen !important;
                    }
                }

                &:not(.blank):hover {
                    background-color: $whiteColor;
                    border: none !important;
                }
                &.disabled {
                    color: $lightColor;
                }

                &.day {
                    margin: 5px 5px;
                    padding: 0 !important;
                    width: 40px;

                    &:not(.blank):hover {
                        border-radius: 50%;
                    }

                    &.selected {
                        border-radius: 50%;
                    }
                }

                &.day-header {
                    width: 50px;
                    background-color: $datePickerDarkGreen;
                    padding: 0 !important;
                    font-size: 11px;
                    text-transform: uppercase;
                    font-weight: 400;
                    color: $whiteColor;
                    border: none !important;

                    &:hover {
                        background-color: $datePickerDarkGreen;
                    }
                }
            }
        }
    }
</style>
