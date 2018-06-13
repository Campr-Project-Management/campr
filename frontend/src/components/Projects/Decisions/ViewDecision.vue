<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="view-todo page-section">
                    <!-- /// Header /// -->
                    <modal v-if="showDeleteModal" @close="showDeleteModal = false">
                        <p class="modal-title">{{ translate('message.delete_decision') }}</p>
                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                            <a href="javascript:void(0)" @click="removeDecision()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translate('message.yes') }}</a>
                        </div>
                    </modal>
                    <modal v-if="showRescheduleModal" @close="cancelRescheduleModal()" v-bind:hasSpecificClass="true">
                        <p class="modal-title">{{ translate('message.reschedule_decision') }}</p>
                        <div class="form-group last-form-group">
                            <div class="col-md-12">
                                <div class="input-holder">
                                    <label class="active">{{ translate('label.select_due_date') }}</label>
                                    <datepicker :clear-button="false" v-model="reschedule.dueDate" format="dd-MM-yyyy" :value="reschedule.dueDate"></datepicker>
                                </div>
                            </div>
                        </div>
                        <hr class="double">

                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" @click="cancelRescheduleModal()" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                            <a href="javascript:void(0)" @click="rescheduleDecision()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
                        </div>
                    </modal>

                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-decisions'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translate('message.back_to_decisions') }}
                            </router-link>
                            <h1>{{ currentDecision.title }}</h1>
                            <h3 class="category"><b>{{ currentDecision.meetingName }}</b> | <b>{{ currentDecision.decisionCategoryName }}</b></h3>
                            <h4>{{ translate('message.created') }}: <b>{{ currentDecision.createdAt | date }}</b> | {{ translate('message.due_date') }}: <b>{{currentDecision.dueDate | date }}</b> </h4>
                            <div class="entry-responsible flex flex-v-center">
                                <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + currentDecision.responsibilityAvatar + ')' }"></div>
                                <div>
                                    {{ translate('message.responsible') }}:
                                    <b>{{currentDecision.responsibilityFullName}}</b>
                                </div>
                            </div>
                            <a @click="showRescheduleModal = true" class="btn-rounded btn-auto btn-md btn-empty">{{ translate('button.reschedule') }} <reschedule-icon></reschedule-icon></a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>

                <div class="entry-body">
                    <div v-html="currentDecision.description"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="margintop20 text-right">
                        <div class="buttons">
                            <router-link class="btn-rounded btn-auto" :to="{name: 'project-decisions-edit-decision', params:{decisionId: currentDecision.id}}">
                                {{ translate('button.edit_decision') }}
                            </router-link>
                            <router-link :to="{name: 'project-decisions-create-decision'}" class="btn-rounded btn-auto second-bg">
                                {{ translate('button.new_decision') }}
                            </router-link>
                            <a @click="showDeleteModal = true" class="btn-rounded btn-auto danger-bg">{{ translate('button.delete_decision') }}</a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="text-right footer-buttons">
                    <div class="buttons">
                        <router-link class="btn-rounded btn-auto" :to="{name: 'project-decisions-edit-decision', params:{decisionId: currentDecision.id}}">
                            {{ translate('button.edit_decision') }}
                        </router-link>
                        <router-link class="btn-rounded btn-auto second-bg" :to="{name: 'project-decisions-create-decision'}">
                            {{ translate('button.new_decision') }}
                        </router-link>
                        <a @click="showDeleteModal = true" class="btn-rounded btn-auto danger-bg">{{ translate('button.delete_decision') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import Switches from '../../3rdparty/vue-switches';
import RescheduleIcon from '../../_common/_icons/RescheduleIcon';
import {mapActions, mapGetters} from 'vuex';
import Modal from '../../_common/Modal';
import datepicker from '../../_common/_form-components/Datepicker';
import moment from 'moment';
import router from '../../../router';

export default {
    components: {
        EditIcon,
        DeleteIcon,
        Switches,
        RescheduleIcon,
        Modal,
        moment,
        datepicker,
    },
    methods: {
        ...mapActions(['getDecision', 'editDecision', 'deleteDecision']),
        rescheduleDecision: function() {
            let data = {
                id: this.$route.params.decisionId,
                dueDate: moment(this.reschedule.dueDate).format('DD-MM-YYYY'),
            };
            this.editDecision(data);
            this.showRescheduleModal = false;
        },
        cancelRescheduleModal: function() {
            this.showRescheduleModal = false;
            this.reschedule.dueDate = new Date(this.currentDecision.dueDate);
        },
        removeDecision: function() {
            if (this.$route.params.decisionId) {
                this.deleteDecision({id: this.$route.params.decisionId});
                this.showDeleteModal = false;
                router.push({name: 'project-decisions', params: {id: this.$route.params.id}});
            }
        },
    },
    computed: {
        ...mapGetters({currentDecision: 'currentDecision'}),
    },
    created() {
        if (this.$route.params.decisionId) {
            this.getDecision(this.$route.params.decisionId);
        }
    },
    data() {
        return {
            showDeleteModal: false,
            showRescheduleModal: false,
            reschedule: {
                dueDate: moment().toDate(),
            },
        };
    },
    watch: {
        currentDecision(val) {
            this.reschedule.dueDate = this.currentDecision.dueDate ? moment(this.currentDecision.dueDate).toDate() : null;
        },
    },
};
</script>

<style lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    ul.attachments {
        li {
            a {
                .icon {
                    svg {
                        width: 12px;
                        height: 12px;
                        @include transition(color, 0.2s, ease);
                    }
                }

                &:hover {
                    .icon {
                        svg {
                            fill: $secondDarkColor;
                        }
                    }                    
                }
            }
        }
    }
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .user-avatar {
        width: 30px;
        height: 30px;
        display: inline-block;        
        margin: 0 10px 0 0;  
        position: relative;
        top: -2px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        @include border-radius(50%);
    }

    .entry-body {
        ul {
            list-style-type: disc;
            list-style-position: inside;

            &:last-child {
                margin-bottom: 0;
            }
        }

        ol {
            list-style-type: decimal;
            list-style-position: inside;
            padding: 0;

            &:last-child {
                margin-bottom: 0;
            }
        }

        img {
            @include box-shadow(0, 0, 20px, $darkColor);
        }
    }

    .entry-responsible {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
        line-height: 1.5em;
        margin: 20px 0;

        b {
            display: block;
            font-size: 12px;
        }
    }

    .category {
        margin-top: 0;
    }

    .footer-buttons {
        margin-top: 60px;
        padding: 30px 0;
        border-top: 1px solid $darkerColor; 
    }

    .buttons {
      a {
        margin: 5px 0 5px 10px;
      }
    }
</style>
