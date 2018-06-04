<template>
    <div>
        <div class="row" v-if="!info">
            <p>{{ translate('message.loading') }}</p>
        </div>

        <div class="row" v-if="info">
            <modal v-if="rescheduleModal" @close="rescheduleModal = false" v-bind:hasSpecificClass="true">
                <p class="modal-title">{{ translate('message.reschedule_info') }}</p>
                <div class="form-group last-form-group">
                    <div class="col-md-12">
                        <div class="input-holder">
                            <label class="active">{{ translate('label.expiry_date') }}</label>
                            <datepicker :clear-button="false" v-model="date" format="dd-MM-yyyy" :value="date"></datepicker>
                        </div>
                    </div>
                </div>

                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="rescheduleModal = false" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                    <a href="javascript:void(0)" @click="rescheduleInfo()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
                </div>
            </modal>
            <div class="col-md-6">
                <div class="view-todo page-section">
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-infos'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translate('message.back_to_infos') }}
                            </router-link>
                            <h1>{{ info.topic }}</h1>
                            <h3 class="category"><b>{{ translate(info.infoCategoryName) }}</b></h3>
                            <h4>
                                {{ translate('message.created') }}:
                                <b>{{ info.createdAt | date }}</b> |

                                <span v-if="info.isExpired">{{ translate('message.expired_at') }}</span>
                                <span v-else>{{ translate('message.expiry_date') }}: </span>
                                <b :class="{'middle-color': info.isExpired}">{{ info.expiresAt | date }}</b>
                            </h4>

                            <div class="entry-responsible flex flex-v-center" v-if="info.responsibility">
                                <user-avatar
                                        :name="info.responsibilityFullName"
                                        :url="info.responsibilityAvatarUrl"
                                        :tooltip="info.responsibilityFullName"/>
                                <div>
                                    {{ translate('message.responsible') }}:
                                    <b>{{ info.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <a @click="rescheduleModal = true;" class="btn-rounded btn-auto btn-md btn-empty">{{ translate('button.reschedule') }} <reschedule-icon></reschedule-icon></a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>

                <div class="entry-body" v-html="info.description"></div>
            </div>
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="margintop20 text-right">
                        <div class="buttons">
                            <router-link :to="{name: 'project-infos-edit'}" class="btn-rounded btn-auto">
                                {{ translate('button.edit_info') }}
                                <reschedule-icon></reschedule-icon>
                            </router-link>

                            <router-link :to="{name: 'project-infos-new'}" class="btn-rounded btn-auto second-bg">
                                {{ translate('button.new_info') }}
                                <reschedule-icon></reschedule-icon>
                            </router-link>
                            <a href="javascript:void(0)" @click="tryDeleteInfo(info.id)" class="btn-rounded btn-auto danger-bg">
                                {{ translate('button.delete_info') }}
                            </a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>
            </div>
        </div>

        <div class="row" v-if="info">
            <div class="col-md-12">
                <div class="text-right footer-buttons">
                    <div class="buttons">
                        <router-link :to="{name: 'project-infos-edit'}" class="btn-rounded btn-auto">
                            {{ translate('button.edit_info') }}
                        </router-link>

                        <router-link :to="{name: 'project-infos-new'}" class="btn-rounded btn-auto second-bg">
                            {{ translate('button.new_info') }}
                        </router-link>

                        <a href="javascript:void(0)" @click="tryDeleteInfo(info.id)" class="btn-rounded btn-auto danger-bg">
                            {{ translate('button.delete_info') }}
                        </a>
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
import DownloadbuttonIcon from '../../_common/_icons/DownloadbuttonIcon';
import router from '../../../router';
import {mapActions, mapGetters} from 'vuex';
import Modal from '../../_common/Modal';
import datepicker from '../../_common/_form-components/Datepicker';
import moment from 'moment';
import UserAvatar from '../../_common/UserAvatar';

export default {
    components: {
        UserAvatar,
        EditIcon,
        DeleteIcon,
        Switches,
        RescheduleIcon,
        DownloadbuttonIcon,
        Modal,
        datepicker,
    },
    created() {
        this.getInfo(this.$route.params.infoId);
    },
    methods: {
        ...mapActions(['getInfo', 'deleteInfo', 'editInfo']),
        tryDeleteInfo(id) {
            if (confirm(this.translate('message.delete_info'))) {
                this
                    .deleteInfo(id)
                    .then(
                        () => {
                            router.push({name: 'project-infos'});
                        },
                        () => {
                            router.push({name: 'project-infos'});
                        }
                    )
                ;
            }
        },
        rescheduleInfo: function() {
            this.rescheduleModal = false;
            const id = this.$route.params.infoId;
            const data = {
                expiresAt: moment(this.date).format('DD-MM-YYYY'),
            };
            this.editInfo({id, data});
        },
    },
    computed: {
        ...mapGetters(['info']),
    },
    data() {
        return {
            showPresent: '',
            distributionList: '',
            rescheduleModal: false,
            date: new Date(),
        };
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
