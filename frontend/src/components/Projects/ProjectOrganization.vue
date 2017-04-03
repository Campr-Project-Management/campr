<template>
    <div class="project-organization page-section">

        <modal v-if="showModal" @close="showModal = false">
            <p class="modal-title">Add New Distribution List</p>
            <input-field type="text" label="Distribution List Title"></input-field>

            <div class="search">
                <input-field type="text" label="Search For Resources"></input-field>
                <div class="results team" v-if="results">
                    <vue-scrollbar class="scroll-list">
                        <div class="members">
                            <div class="member flex">
                                <div class="checkbox-input clearfix" v-bind:class="{'inactive': inactive}">
                                    <input id="toggle-active" type="checkbox" name="" value="1" :checked="inactive ? false : true" @click="toggleActivation()">
                                    <label for="toggle-active"></label>
                                </div>
                                <img src="">
                                <div class="info">
                                    <p class="title">Alexander Martins</p>
                                    <p class="description">Symfony Developer - Tchnical Services</p>
                                </div>
                            </div>
                            <div class="member flex">
                                <div class="checkbox-input clearfix" v-bind:class="{'inactive': inactive}">
                                    <input id="toggle-active" type="checkbox" name="" value="1" :checked="inactive ? false : true" @click="toggleActivation()">
                                    <label for="toggle-active"></label>
                                </div>
                                <img src="">
                                <div class="info">
                                    <p class="title">Alexander Martins</p>
                                    <p class="description">Symfony Developer - Tchnical Services</p>
                                </div>
                            </div>
                            <div class="member flex">
                                <div class="checkbox-input clearfix" v-bind:class="{'inactive': inactive}">
                                    <input id="toggle-active" type="checkbox" name="" value="1" :checked="inactive ? false : true" @click="toggleActivation()">
                                    <label for="toggle-active"></label>
                                </div>
                                <img src="">
                                <div class="info">
                                    <p class="title">Alexander Martins</p>
                                    <p class="description">Symfony Developer - Tchnical Services</p>
                                </div>
                            </div>
                        </div>
                    </vue-scrollbar>
                    <div class="footer">
                        <p>Selected: <span>Michael Martins</span></p>
                        <div class="flex flex-space-between">
                            <a href="javascript:void(0)" class="cancel">Cancel</a>
                            <a href="javascript:void(0)" class="show">Show Selected</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="members main-list">
                <div class="member flex">
                    <img src="">
                    <div class="info">
                        <p class="title">Alexander Martins</p>
                        <p class="description">Symfony Developer - Tchnical Services</p>
                    </div>
                </div>
                <div class="member flex">
                    <img src="">
                    <div class="info">
                        <p class="title">Alexander Martins</p>
                        <p class="description">Symfony Developer - Tchnical Services</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-space-between">
                <a href="javascript:void(0)" class="btn-rounded btn-empty danger-color danger-border">Cancel</a>
                <a href="javascript:void(0)" class="btn-rounded">Create Distribution List +</a>
            </div>
        </modal>

        <div class="header">
            <h1>Project Organization</h1>
        </div>

        <div class="team-graph">
            <member-badge class="lead" size="big" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
            <div class="flex flex-space-between">
                <div>
                    <member-badge size="big" class="lead" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
                    <div class="flex">
                        <div>
                            <member-badge size="small" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
                            <a href="" class="btn-rounded btn-empty btn-small">
                                View Team
                            </a>
                        </div>
                        <div>
                            <member-badge size="small" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
                            <a href="" class="btn-rounded btn-empty btn-small">
                                View Team
                            </a>
                        </div>
                        <div class="member-badge-wrapper">
                            <member-badge size="small" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
                            <a href="javascript:void(0)" class="btn-rounded btn-empty btn-small" @click="toggleTeam()" v-bind:class="{'show-team': showTeam}">
                                View Team
                                <i class="fa fa-angle-down" v-show="!showTeam"></i>
                                <i class="fa fa-angle-up" v-show="showTeam"></i>
                            </a>
                            <div class="team" v-show="showTeam">
                                <div class="member flex">
                                    <img src="">
                                    <div class="info">
                                        <p class="title">Alexander Martins</p>
                                        <p class="description">Symfony Developer - Tchnical Services</p>
                                        <social-links align="left" size="16px" facebook="http://www.facebook.com" twitter="http://www.twitter.com" linkedin="http://www.linkedin.com" gplus="http://www.google.com" email="test@test.com" phone="1234 56 78"></social-links>
                                    </div>
                                </div>
                                <div class="member flex">
                                    <img src="">
                                    <div class="info">
                                        <p class="title">Alexander Martins</p>
                                        <p class="description">Symfony Developer - Tchnical Services</p>
                                        <social-links align="left" size="16px" facebook="http://www.facebook.com" twitter="http://www.twitter.com" linkedin="http://www.linkedin.com" gplus="http://www.google.com" email="test@test.com" phone="1234 56 78"></social-links>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <member-badge size="big" class="lead" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
                    <div class="flex">
                        <div>
                            <member-badge size="small" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
                            <a href="" class="btn-rounded btn-empty btn-small">
                                View Team
                            </a>
                        </div>
                        <div>
                            <member-badge size="small" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
                            <a href="" class="btn-rounded btn-empty btn-small">
                                View Team
                            </a>
                        </div>
                    </div>
                </div>
                <div>
                    <member-badge size="big" class="lead" name="Nelson Carr" title="Project Manager, Global Operations"></member-badge>
                </div>
            </div>
        </div>
        <div class="flex flex-space-between actions">
            <div class="search">
                <input-field type="text" label="Search for team members"></input-field>
                <div class="results team">
                    <vue-scrollbar class="scroll-list">
                        <div class="members">
                            <div class="member flex">
                                <div class="checkbox-input clearfix" v-bind:class="{'inactive': inactive}">
                                    <input id="toggle-active" type="checkbox" name="" value="1" :checked="inactive ? false : true" @click="toggleActivation()">
                                    <label for="toggle-active"></label>
                                </div>
                                <img src="">
                                <div class="info">
                                    <p class="title">Alexander Martins</p>
                                    <p class="description">Symfony Developer - Tchnical Services</p>
                                </div>
                            </div>
                            <div class="member flex">
                                <div class="checkbox-input clearfix" v-bind:class="{'inactive': inactive}">
                                    <input id="toggle-active" type="checkbox" name="" value="1" :checked="inactive ? false : true" @click="toggleActivation()">
                                    <label for="toggle-active"></label>
                                </div>
                                <img src="">
                                <div class="info">
                                    <p class="title">Alexander Martins</p>
                                    <p class="description">Symfony Developer - Tchnical Services</p>
                                </div>
                            </div>
                            <div class="member flex">
                                <div class="checkbox-input clearfix" v-bind:class="{'inactive': inactive}">
                                    <input id="toggle-active" type="checkbox" name="" value="1" :checked="inactive ? false : true" @click="toggleActivation()">
                                    <label for="toggle-active"></label>
                                </div>
                                <img src="">
                                <div class="info">
                                    <p class="title">Alexander Martins</p>
                                    <p class="description">Symfony Developer - Tchnical Services</p>
                                </div>
                            </div>
                        </div>
                    </vue-scrollbar>
                    <div class="footer">
                        <p>Selected: <span>Michael Martins</span></p>
                        <div class="flex flex-space-between">
                            <a href="" class="cancel">Cancel</a>
                            <a href="" class="show">Show Selected</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex">
                <a href="" class="btn-rounded second-bg">Add New Team Members</a>
                <a href="javascript:void(0)" class="btn-rounded btn-empty" @click="showModal = true">Create Distribution List</a>
            </div>
        </div>
        <div class="team-list">
            <vue-scrollbar class="table-wrapper">
                <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th class="center">Resources</th>
                        <th>Company</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Subteam</th>
                        <th>Department</th>
                        <th>Contact</th>
                        <th class="center">Raci</th>
                        <th class="center">Org</th>
                        <th colspan="2" class="center">
                            <p>Distribution Lists</p>
                            <p class="small">
                                <span>TP Meeting</span>
                                <span>EK Meeting</span>
                            </p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="avatar center">
                            <img src="" />
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                        <td>John Doe</td>
                        <td>Heraeus</td>
                        <td>Project Manager</td>
                        <td>PMO</td>
                        <td>GMP</td>
                        <td>
                            <social-links align="left" size="20px" facebook="http://www.facebook.com" twitter="http://www.twitter.com" linkedin="http://www.linkedin.com" gplus="http://www.google.com" email="test@test.com" phone="1234 56 78"></social-links>
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                    </tr>
                    <tr>
                        <td class="avatar center">
                            <img src="" />
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                        <td>John Doe</td>
                        <td>Heraeus</td>
                        <td>Project Manager</td>
                        <td>PMO</td>
                        <td>GMP</td>
                        <td>
                            <social-links align="left" size="20px" facebook="http://www.facebook.com" twitter="http://www.twitter.com" linkedin="http://www.linkedin.com" gplus="http://www.google.com" email="test@test.com" phone="123 456 78"></social-links>
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                        <td class="center">
                            <switches v-model="test" :selected="true"></switches>
                        </td>
                    </tr>
                </tbody>
                </table>
            </vue-scrollbar>

            <div class="flex flex-direction-reverse">
                <div class="pagination" v-if="count > 0">
                    <span v-for="page in count/2" v-bind:class="{'active': page == activePage}" @click="changePage(page)">{{ page }}</span>
                </div>
                <span class="pagination-info">Displaying 10 results out of 43</span>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters, mapActions} from 'vuex';
