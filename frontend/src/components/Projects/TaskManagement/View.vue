<template>
    <div class="project-task-management page-section">
        <div class="row">
            <div class="col-md-6">

               <!-- /// Task Title and Label /// -->
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-task-management-list'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            Back to Task Management
                        </router-link>
                        <h1>Build Self-Landing Rocket Legs</h1>                     
                    </div>

                    <div class="task-label" :style="'background-color:#e04fcc'">
                        High Priority
                    </div>              
                </div>
                <!-- /// End Task Title and Label /// -->

                <!-- /// Task Status /// -->
                <div class="task-status flex flex-v-center">
                    <div>
                        <span class="small">Status:</span>
                        <div class="task-status-box">In Progress</div>
                        <a href="#open-status-edit-modal" class="simple-link small">Edit</a>
                    </div>
                    <div>
                        <div class="task-status-info">
                            <b>#244</b>
                            Created on 25.02.2017 by
                            <div class="user-avatar"> 
                                <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                                <b>Nelson Carr</b>
                            </div>
                            <span class="task-subtasks">&nbsp;&nbsp;|&nbsp;&nbsp; 1 of 3 Subtasks Completed</span>
                        </div>
                    </div>
                </div>
                <!-- /// End Task Status /// -->

                <hr class="double">
                
                <!-- /// Task Description /// -->
                <div class="task-description">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas luctus venenatis lorem, vel semper enim vehicula aliquet. Donec eget egestas augue. Nulla cursus purus lectus, et imperdiet nisl varius quis. Nulla eget augue sed sapien sodales ultrices. Donec et purus neque. Etiam venenatis ut nulla eget vestibulum. Maecenas a eros at erat aliquet pellentesque. Suspendisse scelerisque velit vulputate tortor rutrum, in pellentesque ante condimentum. Vivamus lacinia diam ut neque tempor dictum. Nullam nec rutrum sem, ac maximus odio.</p>

                    <p><b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Maecenas luctus venenatis lorem, vel semper enim vehicula aliquet. Donec eget egestas augue. Nulla cursus purus lectus, et imperdiet nisl varius quis. Nulla eget augue sed sapien sodales ultrices. Donec et purus neque. Etiam venenatis ut nulla eget vestibulum. Maecenas a eros at erat aliquet pellentesque. Suspendisse scelerisque velit vulputate tortor rutrum, in pellentesque ante condimentum. Vivamus lacinia diam ut neque tempor dictum. Nullam nec rutrum sem, ac maximus odio.</p>
                </div>
                <!-- /// End Task Description /// -->

                <hr class="double">

                <!-- /// End Subtasks /// -->
                <h3>Subtasks | 1 of 3 Completed</h3>
                <div class="subtasks">
                    <div class="subtask flex flex-space-between">
                        <div class="checkbox-input clearfix">
                            <input id="subtask1" type="checkbox" name="" value="1" checked>
                            <label for="subtask1">Curabitur lacinia dictum dui ac malesuada</label>
                        </div>
                        <div>
                            <div class="text-right">
                                <button data-target="#subtask1-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                <button data-target="#subtask1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                            </div>
                        </div>
                    </div>
                    <div class="subtask flex flex-space-between">
                        <div class="checkbox-input clearfix">
                            <input id="subtask2" type="checkbox" name="" value="0">
                            <label for="subtask2">Duis euismod ipsum eu luctus varius vestibulum auctor, odio id molestie gravida</label>
                        </div>
                        <div>
                            <div class="text-right">
                                <button data-target="#subtask2-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                <button data-target="#subtask2-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                            </div>
                        </div>
                    </div>
                    <div class="subtask flex flex-space-between">
                        <div class="checkbox-input clearfix">
                            <input id="subtask3" type="checkbox" name="" value="0">
                            <label for="subtask3">Suspendisse lobortis suscipit aliquet. Aliquam lacinia, nunc a lacinia commodo, felis dui mollis erat, cursus fermentum nisl eros a libero. Maecenas tristique
    et sapien quis faucibus.</label>
                        </div>
                        <div>
                            <div class="text-right">
                                <button data-target="#subtask3-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                <button data-target="#subtask3-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /// End Subtasks /// -->

                <hr class="double">

                <!-- /// Task History /// -->
                <div class="task-history">
                    <!-- /// Task Comment /// -->
                    <div class="comment">
                        <div class="comment-header">
                            <div class="user-avatar"> 
                                <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                                <b>Nelson Carr</b>
                            </div>
                            <a href="#link-to-member-page" class="simple-link">@nelson.c</a>
                            assigned task to 
                            <a href="#link-to-member-page" class="simple-link">@sandy.fc</a>
                            2 hours ago
                        </div>    
                    </div>
                    <!-- /// End Task Comment /// -->

                    <hr class="double">

                    <!-- /// Task Comment /// -->
                    <div class="comment">
                        <div class="comment-header">
                            <div class="user-avatar"> 
                                <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                                <b>Nelson Carr</b>
                            </div>
                            <a href="#link-to-member-page" class="simple-link">@nelson.c</a>
                            commented 3 days ago | edited 4 hours ago
                            <button data-target="#comment-1-edit" class="simple-link edit-comment" data-toggle="modal" type="button">edit</button>
                        </div>
                        <div class="comment-body">
                            <p>Morbi lectus massa, sollicitudin quis luctus non, pulvinar sed nibh. Suspendisse id dui a sem tempus pretium. Nunc a ornare lacus. Fusce eleifend enim id euismod scelerisque. Maecenas eu consequat ligula, id mollis mauris. Mauris ac mauris sed lorem vulputate bibendum id ut orci. Maecenas lacinia eget ipsum vitae tincidunt.</p>
                            <ul>
                                <li>Morbi at diam congue ante auctor tincidunt</li>
                                <li>Pellentesque arcu odio</li>
                                <li>Fusce malesuada magna et tincidunt vulputate</li>
                            </ul>
                        </div>  
                    </div>
                    <!-- /// End Task Comment /// -->

                    <hr class="double">

                    <!-- /// Task Comment /// -->
                    <div class="comment">
                        <div class="comment-header">
                            <div class="user-avatar"> 
                                <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                                <b>Nelson Carr</b>
                            </div>
                            <a href="#link-to-member-page" class="simple-link">@nelson.c</a>
                            added
                            <div class="task-label" :style="'background-color:#e04fcc'">
                                High Priority
                            </div>                                                        
                            2 hours ago
                        </div> 
                    </div>
                    <!-- /// End Task Comment /// -->
                    
                    <hr class="double">

                    <!-- /// Task Comment /// -->
                    <div class="comment">
                        <div class="comment-header">
                            <div class="user-avatar"> 
                                <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                                <b>Nelson Carr</b>
                            </div>
                            <a href="#link-to-member-page" class="simple-link">@nelson.c</a>
                            commented 35 min ago
                        </div>
                        <div class="comment-body">
                            <p><a href="#link-to-member-page" class="simple-link">@sandy.fc</a> here is the image you asked for</p>
                            <img src="http://www.spacex.com/sites/spacex/files/landingleg_0.jpg" alt="Landing Leg">
                        </div> 
                    </div>
                    <!-- /// End Task Comment /// -->
                </div>
                <!-- /// End Task History /// -->

                <hr class="double">

                <!-- /// New Task Description /// -->
                <div class="new-comment">
                    <div class="user-avatar"> 
                        <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                    </div>
                    <div class="new-comment-body">
                        <div class="vueditor-holder">
                            <div class="vueditor-header">New Comment</div>
                            <Vueditor></Vueditor>
                        </div>
                        <div class="footer-buttons">
                            <a href="javascript:void(0)" class="btn-rounded btn-auto">Comment</a>
                            <router-link to="" class="btn-rounded btn-auto danger-bg">{{ message.close_task }}</router-link>
                        </div>
                    </div>
                </div>
                <!-- /// End New Task Description /// -->
            </div>

            <div class="col-md-6">
                <!-- /// Header Buttons /// -->
                <div class="header-buttons">
                    <router-link to="" class="btn-rounded btn-auto second-bg">Start Task</router-link>
                    <a href="javascript:void(0)" class="btn-rounded btn-auto">{{ message.edit_task }}</a>
                    <!-- If task has not yet started, don't show the Close button and vice-versa -->
                    <router-link to="" class="btn-rounded btn-auto danger-bg">{{ message.close_task }}</router-link>
                    <router-link :to="{name: 'project-task-management-create'}" class="btn-rounded btn-auto second-bg">New Task</router-link>
                </div>
                <!-- /// End Header Buttons /// -->

                <!-- /// Task Sidebar /// -->
                <div class="task-sidebar">
                    <!-- /// Phase & Milestone /// -->
                    <h3>Planning</h3>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div>This task is part of <a href="#path-to-phase" class="simple-link">Phase 2</a> > <a href="#path-to-milestone" class="simple-link">Milestone 2.2</a></div>
                        <button data-target="#edit-planning-module" data-toggle="modal" class="btn-rounded btn-md btn-empty btn-auto" type="button">Edit Phase/Milestone</button>
                    </div>
                    <!-- /// End Phase & Milestone /// -->

                    <hr class="double">
                    
                    <!-- /// Task Schedule /// -->
                    <h3>Task Schedule</h3>
                    <vue-scrollbar class="table-wrapper">
                        <table class="table table-small">
                            <thead>
                                <tr>
                                    <th>Schedule</th>
                                    <th>Start</th>
                                    <th>Finish</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Base</td>
                                    <td>24.01.2017</td>
                                    <td>26.01.2017</td>
                                    <td>2</td>
                                </tr>
                                <tr class="column-warning">
                                    <td>Forecast</td>
                                    <td>24.01.2017</td>
                                    <td>27.01.2017</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>Actual</td>
                                    <td>24.01.2017</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-scrollbar>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div>Task Predecessor: <a href="#path-to-predecessor-task" class="simple-link">Self Landing System</a></div>
                        <button data-target="#edit-schedule-module" data-toggle="modal" class="btn-rounded btn-md btn-empty btn-auto" type="button">Edit Schedule</button>
                    </div>
                    <!-- /// Task End Schedule /// -->

                    <hr class="double">

                    <!-- /// Task Internal Costs /// -->
                    <h3>Internal Costs</h3>
                    <vue-scrollbar class="table-wrapper">
                        <table class="table table-small">
                            <thead>
                                <tr>
                                    <th>Resource</th>
                                    <th>Daily Rate</th>
                                    <th>Qty.</th>
                                    <th>Days</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Logistics</td>
                                    <td>$ 25</td>
                                    <td>2</td>
                                    <td>2</td>
                                    <td><b>$ 100</b></td>
                                    <td>
                                        <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Procurement</td>
                                    <td>$ 40</td>
                                    <td>3</td>
                                    <td>3</td>
                                    <td><b>$ 360</b></td>
                                    <td>
                                        <button data-target="#procurement-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        <button data-target="#procurement-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quality Assurance</td>
                                    <td>$ 30</td>
                                    <td>1</td>
                                    <td>3</td>
                                    <td><b>$ 90</b></td>
                                    <td>
                                        <button data-target="#qa-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        <button data-target="#qa-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Base Total</b></td>
                                    <td colspan="2"><b>$ 540</b></td>
                                </tr>
                                <tr class="column-warning">
                                    <td colspan="4" class="text-right"><b>Forecast</b></td>
                                    <td><b>$ 600</b></td>
                                    <td>
                                        <button data-target="#internal-costs-forecast-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right"><b>Actual</b></td>
                                    <td><b>$ 220</b></td>
                                    <td>
                                        <button data-target="#internal-costs-actual-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-scrollbar>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div></div>
                        <button data-target="#edit-schedule-module" data-toggle="modal" class="btn-rounded btn-md btn-empty btn-auto" type="button">Add Internal Cost +</button>
                    </div>
                    <!-- /// Task Internal Costs /// -->

                    <hr class="double">

                    <!-- /// Task Internal Costs /// -->
                    <h3>Exernal Costs</h3>
                    <vue-scrollbar class="table-wrapper">
                        <table class="table table-small">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Qty.</th>
                                    <th>Unit</th>
                                    <th>Unit Rate</th>
                                    <th>CAPEX</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Carbon Fiber</td>
                                    <td>200</td>
                                    <td>kg</td>
                                    <td>$ 1200</td>
                                    <td><switches v-model="test" :selected="true"></switches></td>
                                    <td><b>$ 240.000</b></td>
                                    <td>
                                        <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill" ></edit-icon></button>
                                        <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Aluminium Honeycomb</td>
                                    <td>2000</td>
                                    <td>kg</td>
                                    <td>$ 800</td>
                                    <td><switches v-model="test" :selected="true"></switches></td>
                                    <td><b>$ 1.600.000</b></td>
                                    <td>
                                        <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Steel</td>
                                    <td>5</td>
                                    <td>Ton</td>
                                    <td>$ 10.000</td>
                                    <td><switches v-model="test" :selected="false"></switches></td>
                                    <td><b>$ 50.000</b></td>
                                    <td>
                                        <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                        <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">CAPEX Subtotal</b></td>
                                    <td colspan="2">$ 1.840.000</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right">OPEX Subtotal</b></td>
                                    <td colspan="2">$ 50.000</td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right"><b>Base Total</b></td>
                                    <td colspan="2"><b>$ 1.890.000</b></td>
                                </tr>
                                <tr class="column-warning">
                                    <td colspan="5" class="text-right"><b>Forecast</b></td>
                                    <td><b>$ 1.700.000</b></td>
                                    <td>
                                        <button data-target="#internal-costs-forecast-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    </td>
                                </tr>
                                <tr class="column-alert">
                                    <td colspan="5" class="text-right"><b>Actual</b></td>
                                    <td><b>$ 2.250.000</b></td>
                                    <td>
                                        <button data-target="#internal-costs-actual-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </vue-scrollbar>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div></div>
                        <button data-target="#edit-schedule-module" data-toggle="modal" class="btn-rounded btn-md btn-empty btn-auto">Add External Cost +</button>
                    </div>
                    <!-- /// Task Internal Costs /// -->

                    <hr class="double">

                    <!-- /// Task Assignee /// -->
                    <h3>Asignee</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="user-avatar flex flex-v-center"> 
                                <div><img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/></div>
                                <div>
                                    <b>Nelson Carr</b><br/>
                                    <a href="#path-to-member-page" class="simple-link">@nelson.c</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select-field v-bind:title="'Change Assignee'"></select-field>    
                        </div>
                    </div>
                    <!-- /// End Task Assignee /// -->

                    <hr class="double">

                    <!-- /// Task Assignee /// -->
                    <h3>Status</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <range-slider
                            v-bind:title="'Task Completion'"
                            min="0"
                            max="100"
                            minSuffix=" %"
                            type="single"
                            v-model="projectCompletion"
                            v-bind:value="projectCompletion" />
                        </div>
                        <div class="col-md-8">
                            <h4>In Progress</h4>
                        </div>
                        <div class="col-md-4">
                            <select-field v-bind:title="'Change Status'"></select-field>    
                        </div>
                    </div>
                    <!-- /// End Task Assignee /// -->

                    <hr class="double">

                    <!-- /// Task Condition /// -->
                    <h3>Task Condition</h3>
                    <!-- Original vue syntax: <p v-for="status in colorStatuses"><span v-bind:style="{ color: status.color }">{{ status.name }}</span> Add condition description here</p>-->
                    <p>
                        <b class="caps second-color">Green:</b> Quisque maximus quam id nunc congue ultricies. Aliquam quis quam lectus. Suspendisse porta ipsum non 	justo gravida, commodo rhoncus dolor congue.
                    </p>
                    <p>
                        <b class="caps warning-color">Yellow:</b> Aliquam ullamcorper sapien sed leo tincidunt hendrerit.
                    </p>
                    <p>
                        <b class="caps danger-color">Red:</b> Etiam a tellus non purus dignissim molestie ac in nulla. Vestibulum orci nisi, venenatis a sagittis eget, molestie 	quis orci. Morbi fermentum ut metus id porttitor.
                    </p>
                    <div class="flex flex-space-between flex-v-center margintop20">
                        <div class="status-boxes flex flex-v-center">
                            <!-- Original vue syntax: <div v-for="status in colorStatuses" v-bind:style="{ background: status.color }" class="status-box"></div>-->
                            <div class="status-box" style="background-color:#5FC3A5"></div>
                            <div class="status-box" style=""></div>
                            <div class="status-box" style=""></div>
                        </div>
                        <button data-target="#ajax-save-status" class="btn-rounded btn-md btn-empty btn-auto btn-disabled" type="button">Save Status</button>
                    </div>
                    <!-- /// End Task Condition /// -->

                    <hr class="double">

                    <!-- /// Task Attachmets /// -->
                    <h3>2 Attachments</h3>
                    <div class="flex flex-space-between margintop20">
                        <div>
                            <div><a href="#path-to-download-file" class="simple-link">self-landing-leg-materials-required.xls</a></div>
                            <div><a href="#path-to-download-file" class="simple-link">leg-mockup.sketch</a></div>
                        </div>
                        <button class="btn-rounded btn-md btn-empty btn-auto flex" type="button">
                            <span>Attach a file</span>
                            <attach-icon></attach-icon>
                        </button>
                    </div>
                    <!-- /// End Task Attachments /// -->

                    <hr class="double">

                    <!-- /// Task Labels /// -->
                    <h3>Labels</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="task-label-holder flex flex-v-center">
                                <div class="task-label" :style="'background-color:#e04fcc'">
                                    High Priority
                                </div>
                                <button class="btn-icon btn-remove" type="button">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 10 10">
                                        <path d="M9.9,9.3L5.6,5l4.3-4.3c0.2-0.2,0.2-0.4,0-0.6C9.7,0,9.5,0,9.3,0.1L5,4.4L0.7,0.1C0.5,0,0.3,0,0.1,0.1
                                        C0,0.3,0,0.5,0.1,0.7L4.4,5L0.1,9.3C0,9.5,0,9.7,0.1,9.9c0.2,0.2,0.4,0.2,0.6,0L5,5.6l4.3,4.3c0.2,0.2,0.4,0.2,0.6,0
                                        C10,9.7,10,9.5,9.9,9.3z"/>
                                    </svg>
                                </button> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select-field v-bind:title="'Add Label'"></select-field>
                        </div>
                    </div>                        
                    <!-- /// End Labels /// -->

                    <hr class="double">

                    <!-- /// Participants /// -->
                    <h3>Participants</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="flex flex-v-center">
                                <div class="user-avatar" v-tooltip.bottom-center="'Nelson Carr'"> 
                                    <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                                </div>
                                <div class="user-avatar" v-tooltip.bottom-center="'Nelson Carr'"> 
                                    <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                                </div>
                                <div class="user-avatar" v-tooltip.bottom-center="'Nelson Carr'"> 
                                    <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'Nelson Carr'"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select-field v-bind:title="'Invite Members'"></select-field>
                        </div>
                    </div>                        
                    <!-- /// End Participants /// -->

                    <hr class="double">

                    <div class="footer-buttons">
                        <a href="javascript:void(0)" class="btn-rounded btn-auto second-bg">Save Changes</a>
                        <a href="javascript:void(0)" class="btn-rounded btn-auto btn-empty">Export Task</a>
                    </div>
                </div>
                <!-- /// End Task Sidebar /// -->
            </div>
        </div>
    </div>
