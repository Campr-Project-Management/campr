<template>
    <div class="project-meetings page-section">
        <div class="header flex flex-space-between">
            <h1>{{ translateText('message.project_meetings') }}</h1>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-meetings-create-meeting'}" class="btn-rounded btn-auto second-bg">{{ translateText('message.create_new_meeting') }}</router-link>
            </div>
        </div>

        <div class="flex flex-direction-reverse">
            <div class="full-filters">
                <meetings-filters></meetings-filters>
            </div>
        </div>

        <div class="meetings-list">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>{{ translateText('table_header_cell.event') }}</th>
                                <th>{{ translateText('table_header_cell.category') }}</th>
                                <th>{{ translateText('table_header_cell.date') }}</th>
                                <th>{{ translateText('table_header_cell.schedule') }}</th>
                                <th>{{ translateText('table_header_cell.duration') }}</th>
                                <th>{{ translateText('table_header_cell.participants') }}</th>
                                <th>{{ translateText('table_header_cell.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TP Meeting</td>
                                <td>Production</td>
                                <td>20.03.2017</td>
                                <td>11:00 - 11:45</td>
                                <td>45 min</td>
                                <td>
                                    <div class="avatars collapse in" id="tp-meeting-20032017-1">
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_meeting')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.send_notifications')"><notification-icon fill="second-fill"></notification-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.reschedule_meeting')"><reschedule-icon fill="second-fill"></reschedule-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_meeting')"><delete-icon fill="danger-fill"></delete-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr>
                                <td>EK Meeting</td>
                                <td>Production</td>
                                <td>15.03.2017</td>
                                <td>15:30 - 15:55</td>
                                <td>25 min</td>
                                <td>
                                    <div class="avatars collapse in" id="ek-meeting-15032017-1">
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/60.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/64.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <button type="button" data-toggle="collapse" data-target="#ek-meeting-15032017-2" class="two-state collapsed"><span class="more">{{ translateText('message.more') }} +</span><span class="less">{{ translateText('message.less') }} -</span></button>
                                        </div>
                                    </div>
                                    <div class="avatars collapse" id="ek-meeting-15032017-2">                                        
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/60.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                            <button type="button" data-toggle="collapse" data-target="#ek-meeting-15032017-3" class="two-state collapsed"><span class="more">{{ translateText('message.more') }} +</span><span class="less">{{ translateText('message.less') }} -</span></button>
                                        </div>
                                    </div>
                                    <div class="avatars collapse" id="ek-meeting-15032017-3">                                        
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_meeting')"><edit-icon fill="second-fill"></edit-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.send_notifications')"><notification-icon fill="second-fill"></notification-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.reschedule_meeting')"><reschedule-icon fill="second-fill"></reschedule-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr class="inactive">
                                <td>TP Meeting</td>
                                <td>Logistics</td>
                                <td>20.02.2017</td>
                                <td>11:00 - 11:45</td>
                                <td>45 min</td>
                                <td>
                                    <div class="avatars collapse in" id="tp-meeting-20022017">
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/64.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr class="inactive">
                                <td>Anlagenverwertung BTF</td>
                                <td>Quality Management</td>
                                <td>20.02.2017</td>
                                <td>09:30 - 09:45</td>
                                <td>15 min</td>
                                <td>
                                    <div class="avatars collapse in" id="anlagenverwertung-btf-20022017-1">                                        
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/60.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                            <button type="button" data-toggle="collapse" data-target="#anlagenverwertung-btf-20022017-2" class="two-state collapsed"><span class="more">{{ translateText('message.more') }} +</span><span class="less">{{ translateText('message.less') }} -</span></button>
                                        </div>
                                    </div>
                                    <div class="avatars collapse" id="anlagenverwertung-btf-20022017-2">                                        
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr class="inactive">
                                <td>Gebäudeplanung</td>
                                <td>Maintenance</td>
                                <td>18.02.2017</td>
                                <td>15:00 - 17:00</td>
                                <td>120 min</td>
                                <td>
                                    <div class="avatars collapse in" id="gebäudeplanung-20022017">
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr class="inactive">
                                <td>TP Meeting</td>
                                <td>Logistics</td>
                                <td>20.02.2017</td>
                                <td>11:00 - 11:45</td>
                                <td>45 min</td>
                                <td>
                                    <div class="avatars collapse in" id="tp-meeting-20022017">
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/64.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr class="inactive">
                                <td>Anlagenverwertung BTF</td>
                                <td>Quality Management</td>
                                <td>17.02.2017</td>
                                <td>09:30 - 09:45</td>
                                <td>15 min</td>
                                <td>
                                    <div class="avatars collapse in" id="anlagenverwertung-btf-17022017-1">                                        
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/60.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                            <button type="button" data-toggle="collapse" data-target="#anlagenverwertung-btf-17022017-2" class="two-state collapsed"><span class="more">{{ translateText('message.more') }} +</span><span class="less">{{ translateText('message.less') }} -</span></button>
                                        </div>
                                    </div>
                                    <div class="avatars collapse" id="anlagenverwertung-btf-17022017-2">                                        
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr class="inactive">
                                <td>Gebäudeplanung</td>
                                <td>Maintenance</td>
                                <td>16.02.2017</td>
                                <td>15:00 - 17:00</td>
                                <td>120 min</td>
                                <td>
                                    <div class="avatars collapse in" id="gebäudeplanung-20022017">
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr class="inactive">
                                <td>TP Meeting</td>
                                <td>Logistics</td>
                                <td>14.02.2017</td>
                                <td>11:00 - 11:45</td>
                                <td>45 min</td>
                                <td>
                                    <div class="avatars collapse in" id="tp-meeting-14022017">
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/64.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                    </div>
                                </td>                                
                            </tr>

                            <tr class="inactive">
                                <td>TP Meeting</td>
                                <td>Logistics</td>
                                <td>10.02.2017</td>
                                <td>11:00 - 11:45</td>
                                <td>45 min</td>
                                <td>
                                    <div class="avatars collapse in" id="tp-meeting-10022017">
                                        <div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/64.jpg)' }"></div>
                                            <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-right">
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.view_meeting')"><view-icon fill="second-fill"></view-icon></a>
                                        <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.print_meeting')"><print-icon fill="second-fill"></print-icon></a>
                                    </div>
                                </td>                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vue-scrollbar>

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination">
                    <span class="active">1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                </div>
                <div>
                    <span class="pagination-info">{{ translateText('message.displaying') }} 10 {{ translateText('message.results_out_of') }} 43</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MeetingsFilters from '../_common/_meetings-components/MeetingsFilters';
