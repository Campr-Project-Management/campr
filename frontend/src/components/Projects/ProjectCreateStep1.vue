<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-1">
            <h1>{{ translateText('message.project_create_wizard') }}</h1>
            <h2>{{ translateText('message.project_create_step1') }}</h2>            

            <input-field type="text" v-bind:label="translateText('message.project_name')" v-model="projectName" v-bind:content="projectName" />
            <input-field type="text" v-bind:label="translateText('message.project_number')" v-model="projectNumber" v-bind:content="projectNumber" />
            <input id="projectLogo" type="file" name="projectLogo" style="display: none;" accept="image/*" v-on:change="updateProjectLogo"> 

            <select-field
                v-bind:title="translateText('message.select_customer')"
                v-bind:options="customers"
                v-model="selectedCompany"
                v-bind:currentOption="selectedCompany" />
               
            <div v-if="!projectLogo">
                <upload-placeholder />
            </div>
            <div v-else>
                <img :src="projectLogo" class="avatar" />
            </div>
            <div class="flex flex-center">
                <a class="btn-rounded btn-empty btn-md" v-on:click="openProjectLogoFileSelection">{{ translateText('message.project_logo') }}</a>
            </div>
            <div class="checkbox-input clearfix">
                <input id="project-portfolio" type="checkbox" v-model="visiblePortfolio">
                <label for="project-portfolio">{{ translateText('message.project_portfolio') }}</label>
            </div>
            <div v-if="visiblePortfolio">
                <select-field
                    v-bind:title="translateText('message.select_portfolio')"
                    v-bind:options="portfolios"
                    v-model="selectedPortfolio"
                    v-bind:currentOption="selectedPortfolio" />
                <div class="flex" v-if="visibleAddPortfolioField">
                    <input-field v-model="portfolioName" v-bind:content="portfolioName" type="text" v-bind:label="translateText('message.add_portfolio')" />
                    <button
                        v-on:click="addPortfolio"
                        :disabled="portfolioLoading"
                        class="btn-rounded btn-add">
                        {{translateText('label.add')}}
                    </button>
                </div>
                <div class="flex flex-direction-reverse">
                    <button
                            v-if="!visibleAddPortfolioField"
                            v-on:click="showAddPortfolioField"
                            :disabled="portfolioLoading"
                            class="btn-rounded btn-right">
                            {{ translateText('message.add_portfolio') }}
                    </button>
                </div>
            </div>

            <div class="checkbox-input clearfix">
                <input id="project-programme" type="checkbox" v-model="visibleProgramme">
                <label for="project-programme">{{ translateText('message.project_programme') }}</label>
            </div>
            <div v-if="visibleProgramme">
                <select-field
                    v-bind:title="translateText('message.select_programme')"
                    v-bind:options="programmes"
                    v-model="selectedProgramme"
                    v-bind:currentOption="selectedProgramme" />
                <div class="flex" v-if="visibleAddProgrammeField">
                    <input-field v-model="programmeName" v-bind:content="programmeName" type="text" v-bind:label="translateText('message.add_programme')" />
                    <button
                        v-on:click="addProgramme"
                        :disabled="programmeLoading"
                        class="btn-rounded btn-add">
                        {{translateText('label.add')}}
                    </button>
                </div>
                <div class="flex flex-direction-reverse">
                    <button
                            v-if="!visibleAddProgrammeField"
                            v-on:click="showAddProgrammeField"
                            :disabled="programmeLoading"
                            class="btn-rounded btn-right">
                            {{ translateText('message.add_programme') }}
                    </button>
                </div>    
            </div>

            <div class="flex flex-direction-reverse actions">
                <a href="#" v-on:click="nextStep" class="btn-rounded second-bg" v-bind:title="translateText('button.next_step')">
                    {{ translateText('button.next_step') }} >
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../_common/_form-components/InputField';
import SelectField from '../_common/_form-components/SelectField';
import UploadPlaceholder from '../_common/_form-components/UploadPlaceholder';
import {Validator} from 'vee-validate';
import {mapActions, mapGetters} from 'vuex';
import {FIRST_STEP_LOCALSTORAGE_KEY} from '../../helpers/project';

export default {
    validator: null,
    components: {
        InputField,
        SelectField,
        UploadPlaceholder,
    },
    methods: {
        ...mapActions(['createPortfolio', 'getPortfolios', 'createProgramme', 'getProgrammes', 'getCustomers']),
        translateText(text) {
            return this.translate(text);
        },
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
            const stepData = {
                'portfolioName': this.portfoioName,
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
            };
            localStorage.setItem(FIRST_STEP_LOCALSTORAGE_KEY, JSON.stringify(stepData));
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
        clearFormErrors() {
            this.errors.clear();
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
    },
    computed: mapGetters({
        customers: 'customersForSelect',
        localUser: 'localUser',
        portfolios: 'portfoliosForSelect',
        portfolioLoading: 'portfolioLoading',
        programmes: 'programmesForSelect',
        programmeLoading: 'programmeLoading',
    }),
    created() {
        this.validator = new Validator({
            projectName: 'required|alpha|min:3',
            selectedCompany: 'required',
        });
        this.$set(this, 'errors', this.validator.errorBag);

        this.getPortfolios();
        this.getProgrammes();
        this.getCustomers();
    },
    watch: {
        projectName(value) {
            // TODO: Implement validation
            // this.validator.validate('projectName', value);
        },
        portfolios(value) {
            let selectedPortfolio = value.filter((item) => item.label === this.portfolioName);

            if (selectedPortfolio.length > 0) {
                this.selectedPortfolio = selectedPortfolio[0];
                this.portfolioName = '';
            }
        },
        selectedPortfolio(value) {
            this.hideAddPortfolioField();
        },
        programmes(value) {
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
    data: function() {
        const stepData = JSON.parse(localStorage.getItem(FIRST_STEP_LOCALSTORAGE_KEY));

        return {
            projectName: stepData ? stepData.projectName : '',
            projectNumber: stepData ? stepData.projectNumber : '',
            projectLogo: stepData ? stepData.projectLogo : '',
            visiblePortfolio: stepData ? stepData.visiblePortfolio : false,
            visibleAddPortfolioField: stepData ? stepData.visibleAddPortfolioField : false,
            visibleProgramme: stepData ? stepData.visibleProgramme : false,
            visibleAddProgrammeField: stepData ? stepData.visibleAddProgrammeField : false,
            portfolioName: stepData ? stepData.portfolioName : '',
            programmeName: stepData ? stepData.programmeName : '',
            selectedPortfolio: stepData ? stepData.selectedPortfolio : '',
            selectedProgramme: stepData ? stepData.selectedProgramme : '',
            selectedCompany: stepData ? stepData.selectedCompany: '',
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
