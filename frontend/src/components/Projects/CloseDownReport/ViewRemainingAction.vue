<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="view-todo page-section">
                    <modal v-if="showDeleteModal" @close="showDeleteModal = false">
                        <p class="modal-title">{{ translateText('message.delete_remaining_action') }}</p>
                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                            <a href="javascript:void(0)" @click="removeDecision()" class="btn-rounded">{{ translateText('message.yes') }}</a>
                        </div>
                    </modal>

                    <modal v-if="showRescheduleModal" @close="cancelRescheduleModal()">
                        <p class="modal-title">{{ translateText('message.reschedule_remaining_action') }}</p>
                        <div class="form-group last-form-group">
                            <div class="col-md-4">
                                <div class="input-holder">
                                    <label class="active">{{ translateText('label.select_date') }}</label>
                                    <datepicker :clear-button="false" v-model="reschedule.date" format="dd-MM-yyyy" :value="reschedule.date"></datepicker>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-holder">
                                    <label class="active">{{ translateText('label.select_due_date') }}</label>
                                    <datepicker :clear-button="false" v-model="reschedule.dueDate" format="dd-MM-yyyy" :value="reschedule.dueDate"></datepicker>
                                </div>
                            </div>
                        </div>
                        <hr class="double">

                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" @click="cancelRescheduleModal()" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                            <a href="javascript:void(0)" @click="rescheduleDecision()" class="btn-rounded">{{ translateText('button.save') }}</a>
                        </div>
                    </modal>
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-close-down-report'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to') }} {{ translateText('message.close_down_report') }}
                            </router-link>
                            <h1>Current Remaining Action Name</h1>
                            <h4>{{ translateText('message.created') }}: <b>23.08.2017</b> | {{ translateText('message.due_date') }}: <b>25.05.2018</b></h4>
                            <div class="entry-responsible flex flex-v-center">
                                <div class="avatar" v-tooltip.top-center="'David Gilmore'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg"></div>
                                <div>
                                    {{ translateText('message.responsible') }}:
                                    <b>David Gilmour</b>
                                </div>
                            </div>
                            <a @click="showRescheduleModal = true" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.reschedule') }} <reschedule-icon></reschedule-icon></a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>

                <div class="entry-body">
                    Nulla quis quam id arcu tincidunt hendrerit. Aenean volutpat tincidunt posuere. Nulla arcu dolor, dapibus ut augue a, tincidunt semper felis. Curabitur in mauris risus. Vivamus sit amet quam dui. Fusce nec nunc pharetra, consectetur est a, eleifend justo.
                </div>
            </div>
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="margintop20 text-right">
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

export default {
    components: {
        RescheduleIcon,
        Modal,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data() {
        return {
            showDeleteModal: false,
            showRescheduleModal: false,
            reschedule: {
                date: new Date(),
                dueDate: new Date(),
            },
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
