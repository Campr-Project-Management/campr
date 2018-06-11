<template>
    <modal v-if="distributionId" @close="$emit('close')">
        <div style="min-height: 480px;">
            <p class="modal-title">{{ translate('button.edit_distribution_list') }}</p>

            <member-search single-select="true" v-model="newUser" />

            <div v-if="loading">
                {{ translate('label.loading') }}...
            </div>

            <div v-if="loadingFailed">
                {{ translate('label.loading_failed') }}.
            </div>

            <scrollbar class="members customScrollbar">
                <div class="member flex flex-v-center" v-if="users" v-for="user in users">
                    <div class="checkbox-input clearfix" :class="{'inactive': !user.checked}">
                        <input
                            :id="'mid_' + user.id"
                            type="checkbox"
                            :value="user.id"
                            v-model="selectedUsers">
                        <label :for="'mid_' + user.id"></label>
                    </div>
                    <div class="avatar" v-bind:style="{ backgroundImage: 'url(' + user.avatarUrl + ')' }"></div>
                    <div class="info">
                        <p class="title">{{ user.fullName }}</p>
                        <p class="description">
                            <span>{{ title(user) }}</span>
                        </p>
                    </div>
                </div>
            </scrollbar>
            <div class="flex">
                <a href="javascript:void(0)" @click="saveDistributionList()" class="btn-rounded btn-auto second-bg">{{ translate('button.save_distribution_list') }}</a>
            </div>
        </div>
    </modal>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import Modal from './Modal';
import Scrollbar from './Scrollbar';
import MemberSearch from './MemberSearch';

export default {
    props: {
        distributionId: {
            type: Number,
            default() {
                return null;
            },
        },
    },
    components: {
        MemberSearch,
        Modal,
        Scrollbar,
    },
    computed: {
        ...mapGetters(['distributionList']),
    },
    watch: {
        distributionList(value) {
            if (!value || !value.users) {
                return;
            }

            this.users = value.users;

            value.users.forEach(u => {
                this.selectedUsers.push(u.id);
            });
        },
        newUser(value) {
            if (value && value.length) {
                this
                    .addToDistribution({
                        id: this.distributionId,
                        user: value[0],
                    })
                    .then(
                        () => {
                            this.$emit('distributionListUpdated', this.distributionList);
                        },
                        () => {}
                    );
            }
            this.newUser = null;
        },
    },
    methods: {
        ...mapActions(['getDistributionList', 'addToDistribution', 'removeFromDistribution']),
        title(user) {
            let out = [];

            if (this.distributionList.project && user.projectUsers) {
                let projectUser = user
                    .projectUsers
                    .filter(pu => pu.project === this.distributionList.project)[0];

                if (projectUser) {
                    out = out.concat(projectUser.projectRoleNames).map(this.translate);
                    if (projectUser.company) {
                        out.push(projectUser.company);
                    }
                }
            }

            return out.join(' | ');
        },
        saveDistributionList() {
            const users = this
                .users
                .filter(u => this.selectedUsers.indexOf(u.id) === -1)
                .map(u => u.id);

            if (users.length) {
                this
                    .removeFromDistribution({
                        id: this.distributionId,
                        users,
                    })
                    .then(
                        () => {
                            this.$emit('distributionListUpdated', this.distributionList);
                        },
                        () => {}
                    );
            } else {
                this.$emit('close');
            }
        },
    },
    created() {
        this
            .getDistributionList(this.distributionId)
            .then(
                () => {
                    this.loading = false;
                },
                () => {
                    console.log('why tho?');
                    this.loading = false;
                    this.loadingFailed = true;
                }
            );
    },
    data() {
        return {
            loading: true,
            loadingFailed: false,
            selectedUsers: [],
            users: [],
            newUser: null,
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .modal {
        .modal-title {
            text-transform: uppercase;
            text-align: center;
            font-size: 18px;
            letter-spacing: 1.8px;
            font-weight: 300;
            margin-bottom: 40px;
        }

        .input-holder {
            margin: 30px 0 0;

            &:first-of-type {
                margin-top: 0;
            }
        }

        .search {
            margin-top: 30px;
        }

        .main-list .member {
            border-top: 1px solid $darkColor;
        }

        .results {
            width: 600px;
            .members {
                max-height: 265px;
            }
        }
    }

    .results {
        .members {
            max-height: 265px;
        }
    }

    .st0 {
        stroke: $lighterColor;
    }

    .member-badge-wrapper {
        position: relative;
    }

    .team {
        position: absolute;
        width: 420px;
        background: $darkColor;
        top: 40px;
        margin-top: 10px;
        max-height: 400px;
        z-index: 10;
        box-shadow: 0 2px 20px -2px $blackColor;

        &.no-data {
            width: 100% !important;
            position: absolute;
            padding: 5px 20px;
        }
    }

    .members {
        padding: 0 15px 0;
    }

    .member {
        padding: 15px 0;
        border-top: 1px solid $mainColor;

        &:first-child {
            border-top: none;
        }
    }

    .avatar {
        width: 46px;
        height: 46px;
        @include border-radius(50%);
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
    }

    .info {
        text-transform: uppercase;
        padding: 0 0 0 10px;
    }

    .title {
        font-size: 10px;
        color: $lighterColor;
        letter-spacing: 1.5px;
    }

    .description {
        font-size: 8px;
        color: $middleColor;
        letter-spacing: 1.2px;
    }

    .selected-item {
        padding: 11px 20px 9px;
        background-color: $fadeColor;
        margin-top: 3px;
        color: $secondColor;
        position: relative;

        i.fa {
            position: absolute;
            right: 20px;
            top: 13px;
            color: $dangerColor;
            cursor: pointer;
            @include transition(opacity, 0.2s, ease-in);

            &:hover,
            &:active {
                @include opacity(0.8);
            }
        }
    }
</style>
