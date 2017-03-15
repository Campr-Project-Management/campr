<template>
    <div class="project-box box new">
        <span class="tag">
          <span>{{ message.new_message }}</span>
        </span>
        <div class="header">
            <h2>{{ project.name }}</h2>
            <div>
              <eye-icon :link="{name: 'project-dashboard', params: { id: project.id }}"></eye-icon>
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
                    <span class="data"> {{ project.programme }}</span>
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
</template>

<script>
import CircleChart from '../_common/_charts/CircleChart';
import BarChart from '../_common/_charts/BarChart';
import StarIcon from '../_common/_icons/StarIcon';
import EyeIcon from '../_common/_icons/EyeIcon';
import PencilIcon from '../_common/_icons/PencilIcon';

export default {
    components: {
        CircleChart,
        BarChart,
        StarIcon,
        EyeIcon,
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

  .info {
    padding-top: 16px;

    p {
      margin: 0 0 6px 0;
    }

    .title {
      color: $middleColor;
      text-transform: uppercase;
      font-size: 12px;
    }

    .data {
      font-size: 11px;
      letter-spacing: 0.5px;
      margin-left: 6px;
    }

    .status-label {
      padding: 0 19px;
      margin-left: 5px;
      font-size: 8px;
      height: 23px;
      line-height: 23px;
      display: inline-block;

      &.finished {
        background: $secondColor;
      }
    }
  }

  .content-bottom {
    margin-top: 21px;
  }

  .left {
    margin-right: 10px;
  }
  .right {
    margin-left: 10px;
  }

  .notes-title {
    margin-top: 12px;
    font-weight: 300;
    height: 32px;

    span {
      line-height: 32px;
    }
  }

  .bullets {
    font-weight: 300;
    font-size: 11px;
    margin-top: 4px;

    li {
      margin-bottom: 10px;
    }
  }

  .add-note {
    color: $secondColor;
  }
</style>
