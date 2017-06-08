<template>
    <div class="row">
        <modal v-if="showDeleteModal" @close="showDeleteModal = false">
            <p class="modal-title">{{ translateText('message.delete_risk') }}</p>
            <div class="flex flex-space-between">
                <a href="javascript:void(0)" @click="showDeleteModal = false" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('message.no') }}</a>
                <a href="javascript:void(0)" @click="deleteRisk()" class="btn-rounded">{{ translateText('message.yes') }}</a>
            </div>
        </modal>
        <modal v-if="showEditMeasureModal" @close="showEditMeasureModal = false">
            <p class="modal-title">{{ translateText('message.edit_measure') }}</p>
            <div class="form-group">
                <div class="col-md-12">
                    <input-field type="text" v-bind:label="translateText('placeholder.measure_title')" v-model="selectedMeasure.title" v-bind:content="selectedMeasure.title" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="vueditor-holder measure-vueditor-holder">
                        <div class="vueditor-header">{{ translateText('placeholder.measure_description') }}</div>
                        <Vueditor :ref="'selectedMeasureDescription'" />
                    </div>
                </div>
            </div>
            <div class="form-group last-form-group">
                <div class="flex flex-space-between">
                    <div class="col-md-12">
                        <input-field type="text" v-bind:label="translateText('placeholder.measure_cost')" v-model="selectedMeasure.cost" v-bind:content="selectedMeasure.cost" />
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

            <!-- /// Project Risks Summary /// -->
            <div class="ro-summary">
                <div class="text-center flex flex-center">
                    <div class="text-right">
                        <p>{{ translateText('message.priority') }}:</p>
                    </div>
                    <div class="text-left">
                        <p><b>{{ risk.priority }}</b></p>
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
                        <h1>{{ risk.title }}</h1>
                    </div>
                    <div>
                        <div class="header-buttons" v-if="risk">
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
                            <p>{{ translateText('message.priority') }}: <b>{{ risk.priority }}</b></p>
                            <p>{{ translateText('message.strategy') }}: <b>{{ risk.riskStrategyName }}</b></p>
                            <p>{{ translateText('message.status') }}: <b>{{ risk.statusName }}</b></p>
                        </div>

                        <div class="ro-info">
                            <p>{{ translateText('message.budget_saved') }}: <b>{{ risk.currency }} {{ risk.budget }}</b></p>
                            <p>{{ translateText('message.time_saved') }}: <b>{{ risk.delay }} {{ translateText(risk.delayUnit) }}</b></p>
                            <p>{{ translateText('message.due_date') }}: <b>{{ risk.dueDate | moment('DD.MM.YYYY') }}</b></p>
                        </div>

                        <div class="ro-info">
                            <p>{{ translateText('message.measures') }}: <b v-if="risk.measures">{{ risk.measures.length }}</b></p>
                            <p>{{ translateText('message.measures_cost') }}: <b v-if="risksOpportunitiesStats.risks">$ {{ risksOpportunitiesStats.risks.measure_data.totalCost }}</b></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translateText('message.created_on') }} {{ risk.createdAt | moment('DD.MM.YYYY') }}, {{ risk.createdAt | moment('HH:mm') }} {{ translateText('message.by') }}
                            <div class="user-avatar">
                                <img :src="risk.createdByAvatar" :alt="risk.createdByFullName"/>
                                <b>{{ risk.createdByFullName }}</b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translateText('message.responsible') }}:
                            <div class="user-avatar">
                                <img :src="risk.responsibilityAvatar" :alt="risk.responsibilityFullName"/>
                                <b>{{ risk.responsibilityFullName }}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <hr class="double">

                <!-- /// Descripton /// -->
                <p>{{ risk.description }}</p>
                <!-- /// End Description /// -->

                <hr class="double">

                <!-- ///  Impact /// -->
                <div class="range-slider-wrapper" v-if="risk">
                    <range-slider
                            :disabled="true"
                            v-bind:title="translateText('message.impact')"
                            min="0"
                            max="100"
                            minSuffix=" %"
                            type="single"
                            v-bind:value="transformToString(risk.impact)" />
                    <div class="slider-indicator" v-if="risksOpportunitiesStats.risks">
                        <indicator-icon fill="middle-fill" :position="risksOpportunitiesStats.risks.risk_data.averageData.averageImpact" :title="translateText('message.average_impact_risk')"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Impact /// -->

                <!-- /// Probability /// -->
                <div class="range-slider-wrapper" v-if="risk">
                    <range-slider
                            :disabled="true"
                            v-bind:title="translateText('message.probability')"
                            min="0"
                            max="100"
                            minSuffix=" %"
                            type="single"
                            v-bind:value="transformToString(risk.probability)" />
                    <div class="slider-indicator" v-if="risksOpportunitiesStats.risks">
                        <indicator-icon fill="middle-fill" :position="risksOpportunitiesStats.risks.risk_data.averageData.averageProbability" :title="translateText('message.average_probability_risk')"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Probability /// -->

                <!-- /// Measures /// -->
                <h3 v-if="risk.measures">{{ risk.measures.length }} {{ translateText('message.measures') }}</h3>
                <hr>

                <!-- /// Measure /// -->
                <div class="measure" :id="'measure-'+measure.id" v-if="risk.measures" v-for="measure in risk.measures">
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
                            <p class="cost">{{ translateText('message.cost') }}: <b>$ {{ measure.cost }}</b></p>
                            <p>{{ measure.description }}</p>
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
                                    <p>{{ comment.description }}</p>
                                </div>
                                <div class="comment-footer" v-if="comment.medias.length > 0">
                                    <attach-icon fill="second-fill"></attach-icon>
                                    <ul class="comment-attachments">
                                        <li v-for="media in comment.medias"><a href="#" :title="translateText('message.download_attachment')"></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /// End Comments /// -->

                        <!-- /// New Comment /// -->
                        <div class="new-comment collapse" :id="'measure-'+measure.id+'-new-comment'">
                            <div class="new-comment-body">
                                <div class="vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('message.new_comment') }}</div>
                                    <Vueditor :ref="'comment'+measure.id" />
                                </div>
                                <div class="footer-buttons flex flex-space-between">
                                    <button @click="addMeasureComment(measure.id)" type="button" :data-target="'#measure-'+measure.id+'-new-comment'" data-toggle="collapse" :data-parent="'#measure-'+measure.id" aria-expanded="false" class="btn-rounded btn-auto btn-md second-bg">{{ translateText('message.add_comment') }}</button>
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
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="vueditor-holder measure-vueditor-holder">
                                <div class="vueditor-header">{{ translateText('placeholder.new_measure') }}</div>
                                <Vueditor ref="measureDescription" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group last-form-group">
                        <div class="flex flex-space-between">
                            <div class="col-md-4">
                                <input-field type="text" v-bind:label="translateText('placeholder.measure_cost')" v-model="measureCost" v-bind:content="measureCost" />
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

