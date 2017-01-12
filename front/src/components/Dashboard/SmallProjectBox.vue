<template>
    <div class="project-box box small-box">
        <div class="header">
            <h2>{{ project.title }}</h2>
            <div>
                <eye-icon :link="{name: 'project', params: { id: project.id }}"></eye-icon>
                <star-icon :item="project"></star-icon>
            </div>
        </div>
        <div class="content flex flex-space-between">
            <div class="info">
                <p>
                    <span class="title">Started on:</span>
                    <span class="data">{{ project.date | moment('DD.MM.YYYY') }}</span>
                </p>
                <p>
                    <span class="title">Customer:</span>
                    <span class="data">{{ project.customer }}</span>
                </p>
                <p>
                    <span class="title">Status:</span>
                    <span v-bind:class="{ finished: project.status === 'FINISHED' }" class="status-label btn-rounded">
                        {{project.status === 'IN_PROGRESS' && 'In progress' || project.status === 'FINISHED' && 'Finished'}}
                    </span>
                </p>
            </div>
        </div>
        <bar-chart :percentage="project.progress" :status="project.status" title-right="Progress"></bar-chart>
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
