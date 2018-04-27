<template>
    <div class="row">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translate('message.delete_risk') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-auto">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteRisk()" class="btn-rounded btn-empty btn-auto danger-color danger-border">{{ translateText('message.yes') }}</a>
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
                                :currency="projectCurrencySymbol"
                                type="text"
                                :label="translate('placeholder.measure_cost')"
                                v-model="selectedMeasure.cost"/>
                        <error
                            v-if="editMeasureValidationMessages.cost && editMeasureValidationMessages.cost.length"
                            v-for="message in editMeasureValidationMessages.cost"
                            :message="message" />
                    </div>
                </div>
            </div>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showEditMeasureModal = false" class="btn-rounded btn-auto">{{ translateText('button.cancel') }}</a>
                <a href="javascript:void(0)" @click="editSelectedMeasure()" class="btn-rounded btn-auto second-bg">{{ translateText('button.save') }}</a>
            </div>
        </modal>

        <div class="col-lg-5 col-lg-push-7">
            <!-- /// Project Opportunities /// -->
            <div class="ro-grid-wrapper clearfix">
                <!-- /// Project Opportunities Grid /// -->
                <div class="ro-grid">
                    <div class="ro-grid-header vertical-axis-header">
                        <div class="big-header">{{ translate('message.impact') }}</div>
                        <div class="small-headers clearfix">
                            <div class="small-header">{{ translate('message.very_low') }}</div>
                            <div class="small-header">{{ translate('message.low') }}</div>
                            <div class="small-header">{{ translate('message.high') }}</div>
                            <div class="small-header">{{ translate('message.very_high') }}</div>
                        </div>
                    </div>
                    <div class="ro-grid-items clearfix">
                        <div v-for="item in gridData" class="ro-grid-item" :class="[{active: item.isActive}, item.type]"></div>
                    </div>
                    <div class="ro-grid-header horizontal-axis-header">
                        <div class="small-headers clearfix">
                            <div class="small-header">{{ translate('message.very_low') }}</div>
                            <div class="small-header">{{ translate('message.low') }}</div>
                            <div class="small-header">{{ translate('message.high') }}</div>
                            <div class="small-header">{{ translate('message.very_high') }}</div>
                        </div>
                        <div class="big-header">{{ translate('message.probability') }}</div>
                    </div>
                    <div class=""></div>
                </div>
                <!-- /// End Project Opportunities Grid /// -->
            </div>

            <!-- /// Project Risks Summary /// -->
            <div class="ro-summary">
                <div class="text-center flex flex-center">
                    <div class="text-right">
                        <p>{{ translate('message.priority') }}:</p>
                    </div>
                    <div class="text-left">
                        <p><b v-if="priority" :class="priority.color">{{ translate(priority.name) }}</b><b v-else>-</b></p>
                    </div>
                </div>
            </div>
            <!-- /// End Project Risks Summary /// -->
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
                        <h1>{{ risk.title }}</h1>
                    </div>
                    <div>
                        <div class="header-buttons" v-if="risk.id">
                            <router-link :to="{name: 'project-risks-edit-risk', params: {riskId: risk.id}}" class="btn-icon">
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
                            <p>{{ translate('message.strategy') }}: <b>{{ risk.riskStrategyName }}</b></p>
                            <p>{{ translate('message.status') }}: <b>{{ risk.statusName }}</b></p>
                        </div>

                        <div class="ro-info">
                            <p>{{ translate('message.potential_cost') }}: <b>{{ risk.potentialCost | money({symbol: projectCurrencySymbol}) }}</b></p>
                            <p>{{ translate('message.time_saved') }}: <b>{{ risk.potentialDelay | formatNumber }} {{ translate(risk.delayUnit) }}</b></p>
                            <p>{{ translate('message.due_date') }}: <b>{{ risk.dueDate | moment('DD.MM.YYYY') }}</b></p>
                        </div>

                        <div class="ro-info">
                            <p>{{ translate('message.measures') }}: <b v-if="risk.measures">{{ risk.measures.length }}</b></p>
                            <p>{{ translate('message.measures_cost') }}: <b>{{ risk.measuresTotalCost | money({symbol: projectCurrencySymbol}) }}</b></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translateText('message.created_on') }} {{ risk.createdAt | moment('DD.MM.YYYY') }}, {{ risk.createdAt | moment('HH:mm') }} {{ translateText('message.by') }}
                            <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + risk.createdByAvatar + ')' }"></div>
                            <b>{{ risk.createdByFullName }}</b>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translateText('message.responsible') }}:
                            <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + risk.responsibilityAvatar + ')' }"></div>
                            <b>{{ risk.responsibilityFullName }}</b>
                        </div>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <hr class="double">

                <!-- /// Descripton /// -->
                <p v-html="risk.description"></p>
                <!-- /// End Description /// -->

                <hr class="double">

                <!-- ///  Impact /// -->
                <div class="range-slider-wrapper" v-if="risk">
                    <range-slider
                            :disabled="true"
                            :title="translate('message.impact')"
                            minSuffix=" %"
                            :value="risk.impact"/>
                    <div class="slider-indicator" v-if="risksOpportunitiesStats.risks">
                        <indicator-icon fill="middle-fill"
                                        :position="risksOpportunitiesStats.risks.risk_data.averageData.averageImpact"
                                        :title="translate('message.average_impact_risk')"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Impact /// -->

                <!-- /// Probability /// -->
                <div class="range-slider-wrapper" v-if="risk">
                    <range-slider
                            :disabled="true"
                            :title="translate('message.probability')"
                            minSuffix=" %"
                            :value="risk.probability"/>
                    <div class="slider-indicator" v-if="risksOpportunitiesStats.risks">
                        <indicator-icon fill="middle-fill"
                                        :position="risksOpportunitiesStats.risks.risk_data.averageData.averageProbability"
                                        :title="translate('message.average_probability_risk')"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Probability /// -->

                <!-- /// Measures /// -->
                <h3 v-if="risk.measures">{{ risk.measures.length }} {{ translate('message.measures') }}</h3>
                <hr>

                <!-- /// Measure /// -->
                <div class="measure" :id="'measure-'+measure.id" v-if="risk.measures" v-for="measure in risk.measures">
                    <!-- /// Comment /// -->
                    <div class="comment">
                        <div class="comment-header flex flex-space-between flex-v-center">
                            <div>
                                <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + measure.responsibilityAvatar + ')' }"></div>
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
                            <p class="cost">{{ translate('message.cost') }}: <b>{{ measure.cost | money({symbol: projectCurrencySymbol}) }}</b></p>
                            <p v-html="measure.description "></p>
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
                                        <div class="user-avatar" v-bind:style="{ backgroundImage: 'url(' + comment.responsibilityAvatar + ')' }"></div>
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
                                        <li v-for="media in comment.medias"><a href="#" :title="translate('message.download_attachment')"></a></li>
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
                            <div class="vueditor-holder measure-vueditor-holder">
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
                    </div>
                    <div class="form-group last-form-group">
                        <div class="flex flex-space-between">
                            <div class="col-md-4">
                                <money-field
                                        :label="translate('placeholder.measure_cost')"
                                        v-model="measureCost"
                                        :currency="projectCurrencySymbol"/>
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
import MoneyField from '../../_common/_form-components/MoneyField';
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
        MoneyField,
    },
    methods: {
        ...mapActions([
            'getProjectRiskAndOpportunitiesStats',
            'getProjectRisk',
            'createMeasureComment',
            'createRiskMeasure',
            'deleteProjectRisk',
            'editMeasure',
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
                risk: this.$route.params.riskId,
                title: this.measureTitle,
                description: this.measureDescription,
                cost: this.measureCost,
                responsibility: this.risk.responsibility,
            };
            this.createRiskMeasure(data).then((response) => {
                if (response.body && response.body.error) {
                    return;
                }

                this.loadRisk();
            });
        },
        deleteRisk: function() {
            this.deleteProjectRisk(this.$route.params.riskId);
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
                        this.loadRisk();
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
            const riskImpact = this.currentRiskImpact;
            const riskProbability = this.currentRiskProbability;

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
                'very-low': {name: 'message.very_low', color: 'ro-very-low-priority', value: 0},
                'low': {name: 'message.low', color: 'ro-low-priority', value: 1},
                'medium': {name: 'message.medium', color: 'ro-medium-priority', value: 2},
                'high': {name: 'message.high', color: 'ro-high-priority', value: 3},
                'very-high': {name: 'message.very_high', color: 'ro-very-high-priority', value: 4},
            };

            this.priority = priorityNames[type];
        },
        loadRisk() {
            this.getProjectRisk(this.$route.params.riskId);
        },
    },
    computed: {
        ...mapGetters({
            risk: 'currentRisk',
            risksOpportunitiesStats: 'risksOpportunitiesStats',
            validationMessages: 'validationMessages',
            measures: 'measures',
            projectCurrencySymbol: 'projectCurrencySymbol',
        }),
    },
    created() {
        this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        if (this.$route.params.riskId) {
            this.loadRisk();
        }
    },
    data: function() {
        return {
            priority: null,
            selectedMeasure: {},
            measureTitle: '',
            measureDescription: '',
            measureCost: '',
            currentRiskImpact: 0,
            currentRiskProbability: 0,
            showDeleteModal: false,
            showEditMeasureModal: false,
            gridData: [
                {type: 'medium'}, {type: 'high'}, {type: 'very-high'}, {type: 'very-high'},
                {type: 'low'}, {type: 'medium'}, {type: 'high'}, {type: 'very-high'},
                {type: 'very-low'}, {type: 'low'}, {type: 'medium'}, {type: 'high'},
                {type: 'very-low'}, {type: 'very-low'}, {type: 'low'}, {type: 'medium'},
            ],
            editMeasureValidationMessages: {},
            measureCommentValidationMessages: {},
            newComments: {},
        };
    },
    watch: {
        risk(value) {
            this.currentRiskImpact = this.risk.impact;
            this.currentRiskProbability = this.risk.probability;
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

    .user-avatar {
        width: 30px;
        height: 30px;
        display: inline-block;        
        margin: 0 5px;  
        position: relative;
        top: -2px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        vertical-align: middle;
        @include border-radius(50%);
    }

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
    .btn-icon {
        cursor: pointer;
    }
</style>
