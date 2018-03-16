<template>
    <div class="module">
        <div class="header flex flex-space-between flex-v-center">
            <div class="flex flex-v-center">
                <svg v-show="id == 'contract'" version="1.1" id="Layer_1" width="23px" v-bind:class="{'inactive': inactive}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 24.1 28.5" style="enable-background:new 0 0 24.1 28.5;" xml:space="preserve">
                    <g id="XMLID_182_">
                    <g>
                    <g id="XMLID_76_">
                    <g id="XMLID_78_">
                    <polyline id="XMLID_81_" class="st0" points="19.1,4.6 21.1,4.6 21.1,25.6 3.1,25.6 3.1,4.6 5.1,4.6 				"/>
                    <path id="XMLID_80_" class="st0" d="M14.1,4.6c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2h-3v3h10v-3H14.1z"/>
                    <polyline id="XMLID_79_" class="st0" points="7.1,6.6 5.1,6.6 5.1,23.6 15.1,23.6 15.1,19.6 19.1,19.6 19.1,6.6 17.1,6.6"/>
                    </g>
                    <line id="XMLID_77_" class="st0" x1="19.1" y1="19.6" x2="15.1" y2="23.6"/>
                    </g>
                    <line id="XMLID_75_" class="st0" x1="8.1" y1="11.6" x2="16.1" y2="11.6"/>
                    <line id="XMLID_74_" class="st0" x1="8.1" y1="13.6" x2="16.1" y2="13.6"/>
                    <line id="XMLID_73_" class="st0" x1="8.1" y1="15.6" x2="16.1" y2="15.6"/>
                    <line id="XMLID_72_" class="st0" x1="8.1" y1="17.6" x2="12.6" y2="17.6"/>
                    </g>
                    </g>
                </svg>

                <svg v-show="id == 'organization'" width="23px" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_82_">
                  <circle id="XMLID_181_" class="st0" cx="13.1" cy="5.2" r="2"/>
                  <circle id="XMLID_180_" class="st0" cx="13.1" cy="19.9" r="2"/>
                  <circle id="XMLID_179_" class="st0" cx="20" cy="19.9" r="2"/>
                  <circle id="XMLID_177_" class="st0" cx="6.1" cy="19.9" r="2"/>
                  <line id="XMLID_176_" class="st0" x1="13.1" y1="7.3" x2="13.1" y2="17.9"/>
                  <path id="XMLID_175_" class="st0" d="M6.1,17.9c0-2.7,1.8-4.9,4.5-4.9h4.5c2.7,0,4.9,2.2,4.9,4.9"/>
                </g>
                </svg>

                <svg v-show="id == 'task_management'" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" width="23px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_88_">
                  <g id="XMLID_89_">
                    <circle id="XMLID_242_" class="st0" cx="11" cy="15" r="2.4"/>
                    <path id="XMLID_241_" class="st0" d="M16,13.9l1.2-0.7l-1.6-2.7l-1.2,0.7c-0.5-0.5-1.2-0.8-1.9-1.1V8.7H9.4v1.4
                      c-0.7,0.2-1.3,0.6-1.9,1.1l-1.2-0.7l-1.6,2.7L6,13.9c-0.1,0.7-0.2,1.4,0,2.1l-1.2,0.7l1.6,2.7l1.2-0.7c0.5,0.5,1.2,0.8,1.9,1.1
                      v1.4h3.2v-1.4c0.7-0.2,1.3-0.6,1.9-1.1l1.2,0.7l1.6-2.7L16,16.1C16.2,15.4,16.2,14.7,16,13.9z"/>
                  </g>
                  <g id="XMLID_90_">
                    <circle id="XMLID_234_" class="st0" cx="19.3" cy="6.7" r="1.2"/>
                    <path id="XMLID_233_" class="st0" d="M22,6.1l0.8-0.5L22,4.3l-0.8,0.5c-0.3-0.3-0.7-0.5-1.1-0.6V3.2h-1.6v0.9
                      c-0.4,0.1-0.8,0.3-1.1,0.6l-0.8-0.5l-0.8,1.4l0.8,0.5c-0.1,0.4-0.1,0.9,0,1.3l-0.8,0.5l0.8,1.4l0.8-0.5c0.3,0.3,0.7,0.5,1.1,0.6
                      v0.9h1.6V9.4c0.4-0.1,0.8-0.3,1.1-0.6L22,9.2l0.8-1.4L22,7.4C22.1,6.9,22.1,6.5,22,6.1z"/>
                  </g>
                </g>
                </svg>

                <svg v-show="id == 'phases_and_milestones'" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" width="23px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_248_">
                  <circle id="XMLID_271_" class="st0" cx="6" cy="14.2" r="2"/>
                  <circle id="XMLID_269_" class="st0" cx="13" cy="14.2" r="2"/>
                  <circle id="XMLID_267_" class="st0" cx="20" cy="14.2" r="2"/>
                  <line id="XMLID_272_" class="st0" x1="6" y1="12.3" x2="6" y2="5.2"/>
                  <line id="XMLID_273_" class="st0" x1="13" y1="16.2" x2="13" y2="23.3"/>
                  <line id="XMLID_274_" class="st0" x1="20" y1="12.3" x2="20" y2="5.2"/>
                </g>
                </svg>

                <svg v-show="id == 'wbs'" width="23px" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_734_">
                  <line id="XMLID_739_" class="st0" x1="12.4" y1="8" x2="12.4" y2="18.2"/>
                  <rect id="XMLID_738_" x="6.9" y="4.9" class="st0" width="11.7" height="3.1"/>
                  <rect id="XMLID_737_" x="10.8" y="18.2" class="st0" width="3.9" height="3.9"/>
                  <rect id="XMLID_736_" x="3.8" y="18.2" class="st0" width="3.9" height="3.9"/>
                  <rect id="XMLID_735_" x="17.8" y="18.2" class="st0" width="3.9" height="3.9"/>
                  <polyline id="XMLID_733_" class="st0" points="19.4,18.2 19.4,13.5 6.1,13.5 6.1,18.2   "/>
                </g>
                </svg>

                <svg v-show="id == 'internal_costs'" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" width="23px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_93_">
                  <circle id="XMLID_381_" class="st0" cx="17.7" cy="14.9" r="0.4"/>
                  <line id="XMLID_375_" class="st0" x1="7.9" y1="9.8" x2="8.7" y2="9.8"/>
                  <line id="XMLID_374_" class="st0" x1="10.2" y1="9.8" x2="11" y2="9.8"/>
                  <line id="XMLID_373_" class="st0" x1="12.6" y1="9.8" x2="13.4" y2="9.8"/>
                  <line id="XMLID_372_" class="st0" x1="14.9" y1="9.8" x2="15.7" y2="9.8"/>
                  <line id="XMLID_363_" class="st0" x1="17.3" y1="9.8" x2="18.1" y2="9.8"/>
                  <line id="XMLID_346_" class="st0" x1="7.9" y1="20" x2="8.7" y2="20"/>
                  <line id="XMLID_345_" class="st0" x1="10.2" y1="20" x2="11" y2="20"/>
                  <line id="XMLID_344_" class="st0" x1="12.6" y1="20" x2="13.4" y2="20"/>
                  <line id="XMLID_343_" class="st0" x1="14.9" y1="20" x2="15.7" y2="20"/>
                  <line id="XMLID_342_" class="st0" x1="17.3" y1="20" x2="18.1" y2="20"/>
                  <path id="XMLID_341_" class="st0" d="M22.8,15.3c0,0.9-0.7,1.6-1.6,1.6h-3.9c-0.9,0-1.6-0.7-1.6-1.6v-0.8c0-0.9,0.7-1.6,1.6-1.6
                    h3.9c0.9,0,1.6,0.7,1.6,1.6V15.3z"/>
                  <path id="XMLID_340_" class="st0" d="M20.4,12.9c0,0,0-1.1,0-2.3s-1.1-2.3-2.3-2.3h-11"/>
                  <path id="XMLID_337_" class="st0" d="M16.5,6.7H7.1c-1,0-1,1.6,0,1.6"/>
                  <path id="XMLID_334_" class="st0" d="M20.4,16.8v2.3c0,1.3-1.1,2.3-2.3,2.3h-11c-1.3,0-2.3-1.1-2.3-2.3V7.4c0-1.3,1.1-2.3,2.3-2.3
                    h9.4c1.3,0,2.3,0.7,2.3,1.6c0,0.9,0,1.6,0,1.6"/>
                </g>
                </svg>

                <svg v-show="id == 'external_costs'" v-bind:class="{'inactive': inactive}" width="23px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_83_">
                  <polygon id="XMLID_614_" class="st0" points="13.1,12.3 3.8,8.4 3.8,18.5 13.1,22.4 	"/>
                  <polygon id="XMLID_403_" class="st0" points="13.1,12.3 21.8,8.4 21.8,18.5 13.1,22.4 	"/>
                  <polyline id="XMLID_402_" class="st0" points="3.8,8.4 12.8,4.4 21.8,8.4 	"/>
                  <line id="XMLID_301_" class="st0" x1="9" y1="6.1" x2="17.7" y2="10.2"/>
                  <polygon id="XMLID_300_" class="st0" points="10.8,14.2 6.1,11.9 6.1,15.8 10.8,18.1 	"/>
                </g>
                </svg>

                <svg v-show="id == 'risks_and_opportunities'" v-bind:class="{'inactive': inactive}" width="23px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_87_">
                  <polyline id="XMLID_437_" class="st0" points="21.8,22.3 3.8,22.3 3.8,4.3 	"/>
                  <circle id="XMLID_435_" class="st0" cx="9.2" cy="16.8" r="1.2"/>
                  <line id="XMLID_431_" class="st0" x1="16.6" y1="10.4" x2="19.8" y2="6.4"/>
                  <line id="XMLID_430_" class="st0" x1="11.1" y1="9.8" x2="14.7" y2="11.1"/>
                  <line id="XMLID_423_" class="st0" x1="3.8" y1="13.1" x2="9" y2="10"/>
                  <line id="XMLID_417_" class="st0" x1="16.1" y1="16.3" x2="19.6" y2="14.3"/>
                  <line id="XMLID_416_" class="st0" x1="10.4" y1="16.8" x2="13.9" y2="16.8"/>
                  <line id="XMLID_414_" class="st0" x1="3.8" y1="20.4" x2="8.2" y2="17.4"/>
                  <circle id="XMLID_412_" class="st0" cx="10" cy="9.4" r="1.2"/>
                  <circle id="XMLID_411_" class="st0" cx="15.9" cy="11.4" r="1.2"/>
                  <circle id="XMLID_410_" class="st0" cx="20.6" cy="5.5" r="1.2"/>
                  <circle id="XMLID_270_" class="st0" cx="20.6" cy="13.7" r="1.2"/>
                  <circle id="XMLID_268_" class="st0" cx="15.1" cy="16.8" r="1.2"/>
                </g>
                </svg>

                <svg v-show="id == 'meetings'" v-bind:class="{'inactive': inactive}" width="23px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_651_">
                  <g id="XMLID_660_">
                    <path id="XMLID_661_" class="st0" d="M4,15.9v2.4c0,0.8,0.2,1.4,0.9,1.4v4.7h2.8v-4.7c0.8,0,0.9-0.6,0.9-1.4v-2.4H4z"/>
                    <circle id="XMLID_659_" class="st0" cx="6.4" cy="12.6" r="1.4"/>
                  </g>
                  <g id="XMLID_657_">
                    <path id="XMLID_658_" class="st0" d="M17.3,15.9v2.4c0,0.8,0.2,1.4,0.9,1.4v4.7h2.8v-4.7c0.8,0,0.9-0.6,0.9-1.4v-2.4H17.3z"/>
                    <circle id="XMLID_656_" class="st0" cx="19.6" cy="12.6" r="1.4"/>
                  </g>
                  <g id="XMLID_654_">
                    <polygon id="XMLID_653_" class="st0" points="7.8,8.4 10.6,8.4 10.6,10.7 13,8.4 18.2,8.4 18.2,2.7 7.8,2.7        "/>
                  </g>
                  <line id="XMLID_652_" class="st0" x1="9.7" y1="6.5" x2="14.4" y2="6.5"/>
                  <line id="XMLID_650_" class="st0" x1="9.7" y1="4.6" x2="16.3" y2="4.6"/>
                </g>
                </svg>

                <svg v-show="id == 'todos'" width="23px" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_665_">
                  <g id="XMLID_109_">
                    <g id="XMLID_110_">
                      <polygon id="XMLID_671_" class="st0" points="15.4,14.4 15.4,21 4,21 4,3 22,3 22,14.4          "/>
                      <line id="XMLID_669_" class="st0" x1="22" y1="14.4" x2="15.4" y2="21"/>
                    </g>
                    <polyline id="XMLID_668_" class="st0" points="4.9,22.9 21.1,22.9 21.1,18.6      "/>
                    <line id="XMLID_667_" class="st0" x1="5.9" y1="24.8" x2="20.1" y2="24.8"/>
                  </g>
                  <polyline id="XMLID_666_" class="st0" points="17.2,7.7 11,14.8 8.7,12.5   "/>
                </g>
                </svg>

                <svg v-show="id == 'infos'" width="23px" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_108_">
                  <g id="XMLID_111_">
                    <g id="XMLID_112_">
                      <polygon id="XMLID_690_" class="st0" points="16.6,17.5 16.6,22.7 3.8,22.7 3.8,4.7 21.8,4.7 21.8,17.5          "/>
                      <line id="XMLID_689_" class="st0" x1="21.8" y1="17.5" x2="16.6" y2="22.7"/>
                    </g>
                    <line id="XMLID_679_" class="st0" x1="6.3" y1="3" x2="6.3" y2="6.8"/>
                    <line id="XMLID_678_" class="st0" x1="10.6" y1="3" x2="10.6" y2="6.8"/>
                    <line id="XMLID_677_" class="st0" x1="14.9" y1="3" x2="14.9" y2="6.8"/>
                    <line id="XMLID_676_" class="st0" x1="19.2" y1="3" x2="19.2" y2="6.8"/>
                  </g>
                  <path id="XMLID_672_" class="st0" d="M6.8,13.2l0.9-0.9c0.5-0.5,1.2-0.5,1.7,0c0.5,0.5,1.2,0.5,1.7,0c0.5-0.5,1.2-0.5,1.7,0
                    c0.5,0.5,1.2,0.5,1.7,0s1.2-0.5,1.7,0c0.5,0.5,1.2,0.5,1.7,0l0.9-0.9"/>
                  <path id="XMLID_640_" class="st0" d="M6.8,17.5l0.9-0.9c0.5-0.5,1.2-0.5,1.7,0c0.5,0.5,1.2,0.5,1.7,0"/>
                </g>
                </svg>

                <svg v-show="id == 'status_report'" v-bind:class="{'inactive': inactive}" width="23px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_101_">
                  <g id="XMLID_106_">
                    <line id="XMLID_632_" class="st0" x1="3.8" y1="22.2" x2="21.8" y2="22.2"/>
                    <polyline id="XMLID_630_" class="st0" points="6.9,22.2 6.9,18.3 4.5,18.3 4.5,22.2 		"/>
                    <polyline id="XMLID_629_" class="st0" points="11.6,22.2 11.6,14.4 9.2,14.4 9.2,22.2 		"/>
                    <polyline id="XMLID_626_" class="st0" points="16.3,22.2 16.3,15.9 13.9,15.9 13.9,22.2 		"/>
                    <polyline id="XMLID_625_" class="st0" points="21,22.2 21,10.4 18.6,10.4 18.6,22.2 		"/>
                  </g>
                  <g id="XMLID_107_">
                    <polyline id="XMLID_283_" class="st0" points="20.2,5 15.1,10.4 10.4,8.9 5.7,12.8 		"/>
                    <polyline id="XMLID_263_" class="st0" points="17.1,5 20.2,5 20.2,8.1 		"/>
                  </g>
                </g>
                </svg>               

                <svg v-show="id == 'close_down_project'" v-bind:class="{'inactive': inactive}" width="23px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_113_">
                  <polyline id="XMLID_704_" class="st0" points="16.7,11.2 10.8,16.7 8.8,14.8 	"/>
                  <circle id="XMLID_703_" class="st0" cx="12.8" cy="14" r="9"/>
                </g>
                </svg>

                <svg v-show="id == 'rasci_matrix'" width="23px" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_114_">
                  <rect id="XMLID_752_" x="10.3" y="4.5" class="st0" width="4.9" height="4.9"/>
                  <rect id="XMLID_751_" x="3.8" y="4.5" class="st0" width="4.9" height="4.9"/>
                  <rect id="XMLID_750_" x="16.8" y="4.5" class="st0" width="4.9" height="4.9"/>
                  <rect id="XMLID_749_" x="10.3" y="11" class="st0" width="4.9" height="4.9"/>
                  <rect id="XMLID_748_" x="3.8" y="11" class="st0" width="4.9" height="4.9"/>
                  <rect id="XMLID_747_" x="16.8" y="11" class="st0" width="4.9" height="4.9"/>
                  <rect id="XMLID_746_" x="10.3" y="17.6" class="st0" width="4.9" height="4.9"/>
                  <rect id="XMLID_745_" x="3.8" y="17.6" class="st0" width="4.9" height="4.9"/>
                  <rect id="XMLID_744_" x="16.8" y="17.6" class="st0" width="4.9" height="4.9"/>
                </g>
                </svg>

                <svg v-show="id == 'gantt_chart'" width="23px" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_115_">
                  <rect id="XMLID_765_" x="5.8" y="4.5" class="st0" width="10.2" height="2.3"/>
                  <rect id="XMLID_764_" x="13.6" y="13.1" class="st0" width="10.2" height="2.3"/>
                  <rect id="XMLID_763_" x="5.8" y="20.1" class="st0" width="10.2" height="2.3"/>
                  <path id="XMLID_762_" class="st0" d="M11.2,6.8v2.7c0,0.6,0.5,1.2,1.2,1.2h2.3c0.6,0,1.2,0.5,1.2,1.2v1.2"/>
                  <path id="XMLID_761_" class="st0" d="M19.8,15.4v4.3c0,0.6-0.5,1.2-1.2,1.2h-2.7"/>
                </g>
                </svg>

                <svg v-show="id == 'decisions'" width="23px" v-bind:class="{'inactive': inactive}" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 26.2 27.3" style="enable-background:new 0 0 26.2 27.3;" xml:space="preserve">
                <g id="XMLID_162_">
                  <circle id="XMLID_812_" class="st0" cx="14.9" cy="12.8" r="3.1"/>
                  <circle id="XMLID_811_" class="st0" cx="5.9" cy="8.9" r="1.2"/>
                  <circle id="XMLID_810_" class="st0" cx="21.6" cy="6.2" r="1.2"/>
                  <circle id="XMLID_809_" class="st0" cx="5.9" cy="21.8" r="1.2"/>
                  <circle id="XMLID_808_" class="st0" cx="14.9" cy="21.8" r="1.2"/>
                  <line id="XMLID_807_" class="st0" x1="6.8" y1="21" x2="12.7" y2="15"/>
                  <line id="XMLID_806_" class="st0" x1="17.1" y1="10.6" x2="20.7" y2="7"/>
                  <line id="XMLID_805_" class="st0" x1="7" y1="9.4" x2="12" y2="11.6"/>
                  <line id="XMLID_804_" class="st0" x1="20.5" y1="15.6" x2="17.6" y2="14.4"/>
                  <line id="XMLID_803_" class="st0" x1="14.9" y1="20.6" x2="14.9" y2="15.9"/>
                  <circle id="XMLID_802_" class="st0" cx="21.6" cy="15.9" r="1.2"/>
                </g>
                </svg>

                <span v-bind:class="{'inactive': inactive}" class="title">{{ title }}</span>

                <svg v-bind:class="{'inactive': inactive}" class="toggle-content" v-show="contentVisible" @click="toggleContent()" version="1.1" id="Layer_1" width="21px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 17.8 17.4" style="enable-background:new 0 0 17.8 17.4;" xml:space="preserve">
                    <polyline id="XMLID_228_" class="st0" points="16.2,12.9 9.1,4.9 2,12.9 "/>
                </svg>

                <svg v-bind:class="{'inactive': inactive}" class="toggle-content" v-show="!contentVisible" @click="toggleContent()" version="1.1" id="Layer_1" width="21px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   viewBox="0 0 17.7 16" style="enable-background:new 0 0 17.7 16;" xml:space="preserve">
                    <polyline id="XMLID_222_" class="st0" points="1.9,4.3 9,12.3 16.1,4.3 "/>
                </svg>
            </div>
            <div class="checkbox-input clearfix" v-bind:class="{'inactive': inactive}">
              <input :id="id" type="checkbox" name="" value="1" :checked="!inactive" v-on:click="updateValue">
              <label :for="id"></label>
            </div>
        </div>
        <div class="content" v-show="contentVisible" v-bind:class="{'inactive': inactive}">
          <div class="hr top"></div>
          {{ description }}
        </div>
        <div class="hr bottom"></div>
    </div>
