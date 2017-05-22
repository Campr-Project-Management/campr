<template>
    <div class="page-section">
        <div class="row">
            <div class="col-lg-6 dark-border-right">
                <!-- /// Project Opportunities Header /// -->
                <div class="header flex flex-space-between">
                    <div class="flex">
                        <h1>{{ translateText('message.project_opportunities') }}</h1>
                    </div>
                    <div class="flex flex-v-center">
                        <router-link :to="{name: 'project-opportunities-create-opportunity'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.new_opportunity') }}</router-link>
                    </div>
                </div>
                <!-- /// End Project Opportunities Header /// -->

                <!-- /// Project Opportunities /// -->
                <div class="ro-grid-wrapper clearfix">
                    <!-- /// Project Opportunities Grid /// -->
                    <div class="ro-grid">
                        <div class="ro-grid-header vertical-axis-header">
                            <div class="big-header">{{ translateText('message.impact') }}</div>
                            <div class="small-headers clearfix">
                                <div class="small-header">{{ translateText('message.very_low') }}</div>
                                <div class="small-header">{{ translateText('message.low') }}</div>
                                <div class="small-header">{{ translateText('message.high') }}</div>
                                <div class="small-header">{{ translateText('message.very_high') }}</div>
                            </div>
                        </div> 
                        <div class="ro-grid-items clearfix">
                            <div class="ro-grid-item medium"><span>1</span></div>
                            <div class="ro-grid-item low"></div>
                            <div class="ro-grid-item very-low"></div>
                            <div class="ro-grid-item very-low active"><span>3</span></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item high"></div>
                            <div class="ro-grid-item medium"><span>1</span></div>
                            <div class="ro-grid-item low"></div>
                            <div class="ro-grid-item very-low"><span>2</span></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item very-high"></div>
                            <div class="ro-grid-item high"></div>
                            <div class="ro-grid-item medium"></div>
                            <div class="ro-grid-item low"></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item very-high"></div>
                            <div class="ro-grid-item very-high"></div>
                            <div class="ro-grid-item high"></div>
                            <div class="ro-grid-item medium"></div>
                        </div>
                        <div class="ro-grid-header horizontal-axis-header">                            
                            <div class="small-headers clearfix">
                                <div class="small-header">{{ translateText('message.very_low') }}</div>
                                <div class="small-header">{{ translateText('message.low') }}</div>
                                <div class="small-header">{{ translateText('message.high') }}</div>
                                <div class="small-header">{{ translateText('message.very_high') }}</div>
                            </div>
                            <div class="big-header">{{ translateText('message.probability') }}</div>
                        </div>
                    </div>
                    <!-- /// End Project Opportunities Grid /// -->

                    <!-- /// Project Opportunities List /// -->
                    <div class="ro-list">
                        <div class="ro-list-item">
                            <div class="avatar" style="background-image:url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg);" v-tooltip.top="'Responsible: David Guetta'"></div>
                            <a href="#" title="Plant based dietary Program">1. Plant based dietary Program</a>
                            <p>{{ translateText('message.potential_savings') }}: <b>$4.850</b></p>
                            <p>{{ translateText('message.potential_time_savings') }}: <b>14 days</b></p>
                            <p>{{ translateText('message.priority') }}: <b>Very High</b></p>
                            <p>{{ translateText('message.strategy') }}: <b>Take</b></p>
                            <p>{{ translateText('message.status') }}: <b>Ongoing</b></p>
                        </div>
                        <div class="ro-list-item">
                            <div class="avatar" style="background-image:url(http://trisoft.dev.campr.biz/uploads/avatars/60.jpg);" v-tooltip.top="'Responsible: Lindsay Wong'"></div>
                            <a href="#" title="Plant based dietary Program">2. Self Landing Supply Rockets</a>
                            <p>{{ translateText('message.potential_savings') }}: <b>$18.500</b></p>
                            <p>{{ translateText('message.potential_time_savings') }}: <b>60 days</b></p>
                            <p>{{ translateText('message.priority') }}: <b>Very High</b></p>
                            <p>{{ translateText('message.strategy') }}: <b>Increase</b></p>
                            <p>{{ translateText('message.status') }}: <b>Initiated</b></p>
                        </div>
                        <div class="ro-list-item">
                            <div class="avatar" style="background-image:url(http://trisoft.dev.campr.biz/uploads/avatars/64.jpg);" v-tooltip.top="'Responsible: Maria Anabelle'"></div>
                            <a href="#" title="Plant based dietary Program">3. S.M.A.R.T Technology</a>
                            <p>{{ translateText('message.potential_savings') }}: <b>$300.000</b></p>
                            <p>{{ translateText('message.potential_time_savings') }}: <b>90 days</b></p>
                            <p>{{ translateText('message.priority') }}: <b>Very High</b></p>
                            <p>{{ translateText('message.strategy') }}: <b>Take</b></p>
                            <p>{{ translateText('message.status') }}: <b>Ongoing</b></p>
                        </div>
                    </div>
                    <!-- /// End Project Opportunities List /// -->
                </div>
                <!-- /// End Project Opportunities /// -->

                <!-- /// Project Opportunities Summary /// -->
                <div class="ro-summary">
                    <div class="text-center flex flex-center">
                        <div class="text-right">
                            <p>{{ translateText('message.total_potential_savings') }}:</p>
                            <p>{{ translateText('message.total_potential_time_savings') }}:</p>
                            <p>{{ translateText('message.total_number_of_measures') }}:</p>
                            <p>{{ translateText('message.total_cost_of_measures') }}:</p>
                        </div>
                        <div class="text-left">
                            <p><b>$323.350</b></p>
                            <p><b>74 days</b></p>
                            <p><b>12</b></p>
                            <p><b>$85.550</b></p>
                        </div>
                    </div>
                </div>
                <!-- /// End Project Opportunities Summary /// -->
            </div>
            <div class="col-lg-6">
                <!-- /// Project Risks Header /// -->
                <div class="header flex flex-space-between paddingright15">
                    <div class="flex">
                        <h1>{{ translateText('message.project_risks') }}</h1>
                    </div>
                    <div class="flex flex-v-center">
                        <router-link :to="{name: 'project-risks-create-risk'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.new_risk') }}</router-link>
                    </div>
                </div>
                <!-- /// End Project Risks Header /// -->

                <!-- /// Project Risks /// -->
                <div class="ro-grid-wrapper clearfix">
                    <!-- /// Project Risks Grid /// -->
                    <div class="ro-grid">
                        <div class="ro-grid-header vertical-axis-header">
                            <div class="big-header">{{ translateText('message.impact') }}</div>
                            <div class="small-headers clearfix">
                                <div class="small-header">{{ translateText('message.very_low') }}</div>
                                <div class="small-header">{{ translateText('message.low') }}</div>
                                <div class="small-header">{{ translateText('message.high') }}</div>
                                <div class="small-header">{{ translateText('message.very_high') }}</div>
                            </div>
                        </div> 
                        <div class="ro-grid-items clearfix">
                            <div class="ro-grid-item medium"><span>1</span></div>
                            <div class="ro-grid-item high"></div>
                            <div class="ro-grid-item very-high"></div>
                            <div class="ro-grid-item very-high"></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item low"></div>
                            <div class="ro-grid-item medium"></div>
                            <div class="ro-grid-item high"><span>2</span></div>
                            <div class="ro-grid-item very-high active"><span>1</span></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item very-low"></div>
                            <div class="ro-grid-item low"></div>
                            <div class="ro-grid-item medium"></div>
                            <div class="ro-grid-item high"></div>
                            <!-- ================================= -->
                            <div class="ro-grid-item very-low"></div>
                            <div class="ro-grid-item very-low"></div>
                            <div class="ro-grid-item low"></div>
                            <div class="ro-grid-item medium"></div>
                        </div>
                        <div class="ro-grid-header horizontal-axis-header">                            
                            <div class="small-headers clearfix">
                                <div class="small-header">{{ translateText('message.very_low') }}</div>
                                <div class="small-header">{{ translateText('message.low') }}</div>
                                <div class="small-header">{{ translateText('message.high') }}</div>
                                <div class="small-header">{{ translateText('message.very_high') }}</div>
                            </div>
                            <div class="big-header">{{ translateText('message.probability') }}</div>
                        </div>
                    </div>
                    <!-- /// End Project Risks Grid /// -->

                    <!-- /// Project Risks List /// -->
                    <div class="ro-list">
                        <div class="ro-list-item">
                            <div class="avatar" style="background-image:url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg);" v-tooltip.top="'Responsible: Motubu Xebala'"></div>
                            <a href="#" title="Plant based dietary Program">1. Unknown ViraL Breach</a>
                            <p>{{ translateText('message.potential_cost') }}: <b>$120.000</b></p>
                            <p>{{ translateText('message.potential_time_delay') }}: <b>60 days</b></p>
                            <p>{{ translateText('message.priority') }}: <b>Very High</b></p>
                            <p>{{ translateText('message.strategy') }}: <b>Avoid</b></p>
                            <p>{{ translateText('message.status') }}: <b>Initiated</b></p>
                        </div>
                    </div>
                    <!-- /// End Project Risks List /// -->
                </div>
                <!-- /// End Project Risks /// -->

                <!-- /// Project Risks Summary /// -->
                <div class="ro-summary">
                    <div class="text-center flex flex-center">
                        <div class="text-right">
                            <p>{{ translateText('message.total_potential_cost') }}:</p>
                            <p>{{ translateText('message.total_potential_delay') }}:</p>
                            <p>{{ translateText('message.total_number_of_measures') }}:</p>
                            <p>{{ translateText('message.total_cost_of_measures') }}:</p>
                        </div>
                        <div class="text-left">
                            <p><b>$120.000</b></p>
                            <p><b>60 days</b></p>
                            <p><b>2</b></p>
                            <p><b>$12.500</b></p>
                        </div>
                    </div>
                </div>
                <!-- /// End Project Risks Summary /// -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/page-section';
    @import '../../css/_mixins';
    @import '../../css/_variables';

    .dark-border-right {
        border-right: 1px solid $darkerColor;
    }

    .ro-grid-header {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-align: center;
        position: absolute;
        width: 100%;
        left: 0;

        .big-header {
            color: $lightColor;
            font-size: 0.875em;
        }

        .small-header {
            width: 25%;
            font-size: 0.75em;
            float: left;
            white-space: nowrap;
        }

        &.vertical-axis-header {
            @include rotate(-90);
            @include transform-origin(left top);
            top: calc(100% - 67px);
            width: calc(100% - 97px);

            .big-header {
                padding-bottom: 10px;
                margin-bottom: 10px;
                border-bottom: 1px solid $darkerColor;
            }
        }

        &.horizontal-axis-header {
            bottom: 0;
            padding: 0 30px 0 67px;

            .big-header {
                padding-top: 10px;
                margin-top: 10px;
                border-top: 1px solid $darkerColor;
            }
        }
    }

    .ro-grid {
        width: 65%;
        padding: 0 30px 67px 67px;
        float: left;
        position: relative;
    }

    .ro-list {
        width: 35%;
        float: left;
    }

    .ro-grid-item {
        width: 24%;
        height: 0;
        padding-bottom: 24%;
        @include border-radius(50%);
        margin: 0.5%;
        float: left;
        font-size: 3em;
        font-weight: bold;
        color: $mainColor;
        text-align: center;
        white-space: nowrap;
        position: relative;
        cursor: pointer;
        @include transition(background-color, 0.2s, ease);

        span {
            display: inline-block;
            vertical-align: middle;
            width: 100%;
            height: 30px;
            line-height: 35px;
            position: absolute;
            top: 50%;
            left: 0;
            margin-top: -15px;
        }

        &.medium {
            background-color: $warningColor;
        }

        &.high {
            background-color: $dangerColor;
        }

        &.very-high {
            background-color: $dangerDarkColor;
        }

        &.low {
            background-color: $secondColor;
        }

        &.very-low {
            background-color: $secondDarkColor;
        }

        &.active {
            background-color: $lightColor;
        }

        &:hover{
            background-color: $lightColor;
        }
    }

    .ro-list-item {
        text-transform: uppercase;
        letter-spacing: 0.1em;
        position: relative;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid $darkerColor;

        .avatar {
            height: 20px;
            width: 20px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            position: absolute;
            right: 0;
            top: 0;
            @include border-radius(50%);
        }

        a {
            font-size: 1.166em;
            color: $secondColor;
            font-weight: 600;
            padding-right: 25px;
            display: block;
        }

        p {
            font-size: 0.833em;
            color: $lightColor;

            b {
                color: $lighterColor;
            }
        }

        &:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
    }

    .ro-summary {
        margin-top: 60px;
        padding-bottom: 30px;
        font-size: 1.333em;
        text-transform: uppercase;
        letter-spacing: 0.1em;

        p {
            line-height: 1.5em;
            color: $lightColor;

            b {
                color: $lighterColor;
            }
        }

        .text-left {
            p {
                padding-left: 15px;
            }
        }
    }
</style>