export default {
    components: {
        EditIcon,
        DeleteIcon,
        AttachIcon,
        InputField,
        IndicatorIcon,
        RangeSlider,
        Modal,
    },
    methods: {
        ...mapActions([
            'getProjectRiskAndOpportunitiesStats', 'getProjectRisk', 'createMeasureComment',
            'createRiskMeasure', 'deleteProjectRisk', 'editMeasure',
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
                description: this.$refs['comment'+measureId][0].getContent(),
            };
            this.createMeasureComment(data);
        },
        addMeasure: function() {
            let data = {
                risk: this.$route.params.riskId,
                title: this.measureTitle,
                description: this.$refs['measureDescription'].getContent(),
                cost: this.measureCost,
            };
            this.createRiskMeasure(data);
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
            this.selectedMeasure.description = this.$refs['selectedMeasureDescription'].getContent();
            this.editMeasure(this.selectedMeasure);
            this.showEditMeasureModal = false;
        },
        updateGridView() {
            let index = 0;
            const riskImpact = this.currentRiskImpact;
            const riskProbability = this.currentRiskOpportunity;

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
        },
    },
    computed: {
        ...mapGetters({
            risk: 'currentRisk',
            risksOpportunitiesStats: 'risksOpportunitiesStats',
        }),
    },
    created() {
        this.getProjectRiskAndOpportunitiesStats(this.$route.params.id);
        if (this.$route.params.riskId) {
            this.getProjectRisk(this.$route.params.riskId);
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
        };
    },
    watch: {
        risk(value) {
            this.currentRiskImpact = this.risk.impact;
            this.currentRiskProbability = this.risk.probability;
            this.updateGridView();
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
</style>
