<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-1">
            <h1>{{ message.project_create_wizard }}</h1>
            <h2>{{ message.project_create_step1 }}</h2>            

            <input-field type="text" v-bind:label="message.project_name" v-model="projectName" v-bind:content="projectName" />
            <input-field type="text" v-bind:label="message.project_number" v-model="projectNumber" v-bind:content="projectNumber" />
            <input id="projectLogo" type="file" name="projectLogo" style="display: none;" accept="image/*" v-on:change="updateProjectLogo"> 

            <div v-if="!projectLogo">
                <upload-placeholder />
            </div>
            <div v-else>
                <img :src="projectLogo" class="avatar" />
            </div>
            <div class="flex flex-center">
                <a class="btn-rounded btn-empty btn-md" v-on:click="openProjectLogoFileSelection">{{ message.project_logo }}</a>
            </div>
            <div class="checkbox-input clearfix">
                <input id="project-portfolio" type="checkbox" v-model="visiblePortfolio">
                <label for="project-portfolio">{{ message.project_portfolio }}</label>
            </div>
            <div v-show="visiblePortfolio">
                <select-field
                    v-bind:title="message.select_portfolio"
                    v-bind:options="portfolios"
                    v-model="selectedPortfolio"
                    v-bind:currentOption="selectedPortfolio" />
                <input-field v-model="portfolioName" v-bind:content="portfolioName" type="text" v-bind:label="message.add_portfolio" />
                <div class="flex flex-direction-reverse">
                    <button
                        v-on:click="addPortfolio"
                        :disabled="portfolioLoading"
                        class="btn-rounded btn-right">
                        {{ message.add_portfolio }} +
                    </button>
                </div>
            </div>

            <div class="checkbox-input clearfix">
                <input id="project-programme" type="checkbox" v-model="visibleProgramme">
                <label for="project-programme">{{ message.project_programme }}</label>
            </div>
            <div v-if="visibleProgramme">
                <select-field
                    v-bind:title="message.select_programme"
                    v-bind:options="programmes"
                    v-model="selectedProgramme"
                    v-bind:currentOption="selectedProgramme" />
                <input-field v-model="programmeName" v-bind:content="programmeName" type="text" v-bind:label="message.add_programme" />
                <div class="flex flex-direction-reverse">
                    <button
                        v-on:click="addProgramme"
                        :disabled="programmeLoading"
                        class="btn-rounded btn-right">
                        {{ message.add_programme }} +
                    </button>
                </div>
            </div>

            <div class="flex flex-direction-reverse actions">
                <a href="#" v-on:click="nextStep" class="btn-rounded second-bg" v-bind:title="button.next_step">
                    {{ button.next_step }} >
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
        ...mapActions(['createPortfolio', 'getPortfolios', 'createProgramme', 'getProgrammes']),
        togglePortfolio: function() {
            this.visiblePortfolio = !this.visiblePortfolio;
        },
        toggleProgramme: function() {
            this.visibleProgramme = !this.visibleProgramme;
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
                'visibleProgramme': this.visibleProgramme,
                'selectedPortfolio': this.selectedPortfolio,
                'selectedProgramme': this.selectedProgramme,
            };
            localStorage.setItem(FIRST_STEP_LOCALSTORAGE_KEY, JSON.stringify(stepData));
        },
        addPortfolio: function() {
            let data = {
                name: this.portfolioName,
            };
            this.createPortfolio(data);
        },
        addProgramme: function() {
            let data = {
                name: this.programmeName,
            };
            this.createProgramme(data);
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
        portfolios: 'portfoliosForSelect',
        portfolioLoading: 'portfolioLoading',
        programmes: 'programmesForSelect',
        programmeLoading: 'programmeLoading',
    }),
    created() {
        this.validator = new Validator({
            projectName: 'required|alpha|min:3',
        });
        this.$set(this, 'errors', this.validator.errorBag);

        this.getPortfolios();
        this.getProgrammes();
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
        programmes(value) {
            let selectedProgramme = value.filter((item) => item.label === this.programmeName);

            if (selectedProgramme.length > 0) {
                this.selectedProgramme = selectedProgramme[0];
                this.programmeName = '';
            }
        },
    },
    data: function() {
        const stepData = JSON.parse(localStorage.getItem(FIRST_STEP_LOCALSTORAGE_KEY));

        return {
            message: {
                project_create_wizard: Translator.trans('message.project_create_wizard'),
                project_create_step1: Translator.trans('message.project_create_step1'),
                project_name: Translator.trans('message.project_name'),
                project_number: Translator.trans('message.project_number'),
                project_logo: Translator.trans('message.project_logo'),
                project_portfolio: Translator.trans('message.project_portfolio'),
                add_portfolio: Translator.trans('message.add_portfolio'),
                project_programme: Translator.trans('message.project_programme'),
                add_programme: Translator.trans('message.add_programme'),
                select_portfolio: Translator.trans('message.select_portfolio'),
                select_programme: Translator.trans('message.select_programme'),
            },
            button: {
                next_step: Translator.trans('button.next_step'),
            },
            projectName: stepData ? stepData.projectName : '',
            projectNumber: stepData ? stepData.projectNumber : '',
            projectLogo: stepData ? stepData.projectLogo : '',
            visiblePortfolio: stepData ? stepData.visiblePortfolio : false,
            visibleProgramme: stepData ? stepData.visibleProgramme : false,
            portfolioName: stepData ? stepData.portfolioName : '',
            programmeName: stepData ? stepData.programmeName : '',
            selectedPortfolio: stepData ? stepData.selectedPortfolio : '',
            selectedProgramme: stepData ? stepData.selectedProgramme : '',
            errors: null,
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
      height: 122px;
      @include border-radius(50%);
  }

  .input-holder {
      margin-top: 30px;
  }

  .btn-empty {
      margin: 0 auto 30px;
      width: 140px;
      font-size: 9px;
  }

  .btn-rounded {
      //padding: 0;
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
</style>
