<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <div class="flex flex-space-between dates">
                <div class="input-holder">
                    <label class="active">{{ translateText('label.date') }}</label>
                    <datepicker @cleared="clearDate()" v-bind:clear-button="true" v-model="date" format="dd-MM-yyyy" :value="date"></datepicker>
                    <calendar-icon fill="middle-fill"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CalendarIcon from '../../_common/_icons/CalendarIcon';
    import datepicker from 'vuejs-datepicker';

    export default {
        components: {
            CalendarIcon,
            datepicker,
        },
        methods: {
            translateText: function(text) {
                return this.translate(text);
            },
            clearDate: function() {
                this.date = null;
            },
        },
        data() {
            return {
                date: '',
            };
        },
        watch: {
            date: function(value) {
                this.selectDate(value);
                this.date = value;
            },
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/filters';

    .dates {
        .input-holder {
            width: 100%;

            &.right {
                margin-right: 15px;
            }
        }
    }
</style>