<template>
    <div class="search">
        <div class="input-holder">
            <input type="text"
                   class="float-label"
                   :id="'input' + _uid"
                   autocomplete="off"
                   v-model="query"
                   @keydown.down="down"
                   @keydown.up="up"
                   @keydown.enter="hit"
                   @keydown.esc="reset"
                   @input="update"/>
            <label :class="{ 'active': placeholder }">{{ placeholder }}</label>
        </div>
        <i class="member-search-clear-button" @click="clearValue">×</i>
        <div class="results team" v-show="hasItems">
            <div class="members nicescroll">
                <div class="member flex flex-v-center" v-for="item in items">
                    <div class="checkbox-input clearfix" :class="{'inactive': !item.checked}">
                        <input v-if="singleSelect" :id="'mid_' + item.id"  type="radio" :name="item.userFullName" :checked="item.checked" @click="toogleRadioButton(item)">
                        <input v-else="singleSelect" :id="'mid_' + item.id"  type="checkbox" :name="item.userFullName" :checked="item.checked" @click="toggleActivation(item)">
                        <label :for="'mid_' + item.id"></label>
                    </div>
                    <div class="avatar" v-bind:style="{ backgroundImage: 'url(' + item.userAvatar + ')' }"></div>
                    <div class="info">
                        <p class="title">{{ item.userFullName }}</p>
                        <p class="description"><span v-for="roleName in item.projectRoleNames">{{ translateText(roleName) }}, </span></p>
                    </div>
                </div>
            </div>
            <div class="footer">
                <p v-show="!singleSelect">Selected: <span v-for="item in items"><span v-if="item.checked">{{ item.userFullName }}, </span></span></p>
                <div class="flex flex-space-between">
                    <a href="javascript:void(0)" @click="reset" class="cancel">{{ translateText('button.cancel') }}</a>
                    <a v-if="singleSelect" href="javascript:void(0)" @click="updateSelected()" class="show">{{ translateText('button.done') }}</a>
                    <a v-else="singleSelect" href="javascript:void(0)" @click="updateSelected()" class="show">{{ translateText('button.show_selected') }}</a>
                </div>
            </div>
        </div>
        <div class="results team no-data" v-if="noData && query !== ''">
            <div>{{ translateText('label.no_data') }}</div>
        </div>
        <p v-if="usersList && usersList.length" v-for="user in usersList" class="selected-item">
            {{ user.firstName }} {{ user.lastName }}
            <a @click="removeSelectedOption(user.id)"> <i class="fa fa-times"></i></a>
        </p>
    </div>
</template>

<script>
import VueTypeahead from 'vue-typeahead';
import {mapActions, mapGetters} from 'vuex';
import 'jquery.nicescroll/jquery.nicescroll.js';

