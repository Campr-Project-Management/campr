<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="rasci-matrix page-section">
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <h1>{{ translateText('message.rasci_matrix') }}</h1>
                            <div class="rasci-legend flex">
                                <div class="rasci-legend-item">
                                    <responsibility-select responsibility="responsible" disabled="true" />
                                    <span>Responsible</span>
                                </div>
                                <div class="rasci-legend-item">
                                    <responsibility-select responsibility="accountable" disabled=true />
                                    <span>Accountable</span>
                                </div>
                                <div class="rasci-legend-item">
                                    <responsibility-select responsibility="support" disabled=true />
                                    <span>Support</span>
                                </div>
                                <div class="rasci-legend-item">
                                    <responsibility-select responsibility="consulted" disabled=true />
                                    <span>Consulted</span>
                                </div>
                                <div class="rasci-legend-item">
                                    <responsibility-select responsibility="informed" disabled=true />
                                    <span>Informed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>
        
                <!-- /// RASCI /// -->
                <table class="table table-striped table-responsive rasci-table">
                    <thead>
                        <tr>
                            <th class="task-number">{{ translateText('table_header_cell.task_number') }}</th>
                            <th>{{ translateText('table_header_cell.task_title') }}</th>
                            <th class="rasci-cell" v-for="user in rasci.users">
                                <div
                                    class="avatar"
                                    v-tooltip.top-center="user.firstName + ' ' + user.lastName"
                                    :style="{backgroundImage: 'url(' + (user.avatar || user.gravatar) + ')'}"></div>
                            </th>
                            <th class="rasci-cell last-cell"></th>                                  
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(workPackage, rasciIndex) in rasci.workPackages">
                            <td :colspan="workPackage.rasci.length + 3" v-if="workPackage.type === 0">{{ workPackage.name }}</td>
                            <td class="task-number" v-if="workPackage.type !== 0">
                                <span class="light-color">#{{ workPackage.id }}</span>
                            </td>
                            <td v-if="workPackage.type !== 0">{{ workPackage.name }}</td>
                            <td class="rasci-cell" v-if="workPackage.type !== 0" v-for="(user, userIndex) in workPackage.rasci">
                                <responsibility-select
                                    :is-last="userIndex + 1 === workPackage.rasci.length"
                                    :is-second-to-last="userIndex + 2 === workPackage.rasci.length"
                                    :project="workPackage.project"
                                    :user="user.id"
                                    :work-package="workPackage.id"
                                    :responsibility="user.data"
                                    v-on:value="setRaciData({project: workPackage.project, user: user.user, workPackage: workPackage.id, data: $event})"/>
                            </td>
                            <td class="rasci-cell last-cell" v-if="workPackage.type !== 0"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import ResponsibilitySelect from '../_common/_rasci-components/ResponsibilitySelect.vue';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        ResponsibilitySelect,
    },
    methods: {
        ...mapActions(['getRasci', 'setRasci']),
        translateText: function(text) {
            return this.translate(text);
        },
        setRaciData: function({project, user, workPackage, data}) {
            this.setRasci({project, user, workPackage, data});
        },
    },
    computed: {
        ...mapGetters(['rasci', 'currentProject']),
    },
    created() {
        const {id} = this.$route.params;
        this.getRasci({id});
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_mixins';
    @import '../../css/_variables';

    .rasci-legend {
        .rasci-legend-item {
            display: flex;
            align-items: center;
            margin-right: 30px;

            span {
                text-transform: uppercase;
                letter-spacing: 0.1em;
                margin-left: 10px;
                font-size: 10px;
            }
        }
    }

    .rasci-table {
        overflow: hidden;
        position: relative;
        z-index: 1;
        margin-bottom: 30px;

        tr,
        th,
        td {
            position: relative;
        }

        th.rasci-cell,
        td.rasci-cell {
            vertical-align: middle;

            .rasci-select,
            .avatar {
                position: relative;
                z-index: 1;
            }

            .rasci-select {
                &.active {
                    z-index: 10;
                }
            }

            &:hover {               
                .rasci-select,
                .avatar {
                    z-index: 2;
                }
            }
        }

        thead,
        tbody {
            td.rasci-cell,
            th.rasci-cell {
                &:after {
                    content: '';
                    height: 1000000px;
                    left: 0;
                    position: absolute;
                    top: -50000px;
                    width: 100%;
                    z-index: 0;
                    background-color: rgba($lighterColor,.05);
                    @include opacity(0);
                }

                &:hover,
                &:focus {
                    &:after {
                        @include opacity(1);
                    }
                }

                &.last-cell {
                    pointer-events: none;
                }
            }
        }

        tbody { 
            tr:not(.rasci-phase) {
                &:hover {
                    background-color: rgba($lighterColor,.05);
                }
            }
        }
    }

    .task-number {
        width: 5%;
    }

    .rasci-cell {
        width: 30px;
        padding: 4px 0 0;
        text-align: center;

        &:last-child {
            padding-right: 30px
        }
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
    }
</style>
