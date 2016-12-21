<template>
    <div class="project-box box new">
        <span class="tag">
          <span>New</span>
        </span>
        <div class="header">
            <h2>Campr Dashboard Design</h2>
            <div>
              <eye-icon :link="{name: 'project', params: { id: project.id }}"></eye-icon>
              <star-icon :active="project.favourite"></star-icon>
            </div>
        </div>
        <div class="content flex flex-space-between">
            <div class="info">
                <p>
                    <span class="title">Started on:</span>
                    <span class="data">12.05.2016</span>
                </p>
                <p>
                    <span class="title">Customer:</span>
                    <span class="data">Christoph Poll</span>
                </p>
                <p>
                    <span class="title">Programme:</span>
                    <span class="data">CAMPR</span>
                </p>
                <p>
                    <span class="title">Status:</span>
                    <span v-bind:class="{ finished: project.status === 'FINISHED' }" class="status-label btn-rounded">
                        {{project.status === 'IN_PROGRESS' && 'In progress' || project.status === 'FINISHED' && 'Finished'}}
                    </span>
                </p>
            </div>
          </div>
        <bar-chart :percentage="project.percentage" :status="project.status" title-right="Progress"></bar-chart>
        <div class="content-bottom flex">
          <circle-chart percentage="77" title="Task Status" class="left"></circle-chart>
          <circle-chart percentage="44.34" title="Costs Status" class="right"></circle-chart>
        </div>
        <div class="flex flex-space-between notes-title">
            <span class="uppercase">Notes</span>
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px"
             viewBox="0 0 29.4 26.3" style="enable-background:new 0 0 29.4 26.3;" xml:space="preserve">
            <g id="XMLID_1972_">
                <path id="XMLID_1976_" class="st0" d="M6.5,21.6c0,0.1,0,0.2,0.1,0.3C6.7,22,6.8,22.1,6.9,22c0,0,4.4-1,4.5-1.1l-3.8-3.8
                  C7.5,17.2,6.5,21.6,6.5,21.6z"/>

                  <rect id="XMLID_1975_" x="18.8" y="6.7" transform="matrix(-0.7074 0.7069 -0.7069 -0.7074 39.3086 2.5665)" class="st0" width="0.7" height="5.4"/>

                  <rect id="XMLID_1974_" x="8.2" y="11.7" transform="matrix(-0.7071 0.7071 -0.7071 -0.7071 34.3533 14.5166)" class="st0" width="12" height="5.4"/>
                <path id="XMLID_1973_" class="st0" d="M21.8,10.6l0.2-0.2c0.7-0.7,0.7-1.7,0-2.4l-1.4-1.4c-0.3-0.3-0.7-0.5-1.2-0.5
                  c-0.5,0-0.9,0.2-1.2,0.5l-0.2,0.2L21.8,10.6z"/>
            </g>
          </svg>
        </div>
        <ul class="bullets">
            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis at dolor sollicitudin, interdum nibh quis, faucibus justo. Suspendisse sed nisi id mi aliquam finibus ac sem.</li>
            <li>Suspendisse in massa in ligula suscipit vulputate.</li>
            <li>Sed finibus massa nec est rutrum malesuada a et eros. Cras volutpat leo eu lorem viverra ornare.</li>
        </ul>
        <a href="" class="add-note">add some notes for this project</a>
    </div>
</template>

<script>
import CircleChart from '../_common/_charts/CircleChart';
import BarChart from '../_common/_charts/BarChart';
import StarIcon from '../_common/_icons/StarIcon';
import EyeIcon from '../_common/_icons/EyeIcon';

export default {
    components: {
        CircleChart,
        BarChart,
        StarIcon,
        EyeIcon,
    },
    props: ['project'],
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_common';
  @import '../../css/box';

  .st0 {
    fill:#666FA1;
  }

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
