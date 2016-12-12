<template>
  <div class="project-box">
    <div class="header">
      <h2>Campr Dashboard Design</h2>
      <div>
        <svg class="eye" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          width="16px" height="9.334px" viewBox="0 0 16 9.334" enable-background="new 0 0 16 9.334" xml:space="preserve">
          <path fill="#636EA0" d="M0.073,4.874c0.144,0.181,3.582,4.46,7.928,4.46c4.345,0,7.782-4.279,7.927-4.46
            c0.096-0.122,0.096-0.293,0-0.414C15.783,4.276,12.346,0,8.001,0C3.655,0,0.217,4.276,0.073,4.46
            C-0.024,4.581-0.024,4.752,0.073,4.874 M8.001,1.667c1.654,0,3,1.345,3,3c0,1.654-1.346,3-3,3c-1.654,0-3-1.346-3-3
            C5.001,3.012,6.347,1.667,8.001,1.667"/>
          <path fill="#636EA0" d="M8.001,7c1.286,0,2.333-1.047,2.333-2.333c0-1.287-1.047-2.334-2.333-2.334
            c-1.287,0-2.333,1.047-2.333,2.334C5.668,5.953,6.714,7,8.001,7"/>
        </svg>
        <svg class="star" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
           width="16px" height="16px" viewBox="0 0 16 15.121" enable-background="new 0 0 16 15.121" xml:space="preserve">
          <polygon fill="#636EA0" points="16,5.773 10.469,4.974 8,0 5.53,4.974 0,5.773 4.001,9.65 3.058,15.121 8,12.538 12.941,15.12
            11.998,9.65 "/>
        </svg>
      </div>
    </div>
    <div class="content">
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
          <span class="title">Status:</span>
          <span class="status-label">
            In progress
          </span>
        </p>
      </div>
      <div class="progress-area">
        <p class="title">Progress</p>
        <p class="percentage"><span class="number">77</span>%</p>
      </div>
    </div>
    <div class="progress-bar">
      <div class="filled"></div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      title: 'CAMPR'
    }
  }
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../css/_variables.scss';

  .project-box {
    background: $darkColor;
    padding: 20px;
    color: $lighterColor;
    width: 394px;
    letter-spacing: 1px;
    height: 189px;
    margin: 15px;

    .header {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid $mainColor;
      padding-bottom: 13px;
      height: 36px;

      h2 {
        margin: 2px 0 0 0;
        font-size: 12px;
        text-transform: uppercase;
      }

      .eye {
        margin-right: 5px;
        height: 16px;
      }
    }

    .content {
      display: flex;
      height: 92px;
      justify-content: space-between;
    }
  }

  .info {
    padding-top: 16px;

    p {
      margin: 0 0 3px 0;
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
      background: $middleColor;
      border-radius: 10px;
      text-transform: uppercase;
      padding: 5px 19px;
      margin-left: 5px;
      font-size: 8px;
    }
  }

  .progress-area {
    padding-top: 47px;
    width: 60px;
    letter-spacing: 0.7px;

    .title {
      font-size: 8px;
      text-transform: uppercase;
    }

    .percentage {
      color: $middleColor;
      margin-top: 2px;
    }

    .number {
      color: $lighterColor;
      padding-left: 12px;
      padding-right: 3px;
      font-size: 32px;
      line-height: 32px;
    }
  }

  .progress-bar {
    margin-top: 13px;
    width: 100%;
    height: 8px;
    background: $mainColor;

    .filled {
      width: 77%;
      background: $middleColor;
      height: 100%;
    }
  }
</style>
