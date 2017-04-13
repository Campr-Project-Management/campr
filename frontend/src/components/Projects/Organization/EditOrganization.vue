<template>
    <div class="create-task page-section">
        <div class="row">
            <div class="col-md-6">
                <div class="header">
                    <div>
                        <router-link :to="{name: 'project-organization'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{message.back_to_organization}}
                        </router-link>
                        <h1>{{message.edit_organization}}</h1>
                    </div>
                </div>

                <div class="form">
                    <!-- /// Roles /// -->
                    <h3>{{message.distribution_lists}}</h3>                
                    <ul class="roles-hierarchy">
                        <li>
                            <span class="role">
                                Project Sponsor
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                    <g>
                                        <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                        <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                        <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                        <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                    </g>
                                </svg>
                            </span>
                            <ul>
                                <li>
                                    <span class="role">
                                        Project Manager
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                            <g>
                                                <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                                <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                                <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                                <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                            </g>
                                        </svg>
                                    </span>

                                    <ul>
                                        <li>
                                            <span class="role">
                                                Team Leader
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                                    <g>
                                                        <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                                        <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                                        <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                                        <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                                    </g>
                                                </svg>
                                            </span>
                                            <ul>
                                                <li>
                                                    <span class="role">
                                                        Team Member
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                                            <g>
                                                                <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                                                <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                                                <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                                                <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span class="role">
                                        Coach
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16 16">
                                            <g>
                                                <path d="M6.5,5.5L2.3,1.3H4c0.4,0,0.7-0.3,0.7-0.7S4.4,0,4,0H0.7C0.3,0,0,0.3,0,0.7V4 c0,0.4,0.3,0.7,0.7,0.7S1.3,4.4,1.3,4V2.3l4.2,4.2c0.3,0.3,0.7,0.3,0.9,0C6.7,6.2,6.7,5.8,6.5,5.5z"/>
                                                <path d="M9.5,5.5l4.2-4.2H12c-0.4,0-0.7-0.3-0.7-0.7S11.6,0,12,0h3.3C15.7,0,16,0.3,16,0.7V4 c0,0.4-0.3,0.7-0.7,0.7c-0.4,0-0.7-0.3-0.7-0.7V2.3l-4.2,4.2c-0.3,0.3-0.7,0.3-0.9,0C9.3,6.2,9.3,5.8,9.5,5.5z"/>
                                                <path d="M5.5,9.5l-4.2,4.2V12c0-0.4-0.3-0.7-0.7-0.7S0,11.6,0,12v3.3C0,15.7,0.3,16,0.7,16H4 c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7H2.3l4.2-4.2c0.3-0.3,0.3-0.7,0-0.9C6.2,9.3,5.8,9.3,5.5,9.5z"/>
                                                <path d="M10.5,9.5l4.2,4.2V12c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7v3.3 c0,0.4-0.3,0.7-0.7,0.7H12c-0.4,0-0.7-0.3-0.7-0.7c0-0.4,0.3-0.7,0.7-0.7h1.7l-4.2-4.2c-0.3-0.3-0.3-0.7,0-0.9 C9.8,9.3,10.2,9.3,10.5,9.5z"/>
                                            </g>
                                        </svg>
                                    </span>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- /// End Roles /// -->

                    <hr>

                    <!-- /// Add new Role /// -->
                    <div class="form-group">
                        <input-field v-model="title" type="text" label="New Role"></input-field>
                    </div>
                    <div class="flex flex-space-between">
                        <a v-on:click="createTask" class="btn-rounded btn-auto second-bg">Save</a>
                        <a v-on:click="createTask" class="btn-rounded btn-auto">Add new role +</a>
                    </div>
                    <!-- /// End Add new Role /// -->
                </div>

                <hr class="double">

                <!-- /// Departments /// -->
                <h3>Departments</h3> 
                <vue-scrollbar class="table-wrapper">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Department Name</th>
                                <th>Managers</th>
                                <th>Np. of Members</th>
                                <th>Created on</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>GMP</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Nelson Carr'">
                                        <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg"/>
                                    </div>
                                    <div class="avatar-image" v-tooltip.top-center="'Alicia Keys'">
                                        <img src="http://dev.campr.biz/uploads/avatars/10.jpg"/>
                                    </div>
                                </td>
                                <td>12</td>
                                <td>23.03.2017</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Global Operations</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'David Gilmore'">
                                        <img src="http://dev.campr.biz/uploads/avatars/40.jpg"/>
                                    </div>
                                </td>
                                <td>10</td>
                                <td>15.02.2017</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Operations BTF</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Simona Wang'">
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>
                                </td>
                                <td>2</td>
                                <td>02.02.2017</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Operations KOH</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Uvuvwevwevwe Onyetenyevwe Ugwemubwem Ossas'">
                                        <img src="http://dev.campr.biz/uploads/avatars/49.jpg"/>
                                    </div>
                                    <div class="avatar-image" v-tooltip.top-center="'David Gilmore'">
                                        <img src="http://dev.campr.biz/uploads/avatars/20.jpg"/>
                                    </div>
                                    <div class="avatar-image" v-tooltip.top-center="'David Gilmore'">
                                        <img src="http://dev.campr.biz/uploads/avatars/44.jpg"/>
                                    </div>
                                </td>
                                <td>10</td>
                                <td>05.01.2017</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Technical Services</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Danielle Morton'">
                                        <img src="http://dev.campr.biz/uploads/avatars/10.jpg"/>
                                    </div>
                                </td>
                                <td>18</td>
                                <td>15.05.2016</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Purchasing KOH</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Andrea Millovichi'">
                                        <img src="http://dev.campr.biz/uploads/avatars/64.jpg"/>
                                    </div>
                                </td>
                                <td>3</td>
                                <td>25.04.2016</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>QM</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Andrew Willow'">
                                        <img src="http://dev.campr.biz/uploads/avatars/20.jpg"/>
                                    </div>
                                    <div class="avatar-image" v-tooltip.top-center="'Danielle Morton'">
                                        <img src="http://dev.campr.biz/uploads/avatars/10.jpg"/>
                                    </div>
                                </td>
                                <td>8</td>
                                <td>02.12.2015</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Reuko</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Radu Ionescu'">
                                        <img src="http://dev.campr.biz/uploads/avatars/61.jpg"/>
                                    </div>
                                </td>
                                <td>15</td>
                                <td>18.09.2015</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>JC Eckardt</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Danielle Morton'">
                                        <img src="http://dev.campr.biz/uploads/avatars/10.jpg"/>
                                    </div>
                                </td>
                                <td>1</td>
                                <td>22.06.2015</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>EHS BTF</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Danielle Morton'">
                                        <img src="http://dev.campr.biz/uploads/avatars/10.jpg"/>
                                    </div>
                                    <div class="avatar-image" v-tooltip.top-center="'David Gilmore'">
                                        <img src="http://dev.campr.biz/uploads/avatars/44.jpg"/>
                                    </div>
                                    <div class="avatar-image" v-tooltip.top-center="'Uvuvwevwevwe Onyetenyevwe Ugwemubwem Ossas'">
                                        <img src="http://dev.campr.biz/uploads/avatars/49.jpg"/>
                                    </div>
                                    <div class="avatar-image" v-tooltip.top-center="'David Gilmore'">
                                        <img src="http://dev.campr.biz/uploads/avatars/20.jpg"/>
                                    </div>
                                </td>
                                <td>52</td>
                                <td>12.01.2015</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </vue-scrollbar>
                <div class="flex flex-direction-reverse">                    
                    <div class="pagination flex flex-center">
                        <span class="active">1</span>
                        <span class="">2</span>
                    </div>    
                    <span class="pagination-info">Displaying 10 results out of 12</span>                
                </div>
                <!-- /// End Departments /// -->

                <hr>

                <div class="form">
                    <!-- /// Add new Department /// -->
                    <div class="form-group">
                        <input-field v-model="title" type="text" label="New Department"></input-field>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a v-on:click="createTask" class="btn-rounded btn-auto">Add new Department +</a>
                    </div>
                    <!-- /// End Add new Department /// -->
                </div>

                <hr class="double">

                <!-- /// Subteams /// -->
                <h3>Subteams</h3> 
                <vue-scrollbar class="table-wrapper">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subteam Name</th>
                                <th>Team Leader</th>
                                <th>Np. of Members</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PMO</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Nelson Carr'">
                                        <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg"/>
                                    </div>
                                </td>
                                <td>12</td>
                                <td>GMP</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Operations</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'David Gilmore'">
                                        <img src="http://dev.campr.biz/uploads/avatars/40.jpg"/>
                                    </div>
                                </td>
                                <td>10</td>
                                <td>Global Operations</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Project Management</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Simona Wang'">
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>
                                </td>
                                <td>2</td>
                                <td>Global Operations</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>EHS</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Uvuvwevwevwe Onyetenyevwe Ugwemubwem Ossas'">
                                        <img src="http://dev.campr.biz/uploads/avatars/49.jpg"/>
                                    </div>
                                </td>
                                <td>10</td>
                                <td>Operations KOH</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>QM</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Danielle Morton'">
                                        <img src="http://dev.campr.biz/uploads/avatars/10.jpg"/>
                                    </div>
                                </td>
                                <td>6</td>
                                <td>Technical Services</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Marketing</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Andrea Millovichi'">
                                        <img src="http://dev.campr.biz/uploads/avatars/64.jpg"/>
                                    </div>
                                </td>
                                <td>1</td>
                                <td>Purchasing KOH</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Engineering</td>
                                <td class="avatar">
                                    <div class="avatar-image" v-tooltip.top-center="'Andrew Willow'">
                                        <img src="http://dev.campr.biz/uploads/avatars/20.jpg"/>
                                    </div>
                                </td>
                                <td>8</td>
                                <td>Techincal Services</td>
                                <td>
                                    <router-link :to="{name: ''}" class="btn-icon"><view-icon fill="second-fill"></view-icon></router-link>
                                    <button data-target="#logistics-edit-modal" data-toggle="modal" type="button" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></button>
                                    <button data-target="#logistics-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </vue-scrollbar>
                <!-- /// End Subteams /// -->

                <hr>
                
                <div class="form">
                    <!-- /// Add new Subteam /// -->
                    <div class="form-group">
                        <input-field v-model="title" type="text" label="New Subteam"></input-field>
                    </div>
                    <div class="flex flex-direction-reverse">
                        <a v-on:click="createTask" class="btn-rounded btn-auto">Add new Subteam +</a>
                    </div>
                    <!-- /// End Add new Subteam /// -->
                </div>
            </div>
        </div>               
    </div>
