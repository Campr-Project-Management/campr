<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-3">
            <h1>{{ translateText('message.project_create_wizard') }}</h1>
            <h2>{{ translateText('message.project_create_step3') }}</h2>
            <div class="hr"></div>
            <h3>{{ translateText('message.project_size') }}: <span>{{ translateText('message.medium') }}</span></h3>

            <p>{{ translateText('message.recommended_modules') }}</p>
            <project-module v-for="(module, key) in modules"
                v-if="moduleIsRecommended(key)"
                v-bind:title="translateText(module.title)"
                v-bind:description="translateText(module.description)"
                v-bind:id="key"
                v-model="modulesConfiguration[key]"
                v-bind:inactive="!modulesConfiguration[key]" />
         
            <p>{{ translateText('message.not_recommended_modules') }}</p>

            <project-module v-for="(module, key) in modules"
                v-if="!moduleIsRecommended(key)"
                v-bind:title="translateText(module.title)"
                v-bind:description="translateText(module.description)"
                v-bind:id="key"
                v-model="modulesConfiguration[key]"
                v-bind:inactive="!modulesConfiguration[key]" />

            <div class="flex flex-space-between actions">
                <a href="#" v-on:click="previousStep" class="btn-rounded btn-right" v-bind:title="translateText('button.previous_step')">
                    < {{ translateText('button.previous_step') }}
                </a>
                <a v-if="!projectLoading" href="#" v-on:click="submitProject" class="btn-rounded btn-right second-bg" v-bind:title="translateText('button.start_project')">
                    < {{ translateText('button.start_project') }}
                </a>
            </div>
            <!-- /// Show errors modal/// -->
            <modal v-if="showErrorAlert" @close="showErrorAlert = false">
                <p class="modal-title">{{ translateText('title.project_add_error') }}</p>
                <dl v-for="field, key in validationMessages">
                    <dt class="ucwords">{{key}}:</dt>
                    <dd v-for="item in field" >{{item}}</dd>
                </dl>
                <div class="flex flex-space-between">
                    <a
                        @click.preventDefault="showErrorAlert = false"
                        class="btn-rounded btn-empty danger-color danger-border"
                        style="margin: auto"
                    >
                        {{ translateText('message.close') }}
                    </a>
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
import {
    processProjectModules,
    processProjectConfiguration,
    convertImageToBlog,
    FIRST_STEP_LOCALSTORAGE_KEY,
    SECOND_STEP_LOCALSTORAGE_KEY,
    THIRD_STEP_LOCALSTORAGE_KEY,
} from '../../helpers/project';

export default {
    components: {
        ProjectModule,
        Modal,
    },
    computed: {
        ...mapGetters({
            modules: 'modules',
            project: 'project',
            projectLoading: 'projectLoading',
            localUser: 'localUser',
            validationMessages: 'validationMessages',
        }),
    },
    created() {
        if (!this.modules || !this.modules.length) {
            this.getModules();
        }
    },
    watch: {
        project(value) {
            localStorage.removeItem(FIRST_STEP_LOCALSTORAGE_KEY);
            localStorage.removeItem(SECOND_STEP_LOCALSTORAGE_KEY);
            localStorage.removeItem(THIRD_STEP_LOCALSTORAGE_KEY);
            this.$router.push({name: 'project-dashboard', params: {'id': value.id}});
        },
        modules(value) {
            const stepData = JSON.parse(localStorage.getItem(THIRD_STEP_LOCALSTORAGE_KEY) || '{}');
            if (stepData && stepData.modulesConfiguration != null) {
                this.modulesConfiguration = stepData.modulesConfiguration;
                return;
            }
            for (let module in value) {
                if (value.hasOwnProperty(module)) {
                    this.modulesConfiguration[module] = this.moduleIsRecommended(module);
                }
            }
            this.modulesConfiguration = JSON.parse(JSON.stringify(this.modulesConfiguration));
        },
        showErrorAlert(val) {
            if (val === false) {
                this.validationMessages = [];
            }
        },
        validationMessages(value) {
            if (_.isObject(value) && _.keys(value) && _.keys(value).length) {
                this.showErrorAlert = true;
            }
        },
    },
    methods: {
        ...mapActions(['createProject', 'getModules']),
        translateText(text) {
            return this.translate(text);
        },
        getModuleId(moduleKey) {
            return moduleKey.replace(/_/g, '-');
        },
        moduleIsRecommended(moduleKey) {
            /* we will have to implement an algoritm for this*/
            let notRecomandedModules = ['rasci_matrix', 'task_chart', 'gantt_chart', 'decisions'];
            return notRecomandedModules.indexOf(moduleKey) === -1;
        },
        submitProject: function(e) {
            e.preventDefault();
            this.saveStepState();

            this.startProject();
        },
        startProject: function() {
            const firstStepData = JSON.parse(localStorage.getItem(FIRST_STEP_LOCALSTORAGE_KEY) || '{}');
            const secondStepData = JSON.parse(localStorage.getItem(SECOND_STEP_LOCALSTORAGE_KEY) || '{}');
            const thirdStepData = JSON.parse(localStorage.getItem(THIRD_STEP_LOCALSTORAGE_KEY) || '{}');

            let projectModules = processProjectModules(thirdStepData);

            let formData = new FormData();
            let configuration = processProjectConfiguration(secondStepData);

            formData.append('name', firstStepData.projectName);
            formData.append('number', firstStepData.projectNumber);

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

            if (secondStepData.selectedCategory) {
                formData.append('projectCategory', secondStepData.selectedCategory.key);
            }

            if (secondStepData.selectedScope) {
                formData.append('projectScope', secondStepData.selectedScope.key);
            }

            for (let key in configuration) {
                if (configuration.hasOwnProperty(key)) {
                    formData.append('configuration[' + key +']', configuration[key]);
                }
            }

            for (let i = 0; i < projectModules.length; i++) {
                formData.append('projectModules[' + i + '][module]', projectModules[i]['module']);
                if (projectModules[i]['isEnabled']) {
                    formData.append('projectModules[' + i + '][isEnabled]', projectModules[i]['isEnabled']);
                }
            }

            this.createProject(formData);
        },
        previousStep: function(e) {
            e.preventDefault();
            this.saveStepState();
            this.validationMessages = [];
            this.$router.push({name: 'projects-create-2'});
        },
        saveStepState: function() {
            const stepData = {
                modulesConfiguration: this.modulesConfiguration,
            };
            localStorage.setItem(THIRD_STEP_LOCALSTORAGE_KEY, JSON.stringify(stepData));
        },
    },
    data: function() {
        return {
            modulesConfiguration: {},
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
        fill:none;
        stroke:#65BEA3;
        stroke-linecap:round;
        stroke-linejoin:round;
        stroke-miterlimit:10;
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
        font-size: 10px;
        letter-spacing: 1.5px;
        margin-left: 38px;
        color: $lighterColor;
        margin-bottom: 18px;
    }

    .toggle-content {
        cursor: pointer;
    }
    
    .ucwords {
        text-transform: capitalize;
    }

    /*.project-create-wrapper {*/
        /*.error-modal{*/
            /*display: none;*/
            /*&.opened{*/
                /*display: table;*/
            /*}*/
        /*}*/
    /*}*/
</style>
