<template>
    <div class="datepicker" :class="wrapperClass">
        <input
            :type="inline ? 'hidden' : 'text'"
            :class="inputClass"
            :name="name"
            :id="id"
            @click="showCalendar()"
            :value="formattedValue"
            :placeholder="placeholder"
            :clear-button="clearButton"
            readonly>
        <i class="datepicker-clear-button" v-if="clearButton" @click="clearDate()">&times;</i>
        <!-- Day View -->
        <div class="calendar day-view" v-show="showDayView" v-bind:style="calendarStyle">
            <header>
                <span
                    @click="previousMonth"
                    class="prev_custom no-select"
                    v-bind:class="{ 'disabled' : previousMonthDisabled(currDate) }">&lt;</span>
                <span @click="showMonthCalendar" class="up">{{ currYear }} <br> {{ currMonthName }}</span>
                <span
                    @click="nextMonth"
                    class="next_custom no-select"
                    v-bind:class="{ 'disabled' : nextMonthDisabled(currDate) }">&gt;</span>
            </header>
            <span class="cell day-header" v-for="d in daysOfWeek">{{ d }}</span>
            <span class="cell day blank" v-for="d in blankDays"></span><!--
            --><span class="cell day"
                   v-for="day in days"
                   track-by="timestamp"
                   v-bind:class="{ 'selected':day.isSelected, 'disabled':day.isDisabled, 'highlighted': day.isHighlighted}"
                   @click="selectDate(day)">{{ day.date }}</span>
        </div>

        <!-- Month View -->
        <div class="calendar" v-show="showMonthView" v-bind:style="calendarStyleSecondary">
            <header>
                <span
                    @click="previousYear"
                    class="prev_custom no-select"
                    v-bind:class="{ 'disabled' : previousYearDisabled(currDate) }">&lt;</span>
                <span @click="showYearCalendar" class="up">{{ getYear() }}</span>
                <span
                    @click="nextYear"
                    class="next_custom no-select"
                    v-bind:class="{ 'disabled' : nextYearDisabled(currDate) }">&gt;</span>
            </header>
            <span class="cell month"
                v-for="month in months"
                track-by="timestamp"
                v-bind:class="{ 'selected': month.isSelected, 'disabled': month.isDisabled }"
                @click.stop="selectMonth(month)">{{ month.month }}</span>
        </div>

        <!-- Year View -->
        <div class="calendar" v-show="showYearView" v-bind:style="calendarStyleSecondary">
            <header>
                <span @click="previousDecade" class="prev_custom no-select"
                    v-bind:class="{ 'disabled' : previousDecadeDisabled(currDate) }">&lt;</span>
                <span>{{ getDecade() }}</span>
                <span @click="nextDecade" class="next_custom no-select"
                    v-bind:class="{ 'disabled' : nextMonthDisabled(currDate) }">&gt;</span>
            </header>
            <span
                class="cell year"
                v-for="year in years"
                track-by="timestamp"
                v-bind:class="{ 'selected': year.isSelected, 'disabled': year.isDisabled }"
                @click.stop="selectYear(year)">{{ year.year }}</span>
        </div>

    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    export default {
        extends: datepicker,
        props: ['placeholder'],
        methods: {
            isOpen() {
                let scrollTop = $(window).scrollTop();
                let elementOffset = $(this.$el).offset().top;
                let currentElementOffset = (elementOffset - scrollTop);

                let windowInnerHeight = window.innerHeight;

                if (windowInnerHeight - currentElementOffset < 350) {
                    $(this.$el).find('.calendar').addClass('bottom-up');
                } else {
                    $(this.$el).find('.calendar').removeClass('bottom-up');
                }
            },
        },
    };
</script>

<style scoped lang="scss">
    .datepicker{
        input{
            color: #8794C4;
            text-transform: uppercase;
            font-size: 11px;
            text-align: left;
            &::-webkit-input-placeholder { /* WebKit, Blink, Edge */
                color:    #8794C4;
            }
            &::-moz-placeholder { /* Mozilla Firefox 4 to 18 */
                color:    #8794C4;
                opacity:  1;
            }
            &::-moz-placeholder { /* Mozilla Firefox 19+ */
                color:    #8794C4;
                opacity:  1;
            }
            &::-ms-input-placeholder { /* Internet Explorer 10-11 */
                color:    #8794C4;
            }
            &::-ms-input-placeholder { /* Microsoft Edge */
                color:    #8794C4;
            }
        }
        .calendar{
            top: 40px;
            background: white;
            padding: 15px;
            width: 246px;
            &.bottom-up{
                top: -280px;
            }
            &.day-view{
                header{
                    .up{
                        line-height: 20px;
                        text-align: center;
                    }
                }
            }
            header{
                overflow: hidden;
                background: #009F7E;
                margin: -15px;
                margin-bottom: 10px;
                >span{
                    display: block;
                    padding: 34px 0px;
                    font-size: 16px;
                    font-weight: 600;
                    &:hover{
                       background: transparent;
                    }
                    &.next_custom{
                        width: 14.28%;
                        float: right;
                        font-size: 44px;
                        position: relative;
                        font-weight: 100;
                        padding-right: 22px;
                        cursor: pointer;
                    }
                    &.prev_custom{
                        width: 14.28%;
                        float: left;
                        font-size: 44px;
                        position: relative;
                        font-weight: 100;
                        padding-left: 22px;
                        cursor: pointer;
                    }
                 }
            }
            .cell{
                color: #282114;
                border-radius: 4px;
                height: 24px;
                width: 35px;;
                line-height: 25px;
                &.day-header{
                    font-weight: 600;
                    width: 30px;
                }
                &.month, &.year{
                    width: 33.333%;
                }
                &.selected{
                    background: #FF0055;
                    color: white;
                }
                &:hover{
                    border: 0px !important;
                    background: #E8E8E8;
                }
            }
        }
    }
</style>