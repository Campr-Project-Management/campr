<template>
    <div class="filters">
        <span class="title">{{ translateText('message.filter_by') }}</span>
        <div class="dropdowns">
            <div class="flex flex-space-between dates">
                <div class="input-holder left">
                    <date-field
                            :placeholder="translateText('message.start_date')"
                            @cleared="clearStart()"
                            :clear-button="true"
                            v-model="startDate"/>
                </div>
                <div class="input-holder left">
                    <date-field
                            :placeholder="translateText('message.finish_date')"
                            @cleared="clearEnd()"
                            :clear-button="true"
                            v-model="endDate"/>
                </div>
            </div>
            <select-field
                    v-bind:title="translateText('message.status')"
                    v-bind:options="statusesLabel"
                    v-model="statusModel"
                    v-bind:currentOption="statusModel"/>
            <select-field
                    v-bind:title="translateText('label.responsible')"
                    v-bind:options="projectUsersForSelect"
                    v-model="responsibleModel"
                    v-bind:currentOption="responsibleModel"/>
            <a @click="clearFilters()" class="btn-rounded btn-auto second-bg">{{ translateText('button.clear_filters') }}</a>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import SelectField from '../../_common/_form-components/SelectField';
    import DateField from '../_form-components/DateField';

    export default {
        props: ['clearAllFilters', 'selectStatus', 'selectResponsible', 'selectStartDate', 'selectEndDate'],
        created() {
            this.getTaskStatuses();
            this.getProjectUsers({id: this.$route.params.id});
        },
        components: {
            DateField,
            SelectField,
        },
        computed: {
            ...mapGetters({
                taskStatuses: 'taskStatuses',
                projectUsersForSelect: 'projectUsersForSelect',
            }),
            statusesLabel() {
                return this.taskStatuses.map(item => ({label: this.translate(item.name), key: item.id}));
            },
        },
        methods: {
            ...mapActions(['getTaskStatuses', 'getProjectUsers']),
            selectedStatusValue(value) {
                this.selectStatus(value);
            },
            selectedResponsible(value) {
                this.selectResponsible(value);
            },
            translateText(text) {
                return this.translate(text);
            },
            clearStart() {
                this.startDate = null;
            },
            clearEnd() {
                this.endDate = null;
            },
            clearFilters() {
                this.clearStart();
                this.clearEnd();
                this.statusModel = null;
                this.responsibleModel = null;
                this.clearAllFilters(true);
            },
        },
        data() {
            return {
                startDate: '',
                endDate: '',
                statusModel: null,
                responsibleModel: null,
            };
        },
        watch: {
            startDate(value) {
                this.selectStartDate(value);
                this.startDate = value;
            },
            endDate(value) {
                this.selectEndDate(value);
                this.endDate = value;
            },
            statusModel(value) {
                if (this.statusModel !== null) {
                    this.selectStatus(value.key);
                } else {
                    this.selectStatus(null);
                }
            },
            responsibleModel(value) {
                if (this.responsibleModel !== null) {
                    this.selectResponsible(value.key);
                } else {
                    this.selectResponsible(null);
                }
            },
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/filters';

    .dates {
        .input-holder {
            width: 50%;

            &.left {
                margin-right: 15px;
            }

            &.right {
                margin-left: 15px;
            }
        }
    }
</style>
