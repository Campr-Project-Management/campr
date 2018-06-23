<template>
    <div class="row">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translate('message.delete_opportunity') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translate('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteOpportunity()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translate('message.yes') }}</a>
            </div>
        </modal>
        <modal v-if="showEditMeasureModal" @close="showEditMeasureModal = false">
            <p class="modal-title">{{ translate('message.edit_measure') }}</p>
            <div class="form-group">
                <div class="col-md-12">
                    <input-field type="text" :label="translate('placeholder.measure_title')" v-model="selectedMeasure.title" :content="selectedMeasure.title" />
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
                        <money-field
                                type="text"
                                :label="translate('placeholder.measure_cost')"
                                v-model="selectedMeasure.cost"
                                :currency="projectCurrencySymbol"
                                :content="selectedMeasure.cost"/>
                        <error
                            v-if="editMeasureValidationMessages.cost && editMeasureValidationMessages.cost.length"
                            v-for="message in editMeasureValidationMessages.cost"
                            :message="message" />
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditMeasureModal = false" class="btn-rounded btn-auto">{{ translate('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="editSelectedMeasure()" class="btn-rounded btn-auto second-bg">{{ translate('button.save') }}</a>
            </div>
        </modal>

        <div class="col-lg-5 col-lg-push-7">
            <!-- /// Project Opportunities /// -->
            <div class="ro-grid-wrapper clearfix">
                <opportunity-matrix
                        :impact="opportunityImpact"
                        :probability="opportunityProbability"/>
            </div>
        </div>
        <div class="col-lg-7 col-lg-pull-5">
            <div class="page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translate('message.back_to_risks_and_opportunities') }}
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
                            <p>{{ translate('message.priority') }}: <b v-if="priority" :class="priority.color">{{ translate(priority.name) }}</b><b v-else>-</b></p>
                            <p>{{ translate('message.strategy') }}: <b v-if="opportunity.opportunityStrategyName">{{ translate(opportunity.opportunityStrategyName) }}</b><b v-else>-</b></p>
                            <p>{{ translate('message.status') }}: <b>{{ translate(opportunity.opportunityStatusName) }}</b></p>
                        </div>

                        <div class="ro-info">
                            <p>{{ translate('message.budget_saved') }}: <b>{{ opportunity.potentialCostSavings | money({symbol: projectCurrencySymbol}) }}</b></p>
                            <p>{{ translate('message.time_saved') }}: <b>{{ opportunity.potentialTimeSavings }} {{ translate(opportunity.timeUnit) }}</b></p>
                            <p>{{ translate('message.due_date') }}: <b>{{ opportunity.dueDate | moment('DD.MM.YYYY') }}</b></p>
                        </div>

                        <div class="ro-info">
                            <p>{{ translate('message.measures') }}: <b v-if="opportunity.measures">{{ opportunity.measures.length }}</b></p>
                            <p>{{ translate('message.measures_cost') }}: <b>{{ opportunity.measuresTotalCost | money({symbol: projectCurrencySymbol}) }}</b></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translate('message.created_on') }} {{ opportunity.createdAt | moment('DD.MM.YYYY') }}, {{ opportunity.createdAt | moment('HH:mm') }} {{ translate('message.by') }}
                            <user-avatar
                                    :name="opportunity.createdByFullName"
                                    size="small"
                                    :url="opportunity.createdByAvatar"/>
                            <b>{{ opportunity.createdByFullName }}</b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translate('message.responsible') }}:
                            <user-avatar
                                    :name="opportunity.responsibilityFullName"
                                    size="small"
                                    :url="opportunity.responsibilityAvatar"/>
                            <b>{{ opportunity.responsibilityFullName }}</b>
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
                            :title="translate('message.impact')"
                            minSuffix=" %"
                            :value="opportunity.impact"/>
                    <div class="slider-indicator" v-if="risksOpportunitiesStats.opportunities">
                        <indicator-icon fill="middle-fill"
                                        :position="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageImpact"
                                        :title="translate('message.average_impact_opportunity')"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Impact /// -->

                <!-- /// Probability /// -->
                <div class="range-slider-wrapper">
                    <range-slider
                            :disabled="true"
                            :title="translate('message.probability')"
                            minSuffix=" %"
                            :value="opportunity.probability"/>
                    <div class="slider-indicator" v-if="risksOpportunitiesStats.opportunities">
                        <indicator-icon fill="middle-fill"
                                        :position="risksOpportunitiesStats.opportunities.opportunity_data.averageData.averageProbability"
                                        :title="translate('message.average_probability_opportunity')"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Probability /// -->

                <!-- /// Measures /// -->
                <h3 v-if="opportunity.measures">{{ opportunity.measures.length }} {{ translate('message.measures') }}</h3>
                <hr>

                <!-- /// Measure /// -->
                <div class="measure" :id="'measure-'+measure.id" v-if="opportunity.measures" v-for="measure in opportunity.measures">
                    <!-- /// Comment /// -->
                    <div class="comment">
                        <div class="comment-header flex flex-space-between flex-v-center">
                            <div>
                                <user-avatar
                                        :name="measure.responsibilityFullName"
                                        size="small"
                                        :url="measure.responsibilityAvatar"/>
                                <b class="uppercase">{{ measure.responsibilityFullName }}</b>
                                <a href="#link-to-member-page" class="simple-link">@{{ measure.responsibilityUsername }}</a>
                                {{ translate('message.added_a_measure') }} {{ moment(measure.createdAt).fromNow() }} | {{ translate('message.edited') }} {{ moment(measure.updatedAt).fromNow() }}
                            </div>
                            <div class="comment-buttons">
                                <button @click="initEditMeasure(measure)" class="btn btn-rounded second-bg btn-auto btn-md" type="button">{{ translate('button.edit') }}</button>
                                <button type="button" :data-target="'#measure-'+measure.id+'-new-comment'" class="btn btn-rounded btn-empty btn-auto btn-md go-to" data-toggle="collapse" :data-parent="'#measure-'+measure.id" aria-expanded="false">{{ translate('message.comment') }}</button>
                            </div>
                        </div>
                        <div class="comment-body">
                            <b class="title">{{ measure.title }}</b>
                            <p class="cost">{{ translate('message.cost') }}: <b>{{ measure.cost|money({symbol: projectCurrencySymbol}) }}</b></p>
                            <p v-html="measure.description"></p>
                        </div>
                        <div class="comment-footer" v-if="measure.medias.length > 0">
                            <attach-icon fill="second-fill"></attach-icon>
                            <ul class="comment-attachments">
                                <li v-for="media in measure.medias"><a href="#" :title="translate('message.download_attachment')"></a></li>
                            </ul>
                        </div>

                        <!-- /// Comments /// -->
                        <div class="comments" v-if="measure.comments.length > 0">
                            <div class="comment" v-for="comment in measure.comments">
                                <div class="comment-header flex flex-space-between flex-v-center">
                                    <div>
                                        <user-avatar
                                                :name="comment.responsibilityFullName"
                                                size="small"
                                                :url="comment.responsibilityAvatar"/>
                                        <b class="uppercase">{{ comment.responsibilityFullName }}</b>
                                        <a href="#link-to-member-page" class="simple-link">@{{ comment.responsibilityUsername }}</a>
                                        {{ translate('message.commented') }} {{ moment(comment.createdAt).fromNow() }}
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p v-html="comment.description"></p>
                                </div>
                                <div class="comment-footer" v-if="comment.medias.length > 0">
                                    <attach-icon fill="second-fill"></attach-icon>
                                    <ul class="comment-attachments">
                                        <li v-for="media in comment.medias">
                                            <a href="#" :title="translate('message.download_attachment')"></a>
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
                                    <button @click="addMeasureComment(measure.id)" type="button" :data-target="'#measure-'+measure.id+'-new-comment'" :data-parent="'#measure-'+measure.id" aria-expanded="false" class="btn-rounded btn-auto btn-md second-bg">{{ translate('message.add_comment') }}</button>
                                    <button type="button" :data-target="'#measure-'+measure.id+'-new-comment'" class="btn btn-rounded btn-empty btn-auto btn-md" data-toggle="collapse" :data-parent="'#measure-'+measure.id" aria-expanded="false">{{ translate('message.close') }}</button>
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
                            <input-field type="text" :label="translate('placeholder.measure_title')" v-model="measureTitle" :content="measureTitle" />
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
                                <money-field
                                        :currency="projectCurrencySymbol"
                                        v-model="measureCost"
                                        :label="translate('placeholder.measure_cost')" />
                                <error
                                    v-if="validationMessages.cost && validationMessages.cost.length"
                                    v-for="message in validationMessages.cost"
                                    :message="message" />
                            </div>
                            <div class="col-md-4 text-right">
                                <a @click="addMeasure()" class="btn-rounded btn-auto">{{ translate('button.add_new_measure') }}</a>
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
import MoneyField from '../../_common/_form-components/MoneyField';
import OpportunityMatrix from '../RiskManagement/OpportunityMatrix';
import UserAvatar from '../../_common/UserAvatar';