</template>

<script>
import InputField from '../../_common/_form-components/InputField';
import ViewIcon from '../../_common/_icons/ViewIcon';
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import VTooltip from 'v-tooltip';
import VueScrollbar from 'vue2-scrollbar';

export default {
    components: {
        InputField,
        ViewIcon,
        EditIcon,
        DeleteIcon,
        VTooltip,
        VueScrollbar,
    },
    data() {
        return {
            message: {
                back_to_organization: 'Back to Project Organization',
                edit_organization: 'Edit Project Organization',
                distribution_lists: 'Distribution Lists',
            },
        };
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';
    @import '../../../css/page-section';

    .header {
        .small-link {
            margin: 20px 0 0;
        }

        .small-link + h1 {
            padding: 10px 0 30px;
        }
    }

    h3 {
        font-size: 16px;
        text-transform: uppercase;
        font-weight: 300;
        letter-spacing: 1.6px;
        margin-bottom: 15px;
    }

    h4 {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1.6px;
    }

    ul.roles-hierarchy {
        margin: 0;
        padding: 0;
        list-style: none;

        li {
            span.role {
                display: block;
                background-color: $darkColor;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: $lightColor;
                line-height: 1em;
                padding: 17px 56px 13px 20px;
                position: relative;
                cursor: grab;
                margin-bottom: 20px;

                svg {
                    width: 16px;
                    height: 16px;
                    fill: $lightColor;
                    position: absolute;
                    right: 20px;
                    top: 13px;
                }

                &:active {
                    cursor: grabbing;
                }
            }

            ul {
                li {
                    padding-left: 30px;
                }
            }
        }
    }

    .table {
        .avatar {
            word-wrap: break-word;

            .avatar-image {
                display: inline-block;
                margin: 2px 5px 2px 0;

                img {
                    width: 30px;
                    height: 30px;
                }

                &:last-child {
                    margin-right: 0;
                }
            }
        }
    }

    .pagination-info {
        text-transform: uppercase;
        margin-top: 27px;
    }
</style>
