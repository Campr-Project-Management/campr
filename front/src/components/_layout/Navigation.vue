<template>
    <header>
        <div class="dropdown menu-toggler">
            <span class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="17px" height="17px" viewBox="0 0 17.805 16.354" enable-background="new 0 0 17.805 16.354" xml:space="preserve">
                    <g>
                        <g>
                            <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="17.805" y1="4.354" x2="2.848" y2="4.354"/>
                        </g>
                        <g>
                            <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="17.805" y1="8.354" x2="2.848" y2="8.354"/>
                        </g>
                        <g>
                            <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="17.805" y1="12.354" x2="2.848" y2="12.354"/>
                        </g>
                        <g>
                            <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="17.805" y1="16.354" x2="2.848" y2="16.354"/>
                        </g>
                    </g>
                </svg>
            </span>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
            </ul>
        </div>
        <p class="user-message">Hi, <span>{{ user.name }}</span></p>
        <img class="user-avatar" :src="user.avatar" :alt="user.name"/>
        <a class="notifications" href="">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
              width="26.438px" height="24.469px" viewBox="0 0 26.438 24.469" enable-background="new 0 0 26.438 24.469" xml:space="preserve">
                <g>
                    <defs>
                        <rect id="SVGID_1_" x="-946.781" y="-565.752" width="1920" height="1156.462"/>
                    </defs>
                    <clipPath id="SVGID_2_">
                        <use xlink:href="#SVGID_1_"  display="none" overflow="visible"/>
                    </clipPath>
                    <line clip-path="url(#SVGID_2_)" fill="none" stroke="#8694BC" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="953.219" y1="-531.752" x2="938.262" y2="-531.752"/>
                    <line clip-path="url(#SVGID_2_)" fill="none" stroke="#8694BC" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="953.219" y1="-527.752" x2="938.262" y2="-527.752"/>
                    <line clip-path="url(#SVGID_2_)" fill="none" stroke="#8694BC" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="953.219" y1="-523.752" x2="938.262" y2="-523.752"/>
                    <line clip-path="url(#SVGID_2_)" fill="none" stroke="#8694BC" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" x1="953.219" y1="-519.752" x2="938.262" y2="-519.752"/>
                    <polyline clip-path="url(#SVGID_2_)" fill="none" stroke="#636EA0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                    677.566,-524.187 676.523,-524.187 672.35,-520.013 672.35,-524.187 669.219,-524.187 669.219,-536.709 689.045,-536.709
                    689.045,-529.926 	"/>
                    <polygon clip-path="url(#SVGID_2_)" fill="none" stroke="#636EA0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                    680.697,-518.97 686.436,-518.97 691.132,-514.796 691.132,-518.97 693.219,-518.97 693.219,-527.317 680.697,-527.317 	"/>
                    <path display="none" clip-path="url(#SVGID_2_)" fill="#5EBCA2" d="M701.219-540.752c0,5.523-4.477,10-10,10s-10-4.477-10-10
                    s4.477-10,10-10S701.219-546.275,701.219-540.752"/>
                </g>
                <polyline fill="none" stroke="#636EA0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                  9.566,13.813 8.523,13.813 4.35,17.987 4.35,13.813 1.219,13.813 1.219,1.291 21.045,1.291 21.045,8.074 "/>
                <polygon fill="none" stroke="#636EA0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="
                  12.697,19.03 18.436,19.03 23.132,23.204 23.132,19.03 25.219,19.03 25.219,10.683 12.697,10.683 "/>
            </svg>
            <span class="notification-balloon">{{ user.notifications }}</span>
        </a>
    </header>
</template>

<script>
export default {
    name: 'navigation',
    props: ['user'],
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
  @import '../../css/_variables';

  svg {
    stroke: $lightColor;
  }

  header {
    height: 80px;
    border-bottom: 1px solid $darkColor;
    padding: 20px 0;
    display: flex;
    flex-direction: row-reverse;
    color: $lightColor;
  }

  .menu-toggler {
    width: 34px;
    cursor: pointer;
    text-align: right;
    padding-top: 10px;
  }

  .user-message {
    text-transform: uppercase;
    margin: 0 30px 0 0;
    line-height: 45px;
    font-size: 12px;
    letter-spacing: 1.2px;
  }

  .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: block;
    margin-right: 20px;
  }

  .notifications {
    position: relative;
    display: block;
    width: 50px;
    margin-right: 7px;
    padding-top: 8px;

    .notification-balloon {
      right: 5px;
      top: -5px;
      font-size: 11px;
    }
  }
</style>
