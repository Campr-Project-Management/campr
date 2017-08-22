<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="rasci-matrix page-section">
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <h1>{{ translateText('message.rasci_matrix') }}</h1>
                            <div class="rasci-legend flex">
                                <div class="rasci-legend-item"><responsibillity-select responsibillity="responsible disabled"></responsibillity-select> <span>Responsible</span></div>
                                <div class="rasci-legend-item"><responsibillity-select responsibillity="accountable disabled"></responsibillity-select> <span>Accountable</span></div>
                                <div class="rasci-legend-item"><responsibillity-select responsibillity="support disabled"></responsibillity-select> <span>Support</span></div>
                                <div class="rasci-legend-item"><responsibillity-select responsibillity="consulted disabled"></responsibillity-select> <span>Consulted</span></div>
                                <div class="rasci-legend-item"><responsibillity-select responsibillity="informed disabled"></responsibillity-select> <span>Informed</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>
        
                <!-- /// RASCI /// -->
                <table class="table table-striped table-responsive rasci-table">
                    <thead>
                        <tr>
                            <th class="task-number">{{ translateText('table_header_cell.task_number') }}</th>
                            <th>{{ translateText('table_header_cell.task_title') }}</th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Diana Watkinson'" style="background-image:url(http://dev.campr.biz/uploads/avatars/10.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Jude Law'" style="background-image:url(http://dev.campr.biz/uploads/avatars/20.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Colin Crown'" style="background-image:url(http://dev.campr.biz/uploads/avatars/40.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'David Gilmour'" style="background-image:url(http://dev.campr.biz/uploads/avatars/41.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Betty Ice'" style="background-image:url(http://dev.campr.biz/uploads/avatars/44.jpg)"></div>
                            </th> 
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Mobuttu Sesese Ko'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Ariana Notsogrande'" style="background-image:url(http://dev.campr.biz/uploads/avatars/60.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Dull Face'" style="background-image:url(http://dev.campr.biz/uploads/avatars/61.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Victoria Frontcamp'" style="background-image:url(http://dev.campr.biz/uploads/avatars/64.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Diana Watkinson'" style="background-image:url(http://dev.campr.biz/uploads/avatars/10.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Jude Law'" style="background-image:url(http://dev.campr.biz/uploads/avatars/20.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Colin Crown'" style="background-image:url(http://dev.campr.biz/uploads/avatars/40.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'David Gilmour'" style="background-image:url(http://dev.campr.biz/uploads/avatars/41.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Betty Ice'" style="background-image:url(http://dev.campr.biz/uploads/avatars/44.jpg)"></div>
                            </th> 
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Mobuttu Sesese Ko'" style="background-image:url(http://dev.campr.biz/uploads/avatars/49.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Ariana Notsogrande'" style="background-image:url(http://dev.campr.biz/uploads/avatars/60.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Dull Face'" style="background-image:url(http://dev.campr.biz/uploads/avatars/61.jpg)"></div>
                            </th>
                            <th class="rasci-cell">
                                <div class="avatar" v-tooltip.top-center="'Victoria Frontcamp'" style="background-image:url(http://dev.campr.biz/uploads/avatars/64.jpg)"></div>
                            </th> 
                            <th class="rasci-cell last-cell"></th>                                  
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="rasci-phase">
                            <td colspan="21">Phase 4</td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#228</span></td>
                            <td>Lorem ipsum dolor sit amet</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#227</span></td>
                            <td>Consectetur adipiscing elit</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#226</span></td>
                            <td>Proin eu magna quis est fringilla ultricies</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#225</span></td>
                            <td>Curabitur eget scelerisque ligula</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#224</span></td>
                            <td>Morbi gravida neque in orci pulvinar facilisis</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#223</span></td>
                            <td>Pellentesque ac magna eu nunc ullamcorper cursus</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable active"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity=""></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#222</span></td>
                            <td>Donec id nibh lacinia, tincidunt risus eu, commodo odio</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#221</span></td>
                            <td>Sed eu lorem nisl</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#220</span></td>
                            <td>Nam finibus ipsum a euismod rhoncus</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#219</span></td>
                            <td>Lorem ipsum dolor sit amet</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr class="rasci-phase">
                            <td colspan="21">Phase 3</td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#218</span></td>
                            <td>Sed pretium, nulla sed lacinia condimentum, ligula augue blandit dolor, quis porttitor urna arcu sit amet ante</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#217</span></td>
                            <td>Nam orci leo, dapibus quis nisi at, tempor ultricies tellus</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#216</span></td>
                            <td>Praesent gravida accumsan bibendum</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#215</span></td>
                            <td>Praesent non cursus nulla</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr class="rasci-phase">
                            <td colspan="21">Phase 2</td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#214</span></td>
                            <td>Nunc fringilla massa vitae libero varius, quis semper diam imperdiet</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#213</span></td>
                            <td>Nulla facilisi</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#212</span></td>
                            <td>Quisque pharetra sem ac orci blandit, sit amet condimentum magna rhoncus</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#211</span></td>
                            <td>Aliquam a diam eu est ultricies porta quis non ipsum</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity=""></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#210</span></td>
                            <td>Nullam non feugiat nisl</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#209</span></td>
                            <td>In in sapien mattis, rhoncus diam venenatis, tincidunt eros</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#208</span></td>
                            <td>Morbi rhoncus metus est, id rhoncus metus ullamcorper eget</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr class="rasci-phase">
                            <td colspan="21">Phase 1</td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#207</span></td>
                            <td>Cras efficitur dui ligula</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#206</span></td>
                            <td>Fusce a nunc vitae nunc bibendum dignissim</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#205</span></td>
                            <td>Interdum et malesuada fames ac ante ipsum primis in faucibus</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#204</span></td>
                            <td>Integer consequat leo vel blandit lacinia</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#203</span></td>
                            <td>Duis sagittis mi dolor, nec pharetra orci porta nec</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity=""></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#202</span></td>
                            <td>Vestibulum bibendum molestie arcu</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                        <tr>
                            <td class="task-number"><span class="light-color">#201</span></td>
                            <td>Praesent vel lorem elit</td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="informed"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="consulted"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="support"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="accountable"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="responsible"></responsibillity-select></td>
                            <td class="rasci-cell"><responsibillity-select responsibillity="default"></responsibillity-select></td>
                            <td class="rasci-cell last-cell"></td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="meetingAgendas && meetingAgendas.items" class="flex flex-direction-reverse flex-v-center">
                    <div class="pagination flex flex-center" v-if="meetingAgendas && meetingAgendas.totalItems > 0">
                        <span v-if="agendasPages > 1" v-for="page in agendasPages" v-bind:class="{'active': page == agendasActivePage}" @click="changeAgendasPage(page)">{{ page }}</span>
                    </div>
                    <div>
                        <span class="pagination-info">{{ translateText('message.displaying') }} {{ meetingAgendas.items.length }} {{ translateText('message.results_out_of') }} {{ meetingAgendas.totalItems }}</span>
                    </div>
                </div>
                <!-- /// End RASCI /// -->
            </div>
        </div>
    </div>
</template>

<script>
import ResponsibillitySelect from '../_common/_rasci-components/ResponsibillitySelect';
export default {
    components: {
        ResponsibillitySelect,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/_mixins';
    @import '../../css/_variables';

    .rasci-legend {
        .rasci-legend-item {
            display: flex;
            align-items: center;
            margin-right: 30px;

            span {
                text-transform: uppercase;
                letter-spacing: 0.1em;
                margin-left: 10px;
                font-size: 10px;
            }
        }
    }

    .rasci-table {
        overflow: hidden;
        position: relative;
        z-index: 1;
        margin-bottom: 30px;

        tr,
        th,
        td {
            position: relative;
        }

        th.rasci-cell,
        td.rasci-cell {
            vertical-align: middle;

            .rasci-select,
            .avatar {
                position: relative;
                z-index: 1;
            }

            .rasci-select {
                &.active {
                    z-index: 10;
                }
            }

            &:hover {               
                .rasci-select,
                .avatar {
                    z-index: 2;
                }
            }
        }

        thead,
        tbody {
            td.rasci-cell,
            th.rasci-cell {
                &:after {
                    content: '';
                    height: 1000000px;
                    left: 0;
                    position: absolute;
                    top: -50000px;
                    width: 100%;
                    z-index: 0;
                    background-color: rgba($lighterColor,.05);
                    @include opacity(0);
                }

                &:hover,
                &:focus {
                    &:after {
                        @include opacity(1);
                    }
                }

                &.last-cell {
                    pointer-events: none;
                }
            }
        }

        tbody { 
            tr:not(.rasci-phase) {
                &:hover {
                    background-color: rgba($lighterColor,.05);
                }
            }
        }
    }

    .task-number {
        width: 5%;
    }

    .rasci-cell {
        width: 30px;
        padding: 4px 0 0;
        text-align: center;

        &:last-child {
            padding-right: 30px
        }
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
    }
</style>