export default {
    extends: VueTypeahead,
    props: ['placeholder', 'singleSelect', 'value', 'selectedUser'],
    computed: {
        ...mapGetters(['users']),
    },
    watch: {
        users(val) {
            if (this.displaySelectedMembers()) {
                this.usersList = this.users;
            }
        },
        value(val) {
            if (val.length && val[0] != null) {
                this.getUsers({id: val});
            } else {
                this.clearUsers();
            }
        },
        hasItems(val) {
            if (val) {
                let scrollTop = $(window).scrollTop();
                let elementOffset = $(this.$el).offset().top;
                let currentElementOffset = (elementOffset - scrollTop);

                let windowInnerHeight = window.innerHeight;

                if (windowInnerHeight - currentElementOffset < 260) {
                    $(this.$el).find('.results.team').css('top', '-340px');
                } else {
                    $(this.$el).find('.results.team').css('top', '41px');
                }
            }
        },
    },
    methods: {
        ...mapActions(['getUsers', 'clearUsers']),
        translateText: function(text) {
            return this.translate(text);
        },
        toggleActivation(item) {
            item.checked = !item.checked;
        },
        toogleRadioButton(item) {
            this.items = this.items.map(item => {
                item.checked = false;
                return item;
            });
            item.checked = true;
        },
        prepareResponseData(data) {
            let items = data.items;
            if (!Array.isArray(items) || !items) {
                return [];
            }
            let users = [];
            let selected = this.selectedUsers;
            items.map(function(user) {
                let checked = false;
                for (let i=0; i < selected.length; i++) {
                    if (selected[i] === user.user) {
                        checked = true;
                    }
                    user.checked = checked;
                }
                users.push(user);
            });
            this.noData = users.length === 0 ? true : false;

            return users;
        },
        updateSelected() {
            let users = [];
            this.items.map(function(user) {
                if (user.checked) {
                    users.push(user.user);
                }
            });
            this.selectedUsers = users;
            this.$emit('input', this.selectedUsers);
            const items = this.items;
            this.reset();
            if (this.singleSelect && items.length > 0) {
                this.query = items.filter((item) => item.checked)[0].userFullName;
            }
        },
        clearValue() {
            this.query = '';
            this.items = [];
            this.noData = false;
            this.usersList = [];
            this.selectedUsers = [];
            this.updateSelected();
        },
        removeSelectedOption(id) {
            this.$emit('input', this.value.filter(item => parseInt(item, 10) !== parseInt(id, 10)));

            if (this.singleSelect) {
                this.usersList = [];
                this.selectedUsers = [];
            } else {
                let indexTmp;
                this.usersList.map(function(user, index) {
                    if (user.id == id) {
                        indexTmp = index;
                    }
                });
                this.usersList.splice(indexTmp, 1);
                this.selectedUsers.splice(indexTmp, 1);
            }
        },
        displaySelectedMembers() {
            if (!this.value || this.value.length <= 0) {
                return false;
            }

            for (let i = 0; i < this.value.length; i++) {
                if (this.users[i] && this.value[i] !== this.users[i].id) {
                    return false;
                }
            }
            return true;
        },
    },
    data() {
        return {
            src: Routing.generate('app_api_project_project_users', {id: this.$route.params.id}),
            queryParamName: 'search',
            noData: null,
            minChars: 1,
            selectedUsers: [],
            usersList: [],
        };
    },
    created() {
        this.clearUsers();
    },
    mounted() {
        const $this = window.$('#input' + this._uid);
        let textValue = $this.val();
        let $label = $this.next();

        if (this.selectedUser && this.value) {
            this.query = this.selectedUser;
            this.selectedUsers = this.value;
        }

        $label.on('click', function() {
            $(this).prev().focus();
        });

        $this.focus(function() {
            $this.next().addClass('active');
        });

        if ($this.disabled = true) {
            $this.next().addClass('active');
        }

        if ($this.val() === '' || $this.val() === 'blank') {
            $this.next().removeClass();
        }

        $this.blur(function() {
            if ($this.val() === '' || $this.val() === 'blank') {
                $this.next().removeClass();
            }
        });

        if (textValue !== '') {
            $this.next().addClass('active');
        }

        // nicescroll
        window.$(document).ready(function() {
            window.$('.nicescroll').niceScroll({
                autohidemode: false,
            });
        });

        $('select').next().removeClass();
    },
};
</script>

<style lang="scss">
    @import '../../css/page-section';
    @import '../../css/_variables';
    @import '../../css/_mixins.scss';

    .modal .modal-inner {
        width: 600px;
    }

    .actions .search input[type=text] {
        width: 420px;
        height: 40px;
    }
</style>

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
            margin-bottom: 30px;
        }

        .main-list .member {
            border-top: 1px solid $darkColor;
        }

        .results {
            width: 600px;
            .members.nicescroll{
                max-height: 265px;
            }
        }
    }

    .results {
        .members.nicescroll{
            max-height: 265px;
        }
    }

    .st0 {
        stroke: $lighterColor;
    }

    .search {
        position: relative;

        .scroll-list {
            max-height: 200px;
        }

        .team {
            margin-top: 0;

            .footer {
                margin: 0 -20px;
                padding: 17px 20px 0 20px;
                border-top: 1px solid $mainColor;
            }

            .footer p {
                margin-bottom: 11px;
                font-size: 10px;
                letter-spacing: 0.1em;
                text-transform: uppercase;
            }

            .footer a {
                text-transform: uppercase;
                letter-spacing: 0.1em;
                @include transition(color, 0.3s, ease);
            }

            .footer .cancel {
                color: $middleColor;

                &:hover {
                    color: darken($middleColor, 15%);
                }
            }

            .footer .show {
                color: $secondColor;

                &:hover {
                    color: $secondDarkColor;
                }
            }
        }
        .member-search-clear-button {
            position: absolute;
            right: 0;
            top: -14px;
            color: $dangerColor;
            cursor: pointer;
            font-style: normal;
        }
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
        padding: 0 20px;
        max-height: 400px;
        z-index: 10;
        box-shadow: 0 0 8px -2px #000;

        &.no-data {
            width: 100% !important;
            position: absolute;
            padding: 5px 20px;
        }
    }

    .member {
        padding: 20px 0;
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
