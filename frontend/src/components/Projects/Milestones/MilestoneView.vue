<template>
    <div class="row">
        <div class="col-md-6">
            <div class="view-milestone page-section">
                <!-- /// Header /// -->
                <div class="header flex flex-space-between">
                    <div class="flex">
                        <div>
                            <router-link :to="{name: 'project-phases-and-milestones'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to_phases_and_milestones') }}
                            </router-link>
                            <h1 class="title">Phase 3.1</h1>

                            <router-link :to="{name: 'project-phases-and-milestones'}" class="parent-phase router-link-active uppercase middle-color">
                                {{ translateText('message.parent_phase') }}
                                <span class="second-color">Phase 3</span>
                            </router-link>
                        </div>
                    </div>

                    <div class="flex flex-v-center">
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto second-bg">
                            {{ translateText('button.edit_milestone') }}
                        </router-link>
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto danger-bg">
                            {{ translateText('button.delete_milestone') }}
                        </router-link>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <!-- /// Content /// -->
                <div class="milestone-content">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris id ante eu velit suscipit elementum. Curabitur dolor tellus, lacinia nec massa eu, ullamcorper consequat nulla. Nulla facilisi. Phasellus ac odio odio. Phasellus pharetra ornare velit a auctor. Nam ac lectus enim. Sed elementum placerat elit in blandit. Praesent et augue convallis, finibus tellus nec, condimentum ex. Donec viverra volutpat mauris ut interdum.
                    </p>

                    <p>
                        In sit amet pharetra mauris, vel facilisis tellus. Curabitur sed tempor turpis. Nullam tincidunt magna vel odio malesuada lacinia. Phasellus mollis lobortis metus non bibendum. Cras ante ipsum, iaculis ut massa eget, tincidunt auctor nulla. Donec interdum lacus ac lacus euismod vehicula. Sed sed fringilla magna. Nam quis tincidunt ante. Nam ac est nunc. Curabitur eros nisi, sollicitudin sit amet tempor id, rutrum quis nunc. Sed ac leo ipsum.
                    </p>

                    <hr>

                    <h3>{{ translateText('message.schedule') }}</h3>

                    <div class="table-wrapper">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>{{ translateText('message.schedule') }}</th>
                                    <th>{{ translateText('button.due_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Base</td>
                                    <td>25.05.2017</td>
                                </tr>
                                <tr class="column-warning">
                                    <td>Automatic</td>
                                    <td>25.05.2017</td>
                                </tr>
                                <tr class="column-alert">
                                    <td>Actual</td>
                                    <td>25.05.2017</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <div class="row responsible-status">
                        <div class="col-md-6">
                            <h3>{{ translateText('message.responsible') }}</h3>
                            <div class="user-info">
                                <img class="user-avatar" src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" alt=""/>
                                <span class="uppercase">
                                    John Doe
                                    <router-link :to="{name: 'project-phases-and-milestones'}" class="second-color">
                                        @john.doe
                                    </router-link>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3>{{ translateText('message.status') }}</h3>
                            <div class="status warning-color uppercase">
                                <span class="danger-color">overdue</span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- /// Actions /// -->
                    <div class="flex flex-space-between">
                        <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto disable-bg">
                            {{ translateText('button.cancel') }}
                        </router-link>

                        <div>
                            <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto second-bg">
                                {{ translateText('button.edit_milestone') }}
                            </router-link>
                            <router-link :to="{name: 'project-phases-and-milestones'}" class="btn-rounded btn-auto danger-bg">
                                {{ translateText('button.delete_milestone') }}
                            </router-link>
                        </div>
                    </div>
                    <!-- /// End Actions /// -->
                </div>
                <!-- /// End Content /// -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            translateText(text) {
                return this.translate(text);
            },
        },
    };
</script>

<style scoped lang="scss">
    .btn-rounded {
        margin-left: 20px;
    }
</style>
