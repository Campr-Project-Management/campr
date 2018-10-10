<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-1">
            <h1>{{ translate('message.project_create_wizard') }}</h1>
            <h2>{{ translate('message.project_create_step1') }}</h2>

            <input-field type="text" :label="translate('message.project_name')" v-model="projectName"
                         :content="projectName"/>
            <input-field type="text" :label="translate('message.project_number')" v-model="projectNumber"
                         :content="projectNumber"/>
            <input id="projectLogo" type="file" name="projectLogo" style="display: none;" accept="image/*"
                   @change="updateProjectLogo">

            <select-field
                    :title="translate('message.select_customer')"
                    :options="customersForSelect"
                    v-model="selectedCompany"/>

            <select-field
                    :title="translate('message.currency')"
                    :options="currenciesForSelect"
                    v-model="selectedCurrency"/>

            <div v-if="!projectLogo">
                <upload-placeholder/>
            </div>
            <div v-else>
                <img :src="projectLogo" class="avatar"/>
            </div>
            <div class="flex flex-center">
                <a class="btn-rounded btn-empty btn-md" @click="openProjectLogoFileSelection">{{ translate('message.project_logo') }}</a>
            </div>
            <div class="checkbox-input clearfix">
                <input id="project-portfolio" type="checkbox" v-model="visiblePortfolio">
                <label for="project-portfolio">{{ translate('message.project_portfolio') }}</label>
            </div>
            <div v-if="visiblePortfolio">
                <select-field
                        :title="translate('message.select_portfolio')"
                        :options="portfoliosForSelect"
                        v-model="selectedPortfolio"/>
                <div class="flex" v-if="visibleAddPortfolioField">
                    <input-field v-model="portfolioName" :content="portfolioName" type="text"
                                 :label="translate('message.add_portfolio')"/>
                    <button
                            @click="addPortfolio"
                            :disabled="portfolioLoading"
                            class="btn-rounded btn-add">{{translate('label.add')}}
                    </button>
                </div>
                <div class="flex flex-direction-reverse">
                    <button
                            v-if="!visibleAddPortfolioField"
                            @click="showAddPortfolioField"
                            :disabled="portfolioLoading"
                            class="btn-rounded btn-right">{{ translate('message.add_portfolio') }}
                    </button>
                </div>
            </div>

            <div class="checkbox-input clearfix">
                <input id="project-programme" type="checkbox" v-model="visibleProgramme">
                <label for="project-programme">{{ translate('message.project_programme') }}</label>
            </div>
            <div v-if="visibleProgramme">
                <select-field
                        :title="translate('message.select_programme')"
                        :options="programmesForSelect"
                        v-model="selectedProgramme"/>
                <div class="flex" v-if="visibleAddProgrammeField">
                    <input-field v-model="programmeName" :content="programmeName" type="text"
                                 :label="translate('message.add_programme')"/>
                    <button
                            @click="addProgramme"
                            :disabled="programmeLoading"
                            class="btn-rounded btn-add">
                        {{translate('label.add')}}
                    </button>
                </div>
                <div class="flex flex-direction-reverse">
                    <button
                            v-if="!visibleAddProgrammeField"
                            @click="showAddProgrammeField"
                            :disabled="programmeLoading"
                            class="btn-rounded btn-right">
                        {{ translate('message.add_programme') }}
                    </button>
                </div>
            </div>

            <div class="flex flex-direction-reverse actions">
                <a href="#" @click="nextStep" class="btn-rounded second-bg" :title="translate('button.next_step')">
                    {{ translate('button.next_step') }} >
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import InputField from '../_common/_form-components/InputField';
    import SelectField from '../_common/_form-components/SelectField';
    import UploadPlaceholder from '../_common/_form-components/UploadPlaceholder';
    import {mapActions, mapGetters} from 'vuex';

    export default {
        components: {
            InputField,
            SelectField,
            UploadPlaceholder,
        },
        methods: {
            ...mapActions([
                'createPortfolio',
                'getPortfolios',
                'createProgramme',
                'getProgrammes',
                'getCustomers',
                'getCurrencies',
                'setProjectCreateWizardStep1',
            ]),
            hideAddPortfolioField: function() {
                this.visibleAddPortfolioField = false;
            },
            showAddPortfolioField: function() {
                this.visibleAddPortfolioField = true;
            },
            hideAddProgrammeField: function() {
                this.visibleAddProgrammeField = false;
            },
            showAddProgrammeField: function() {
                this.visibleAddProgrammeField = true;
            },
            nextStep: function(e) {
                e.preventDefault();
                this.saveStepState();
                this.$router.push({name: 'projects-create-2'});
            },
            saveStepState: function() {
                const data = {
                    'portfolioName': this.portfolioName,
                    'programmeName': this.programmeName,
                    'projectName': this.projectName,
                    'projectNumber': this.projectNumber,
                    'projectLogo': this.projectLogo,
                    'visiblePortfolio': this.visiblePortfolio,
                    'visibleAddPortfolioField': this.visibleAddPortfolioField,
                    'visibleProgramme': this.visibleProgramme,
                    'visibleAddProgrammeField': this.visibleAddProgrammeField,
                    'selectedPortfolio': this.selectedPortfolio,
                    'selectedProgramme': this.selectedProgramme,
                    'selectedCompany': this.selectedCompany,
                    'selectedCurrency': this.selectedCurrency,
                };
                this.setProjectCreateWizardStep1(data);
            },
            addPortfolio: function() {
                let data = {
                    name: this.portfolioName,
                };
                this.createPortfolio(data);
                this.hideAddPortfolioField();
            },
            addProgramme: function() {
                let data = {
                    name: this.programmeName,
                };
                this.createProgramme(data);
                this.hideAddProgrammeField();
            },
            openProjectLogoFileSelection() {
                document.getElementById('projectLogo').click();
            },
            updateProjectLogo(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
                    return;
                }
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.projectLogo = e.target.result;
                };
                reader.readAsDataURL(files[0]);
            },
            init() {
                const stepData = this.projectCreateWizardStep1;

                this.projectName = stepData ? stepData.projectName : '';
                this.projectNumber = stepData ? stepData.projectNumber : '';
                this.projectLogo = stepData ? stepData.projectLogo : '';
                this.visiblePortfolio = stepData ? stepData.visiblePortfolio : false;
                this.visibleAddPortfolioField = stepData ? stepData.visibleAddPortfolioField : false;
                this.visibleProgramme = stepData ? stepData.visibleProgramme : false;
                this.visibleAddProgrammeField = stepData ? stepData.visibleAddProgrammeField : false;
                this.portfolioName = stepData ? stepData.portfolioName : '';
                this.programmeName = stepData ? stepData.programmeName : '';
                this.selectedPortfolio = stepData ? stepData.selectedPortfolio : {};
                this.selectedProgramme = stepData ? stepData.selectedProgramme : {};
                this.selectedCompany = stepData ? stepData.selectedCompany : {};
                this.selectedCurrency = stepData ? stepData.selectedCurrency : {};
            },
        },
        computed: {
            ...mapGetters([
                'localUser',
                'currenciesForSelect',
                'currencies',
                'programmeLoading',
                'projectCreateWizardStep1',
                'customersForSelect',
                'portfolioLoading',
                'programmesForSelect',
                'portfoliosForSelect',
            ]),
        },
        created() {
            this.init();
            this.getPortfolios();
            this.getProgrammes();
            this.getCustomers();

            if (this.currencies.length === 0) {
                this.getCurrencies();
            }
        },
        watch: {
            portfoliosForSelect(value) {
                let selectedPortfolio = value.filter((item) => item.label === this.portfolioName);

                if (selectedPortfolio.length > 0) {
                    this.selectedPortfolio = selectedPortfolio[0];
                    this.portfolioName = '';
                }
            },
            selectedPortfolio(value) {
                this.hideAddPortfolioField();
            },
            programmesForSelect(value) {
                let selectedProgramme = value.filter((item) => item.label === this.programmeName);

                if (selectedProgramme.length > 0) {
                    this.selectedProgramme = selectedProgramme[0];
                    this.programmeName = '';
                }
            },
            selectedProgramme(value) {
                this.hideAddProgrammeField();
            },
        },
        data() {
            return {
                projectName: '',
                projectNumber: '',
                projectLogo: '',
                visiblePortfolio: false,
                visibleAddPortfolioField: false,
                visibleProgramme: false,
                visibleAddProgrammeField: false,
                portfolioName: '',
                programmeName: '',
                selectedPortfolio: {},
                selectedProgramme: {},
                selectedCompany: {},
                selectedCurrency: {},
            };
        },
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_mixins';
    @import '../../css/project-create';
    @import '../../css/_common';

    .avatar {
        margin: 28px auto;
        display: block;
        width: 122px;
        height: 122px;
        border-radius: 122px;
        object-fit: cover;
    }

    .input-holder {
        margin-bottom: 30px;
    }

    .btn-empty {
        margin: 0 auto 30px;
        width: 140px;
        font-size: 9px;
    }

    .btn-rounded {
        //padding: 0;
        width: auto;
    }

    button.btn-rounded {
        border: none;
    }

    .checkbox-input {
        margin-bottom: 23px;
    }

    .dropdown {
        margin-bottom: 30px;
    }

    .btn-right {
        margin: 10px 0 30px;
    }

    .btn-add {
        margin-left: 10px;
    }
</style>