</template>

<script>
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import AttachIcon from '../../_common/_icons/AttachIcon';
import Switches from '../../3rdparty/vue-switches';
import VueScrollbar from 'vue2-scrollbar';
import SelectField from '../../_common/_form-components/SelectField';
import RangeSlider from '../../_common/_form-components/RangeSlider';

export default {
    components: {
        EditIcon,
        DeleteIcon,
        AttachIcon,
        Switches,
        VueScrollbar,
        SelectField,
        RangeSlider,
    },
    data: function() {
        return {
            message: {
                edit_task: Translator.trans('message.edit_task'),
                close_task: Translator.trans('message.close_task'),
            },
            button: {
                new_task: Translator.trans('button.new_task'),
            },
        };
    },
};
</script>

<style scoped lang="scss">
    @import '../../../css/page-section';
    @import '../../../css/_variables';
    @import '../../../css/_mixins';

    .btn-rounded {
        margin-left: 10px;
    }

    .page-section {
        .header {
            justify-content: flex-start;
            align-items: center;

            .task-label {
                margin:0 0 0 20px;                
            }
        }
    }

    .task-label {
        @include border-radius(3px);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: $whiteColor;
        height: 30px;
        padding: 0 30px;
        line-height: 31px;
        display: inline-block;
    }

    .header-buttons {
        margin: 20px 0;
        text-align: right;

        a {
            margin-left: 20px;
        }
    }

    .footer-buttons {
        margin: 20px 0 0;

        a {
            margin: 0 20px 0 0;
        }
    }

    .task-status {
        text-transform: uppercase;
        letter-spacing: 0.1em;

        .small {
            display: block;
        }

        .task-status-box {
            background-color: $middleColor;
            color: $lighterColor;
            @include border-radius(3px);
            font-size: 10px;
            padding: 0 30px;
            line-height: 31px;
            margin-right: 10px;
            display: inline-block;
            white-space: nowrap;
        }

        .task-status-info {
            padding-left: 10px;

            .user-avatar {
                img {
                    margin: 0 10px;
                }
            }
        }
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

    .subtasks {
        .subtask {
            margin-bottom: 20px;
            border-bottom: 1px solid $darkColor;

            &:last-child {
                margin-bottom: 0;
                border-bottom: none;

                .checkbox-input {
                    margin: 0;

                    label {
                        margin: 0;
                    }
                }
            }
        }

        .checkbox-input {
            margin-bottom: 15px;

            label {
                position: relative;
                font-size: 12px;
                text-transform: none;
                padding-left: 30px;

                &:before {
                    position: absolute;
                    left: 0;
                }
            }

            &:last-child {
                margin-bottom: 0;
            }
        }

        .text-right {
            width: 100px;
        }

        .btn-icon {
            margin-left: 10px;
        }
    }

    .comment {
        .comment-header {
            padding-right: 60px;
            position: relative;

            .edit-comment {
                position: absolute;
                right: 0;
                top: 7px;
            }

            .task-label {
                margin: 0 20px;                
            }
        }

        .comment-body {
            padding: 10px 0 0 55px;

            ul {
                list-style-type: disc;
            }

            ol {
                list-style-type: decimal;
            }

            img {
                @include box-shadow(0, 0, 20px, $darkColor);
            }
        }
    }

    .new-comment {
        position: relative;
        padding-left: 55px;

        .user-avatar {
            position: absolute;
            top: 2px;
            left: 0;            
        }
    }

    .task-sidebar {
        background-color: $darkColor;
        padding: 30px;

        hr.double {
            border-top: 4px double $middleColor;
        }

        .dropdown {
            border: 1px solid $middleColor;
        }

        .slider-holder {
            margin-bottom: 15px;
        }
    }

    h3 {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 300;
        letter-spacing: 0.1em;
        margin:0 0 15px;
    }

    h4 {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }

    .table-small {
        >thead {
            >tr {
                >th {
                    height: 30px;
                    padding: 5px 15px;
                }
            }
        }

        >tbody {
            >tr {
                >td {
                    height: 30px;
                    padding: 5px 15px;
                }
            }
        }

        .btn-icon {
            margin-right: 10px;
            position: relative;
            top: 2px;
        }

        label {
            margin: 0;
        }
    }

    .status-boxes {
        .status-box {
            width: 30px;
            height: 30px;
            margin-right: 5px;
            background-color:$fadeColor;
        }
    }

    .btn-remove {
        margin-left: 10px;
        width: 10px;
        height: 10px;

        svg {
            width: 10px;
            height: 10px;
            fill: $dangerColor;
            @include transition(all, 0.2s, ease-in);
        }

        &:hover,
        &:active {
            svg {
                @include opacity(0.8);
            }
        }
    }
    
    .task-label-holder {
        margin-bottom: 10px;

        &:last-child {
            margin: 0;
        }
    }
</style>
