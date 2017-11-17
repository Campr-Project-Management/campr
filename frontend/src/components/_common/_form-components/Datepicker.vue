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
        <div class="calendar" v-show="showDayView" v-bind:style="calendarStyle">
            <header>
                <span
                        @click="previousMonth"
                        class="prev"
                        v-bind:class="{ 'disabled' : previousMonthDisabled(currDate) }">&lt;</span>
                <span @click="showMonthCalendar" class="up">{{ currMonthName }} {{ currYear }}</span>
                <span
                        @click="nextMonth"
                        class="next"
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
                        class="prev"
                        v-bind:class="{ 'disabled' : previousYearDisabled(currDate) }">&lt;</span>
                <span @click="showYearCalendar" class="up">{{ getYear() }}</span>
                <span
                        @click="nextYear"
                        class="next"
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
                <span @click="previousDecade" class="prev"
                      v-bind:class="{ 'disabled' : previousDecadeDisabled(currDate) }">&lt;</span>
                <span>{{ getDecade() }}</span>
                <span @click="nextDecade" class="next"
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

                if (windowInnerHeight - currentElementOffset < 290) {
                    $(this.$el).find('.calendar').addClass('bottom-up');
                }else{
                    $(this.$el).find('.calendar').removeClass('bottom-up');
                }
            },
        },
    };
</script>

<style scoped lang="scss">
    .datepicker{
        .calendar{
            top: 40px;
            background: white;
            padding: 25px;
            &.bottom-up{
                top: -280px;
            }
            header{
                overflow: hidden;
                background: #009F7E;
                margin: -25px;
                margin-bottom: 10px;
                >span{
                     display: block;
                     padding: 34px 0px;
                     font-size: 16px;
                     font-weight: 600;
                     &:hover{
                        background: transparent;
                      }
                 }
            }
            .cell{
                color: #282114;
                border-radius: 4px;
                height: 29px;
                line-height: 29px;
                &.day-header{
                    font-weight: 600;
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