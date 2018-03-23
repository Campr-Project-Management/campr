<template>
    <div class="task-history">
        <div v-for="item in history" :key="item.id">

            <!-- /// Task assignement /// -->
            <div v-if="item.isResponsibilityAdded">
                <div class="comment">
                    <div class="comment-header">
                        <div class="user-avatar">
                            <img :src="item.userAvatar" :alt="item.userFullName"/>
                            <b>{{item.userFullName}}</b>
                        </div>
                        <router-link
                                :to="{name: 'project-organization-view-member', params: {userId: item.userId} }"
                                class="simple-link">
                            @{{ item.userUsername }}
                        </router-link>
                        {{ translate('message.assigned_to') }}
                        <router-link
                                :to="{name: 'project-organization-view-member', params: {userId: item.newValue.responsibility[1]} }"
                                class="simple-link">
                            @{{ item.userUsername }}
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
                        <div class="user-avatar">
                            <img :src="item.userAvatar" :alt="item.userFullName"/>
                            <b>{{item.userFullName}}</b>
                        </div>
                        <router-link
                                :to="{name: 'project-organization-view-member', params: {userId: item.userId} }"
                                class="simple-link">
                            @{{ item.userUsername }}
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
                        <div class="user-avatar">
                            <img :src="item.userAvatar" :alt="item.userFullName"/>
                            <b>{{item.userFullName}}</b>
                        </div>
                        <router-link
                                :to="{name: 'project-organization-view-member', params: {userId: item.userId} }"
                                class="simple-link">
                            @{{ item.userUsername }}
                        </router-link>
                        <div class="task-label" :style="'background-color:#e04fcc'">
                            High Priority
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
                        <div class="user-avatar">
                            <img :src="item.userAvatar" :alt="item.userFullName"/>
                            <b>{{item.userFullName}}</b>
                        </div>
                        <router-link
                                :to="{name: 'project-organization-view-member', params: {userId: item.userId} }"
                                class="simple-link">
                            @{{ item.userUsername }}
                        </router-link>
                        {{ translate('message.has_edited_task') }} {{ item.createdAt | humanizeDate }}
                    </div>
                </div>
                <hr class="double">
            </div>
            <!-- /// End Task Edited /// -->
        </div>
    </div>
</template>

<script>
    export default {
        name: 'task-history',
        props: {
            history: {
                type: Array,
                required: true,
                default: [],
            },
        },
    };
</script>