</template>

<script>

export default {
    props: ['title', 'id', 'inactive', 'description'],
    methods: {
        toggleContent() {
            this.contentVisible = !this.contentVisible;
        },
        updateValue: function(e) {
            this.$emit('input', e.target.checked);
        },
    },
    data: function() {
        return {
            project: 0,
            contentVisible: false,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_common';
    @import '../../css/project-create';

    .st0 {
        fill:none;
        stroke: $secondColor;
        stroke-linecap:round;
        stroke-linejoin:round;
        stroke-miterlimit:10;
    }

    .module {
        overflow: hidden;

        .hr.top {
            margin-bottom: 12px;
            width: 38px;
            background: $secondColor;
        }

        .hr.bottom {
            margin: 26px 0 30px 40px;
            background: $darkColor;
        }

        .inactive {
            color: $middleColor;

            .st0 {
                stroke: $middleColor;
            }

            .hr.top {
                background-color: $middleColor;
            }
        }
    }

    .title {
        font-size: 17px;
        text-transform: uppercase;
        margin-left: 15px;
        letter-spacing: 2.5px;
        color: $secondColor;

        &.inactive {
          color: $middleColor;
        }
    }

    .content {
        padding: 8px 10% 0 38px;
        font-weight: 300;
        font-size: 1.16em;
        line-height: 1.5em;
    }

    .toggle-content {
        cursor: pointer;
        margin-left: 13px;
    }
</style>
