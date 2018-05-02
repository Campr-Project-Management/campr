<template>
    <div class="project-box-wrapper">
        <div class="project-box box small-box">
            <div class="box-header">
                <h2>
                    <router-link :to="{name: 'project-dashboard', params: { id: project.id }}">
                        {{ project.name }}
                    </router-link>
                </h2>
                <div class="favourite" v-if="project">
                    <star-icon :item="project"></star-icon>
                </div>
            </div>
            <div class="content flex flex-space-between">
                <div class="info">
                    <p>
                        <span class="title">{{ translateText('message.started_on') }}:</span>
                        <span class="data">{{ baseDate(project) | moment('DD.MM.YYYY') }}</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.customer') }}:</span>
                        <span class="data">{{ project.companyName }}</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.project_manager') }}</span>
                        <span class="data" v-if="project.projectManager">{{ project.projectManagerName }}</span><span class="data" v-else>-</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.project_sponsor') }}</span>
                        <span class="data" v-if="project.projectSponsor">{{ project.projectSponsorName }}</span><span class="data" v-else>-</span>
                    </p>
                    <p>
                        <span class="title">{{ translateText('message.status') }}:</span>
                        <span class="status-label btn-rounded btn-auto">
                            {{ translateText(project.statusName) }}
                        </span>
                    </p>
                </div>
            </div>
            <bar-chart :percentage="project.progress" title-right="Progress"></bar-chart>
        </div>
    </div>
</template>

<script>
import BarChart from '../_common/_charts/BarChart';
import StarIcon from '../_common/_icons/StarIcon';

export default {
    components: {
        BarChart,
        StarIcon,
    },
    props: ['project'],
    methods: {
        baseDate(project) {
            return project && project.contracts && project.contracts.length
                ? project.contracts[0].proposedStartDate
                : '-'
            ;
        },
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables.scss';
  @import '../../css/box';
  @import '../../css/box-project';

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
      text-transform: uppercase;
      letter-spacing: 0.1em;
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
