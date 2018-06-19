<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="view-todo page-section">
                    <modal v-if="showDeleteModal" @close="showDeleteModal = false">
                        <p class="modal-title">{{ translateText('message.delete_remaining_action') }}</p>
                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translateText('message.no') }}</a>
                            <a href="javascript:void(0)" @click="removeAction()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translateText('message.yes') }}</a>
                        </div>
                    </modal>

                    <modal v-if="showRescheduleModal" @close="showRescheduleModal = false">
                        <p class="modal-title">{{ translateText('message.reschedule_remaining_action') }}</p>
                        <div class="form-group last-form-group">
                            <div class="col-md-8">
                                <div class="input-holder">
                                    <label class="active">{{ translateText('label.select_due_date') }}</label>
                                    <date-field v-model="dueDate"/>
                                </div>
                            </div>
                        </div>
                        <hr class="double">

                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" @click="showRescheduleModal = false" class="btn-rounded btn-auto">{{ translateText('button.cancel') }}</a>
                            <a href="javascript:void(0)" @click="rescheduleAction" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
                        </div>
                    </modal>
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-close-down-report'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to') }} {{ translateText('message.close_down_report') }}
                            </router-link>
                            <h1>{{ currentCloseDownAction.title }}</h1>
                            <h4>{{ translateText('message.created') }}: <b>{{ currentCloseDownAction.createdAt|moment('DD.MM.YYYY') }}</b> | {{ translateText('message.due_date') }}: <b>{{ dueDate|moment('DD.MM.YYYY')  }}</b></h4>
                            <div class="entry-responsible flex flex-v-center">
                                <div class="avatar" v-tooltip.top-center="currentCloseDownAction.responsibilityFullName" v-bind:style="{ backgroundImage: 'url(' + currentCloseDownAction.responsibilityAvatar + ')' }"></div>
                                <div>
                                    {{ translateText('message.responsible') }}:
                                    <b>{{ currentCloseDownAction.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <a @click="initReschedule" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.reschedule') }} <reschedule-icon></reschedule-icon></a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>

                <div class="entry-body" v-html="currentCloseDownAction.description"></div>
            </div>
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="margintop20 text-right">
                        <div class="buttons">
                            <router-link class="btn-rounded btn-auto" :to="{name: 'project-close-down-report-edit-remaining-action', params: {actionId: currentCloseDownAction.id}}">
                                {{ translateText('button.edit_remaining_action') }}
                            </router-link>
                            <router-link class="btn-rounded btn-auto second-bg" :to="{name: 'project-decisions-create-decision'}">
                                {{ translateText('button.new_remaining_action') }}
                            </router-link>
                            <a @click="showDeleteModal = true" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_remaining_action') }}</a>
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
                        <router-link class="btn-rounded btn-auto" :to="{name: 'project-close-down-report-edit-remaining-action'}">
                            {{ translateText('button.edit_remaining_action') }}
                        </router-link>
                        <router-link class="btn-rounded btn-auto second-bg" :to="{name: 'project-decisions-create-decision'}">
                            {{ translateText('button.new_remaining_action') }}
                        </router-link>
                        <a @click="showDeleteModal = true" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_remaining_action') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RescheduleIcon from '../../_common/_icons/RescheduleIcon';
import Modal from '../../_common/Modal';
import {mapActions, mapGetters} from 'vuex';
import router from '../../../router';
import moment from 'moment';
import DateField from '../../_common/_form-components/DateField';

export default {
    components: {
        DateField,
        RescheduleIcon,
        Modal,
    },
    methods: {
        ...mapActions(['getCloseDownAction', 'editCloseDownAction', 'deleteCloseDownAction']),
        translateText: function(text) {
            return this.translate(text);
        },
        removeAction: function() {
            this.deleteCloseDownAction(this.currentCloseDownAction.id)
                .then(
                    (response) => {
                        this.showDeleteModal = false;
                        router.push({
                            name: 'project-close-down-report',
                            params: {
                                id: this.$route.params.id,
                            },
                        });
                    },
                );
        },
        initReschedule: function() {
            this.showRescheduleModal= true;
            this.dueDate = new Date(this.currentCloseDownAction.dueDate);
        },
        rescheduleAction: function() {
            this.editCloseDownAction({
                id: this.currentCloseDownAction.id,
                dueDate: moment(this.dueDate, 'DD-MM-YYYY').format('DD-MM-YYYY'),
            }).then(
                (response) => {
                    if (response && response.body && !response.body.error) {
                        let data = response.body;
                        this.dueDate.dueDate = data.dueDate;
                        this.showRescheduleModal= false;
                    }
                },
            );
        },
    },
    computed: {
        ...mapGetters({currentCloseDownAction: 'currentCloseDownAction'}),
    },
    created() {
        if (this.$route.params.actionId) {
            this.getCloseDownAction(this.$route.params.actionId);
        }
    },
    watch: {
        currentCloseDownAction(value) {
            this.dueDate = new Date(this.currentCloseDownAction.dueDate);
        },
    },
    data() {
        return {
            showDeleteModal: false,
            showRescheduleModal: false,
            dueDate: new Date(),
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
        margin-right: 10px;
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
