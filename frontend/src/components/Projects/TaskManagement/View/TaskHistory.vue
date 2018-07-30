<template>
    <div class="task-history">
        <scrollbar class="histories-scroll customScrollbar" @ps-y-reach-end="onScrollEnd">
            <div v-for="item in items" :key="item.id">
                <!-- /// Task assignement /// -->
                <div v-if="item.isResponsibilityAdded">
                    <div class="comment">
                        <div class="comment-header">
                            <user-avatar
                                    size="small"
                                    :url="item.userAvatar"
                                    :name="item.userFullName"/>
                            <router-link
                                    :to="{name: 'project-organization-view-member', params: {userId: item.projectUserId} }"
                                    class="simple-link">
                                <b class="uppercase">{{item.userFullName}}</b>
                            </router-link>
                            {{ translate('message.assigned_to') }}
                            <router-link
                                    :to="{name: 'project-organization-view-member', params: {userId: item.newResponsibility.id} }"
                                    class="simple-link">
                                <b class="uppercase">{{ item.newResponsibility.userFullName }}</b>
                            </router-link>
                            {{ item.createdAt | humanizeDate }}
                        </div>
                    </div>
                    <hr class="double">
                </div>
                <!-- /// End Task Assignement /// -->

                <!-- /// Task Comment /// -->
                <div v-else-if="item.isCommentAdded">
                    <div class="comment">
                        <div class="comment-header">
                            <user-avatar
                                    size="small"
                                    :url="item.userAvatar"
                                    :name="item.userFullName"/>
                            <router-link
                                    :to="{name: 'project-organization-view-member', params: {userId: item.projectUserId} }"
                                    class="simple-link">
                                <b class="uppercase">{{ item.userFullName }}</b>
                            </router-link>
                            {{ translate('message.has_commented_task') }} {{ item.createdAt | humanizeDate }}
                        </div>
                        <div class="comment-body" v-html="item.newValue.comment">
                        </div>
                    </div>
                    <hr class="double">
                </div>
                <!-- /// End Task Comment /// -->

                <!-- /// Task Label added /// -->
                <div v-else-if="item.isLabelAdded">
                    <div class="comment">
                        <div class="comment-header">
                            <user-avatar
                                    size="small"
                                    :url="item.userAvatar"
                                    :name="item.userFullName"/>
                            <router-link
                                    :to="{name: 'project-organization-view-member', params: {userId: item.projectUserId} }"
                                    class="simple-link">
                                <b class="uppercase">{{item.userFullName}}</b>
                            </router-link>
                            <div class="task-label" :style="'background-color:#e04fcc'">
                                {{ translate('message.high_priority') }}
                            </div>
                            {{ item.createdAt | humanizeDate }}
                        </div>
                    </div>
                    <hr class="double">
                </div>
                <!-- /// End Task Label Added /// -->

                <!-- /// Task Edited /// -->
                <div v-else-if="item.isFieldEdited" >
                    <div class="comment">
                        <div class="comment-header">
                            <user-avatar
                                    size="small"
                                    :url="item.userAvatar"
                                    :name="item.userFullName"/>
                            <router-link
                                    :to="{name: 'project-organization-view-member', params: {userId: item.projectUserId} }"
                                    class="simple-link">
                                <b class="uppercase">{{item.userFullName}}</b>
                            </router-link>
                            {{ translate('message.has_edited_task') }} {{ item.createdAt | humanizeDate }}
                        </div>
                    </div>
                    <hr class="double">
                </div>
                <!-- /// End Task Edited /// -->
            </div>
        </scrollbar>
    </div>
</template>

<script>
    import UserAvatar from '../../../_common/UserAvatar';

    export default {
        name: 'task-history',
        components: {
            UserAvatar,
        },
        props: {
            items: {
                type: Array,
                required: true,
                default: [],
            },
        },
        methods: {
            onScrollEnd(evt) {
                this.$emit('input', true);
            },
        },
    };
</script>