export default {
    components: {
        UserAvatar,
        OpportunityMatrix,
        MoneyField,
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
        moment: function(date) {
            return moment.utc(date).local();
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

            this.createOpportunityMeasure(data).then((response) => {
                if (response.body && response.body.error) {
                    return;
                }

                this.loadOpportunity();
            });
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
                            return;
                        }

                        this.editMeasureValidationMessages = {};
                        this.showEditMeasureModal = false;
                        this.loadOpportunity();
                    },
                    () => {
                        this.editMeasureValidationMessages = {};
                        this.showEditMeasureModal = false;
                    }
                )
            ;
        },
        loadOpportunity() {
            this.getProjectOpportunity(this.$route.params.opportunityId);
        },
    },
    computed: {
        ...mapGetters({
            opportunity: 'currentOpportunity',
            risksOpportunitiesStats: 'risksOpportunitiesStats',
            validationMessages: 'validationMessages',
            measures: 'currentOpportunityMeasures',
            projectCurrencySymbol: 'projectCurrencySymbol',
        }),
    },
    created() {
        this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        if (this.$route.params.opportunityId) {
            this.loadOpportunity();
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
            editMeasureValidationMessages: {},
            measureCommentValidationMessages: {},
            newComments: {},
        };
    },
    watch: {
        opportunity(value) {
            this.opportunityImpact = this.opportunity.impact;
            this.opportunityProbability = this.opportunity.probability;
        },
        measures(value) {
            this.measureTitle = '';
            this.measureDescription = '';
            this.measureCost = '';
        },
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/risks-and-opportunities/view';
</style>
