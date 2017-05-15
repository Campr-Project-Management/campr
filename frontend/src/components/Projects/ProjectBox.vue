<template>
    <div class="project-box-wrapper">
        <div class="project-box box new">
            <span class="tag">
              <span>{{ message.new_message }}</span>
            </span>
            <div class="box-header">
                <h2><router-link class="simple-link" :to="{name: 'project-dashboard', params: { id: project.id }}">{{ project.name }}</router-link></h2>                
                <div class="favourite">
                    <star-icon :item="project"></star-icon>
                </div>
            </div>
            <div class="content flex flex-space-between">
                <div class="info">
                    <p>
                        <span class="title">{{ message.started_on }}:</span>
                        <span class="data">{{ project.createdAt | moment('DD.MM.YYYY') }}</span>
                    </p>
                    <p>
                        <span class="title">{{ message.customer }}:</span>
                        <span class="data">{{ project.companyName }}</span>
                    </p>
                    <p>
                        <span class="title">{{ message.programme }}:</span>
                        <span class="data"> {{ project.programmeName }}</span>
                    </p>
                    <p>
                        <span class="title">{{ message.status }}:</span>
                        <span v-bind:class="{ finished: project.statusName === 'Finished' }" class="status-label btn-rounded btn-auto">
                            {{ project.statusName }}
                        </span>
                    </p>
                </div>
              </div>
            <bar-chart :percentage="project.progress" :status="project.statusName" title-right="Progress"></bar-chart>
            <div class="content-bottom flex">
              <circle-chart :percentage="project.task_status" v-bind:title="message.task_status" class="left"></circle-chart>
              <circle-chart :percentage="project.costs_status" v-bind:title="message.cost_status" class="right"></circle-chart>
            </div>
            <div class="flex flex-space-between notes-title">
                <span class="uppercase">{{ message.notes }}</span>
                <pencil-icon :link="{name: 'edit'}"></pencil-icon>
            </div>
            <ul class="bullets">
                <li v-for="note in project.notes">{{ note.title }}</li>
            </ul>
            <a href="" class="add-note">{{ message.add_project_notes }}</a>
        </div>
    </div>
</template>

<script>
import CircleChart from '../_common/_charts/CircleChart';
import BarChart from '../_common/_charts/BarChart';
import StarIcon from '../_common/_icons/StarIcon';
import PencilIcon from '../_common/_icons/PencilIcon';

export default {
    components: {
        CircleChart,
        BarChart,
        StarIcon,
        PencilIcon,
    },
    props: ['project'],
    data() {
        return {
            message: {
                new_message: Translator.trans('message.new_message'),
                started_on: Translator.trans('message.started_on'),
                customer: Translator.trans('message.customer'),
                programme: Translator.trans('message.programme'),
                status: Translator.trans('message.status'),
                task_status: Translator.trans('message.task_status'),
                cost_status: Translator.trans('message.cost_status'),
                notes: Translator.trans('message.notes'),
                add_project_notes: Translator.trans('message.add_project_notes'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common';
  @import '../../css/box';
  @import '../../css/box-project'; 
</style>
