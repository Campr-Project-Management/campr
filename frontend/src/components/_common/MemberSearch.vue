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
                    <div class="member flex" v-for="item in items">
                        <div class="checkbox-input clearfix" :class="{'inactive': !item.checked}">
                            <input :id="item.id"  type="checkbox" :name="item.userFullName" :checked="item.checked" @click="toggleActivation(item)">
                            <label :for="item.id"></label>
                        </div>
                        <img :src="item.userAvatar">
                        <div class="info">
                            <p class="title">{{ item.userFullName }}</p>
                            <p class="description">{{ item.projectRoleName }}</p>
                        </div>
                    </div>
                    <div class="footer">
                        <p>Selected: <span v-for="item in items"><span v-if="item.checked">{{ item.userFullName }}, </span></span></p>
                        <div class="flex flex-space-between">
                            <a @click="reset" class="cancel">{{ button.cancel }}</a>
                            <a @click="updateSelected()" class="show">{{ button.show_selected }}</a>
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
    props: ['placeholder'],
    components: {
        VueScrollbar,
    },
    methods: {
        ...mapActions(['getProjectUsers']),
        toggleActivation(item) {
            item.checked = !item.checked;
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
                    if (selected[i] === user.id) {
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
                    users.push(user.id);
                }
            });
            this.selectedUsers = users;
            this.$emit('input', this.selectedUsers);
            this.reset();
        },
    },
    data: function() {
        return {
            button: {
                cancel: this.translate('button.cancel'),
                show_selected: this.translate('button.show_selected'),
            },
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

            .checkbox-input {
                margin-top: 13px;
                margin-right: 10px;
            }

            .footer {
                margin: 0 -20px;
                padding: 17px 20px;
                border-top: 1px solid $mainColor;
            }

            .footer p {
                margin-bottom: 11px;
                font-size: 10px;
            }

            .footer a {
                text-transform: uppercase;
            }

            .footer .cancel {
                color: $middleColor;
            }

            .footer .show {
                color: $secondColor;
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
            border-bottom: none;
        }
    }

    img {
        width: 46px;
        height: 46px;
    }

    .info {
        text-transform: uppercase;
        padding-left: 10px;
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
