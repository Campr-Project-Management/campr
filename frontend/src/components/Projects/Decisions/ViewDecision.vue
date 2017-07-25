<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="view-todo page-section">
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-decisions'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to_decisions') }}
                            </router-link>
                            <h1>Lorem ipsum dolor sit amet, consectetur adipiscing elit</h1>
                            <h3 class="category"><b>TP Meeting</b> | <b>Logistics</b></h3>
                            <h4>{{ translateText('message.created') }}: <b>28.03.2017</b> | {{ translateText('message.expiry_date') }}: <b>01.04.2017</b> | {{ translateText('message.status') }}: <b class="second-color">Done</b></h4>
                            <div class="entry-responsible flex flex-v-center">
                                <div class="user-avatar"> 
                                    <img src="http://trisoft.dev.campr.biz/uploads/avatars/49.jpg" :alt="'Kyle Kennedy'"/>
                                </div>
                                <div>
                                    {{ translateText('message.responsible') }}:
                                    <b>Kyle Kennedy</b>
                                </div>
                            </div>
                            <a ref="#" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.reschedule') }} <reschedule-icon></reschedule-icon></a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>

                <div class="entry-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nisl justo, bibendum vel nisi vel, suscipit lobortis felis. Donec hendrerit varius erat, eu mollis arcu porttitor aliquet. Nam pellentesque nisi ac diam rutrum lobortis. Pellentesque imperdiet fermentum nisl, quis faucibus nisl blandit dictum. Integer ut tristique mauris.</p>
                    <p>Nulla vel odio dui. Praesent non commodo dolor. Donec quis consectetur ex. Sed malesuada mi a nunc rutrum, at posuere eros vestibulum. Quisque lobortis ligula sed vestibulum scelerisque. Duis ultrices dui vitae aliquet fermentum. Maecenas vulputate magna lorem, ac laoreet leo molestie vel. Duis sagittis dui eget sem finibus iaculis et at augue. Nam non semper nulla, id pharetra tellus. Sed pellentesque ac tortor feugiat pulvinar.</p>
                    <p>Maecenas tempor, nunc vel faucibus rutrum, ante nulla ornare massa, sit amet dictum orci nisi consectetur nisl. In cursus risus diam, ut viverra nunc vulputate consequat. </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="margintop20 text-right">
                        <div class="buttons">
                            <a ref="#" class="btn-rounded btn-auto">{{ translateText('button.edit_decision') }}</a>
                            <a ref="#" class="btn-rounded btn-auto second-bg">{{ translateText('button.new_decision') }}</a>
                            <a ref="#" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_decision') }}</a>
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
                        <a ref="#" class="btn-rounded btn-auto">{{ translateText('button.edit_decision') }}</a>
                        <a ref="#" class="btn-rounded btn-auto second-bg">{{ translateText('button.new_decision') }}</a>
                        <a ref="#" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_decision') }}</a>
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

export default {
    components: {
        EditIcon,
        DeleteIcon,
        VueScrollbar,
        Switches,
        RescheduleIcon,
        DownloadbuttonIcon,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
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
