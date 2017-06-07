<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="header flex-v-center">
                        <div>
                            <router-link :to="{name: 'project-meetings'}" class="small-link">
                                <i class="fa fa-angle-left"></i>
                                {{ translateText('message.back_to_meetings') }}
                            </router-link>
                            <h1>TP Meeting</h1>
                            <h3 class="category"><b>Logistics</b></h3>
                            <h4>{{ translateText('message.starting_on') }} <b>Monday</b>, <b>20.03.2017</b> {{ translateText('message.from') }} <b>11:00</b> {{ translateText('message.to') }} <b>11:45</b> | {{ translateText('message.duration') }}: <b>45 min</b></h4>
                            <a ref="#" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.reschedule') }} <reschedule-icon></reschedule-icon></a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
                </div>
                
        
                <!-- /// Meeting Location /// -->
                <h3>{{ translateText('message.location') }}</h3>
                <p>Trisoft HQ, Dobrogeanu Gherea Street, No. 9, Suite 4, 1st Floor, Brasov, BV, Romania</p>
                <!-- /// End Meeting Location /// -->
        
                <hr class="double">
                
                <!-- /// Meeting Objectives /// -->
                <h3>{{ translateText('message.objectives') }}</h3>
                <ul class="action-list">
                    <li>
                        <div class="list-item-description">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </div>
                        <div class="list-item-actions">
                            <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_objective')"><edit-icon fill="second-fill"></edit-icon></a>
                            <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_objective')"><delete-icon fill="danger-fill"></delete-icon></a>
                        </div>
                    </li>
                    <li>
                        <div class="list-item-description">
                            Duis at dolor sollicitudin, interdum nibh quis, faucibus justo.
                        </div>
                        <div class="list-item-actions">
                            <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_objective')"><edit-icon fill="second-fill"></edit-icon></a>
                            <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_objective')"><delete-icon fill="danger-fill"></delete-icon></a>
                        </div>
                    </li>
                    <li>
                        <div class="list-item-description">
                            Suspendisse sed nisi id mi aliquam finibus ac sem. Suspendisse in massa in ligula suscipit vulputate. Sed finibus massa nec est rutrum malesuada a et eros. Cras volutpat leo eu lorem viverra ornare.
                        </div>
                        <div class="list-item-actions">
                            <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_objective')"><edit-icon fill="second-fill"></edit-icon></a>
                            <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_objective')"><delete-icon fill="danger-fill"></delete-icon></a>
                        </div>
                    </li>
                </ul>
                <!-- /// End Meeting Objectives /// -->
        
                <hr class="double">
        
                <!-- /// Meeting Agenda /// -->
                <h3>{{ translateText('message.agenda') }}</h3>
                <div class="overflow-hidden">
                    <vue-scrollbar class="table-wrapper">
                        <div class="scroll-wrapper">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>{{ translateText('table_header_cell.topic') }}</th>
                                        <th>{{ translateText('table_header_cell.responsible') }}</th>
                                        <th>{{ translateText('table_header_cell.start') }}</th>
                                        <th>{{ translateText('table_header_cell.finish') }}</th>
                                        <th>{{ translateText('table_header_cell.duration') }}</th>
                                        <th>{{ translateText('table_header_cell.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="topic">Cras condimentum</td>
                                        <td>
                                            <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                <div>
                                                    <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>11:00</td>
                                        <td>11:15</td>
                                        <td>15 min</td>
                                        <td>
                                            <div class="text-right">
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_topic')"><edit-icon fill="second-fill"></edit-icon></a>
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_topic')"><delete-icon fill="danger-fill"></delete-icon></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="topic">Donec tellus massa, pulvinar ac tellus eget</td>
                                        <td>
                                            <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                <div>
                                                    <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>11:15</td>
                                        <td>11:30</td>
                                        <td>15 min</td>
                                        <td>
                                            <div class="text-right">
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_topic')"><edit-icon fill="second-fill"></edit-icon></a>
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_topic')"><delete-icon fill="danger-fill"></delete-icon></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="topic">Duis maximus quam at augue commodo</td>
                                        <td>
                                            <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                <div>
                                                    <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>11:30</td>
                                        <td>11:35</td>
                                        <td>5 min</td>
                                        <td>
                                            <div class="text-right">
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_topic')"><edit-icon fill="second-fill"></edit-icon></a>
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_topic')"><delete-icon fill="danger-fill"></delete-icon></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="topic">Mauris nec dapibus arcu</td>
                                        <td>
                                            <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                <div>
                                                    <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>11:35</td>
                                        <td>11:45</td>
                                        <td>10 min</td>
                                        <td>
                                            <div class="text-right">
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_topic')"><edit-icon fill="second-fill"></edit-icon></a>
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_topic')"><delete-icon fill="danger-fill"></delete-icon></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="topic">Cras condimentum donec tellus massa, pulvinar ac tellus eget</td>
                                        <td>
                                            <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                <div>
                                                    <div class="avatar" v-tooltip.top-center="'FirstName LastName'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>11:45</td>
                                        <td>12:00</td>
                                        <td>15 min</td>
                                        <td>
                                            <div class="text-right">
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.edit_topic')"><edit-icon fill="second-fill"></edit-icon></a>
                                                <a href="javascript:void(0)" class="btn-icon" v-tooltip.top-center="translateText('message.delete_topic')"><delete-icon fill="danger-fill"></delete-icon></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </vue-scrollbar>
                </div>
                <div class="flex flex-direction-reverse flex-v-center">
                    <div class="pagination">
                        <span class="active">1</span>
                    </div>
                    <div>
                        <span class="pagination-info">{{ translateText('message.displaying') }} 5 {{ translateText('message.results_out_of') }} 5</span>
                    </div>
                </div>
                <!-- /// End Meeting Agenda /// -->
        
                <hr class="double">
        
                <!-- /// Meeting Documents /// -->
                <h3>{{ translateText('message.documents') }}</h3>
                <ul class="attachments">
                    <li><a href="#" title="Download Attachment">Measure-requirements.docx <downloadbutton-icon fill="second-fill"></downloadbutton-icon></a></li>
                    <li><a href="#" title="Download Attachment">Measure-objectives.xls <downloadbutton-icon fill="second-fill"></downloadbutton-icon></a></li>
                </ul>
                <!-- /// End Meeting Documents /// -->
        
                <hr class="double">
                
                <!-- /// Decisions /// -->
                <h3>{{ translateText('message.decisions') }}</h3>
        
                <div class="entries-wrapper">
                    <!-- /// Decision /// -->
                    <div class="entry" id="decision-1">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>Etiam maximus arcu vitae mauris</h4>  | {{ translateText('message.due_date') }}: <b>25.03.2017</b> | {{ translateText('message.status') }}: <b class="undone">Undone</b>
                            </div>
                            <div class="entry-buttons">
                                <button type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                            </div>
                        </div>
                        <div class="entry-responsible flex flex-v-center">
                            <div class="user-avatar"> 
                                <img src="http://trisoft.dev.campr.biz/uploads/avatars/10.jpg" :alt="'Anna Floyd'"/>
                            </div>
                            <div>
                                {{ translateText('message.responsible') }}::
                                <b>Anna Floyd</b>
                            </div>
                        </div>
                        <div class="decision-body">
                            <p>Morbi lectus massa, sollicitudin quis luctus non, pulvinar sed nibh. Suspendisse id dui a sem tempus pretium. Nunc a ornare lacus. Fusce eleifend enim id euismod scelerisque. Maecenas eu consequat ligula, id mollis mauris. Mauris ac mauris sed lorem vulputate bibendum id ut orci. Maecenas lacinia eget ipsum vitae tincidunt.</p>
                            <ul>
                                <li>Morbi at diam congue ante auctor tincidunt</li>
                                <li>Pellentesque arcu odio</li>
                                <li>Fusce malesuada magna et tincidunt vulputate</li>
                            </ul>
                        </div>  
                    </div>
                    <!-- /// End Decision /// -->
                    
                    <!-- /// Decision /// -->
                    <div class="entry" id="decision-2">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>Curabitur iaculis</h4>  | {{ translateText('message.due_date') }}: <b>25.03.2017</b> | {{ translateText('message.status') }}: <b class="undone">Undone</b>
                            </div>
                            <div class="entry-buttons">
                                <button data-target="#decision-2-edit" class="btn btn-rounded second-bg btn-auto btn-md" data-toggle="modal" type="button">edit</button>
                                <button type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                            </div>
                        </div>
                        <div class="entry-responsible flex flex-v-center">
                            <div class="user-avatar"> 
                                <img src="http://trisoft.dev.campr.biz/uploads/avatars/61.jpg" :alt="'John Doe'"/>
                            </div>
                            <div>
                                {{ translateText('message.responsible') }}:
                                <b>John Doe</b>
                            </div>
                        </div>
                        <div class="decision-body">
                            <ul>
                                <li>vitae enim quis elit volutpat sodales</li>
                                <li>vitae molestie ante</li>
                                <li>pulvinar arcu eu, auctor tellus</li>
                                <li>gravida ut lorem sit amet</li>
                            </ul>
                        </div>  
                    </div>
                    <!-- /// End Decision /// -->
                    
                    <!-- /// Decision /// -->
                    <div class="entry" id="decision-2">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>Donec mauris nunc</h4>  | {{ translateText('message.due_date') }}: <b>25.03.2017</b> | {{ translateText('message.status') }}: <b class="done">Done</b>
                            </div>
                            <div class="entry-buttons">
                                <button type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                            </div>
                        </div>
                        <div class="entry-responsible flex flex-v-center">
                            <div class="user-avatar"> 
                                <img src="http://trisoft.dev.campr.biz/uploads/avatars/41.jpg" :alt="'Martin Lawrence'"/>
                            </div>
                            <div>
                                {{ translateText('message.responsible') }}:
                                <b>Martin Lawrence</b>
                            </div>
                        </div>
                        <div class="decision-body">
                            <p>Phasellus mattis massa non metus pretium mollis sed eget justo. Cras non nisi et ligula rhoncus lobortis. Curabitur iaculis sem magna, sed efficitur magna sodales quis. Nam eget commodo eros.</p>
                            <p>Nullam vestibulum urna id laoreet porttitor. Praesent eu purus fermentum, varius augue eget, sollicitudin dolor. Mauris feugiat dictum convallis. Nulla quis quam id arcu tincidunt hendrerit. Aenean volutpat tincidunt posuere. Nulla arcu dolor, dapibus ut augue a, tincidunt semper felis. Curabitur in mauris risus. Maecenas eget blandit nibh. Sed vel laoreet lacus. Nulla bibendum risus at sem convallis consequat.</p>
                            <ol>
                                <li>vitae enim quis elit volutpat sodales</li>
                                <li>vitae molestie ante</li>
                                <li>pulvinar arcu eu, auctor tellus</li>
                                <li>gravida ut lorem sit amet</li>
                            </ol>
                        </div>  
                    </div>
                    <!-- /// End Decision /// -->
                </div>
                <!-- /// End Decisions /// -->
        
                <hr class="double">
        
                <!-- /// ToDos /// -->
                <h3>{{ translateText('message.todos') }}</h3>
        
                <div class="entries-wrapper">
                    <!-- /// ToDo /// -->
                    <div class="entry" id="decision-1">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>Proin vestibulum</h4>  | {{ translateText('message.due_date') }}: <b>25.03.2017</b> | {{ translateText('message.status') }}: <b class="done">Done</b>
                            </div>
                            <div class="entry-buttons">
                                <button type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                            </div>
                        </div>
                        <div class="entry-responsible flex flex-v-center">
                            <div class="user-avatar"> 
                                <img src="http://trisoft.dev.campr.biz/uploads/avatars/49.jpg" :alt="'Kyle Kennedy'"/>
                            </div>
                            <div>
                                {{ translateText('message.responsible') }}::
                                <b>Kyle Kennedy</b>
                            </div>
                        </div>
                        <div class="decision-body">
                            <p>Morbi lectus massa, sollicitudin quis luctus non, pulvinar sed nibh. Suspendisse id dui a sem tempus pretium. Nunc a ornare lacus. Fusce eleifend enim id euismod scelerisque. Maecenas eu consequat ligula, id mollis mauris. Mauris ac mauris sed lorem vulputate bibendum id ut orci. Maecenas lacinia eget ipsum vitae tincidunt.</p>
                        </div>  
                    </div>
                    <!-- /// End ToDo /// -->
                    
                    <!-- /// ToDo /// -->
                    <div class="entry" id="decision-2">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>Duis sodales lectus</h4>  | {{ translateText('message.due_date') }}: <b>25.03.2017</b> | {{ translateText('message.status') }}: <b class="undone">Undone</b>
                            </div>
                            <div class="entry-buttons">
                                <button type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                            </div>
                        </div>
                        <div class="entry-responsible flex flex-v-center">
                            <div class="user-avatar"> 
                                <img src="http://trisoft.dev.campr.biz/uploads/avatars/64.jpg" :alt="'Cathrine Magnusson'"/>
                            </div>
                            <div>
                                {{ translateText('message.responsible') }}:
                                <b>Cathrine Magnusson</b>
                            </div>
                        </div>
                        <div class="decision-body">
                            <ul>
                                <li>vitae enim quis elit volutpat sodales</li>
                                <li>vitae molestie ante</li>
                                <li>pulvinar arcu eu, auctor tellus</li>
                                <li>gravida ut lorem sit amet</li>
                            </ul>
                        </div>  
                    </div>
                    <!-- /// End ToDo /// -->
                </div>
                <!-- /// End ToDos /// -->
        
                <hr class="double">
        
                <!-- /// Infos /// -->
                <h3>{{ translateText('message.infos') }}</h3>
        
                <div class="entries-wrapper">
                    <!-- /// Info /// -->
                    <div class="entry" id="decision-1">
                        <div class="entry-header flex flex-space-between flex-v-center">
                            <div class="entry-title">
                                <h4>Proin vestibulum</h4>  | {{ translateText('message.due_date') }}: <b>25.03.2017</b> | {{ translateText('message.status') }}: <b class="done">Done</b>
                            </div>
                            <div class="entry-buttons">
                                <button type="button" class="btn btn-rounded btn-auto btn-md danger-bg" >{{ translateText('message.delete') }}</button>
                            </div>
                        </div>
                        <div class="entry-responsible flex flex-v-center">
                            <div class="user-avatar"> 
                                <img src="http://trisoft.dev.campr.biz/uploads/avatars/44.jpg" :alt="'Anne Manning'"/>
                            </div>
                            <div>
                                {{ translateText('message.responsible') }}::
                                <b>Anne Manning</b>
                            </div>
                        </div>
                        <div class="decision-body">
                            <ul>
                                <li>vitae enim quis elit volutpat sodales</li>
                                <li>vitae molestie ante</li>
                                <li>pulvinar arcu eu, auctor tellus</li>
                                <li>gravida ut lorem sit amet</li>
                            </ul>
                    
                            <p>Morbi lectus massa, sollicitudin quis luctus non, pulvinar sed nibh. Suspendisse id dui a sem tempus pretium. Nunc a ornare lacus. Fusce eleifend enim id euismod scelerisque. Maecenas eu consequat ligula, id mollis mauris. Mauris ac mauris sed lorem vulputate bibendum id ut orci. Maecenas lacinia eget ipsum vitae tincidunt.</p>
                        </div>  
                    </div>
                    <!-- /// End Info /// -->
                </div>
                <!-- /// End Infos /// -->
            </div>
            <div class="col-md-6">
                <div class="create-meeting page-section">
                    <!-- /// Header /// -->
                    <div class="margintop20 text-right">
                        <div class="buttons">
                            <a ref="#" class="btn-rounded btn-auto">{{ translateText('button.edit_meeting') }}</a>
                            <a ref="#" class="btn-rounded btn-auto second-bg">{{ translateText('button.new_meeting') }}</a>
                            <a ref="#" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_meeting') }}</a>
                        </div>
                    </div>
                    <!-- /// End Header /// -->
        
                    <div class="header flex-v-center">
                        <div>
                            <h3>{{ translateText('message.participants') }}</h3>
                        </div>
                        <div class="buttons">
                            <router-link :to="{name: 'project-organization-edit'}" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.edit_distribution_list') }}</router-link>
                            <a ref="#" class="btn-rounded btn-auto btn-md btn-empty">{{ translateText('button.send_notifications') }}</a>
                        </div>
                    </div>
        
                    <div class="overflow-hidden">
                        <vue-scrollbar class="table-wrapper">
                            <div class="scroll-wrapper">
                                <table class="table table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ translateText('table_header_cell.team_member') }}</th>
                                            <th>{{ translateText('table_header_cell.department') }}</th>
                                            <th>{{ translateText('table_header_cell.present') }}</th>
                                            <th>{{ translateText('table_header_cell.distribution_list') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Anabelle Johansson'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                                    </div>
                                                    <span>Anabelle Johansson</span>
                                                </div>
                                            </td>
                                            <td>Purchasing KOH</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="false"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Carl Percy'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/20.jpg)' }"></div>
                                                    </div>
                                                    <span>Carl Percy</span>
                                                </div>
                                            </td>
                                            <td>Purchasing KOH</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="true"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Johnathan Burges'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/40.jpg)' }"></div>
                                                    </div>
                                                    <span>Johnathan Burges</span>
                                                </div>
                                            </td>
                                            <td>Global Operations</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="false"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
        
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'John Doe'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/41.jpg)' }"></div>
                                                    </div>
                                                    <span>John Doe</span>
                                                </div>
                                            </td>
                                            <td>Technical Services</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="false"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Christinne Galoppy'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/44.jpg)' }"></div>
                                                    </div>
                                                    <span>Christinne Galoppy</span>
                                                </div>
                                            </td>
                                            <td>Technical Services</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="true"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Mobutu Seseseko'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/49.jpg)' }"></div>
                                                    </div>
                                                    <span>Mobutu Seseseko</span>
                                                </div>
                                            </td>
                                            <td>Global Operations</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="true"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Anne Wong'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/60.jpg)' }"></div>
                                                    </div>
                                                    <span>Anne Wong</span>
                                                </div>
                                            </td>
                                            <td>Global Operations</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="true"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Albert Strauss'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/61.jpg)' }"></div>
                                                    </div>
                                                    <span>Albert Strauss</span>
                                                </div>
                                            </td>
                                            <td>GMP</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="true"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Jhoanne Rothschild-Moore'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/64.jpg)' }"></div>
                                                    </div>
                                                    <span>Jhoanne Rothschild-Moore</span>
                                                </div>
                                            </td>
                                            <td>Purchasing KOH</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="true"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="avatars flex flex-v-center" id="tp-meeting-20032017-1">
                                                    <div>
                                                        <div class="avatar" v-tooltip.top-center="'Kelly West'" v-bind:style="{ backgroundImage: 'url(http://trisoft.dev.campr.biz/uploads/avatars/10.jpg)' }"></div>
                                                    </div>
                                                    <span>Kelly West</span>
                                                </div>
                                            </td>
                                            <td>GMP</td>
                                            <td class="text-center switchers">
                                                <switches v-model="showPresent" :selected="false"></switches>
                                            </td>
                                            <td class="text-center switchers">
                                                <switches v-model="distributionList" :selected="true"></switches>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </vue-scrollbar>
                    </div>
        
                    <div class="flex flex-direction-reverse flex-v-center">
                        <div class="pagination">
                            <span class="active">1</span>
                            <span>2</span>
                        </div>
                        <div>
                            <span class="pagination-info">{{ translateText('message.displaying') }} 10 {{ translateText('message.results_out_of') }} 25</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="text-right footer-buttons">
                    <div class="buttons">
                        <a ref="#" class="btn-rounded btn-auto">{{ translateText('button.edit_meeting') }}</a>
                        <a ref="#" class="btn-rounded btn-auto second-bg">{{ translateText('button.new_meeting') }}</a>
                        <a ref="#" class="btn-rounded btn-auto danger-bg">{{ translateText('button.delete_meeting') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import VueScrollbar from 'vue2-scrollbar';
import Switches from '../../3rdparty/vue-switches';
import RescheduleIcon from '../../_common/_icons/RescheduleIcon';
import DownloadbuttonIcon from '../../_common/_icons/DownloadbuttonIcon';

export default {
    components: {
        EditIcon,
        DeleteIcon,
        VueScrollbar,
        Switches,
        RescheduleIcon,
        DownloadbuttonIcon,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    data() {
        return {
            showPresent: '',
            distributionList: '',
        };
    },
};
</script>

<style lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    ul.attachments {
        li {
            a {
                .icon {
                    svg {
                        width: 12px;
                        height: 12px;
                        @include transition(color, 0.2s, ease);
                    }
                }

                &:hover {
                    .icon {
                        svg {
                            fill: $secondDarkColor;
                        }
                    }                    
                }
            }
        }
    }
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_mixins';
    @import '../../../css/_variables';

    .title {
        position: relative;
        top: 15px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;
    }

    .action-list {
        margin-bottom: 15px;

        li {
            margin-bottom: 15px;
            position: relative;
            padding-right: 60px;
            padding-bottom: 15px;
            border-bottom: 1px solid $darkerColor;

            .list-item-description {
                position: relative;
                padding-left: 30px;

                &:before {
                    content: '';
                    display: block;
                    position: absolute;
                    @include border-radius(50%);
                    background-color: $lightColor;
                    width: 10px;
                    height: 10px;
                    left: 0;
                    top: 0;
                }
            }

            .list-item-actions {
                position: absolute;
                top: 0;
                right: 0;
                width: 60px;
                text-align: right;

                a {
                    margin-left: 10px;
                }
            }

            &:last-child {
                margin-bottom: 0;
                padding-bottom: 0;
                border: none;
            }
        }
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
            display: inline-block;
        }

        span {
            margin-left: 10px;
            line-height: 34px;
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

    .topic {
        white-space: normal;
        text-transform: none;
    } 

    .user-avatar {
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 700;

        img {
            width: 30px;
            height: 30px;
            @include border-radius(50%);
            margin: 0 10px 0 0;  
            display: inline-block;
            position: relative;
            top: -2px;
        }
    }

    p {
        margin-bottom: 20px;
    }

    .entry {
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid $darkerColor;

        .entry-header {
            .entry-title {
                text-transform: uppercase;
                letter-spacing: 0.1em;
                font-size: 10px;  
                margin-bottom: 10px;

                h4 {
                    display: inline-block;
                    text-transform: none;
                    letter-spacing: 0;
                    margin: 0;
                    font-weight: 700;
                }
            }

            .done {
                color: $secondColor;
            }

            .undone {
                color: $dangerColor;
            }

            .entry-buttons {
                text-align: right;

                .btn {
                    margin: 0 0 10px 10px;
                }
            }
        }

        .entry-responsible {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 10px;
            line-height: 1.5em;

            b {
                display: block;
                font-size: 12px;
            }
        }

        .decision-body {
            padding: 10px 0 0 0;

            ul {
                list-style-type: disc;
                list-style-position: inside;

                &:last-child {
                    margin-bottom: 0;
                }
            }

            ol {
                list-style-type: decimal;
                list-style-position: inside;
                padding: 0;

                &:last-child {
                    margin-bottom: 0;
                }
            }

            img {
                @include box-shadow(0, 0, 20px, $darkColor);
            }

            .title {
                font-weight: bold;
                margin-bottom: 5px;
            }

            .cost {
                text-transform: uppercase;
                color: $lightColor;
                letter-spacing: 0.1em;

                b {
                    color: $lighterColor;
                }
            }

            p {
                &:last-child {
                    margin-bottom: 0;
                }
            }
        }

        &:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border: none;
        }
    }

    .new-comment {
        .footer-buttons {
            margin-top: 15px;
        }
    }

    .buttons {
      a {
        margin: 5px 0 5px 10px;
      }
    }

    .category {
        margin-top: 0;
    }

    ul.attachments {
        display: inline-block;
        margin: 0;
        list-style: none;
        padding: 0;

        li {
            margin-bottom: 15px;

            a {
                color: $secondColor;
                @include transition(color, 0.2s, ease);

                &:hover {
                    color: $secondDarkColor;
                }
            }

            &:last-child {
                margin: 0;
            }
        }
    }

    .footer-buttons {
        margin-top: 60px;
        padding: 30px 0;
        border-top: 1px solid $darkerColor; 
    }
</style>