import VueScrollbar from 'vue2-scrollbar';
import ViewIcon from '../_common/_icons/ViewIcon';
import EditIcon from '../_common/_icons/EditIcon';
import PrintIcon from '../_common/_icons/PrintIcon';
import NotificationIcon from '../_common/_icons/NotificationIcon';
import RescheduleIcon from '../_common/_icons/RescheduleIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';

export default {
    components: {
        MeetingsFilters,
        VueScrollbar,
        ViewIcon,
        EditIcon,
        PrintIcon,
        NotificationIcon,
        RescheduleIcon,
        DeleteIcon,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data: function() {
        return {
            pages: 4,
            page: 1,
            activePage: 1,
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style scoped lang="scss">
    @import '../../css/_variables';
    @import '../../css/_mixins';

    .full-filters {
        margin: 20px 0;
    }

    .meetings-list {
        overflow: hidden;
    }

    .actions {
        margin: 30px 0;
    }

    .table-wrapper {
        width: 100%;
        padding-bottom: 40px;
    }  

    .avatars {
        > div {
            height: 34px;
            padding: 2px 0;
        }
    }

    .avatar {
        width: 30px;
        height: 30px;
        @include border-radius(50%);
        background-size: cover;
        display: inline-block;
        margin-right: 5px;

        &:last-child {
            margin-right: 0;
        }
    }  

    .two-state {
        position: relative;
        top: -12px;        
        background-color: transparent;
        border: none;
        color: $secondColor;
        padding: 5px 0;

        &:hover {
            color: $secondDarkColor;
        }
    }
</style>
