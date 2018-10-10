<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-3">
            <h1>{{ translate('message.project_create_wizard') }}</h1>
            <h2>{{ translate('message.project_create_step3') }}</h2>
            <div class="hr"></div>
            <h3>{{ translate('message.project_size') }}: <span>{{ translate('message.medium') }}</span></h3>

            <p>{{ translate('message.recommended_modules') }}</p>

            <project-module
                    v-for="(module, key) in recommendedModules"
                    :title="translate(`modules.${module}.title`)"
                    :description="translate(`modules.${module}.description`)"
                    :id="`recommended_module_${key}`"
                    :value="isModuleSelected(module)"
                    @input="onModuleSelectionChanged(module, $event)"/>

            <template v-if="optionalModules.length > 0">
                <p>{{ translate('message.not_recommended_modules') }}</p>

                <project-module
                        v-for="(module, key) in optionalModules"
                        :title="translate(`modules.${module}.title`)"
                        :description="translate(`modules.${module}.description`)"
                        :id="`optional_module_${key}`"
                        :inactive="true"
                        :value="isModuleSelected(module)"
                        @input="onModuleSelectionChanged(module, $event)"/>
            </template>

            <div class="flex flex-space-between actions">
                <a
                        href="#"
                        @click="previousStep"
                        class="btn-rounded btn-right"
                        :title="translate('button.previous_step')">< {{ translate('button.previous_step') }}</a>
                <a
                        href="#"
                        @click.prevent="submitProject"
                        class="btn-rounded btn-right second-bg"
                        :title="translate('button.start_project')">< {{ translate('button.start_project') }}</a>
            </div>
            <!-- /// Show errors modal/// -->
            <modal v-if="showErrorAlert" @close="showErrorAlert = false">
                <p class="modal-title">{{ translate('title.project_add_error') }}</p>
                <dl v-for="(field, key) in validationMessages">
                    <dt class="ucwords">{{key}}:</dt>
                    <dd v-for="item in field">{{item}}</dd>
                </dl>
                <div class="flex flex-space-between">
                    <a
                            @click.preventDefault="showErrorAlert = false"
                            class="btn-rounded btn-empty danger-color danger-border"
                            style="margin: auto">{{ translate('message.close') }}</a>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
    import ProjectModule from './ProjectModule';
    import Modal from '../_common/Modal';
    import {mapActions, mapGetters} from 'vuex';
    import _ from 'lodash';
    import {convertImageToBlog} from '../../helpers/project';
    import * as projectModuleHelper from '../../helpers/project-module';

    export default {
        components: {
            ProjectModule,
            Modal,
        },
        computed: {
            ...mapGetters([
                'modules',
                'project',
                'localUser',
                'validationMessages',
                'recommendedModules',
                'optionalModules',
                'projectCreateWizardStep1',
                'projectCreateWizardStep2',
                'projectCreateWizardStep3',
                'isRecommendedModule',
            ]),
        },
        created() {
            this.getModules();
            this.loadRecommendedModules();
        },
        watch: {
            project(value) {
                this.resetProjectCreateWizard();
                this.$router.push({name: 'project-dashboard', params: {'id': value.id}});
            },
            recommendedModules(modules) {
                this.selectedModules = [];
                modules.forEach((module) => {
                    this.selectedModules.push(module);
                });
            },
            validationMessages(value) {
                if (_.isObject(value) && _.keys(value) && _.keys(value).length) {
                    this.showErrorAlert = true;
                }
            },
        },
        methods: {
            ...mapActions([
                'createProject',
                'getModules',
                'getRecommendedModules',
                'setProjectCreateWizardStep3',
                'resetProjectCreateWizard',
            ]),
            isModuleSelected(module) {
                return this.selectedModules.indexOf(module) >= 0;
            },
            onModuleSelectionChanged(module, isChecked) {
                if (isChecked) {
                    this.selectedModules.push(module);
                    return;
                }

                let index = this.selectedModules.indexOf(module);
                if (index < 0) {
                    return;
                }

                this.$delete(this.selectedModules, index);
            },
            loadRecommendedModules() {
                const stepData = this.projectCreateWizardStep2;
                this.getRecommendedModules({
                    [projectModuleHelper.MODULE_ANALYSER_PROJECT_DURATION]: stepData.projectDuration,
                    [projectModuleHelper.MODULE_ANALYSER_PROJECT_BUDGET]: stepData.projectBudget,
                    [projectModuleHelper.MODULE_ANALYSER_PROJECT_INNOVATION_DEGREEE]: stepData.projectInnovationDegree,
                    [projectModuleHelper.MODULE_ANALYSER_PROJECT_MEMBERS]: stepData.projectMembers,
                    [projectModuleHelper.MODULE_ANALYSER_PROJECT_STRATEGICAL_MEANING]: stepData.projectStrategicalMeaning,
                    [projectModuleHelper.MODULE_ANALYSER_PROJECT_TECHNOLOGY_COMPLEXITY]: stepData.projectTechnologyComplexity,
                });
            },
            submitProject() {
                this.saveStepState();
                this.startProject();
            },
            startProject: function() {
                const firstStepData = this.projectCreateWizardStep1;
                const secondStepData = this.projectCreateWizardStep2;

                let formData = new FormData();

                if (firstStepData.projectName) {
                    formData.append('name', firstStepData.projectName);
                }

                if (firstStepData.projectNumber) {
                    formData.append('number', firstStepData.projectNumber);
                }

                if (firstStepData.selectedCompany) {
                    formData.append('company', firstStepData.selectedCompany.key);
                }

                if (firstStepData.projectLogo) {
                    formData.append('logoFile[file]', convertImageToBlog(firstStepData.projectLogo));
                }
                if (firstStepData.visiblePortfolio) {
                    formData.append('portfolio', firstStepData.selectedPortfolio.key);
                }
                if (firstStepData.visibleProgramme) {
                    formData.append('programme', firstStepData.selectedProgramme.key);
                }
                if (firstStepData.selectedCurrency) {
                    formData.append('currency', firstStepData.selectedCurrency.key);
                }

                if (secondStepData.selectedCategory) {
                    formData.append('projectCategory', secondStepData.selectedCategory.key);
                }

                if (secondStepData.selectedScope) {
                    formData.append('projectScope', secondStepData.selectedScope.key);
                }

                this.modules.forEach((module, i) => {
                    formData.append(`projectModules[${i}][module]`, module);
                    formData.append(`projectModules[${i}][isEnabled]`, this.isModuleSelected(module) ? 1: 0);
                });

                this.createProject(formData);
            },
            previousStep: function(e) {
                e.preventDefault();
                this.saveStepState();
                this.$router.push({name: 'projects-create-2'});
            },
            saveStepState: function() {
                this.setProjectCreateWizardStep3({
                    selectedModules: this.selectedModules,
                });
            },
        },
        data() {
            return {
                selectedModules: [],
                showErrorAlert: false,
            };
        },
    };
</script>

<style lang="scss">
    @import '../../css/project-create';
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_common';

    .st0 {
        fill: none;
        stroke: #65BEA3;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-miterlimit: 10;
    }

    .hr {
        background: $secondColor;
        width: 90px;
        margin: 0 auto;
    }

    .module .hr {
        margin: 0;
        width: 90px;
    }

    h2 {
        margin-bottom: 16px;
    }

    h3 {
        text-align: center;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 2.2px;
        margin-bottom: 54px;

        span {
            color: $secondColor;
        }
    }

    p {
        letter-spacing: 0.1em;
        text-align: center;
        color: $lightColor;
        margin-bottom: 18px;
    }

    .toggle-content {
        cursor: pointer;
    }

    .ucwords {
        text-transform: capitalize;
    }
</style>
