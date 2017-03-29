<template>
    <div class="project-box box small-box">
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
                    <span class="data">{{ project.date | moment('DD.MM.YYYY') }}</span>
                </p>
                <p>
                    <span class="title">{{ message.customer }}:</span>
                    <span class="data">{{ project.companyName }}</span>
                </p>
                <p>
                    <span class="title">{{ message.status }}:</span>
                    <span class="status-label btn-rounded btn-auto">
                        {{ project.statusName }}
                    </span>
                </p>
            </div>
        </div>
        <bar-chart :percentage="project.progress" title-right="Progress"></bar-chart>
   </div>
</template>

<script>
import BarChart from '../_common/_charts/BarChart';
import StarIcon from '../_common/_icons/StarIcon';
import EyeIcon from '../_common/_icons/EyeIcon';

export default {
    components: {
        BarChart,
        StarIcon,
        EyeIcon,
    },
    props: ['project'],
    data() {
        return {
            message: {
                started_on: Translator.trans('message.started_on'),
                customer: Translator.trans('message.customer'),
                status: Translator.trans('message.status'),
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common.scss';
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
</style>
