<template>
    <div>
        <div class="row" v-if="!info">
            <p>{{ translateText('message.loading') }}</p>
        </div>

        <div class="row" v-if="info">
            <div class="col-md-6">
                <div class="view-todo page-section">
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-infos'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to_infos') }}
                            </router-link>
                            <h1>{{ info.topic }}</h1>
                            <h3 class="category"><b>{{ translateText(info.infoCategoryName) }}</b></h3>
                            <h4>{{ translateText('message.created') }}: <b>{{ displayDate(info.createdAt) }}</b> | {{ translateText('message.due_date') }}: <b>{{ displayDate(info.dueDate) }}</b> | {{ translateText('message.status') }}: <b :style="{color: info.infoStatusColor}">{{ translateText(info.infoStatusName) }}</b></h4>

                            <div class="entry-responsible flex flex-v-center" v-if="info.responsibility">
                                <div class="user-avatar"> 
                                    <img :src="(info.responsibilityAvatar ? '/uploads/avatars/' + info.responsibilityAvatar : info.responsibilityGravatar)" :alt="info.responsibilityFullName"/>
                                </div>
                                <div>
                                    {{ translateText('message.responsible') }}:
                                    <b>{{ info.responsibilityFullName }}</b>
                                </div>
                            </div>
                            <router-link :to="{name: 'project-infos-edit'}" class="btn-rounded btn-auto btn-md btn-empty">
                                {{ translateText('button.reschedule') }}
                                <reschedule-icon></reschedule-icon>
                            </router-link>
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
                                {{ translateText('button.edit_info') }}
                                <reschedule-icon></reschedule-icon>
                            </router-link>

                            <router-link :to="{name: 'project-infos-new'}" class="btn-rounded btn-auto second-bg">
                                {{ translateText('button.new_info') }}
                                <reschedule-icon></reschedule-icon>
                            </router-link>
                            <a href="javascript:void(0)" @click="tryDeleteInfo(info.id)" class="btn-rounded btn-auto danger-bg">
                                {{ translateText('button.delete_info') }}
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
                            {{ translateText('button.edit_info') }}
                        </router-link>

                        <router-link :to="{name: 'project-infos-new'}" class="btn-rounded btn-auto second-bg">
                            {{ translateText('button.new_info') }}
                        </router-link>

                        <a href="javascript:void(0)" @click="tryDeleteInfo(info.id)" class="btn-rounded btn-auto danger-bg">
                            {{ translateText('button.delete_info') }}
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
import VueScrollbar from 'vue2-scrollbar';
import Switches from '../../3rdparty/vue-switches';
import RescheduleIcon from '../../_common/_icons/RescheduleIcon';
import DownloadbuttonIcon from '../../_common/_icons/DownloadbuttonIcon';
import router from '../../../router';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        EditIcon,
        DeleteIcon,
        VueScrollbar,
        Switches,
        RescheduleIcon,
        DownloadbuttonIcon,
    },
    created() {
        this.getInfo(this.$route.params.infoId);
    },
    methods: {
        ...mapActions(['getInfo', 'deleteInfo']),
        translateText: function(text) {
            return this.translate(text);
        },
        displayDate(d8) {
            if (!d8) {
                return '-';
            }

            const dt = new Date(d8);
            if (isNaN(dt.getTime())) {
                return '-';
            }
            const out = [dt.getFullYear()];
            let month = dt.getMonth() + 1;
            let day = dt.getDay();

            if (month < 10) {
                month = '0' + month;
            }
            if (day < 10) {
                day = '0' + day;
            }

            out.push(month);
            out.push(day);

            return out.join('-');
        },
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
    },
    computed: {
        ...mapGetters(['info']),
    },
    data() {
        return {
            showPresent: '',
            distributionList: '',
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
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;

        img {
            width: 30px;
            height: 30px;
            @include border-radius(50%);
            margin: 0 10px 0 0;  
            display: inline-block;
            position: relative;
            top: -2px;
        }
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
