<template>
    <div class="row">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translateText('message.delete_opportunity') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteOpportunity()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <modal v-if="showEditMeasureModal" @close="showEditMeasureModal = false">
            <p class="modal-title">{{ translateText('message.edit_measure') }}</p>
            <div class="form-group">
                <div class="col-md-12">
                    <input-field type="text" v-bind:label="translateText('placeholder.measure_title')" v-model="selectedMeasure.title" v-bind:content="selectedMeasure.title" />
                    <error
                        v-if="editMeasureValidationMessages.title && editMeasureValidationMessages.title.length"
                        v-for="message in editMeasureValidationMessages.title"
                        :message="message" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <editor
                        height="200px"
                        class="measure-vueditor-holder"
                        id="selectedMeasure"
                        v-model="selectedMeasure.description"
                        :label="'placeholder.measure_description'" />
                    <error
                        v-if="editMeasureValidationMessages.description && editMeasureValidationMessages.description.length"
                        v-for="message in editMeasureValidationMessages.description"
                        :message="message" />
                </div>
            </div>
            <div class="form-group last-form-group">
                <div class="flex flex-space-between">
                    <div class="col-md-12">
                        <input-field type="text" v-bind:label="translateText('placeholder.measure_cost')" v-model="selectedMeasure.cost" v-bind:content="selectedMeasure.cost" />
                        <error
                            v-if="editMeasureValidationMessages.cost && editMeasureValidationMessages.cost.length"
                            v-for="message in editMeasureValidationMessages.cost"
                            :message="message" />
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditMeasureModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="editSelectedMeasure()" class="btn-rounded">{{ translateText('button.save') }}</a>
            </div>
        </modal>

        <div class="col-md-6 col-md-push-6">
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
                        <div v-for="item in gridData" class="ro-grid-item" :class="[{active: item.isActive}, item.type]"></div>
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
                    <div class=""></div>
                </div>
                <!-- /// End Project Opportunities Grid /// -->
            </div>

            <!-- /// Project Opportunitty Summary /// -->
            <div class="ro-summary">
                <div class="text-center flex flex-center">
                    <div class="text-right">
                        <p>{{ translateText('message.priority') }}:</p>
                    </div>
                    <div class="text-left">
                        <p><b v-if="priority" v-bind:class="priority.color">{{ translateText(priority.name) }}</b><b v-else>-</b></p>
                    </div>
                </div>
            </div>
            <!-- /// End Project Risks Summary /// -->
        </div>
        <div class="col-md-6 col-md-pull-6">
            <div class="page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_risks_and_opportunities') }}
                        </router-link>
                        <h1>{{ opportunity.title }}</h1>
                    </div>
                    <div>
                        <div class="header-buttons" v-if="opportunity.id">
                            <router-link :to="{name: 'project-opportunities-edit-opportunity', params: {opportunityId: opportunity.id}}" class="btn-icon">
                                <edit-icon fill="second-fill"></edit-icon>
                            </router-link>
                            <a @click="showDeleteModal = true" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></a>
                        </div>
                    </div>
                </div>
                <div class="row ro-details">
                    <div class="col-md-12">
                        <div class="ro-info">
                            <p>{{ translateText('message.priority') }}: <b v-if="priority" v-bind:class="priority.color">{{ translateText(priority.name) }}</b><b v-else>-</b></p>
                            <p>{{ translateText('message.strategy') }}: <b>{{ opportunity.opportunityStrategyName }}</b></p>
                            <p>{{ translateText('message.status') }}: <b>{{ opportunity.opportunityStatusName }}</b></p>
                        </div>

                        <div class="ro-info">
                            <p>{{ translateText('message.budget_saved') }}: <b>{{ opportunity.currency }} {{ opportunity.budget }}</b></p>
                            <p>{{ translateText('message.time_saved') }}: <b>{{ opportunity.timeSavings }} {{ translateText(opportunity.timeUnit) }}</b></p>
                            <p>{{ translateText('message.due_date') }}: <b>{{ opportunity.dueDate | moment('DD.MM.YYYY') }}</b></p>
                        </div>

                        <div class="ro-info">
                            <p>{{ translateText('message.measures') }}: <b v-if="opportunity.measures">{{ opportunity.measures.length }}</b></p>
                            <p>{{ translateText('message.measures_cost') }}: <b v-if="risksOpportunitiesStats.opportunities">{{ opportunity.currency }} {{ risksOpportunitiesStats.opportunities.measure_data.totalCost }}</b></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translateText('message.created_on') }} {{ opportunity.createdAt | moment('DD.MM.YYYY') }}, {{ opportunity.createdAt | moment('HH:mm') }} {{ translateText('message.by') }}
                            <div class="user-avatar">
                                <img :src="opportunity.createdByAvatar" :alt="opportunity.createdByFullName"/>
                                <b>{{ opportunity.createdByFullName }}</b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translateText('message.responsible') }}:
                            <div class="user-avatar">
                                <img :src="opportunity.responsibilityAvatar" :alt="opportunity.responsibilityFullName"/>
                                <b>{{ opportunity.responsibilityFullName }}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <hr class="double">

                <!-- /// Descripton /// -->
                <p v-html="opportunity.description"></p>
                <!-- /// End Description /// -->

                <hr class="double">

                <!-- ///  Impact /// -->
                <div class="range-slider-wrapper">
                    <range-slider
                            :disabled="true"
                            :title="translateText('message.impact')"
                            minSuffix=" %"
                            :value="opportunity.impact"/>
                    <div class="slider-indicator" v-if="risksOpportunitiesStats.opportunities">
                        <indicator-icon fill="middle-fill"
                                        :position="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageImpact"
                                        :title="translateText('message.average_impact_opportunity')"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Impact /// -->

                <!-- /// Probability /// -->
                <div class="range-slider-wrapper">
                    <range-slider
                            :disabled="true"
                            :title="translateText('message.probability')"
                            minSuffix=" %"
                            :value="opportunity.probability"/>
                    <div class="slider-indicator" v-if="risksOpportunitiesStats.opportunities">
                        <indicator-icon fill="middle-fill"
                                        :position="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageProbability"
                                        :title="translateText('message.average_probability_opportunity')"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Probability /// -->

                <!-- /// Measures /// -->
                <h3 v-if="opportunity.measures">{{ opportunity.measures.length }} {{ translateText('message.measures') }}</h3>
                <hr>

                <!-- /// Measure /// -->
                <div class="measure" :id="'measure-'+measure.id" v-if="opportunity.measures" v-for="measure in opportunity.measures">
                    <!-- /// Comment /// -->
                    <div class="comment">
                        <div class="comment-header flex flex-space-between flex-v-center">
                            <div>
                                <div class="user-avatar">
                                    <img :src="measure.responsibilityAvatar" :alt="measure.responsibilityFullName"/>
                                    <b>{{ measure.responsibilityFullName }}</b>
                                </div>
                                <a href="#link-to-member-page" class="simple-link">@{{ measure.responsibilityUsername }}</a>
                                {{ translateText('message.added_a_measure') }} {{ moment(measure.createdAt).fromNow() }} | {{ translateText('message.edited') }} {{ moment(measure.updatedAt).fromNow() }}
                            </div>
                            <div class="comment-buttons">
                                <button @click="initEditMeasure(measure)" class="btn btn-rounded second-bg btn-auto btn-md" type="button">{{ translateText('button.edit') }}</button>
                                <button type="button" :data-target="'#measure-'+measure.id+'-new-comment'" class="btn btn-rounded btn-empty btn-auto btn-md go-to" data-toggle="collapse" :data-parent="'#measure-'+measure.id" aria-expanded="false">{{ translateText('message.comment') }}</button>
                            </div>
                        </div>
                        <div class="comment-body">
                            <b class="title">{{ measure.title }}</b>
                            <p class="cost">{{ translateText('message.cost') }}: <b>{{opportunity.currency}} {{ measure.cost }}</b></p>
                            <p v-html="measure.description"></p>
                        </div>
                        <div class="comment-footer" v-if="measure.medias.length > 0">
                            <attach-icon fill="second-fill"></attach-icon>
                            <ul class="comment-attachments">
                                <li v-for="media in measure.medias"><a href="#" :title="translateText('message.download_attachment')"></a></li>
                            </ul>
                        </div>

                        <!-- /// Comments /// -->
                        <div class="comments" v-if="measure.comments.length > 0">
                            <div class="comment" v-for="comment in measure.comments">
                                <div class="comment-header flex flex-space-between flex-v-center">
                                    <div>
                                        <div class="user-avatar">
                                            <img :src="comment.responsibilityAvatar" :alt="comment.responsibilityFullName"/>
                                            <b>{{ comment.responsibilityFullName }}</b>
                                        </div>
                                        <a href="#link-to-member-page" class="simple-link">@{{ comment.responsibilityUsername }}</a>
                                        {{ translateText('message.commented') }} {{ moment(comment.createdAt).fromNow() }}
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p v-html="comment.description"></p>
                                </div>
                                <div class="comment-footer" v-if="comment.medias.length > 0">
                                    <attach-icon fill="second-fill"></attach-icon>
                                    <ul class="comment-attachments">
                                        <li v-for="media in comment.medias">
                                            <a href="#" :title="translateText('message.download_attachment')"></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /// End Comments /// -->

                        <!-- /// New Comment /// -->
                        <div class="new-comment collapse" :id="'measure-'+measure.id+'-new-comment'">
                            <div class="new-comment-body">
                                <editor
                                    height="200px"
                                    class="measure-vueditor-holder"
                                    :id="`measure-${measure.id}-new-comment`"
                                    v-model="newComments[measure.id]"
                                    :label="'message.new_comment'" />
                                <error
                                    v-if="measureCommentValidationMessages.description && measureCommentValidationMessages.description.length"
                                    v-for="message in measureCommentValidationMessages.description"
                                    :message="message" />
                                <div class="footer-buttons flex flex-space-between">
                                    <button @click="addMeasureComment(measure.id)" type="button" :data-target="'#measure-'+measure.id+'-new-comment'" :data-parent="'#measure-'+measure.id" aria-expanded="false" class="btn-rounded btn-auto btn-md second-bg">{{ translateText('message.add_comment') }}</button>
                                    <button type="button" :data-target="'#measure-'+measure.id+'-new-comment'" class="btn btn-rounded btn-empty btn-auto btn-md" data-toggle="collapse" :data-parent="'#measure-'+measure.id" aria-expanded="false">{{ translateText('message.close') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /// End New Comment /// -->
                    </div>
                    <!-- /// End Comment /// -->
                </div>
                <!-- /// End Measure /// -->

                <!-- /// New Measure /// -->
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input-field type="text" v-bind:label="translateText('placeholder.measure_title')" v-model="measureTitle" v-bind:content="measureTitle" />
                            <error
                                v-if="validationMessages.title && validationMessages.title.length"
                                v-for="message in validationMessages.title"
                                :message="message" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <editor
                                height="200px"
                                class="measure-vueditor-holder"
                                id="measureDescription"
                                v-model="measureDescription"
                                :label="'placeholder.new_measure'" />
                            <error
                                v-if="validationMessages.description && validationMessages.description.length"
                                v-for="message in validationMessages.description"
                                :message="message" />
                        </div>
                    </div>
                    <div class="form-group last-form-group">
                        <div class="flex flex-space-between">
                            <div class="col-md-4">
                                <input-field type="text" v-bind:label="translateText('placeholder.measure_cost')" v-model="measureCost" v-bind:content="measureCost" />
                                <error
                                    v-if="validationMessages.cost && validationMessages.cost.length"
                                    v-for="message in validationMessages.cost"
                                    :message="message" />
                            </div>
                            <div class="col-md-4 text-right">
                                <a @click="addMeasure()" class="btn-rounded btn-auto">{{ translateText('button.add_new_measure') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /// End New Measure /// -->
            </div>
        </div>
    </div>
</template>

<script>
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import AttachIcon from '../../_common/_icons/AttachIcon';
import InputField from '../../_common/_form-components/InputField';
import IndicatorIcon from '../../_common/_icons/IndicatorIcon';
import RangeSlider from '../../_common/_form-components/RangeSlider';
import {mapGetters, mapActions} from 'vuex';
import moment from 'moment';
import Modal from '../../_common/Modal';
import Error from '../../_common/_messages/Error.vue';
import Editor from '../../_common/Editor';

export default {
    components: {
        EditIcon,
        DeleteIcon,
        AttachIcon,
        InputField,
        IndicatorIcon,
        RangeSlider,
        Modal,
        Error,
        Editor,
    },
    methods: {
        ...mapActions([
            'getProjectRiskAndOpportunitiesStats', 'getProjectOpportunity', 'createMeasureComment',
            'createOpportunityMeasure', 'deleteProjectOpportunity', 'editMeasure',
        ]),
        translateText: function(text) {
            return this.translate(text);
        },
        moment: function(date) {
            return moment(date);
        },
        transformToString: function(value) {
            return value ? value.toString() : '';
        },
        addMeasureComment: function(measureId) {
            let data = {
                measure: measureId,
                description: this.newComments[measureId],
            };

            this.newComments[measureId] = '';

            this
                .createMeasureComment(data)
                .then(
                    (response) => {
                        if (response.body && response.body.error) {
                            const {messages} = response.body;
                            this.measureCommentValidationMessages = messages;
                        }
                    },
                    () => {}
                )
            ;
        },
        addMeasure: function() {
            let data = {
                opportunity: this.$route.params.opportunityId,
                title: this.measureTitle,
                description: this.measureDescription,
                cost: this.measureCost,
                responsibility: this.opportunity.responsibility,
            };
            this.createOpportunityMeasure(data);
        },
        deleteOpportunity: function() {
            this.deleteProjectOpportunity(this.$route.params.opportunityId);
        },
        initEditMeasure: function(measure) {
            this.showEditMeasureModal = true;
            this.selectedMeasure = {
                id: measure.id,
                title: measure.title,
                description: measure.description,
                cost: measure.cost,
            };
        },
        editSelectedMeasure: function() {
            this
                .editMeasure(this.selectedMeasure)
                .then(
                    (response) => {
                        if (response.body && response.body.error) {
                            const {messages} = response.body;
                            this.editMeasureValidationMessages = messages;
                        }

                        this.editMeasureValidationMessages = {};
                        this.showEditMeasureModal = false;
                    },
                    () => {
                        this.editMeasureValidationMessages = {};
                        this.showEditMeasureModal = false;
                    }
                )
            ;
        },
        updateGridView() {
            let index = 0;
            const riskImpact = this.opportunityImpact;
            const riskProbability = this.opportunityProbability;

            if (riskImpact < 25 || !riskImpact) {
                index += 12;
            }
            if (riskImpact >= 25 && riskImpact < 50) {
                index += 8;
            }
            if (riskImpact >= 50 && riskImpact < 75) {
                index += 4;
            }
            if (riskImpact >= 75) {
                index += 0;
            }

            if (riskProbability < 25 || !riskProbability) {
                index += 0;
            }
            if (riskProbability >= 25 && riskProbability < 50) {
                index += 1;
            }
            if (riskProbability >= 50 && riskProbability < 75) {
                index += 2;
            }
            if (riskProbability >= 75) {
                index += 3;
            }

            if(this.activeItem) {
                this.activeItem.isActive = false;
            }

            this.activeItem = this.gridData[index];
            this.activeItem.isActive = true;
            this.setPriority(this.gridData[index].type);
        },
        setPriority: function(type) {
            const priorityNames = {
                'very-low': {name: 'message.very_high', color: 'ro-very-low-priority', value: 0},
                'low': {name: 'message.high', color: 'ro-low-priority', value: 1},
                'medium': {name: 'message.medium', color: 'ro-medium-priority', value: 2},
                'high': {name: 'message.low', color: 'ro-high-priority', value: 3},
                'very-high': {name: 'message.very_low', color: 'ro-very-high-priority', value: 4},
            };

            this.priority = priorityNames[type];
        },
    },
    computed: {
        ...mapGetters({
            opportunity: 'currentOpportunity',
            risksOpportunitiesStats: 'risksOpportunitiesStats',
            validationMessages: 'validationMessages',
            measures: 'currentOpportunityMeasures',
        }),
    },
    created() {
        this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        if (this.$route.params.opportunityId) {
            this.getProjectOpportunity(this.$route.params.opportunityId);
        }
    },
    mounted() {
        $('.new-comment').on('shown.bs.collapse', function(e) {
            let $scrollTo = $(this);
            $('html,body').animate({
                scrollTop: $scrollTo.offset().top - 30,
            }, 500);
        });
    },
    data: function() {
        return {
            selectedMeasure: {},
            measureTitle: '',
            measureDescription: '',
            measureCost: '',
            opportunityImpact: 0,
            opportunityProbability: 0,
            showDeleteModal: false,
            showEditMeasureModal: false,
            gridData: [
                {type: 'medium'}, {type: 'low'}, {type: 'very-low'}, {type: 'very-low'},
                {type: 'high'}, {type: 'medium'}, {type: 'low'}, {type: 'very-low'},
                {type: 'very-high'}, {type: 'high'}, {type: 'medium'}, {type: 'low'},
                {type: 'very-high'}, {type: 'very-high'}, {type: 'high'}, {type: 'medium'},
            ],
            editMeasureValidationMessages: {},
            measureCommentValidationMessages: {},
            newComments: {},
        };
    },
    watch: {
        opportunity(value) {
            this.opportunityImpact = this.opportunity.impact;
            this.opportunityProbability = this.opportunity.probability;
            this.updateGridView();
        },
        measures(value) {
            this.measureTitle = '';
            this.measureDescription = '';
            this.measureCost = '';
        },
    },
};
</script>

<style lang="scss">
    @import '../../../css/_variables';

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

    .tooltip {
        .tooltip-content {
            text-transform: none;
            letter-spacing: 0;
            font-size: 12px;
        }
    }
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/risks-and-opportunities/view';

    .ro-summary {
        .ro-very-high-priority {
            color: $dangerDarkColor;
        }
        .ro-high-priority {
            color: $dangerColor;
        }
        .ro-medium-priority {
            color: $warningColor;
        }
        .ro-low-priority {
            color: $secondColor;
        }
        .ro-very-low-priority {
            color: $secondDarkColor;
        }
    }
</style>
