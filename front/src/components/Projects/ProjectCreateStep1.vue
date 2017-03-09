<template>
    <div class="project-create-wrapper">
        <div class="page-section project-create step-1">
            <h1>{{ message.project_create_wizard }}</h1>
            <h2>{{ message.project_create_step1 }}</h2>

            <input-field type="text" v-bind:label="message.project_name"></input-field>
            <input-field type="text" v-bind:label="message.project_number"></input-field>

            <svg version="1.1" id="Layer_1" class="avatar" height="122px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
               viewBox="0 0 123.5 123.8" style="enable-background:new 0 0 123.5 123.8;" xml:space="preserve">
                <g id="XMLID_889_">
                  <circle id="XMLID_893_" class="st0" cx="61.7" cy="62" r="60"/>
                  <g id="XMLID_890_">
                    <path id="XMLID_892_" class="st0" d="M46,46.3c0-8.6,7-15.7,15.7-15.7c8.6,0,15.7,7,15.7,15.7c0,8.6-7,15.7-15.7,15.7v15.7"/>
                    <path id="XMLID_891_" class="st0" d="M64.3,90.7c0,1.4-1.2,2.6-2.6,2.6l0,0c-1.4,0-2.6-1.2-2.6-2.6l0,0c0-1.4,1.2-2.6,2.6-2.6l0,0
                      C63.1,88.1,64.3,89.3,64.3,90.7L64.3,90.7z"/>
                  </g>
                </g>
            </svg>
            <a class="btn-rounded btn-empty btn-md">{{ message.project_logo }}</a>

            <div class="checkbox-input clearfix">
                <input id="project-portfolio" type="checkbox" name="" value="1" @click="togglePortfolio">
                <label for="project-portfolio">{{ message.project_portfolio }}</label>
            </div>
            <div v-show="visiblePortfolio">
                <select-field title="Select Portfolio" v-bind:options="customers"></select-field>
                <input-field type="text" v-bind:label="message.add_portfolio"></input-field>
                <div class="flex flex-direction-reverse">
                    <a href="" class="btn-rounded btn-right">{{ message.add_portfolio }} +</a>
                </div>
            </div>

            <div class="checkbox-input clearfix">
                <input id="project-programme" type="checkbox" name="" value="1" @click="toggleProgramme">
                <label for="project-programme">{{ message.project_programme }}</label>
            </div>
            <div v-if="visibleProgramme">
                <select-field title="Select Portfolio" v-bind:options="customers"></select-field>
                <input-field type="text" v-bind:label="message.add_programme"></input-field>
                <div class="flex flex-direction-reverse">
                    <a href="" class="btn-rounded btn-right">{{ message.add_programme }} +</a>
                </div>
            </div>

            <div class="flex flex-direction-reverse actions">
                <router-link :to="{name: 'projects-create-2'}" class="btn-rounded second-bg" v-bind:title="button.next_step">
                    {{ button.next_step }} >
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import InputField from '../_common/_form-components/InputField';
import SelectField from '../_common/_form-components/SelectField';

export default {
    components: {
        InputField,
        SelectField,
    },
    methods: {
        togglePortfolio: function() {
            this.visiblePortfolio = !this.visiblePortfolio;
        },
        toggleProgramme: function() {
            this.visibleProgramme = !this.visibleProgramme;
        },
    },
    data: function() {
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
            },
            button: {
                next_step: Translator.trans('button.next_step'),
            },
            visiblePortfolio: false,
            visibleProgramme: false,
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

  .avatar {
      margin: 28px auto;
      display: block;
  }

  .st0 {
      fill:none;
      stroke:#666FA1;
      stroke-miterlimit:10;
  }

  .part-of {
      display: none;
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
      padding: 0;
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