import MemberBadge from '../_common/MemberBadge';
import SocialLinks from '../_common/SocialLinks';
import InputField from '../_common/_form-components/InputField';
import Switches from '../3rdparty/vue-switches';
import VueScrollbar from 'vue2-scrollbar';
import Modal from '../_common/Modal';
import 'vue2-scrollbar/dist/style/vue2-scrollbar.css';

export default {
    components: {
        MemberBadge,
        SocialLinks,
        InputField,
        Switches,
        Modal,
        VueScrollbar,
    },
    methods: {
        ...mapActions(['getProjectById']),
        changePage(page) {
            this.activePage = page;
        },
        toggleTeam() {
            this.showTeam = !this.showTeam;
        },
        toggleActivation() {
            this.inactive = !this.inactive;
        },
    },
    created() {
        this.getProjectById(this.$route.params.id);
    },
    computed: mapGetters({
        project: 'project',
    }),
    data: function() {
        return {
            'test': 1,
            'inactive': 1,
            'count': 4,
            'page': 1,
            'activePage': 1,
            'showTeam': false,
            'showModal': false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

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

    .social-links {
        margin-top: 5px;
    }

    .member-badge.small {
        margin: 0 auto 13px;
    }

    .pagination-info {
        text-transform: uppercase;
        margin-top: 27px;
    }

    .team-graph {
        width: 70%;
        margin: 0 auto;
    }

    .lead {
        margin: 0 auto 15px !important;
    }

    .team-list {
        overflow: hidden;
    }

    .center {
        text-align: center;
    }

    .btn-rounded {
        width: auto;
        margin: 0 10px;
    }

    .btn-small {
        height: 30px;
        line-height: 30px;
        width: 161px;

        &.show-team {
            background: $middleColor;
        }
    }

    .actions {
        margin: 54px 0 30px;
    }

    .table-wrapper {
        padding-bottom: 40px;
    }

    .table {
        margin-bottom: 0;
        font-size: 10px;
        font-weight: 400;
        letter-spacing: 1.8px;

        >tbody>tr:nth-of-type(odd) {
            background: $mainColor;
        }

        >tbody>tr:nth-of-type(even) {
            background: $semiDarkColor;
        }

        th {
            border-bottom: none;
            background: $darkColor;
            text-transform: uppercase;
            vertical-align: middle;
            height: 60px;
            padding-top: 0;
            padding-bottom: 0;
            font-weight: 400;

            p {
                padding: 8px 0;

                &.small {
                    size: 8px;
                }
            }
        }

        td {
            border-top: none;
            vertical-align: middle;
        }

        .avatar img {
            width: 46px;
            height: 46px;
            border-radius: 50%;
        }
    }
</style>
