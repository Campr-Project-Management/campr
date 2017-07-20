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
        <div class="results team" v-show="hasItems">
            <vue-scrollbar class="scroll-list">
                <div class="members">
                    <div class="member flex flex-v-center" v-for="item in items">
                        <div class="checkbox-input clearfix" :class="{'inactive': !item.checked}">
                            <input v-if="singleSelect" :id="item.id"  type="radio" :name="item.userFullName" :checked="item.checked" @click="toogleRadioButton(item)">
                            <input v-else="singleSelect" :id="item.id"  type="checkbox" :name="item.userFullName" :checked="item.checked" @click="toggleActivation(item)">
                            <label :for="item.id"></label>
                        </div>
                        <div class="avatar" v-bind:style="{ backgroundImage: 'url(' + item.userAvatar + ')' }"></div>
                        <div class="info">
                            <p class="title">{{ item.userFullName }}</p>
                            <p class="description">{{ item.projectRoleName }}</p>
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
            </vue-scrollbar>
        </div>
    </div>
</template>

<script>
import VueTypeahead from 'vue-typeahead';
import VueScrollbar from 'vue2-scrollbar';
import 'vue2-scrollbar/dist/style/vue2-scrollbar.css';
import {mapActions} from 'vuex';

export default {
    extends: VueTypeahead,
    props: ['placeholder', 'singleSelect', 'value', 'selectedUser'],
    components: {
        VueScrollbar,
    },
    methods: {
        ...mapActions(['getProjectUsers']),
        translateText: function(text) {
            return this.translate(text);
        },
        toggleActivation(item) {
            item.checked = !item.checked;
        },
        toogleRadioButton(item) {
            this.items.map(item => item.checked = false);
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
            if (this.singleSelect) {
                this.query = items.filter((item) => item.checked)[0].userFullName;
            }
        },
    },
    data() {
        return {
            src: Routing.generate('app_api_project_project_users', {'id': this.$route.params.id}),
            queryParamName: 'search',
            minChars: 3,
            selectedUsers: [],
        };
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
        };

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

        $('select').next().removeClass();
    },
};
</script>

<style lang="scss">
    @import '../../css/page-section';
    @import '../../css/_variables';

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
    }

    .member-badge-wrapper {
        position: relative;
    }

    .team {
        position: absolute;
        width: 420px;
        background: $darkColor;
        top: 100%;
        margin-top: 10px;
        padding: 0 20px;
        max-height: 400px;
        z-index: 10;
        box-shadow: 0 0 8px -2px #000;
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
</style>
