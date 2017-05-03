<template>
    <div class="page-section">
        <!-- /// P&M Header /// -->
        <div class="header">
            <h1>Phases &amp; Milestones</h1>
        </div>  
        <!-- /// End P&M Header /// --> 

        <!-- /// P&M Timeline /// -->
        <vis-timeline></vis-timeline>
        <!-- /// End P&M Timeline /// -->    

        <!-- /// Phases Header /// -->
        <div class="header flex flex-space-between margintop30">
            <div class="flex">
                <h1>Project Phases</h1>
            </div>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-phases-create-phase'}" class="btn-rounded btn-auto second-bg">Add New Phase</router-link>
            </div>
        </div>
        <div class="full-filters flex flex-direction-reverse">
            <phase-filters></phase-filters>
        </div>
        <!-- /// End Phases Header /// -->

        <!-- /// Phases List /// -->
        <div class="phases-list margintop20">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th class="small-cell">ID</th>
                                <th>Phase</th>
                                <th class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <th class="text-center" colspan="3">Base Schedule</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Start</th>
                                            <th class="text-center">Finish</th>
                                            <th class="text-center">Duration</th>
                                        </tr>
                                    </table>
                                </th>
                                <th class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <th class="text-center" colspan="3">Forecast Schedule</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Start</th>
                                            <th class="text-center">Finish</th>
                                            <th class="text-center">Duration</th>
                                        </tr>
                                    </table>
                                </th>
                                <th class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <th class="text-center" colspan="3">Actual Schedule</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Start</th>
                                            <th class="text-center">Finish</th>
                                            <th class="text-center">Duration</th>
                                        </tr>
                                    </table>
                                </th>
                                <th>Status</th>
                                <th>Responsible</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="small-cell">7</td>
                                <td>Phase 7</td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">11.11.2017</td>
                                            <td class="text-center">20.12.2017</td>
                                            <td class="text-center">39</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">11.11.2017</td>
                                            <td class="text-center">20.12.2017</td>
                                            <td class="text-center">39</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                    </table> 
                                </td> 
                                <td>Pending</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">6</td>
                                <td>Phase 6</td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">19.09.2017</td>
                                            <td class="text-center">10.11.2017</td>
                                            <td class="text-center">22</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">19.09.2017</td>
                                            <td class="text-center">10.11.2017</td>
                                            <td class="text-center">22</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                    </table> 
                                </td> 
                                <td>Pending</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Maria Anthony'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/10.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">5</td>
                                <td>Phase 5</td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">19.08.2017</td>
                                            <td class="text-center">18.09.2017</td>
                                            <td class="text-center">30</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">19.08.2017</td>
                                            <td class="text-center">18.09.2017</td>
                                            <td class="text-center">30</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                    </table> 
                                </td> 
                                <td>Pending</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Adrianna Chekz'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/64.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">4</td>
                                <td>Phase 4</td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">17.06.2017</td>
                                            <td class="text-center">18.08.2017</td>
                                            <td class="text-center">62</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">17.06.2017</td>
                                            <td class="text-center">18.08.2017</td>
                                            <td class="text-center">62</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr class="column-alert">
                                            <td class="text-center">17.06.2017</td>
                                            <td class="text-center">-</td>
                                            <td class="text-center">-</td>
                                        </tr>
                                    </table> 
                                </td> 
                                <td>Ongoing</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Bill Connors'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/41.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">3</td>
                                <td>Phase 3</td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">16.03.2017</td>
                                            <td class="text-center">15.06.2017</td>
                                            <td class="text-center">92</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr class="column-warning">
                                            <td class="text-center">20.03.2017</td>
                                            <td class="text-center">15.06.2017</td>
                                            <td class="text-center">82</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr class="column-warning">
                                            <td class="text-center">20.03.2017</td>
                                            <td class="text-center">15.06.2017</td>
                                            <td class="text-center">82</td>
                                        </tr>
                                    </table> 
                                </td> 
                                <td class="warning-color">On Hold</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Neil Wattson'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/40.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">2</td>
                                <td>Phase 2</td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">01.03.2017</td>
                                            <td class="text-center">15.03.2017</td>
                                            <td class="text-center">14</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">01.03.2017</td>
                                            <td class="text-center">15.03.2017</td>
                                            <td class="text-center">14</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">01.03.2017</td>
                                            <td class="text-center">15.03.2017</td>
                                            <td class="text-center">14</td>
                                        </tr>
                                    </table> 
                                </td> 
                                <td class="second-color">Finished</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Jean Down'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/44.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">1</td>
                                <td>Phase 1</td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">01.01.2017</td>
                                            <td class="text-center">28.02.2017</td>
                                            <td class="text-center">58</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr>
                                            <td class="text-center">01.01.2017</td>
                                            <td class="text-center">28.02.2017</td>
                                            <td class="text-center">58</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="no-padding">
                                    <table class="table inner-table">
                                        <tr class="column-alert">
                                            <td class="text-center">01.01.2017</td>
                                            <td class="text-center">03.03.2017</td>
                                            <td class="text-center">61</td>
                                        </tr>
                                    </table> 
                                </td> 
                                <td class="second-color">Finished</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Mobutu Seseseco'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/49.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vue-scrollbar>

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination">
                    <span class="active">1</span>
                </div>
                <div>
                    <span class="pagination-info">Displaying 7 results out of 7</span>
                </div>
            </div>
        </div>
        <!-- /// End Phases List /// -->

        <!-- /// Milestones Header /// -->
        <div class="header flex flex-space-between margintop30">
            <div class="flex">
                <h1>Project Milestones</h1>
            </div>
            <div class="flex flex-v-center">
                <router-link :to="{name: 'project-milestones-create-milestone'}" class="btn-rounded btn-auto second-bg">Add New Milestone</router-link>
            </div>
        </div>
        <div class="full-filters flex flex-direction-reverse">
            <milestone-filters></milestone-filters>
        </div>
        <!-- /// End Milestones Header /// -->

        <!-- /// Milestones List /// -->
        <div class="phases-list margintop20">
            <vue-scrollbar class="table-wrapper">
                <div class="scroll-wrapper">
                    <table class="table table-striped table-responsive">
                        <thead>
                            <tr>
                                <th class="small-cell">ID</th>
                                <th>Milestone</th>
                                <th>Base due date</th>
                                <th>Forecast due date</th>
                                <th>Actual due date</th>
                                <th>Status</th>
                                <th>Responsible</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="small-cell">35</td>
                                <td>QM Strategy Defined</td>
                                <td>15.06.2017</td>
                                <td>15.06.2017</td>
                                <td>-</td>
                                <td>OK</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">34</td>
                                <td>Customer Communication Strategy</td>
                                <td>01.05.2017</td>
                                <td>01.05.2017</td>
                                <td>-</td>
                                <td>OK</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">33</td>
                                <td>HR Requirements Defined</td>
                                <td>22.05.2017</td>
                                <td>22.05.2017</td>
                                <td>-</td>
                                <td class="warning-color">On Hold</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">32</td>
                                <td>Ressource Plan Finalized</td>
                                <td>18.04.2017</td>
                                <td>18.04.2017</td>
                                <td class="danger-color">03.05.2017</td>
                                <td class="danger-color">Overdue</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">31</td>
                                <td>Relocation Strategy Defined</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td class="second-color">Reached</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">30</td>
                                <td>Relocation Strategy Defined</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td class="second-color">Reached</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">29</td>
                                <td>Relocation Strategy Defined</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td class="second-color">Reached</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">28</td>
                                <td>Relocation Strategy Defined</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td class="second-color">Reached</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">27</td>
                                <td>Relocation Strategy Defined</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td>10.03.2017</td>
                                <td class="second-color">Reached</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="small-cell">26</td>
                                <td>Relocation Strategy Brainstorming</td>
                                <td>02.03.2017</td>
                                <td>02.03.2017</td>
                                <td>02.03.2017</td>
                                <td class="second-color">Reached</td>
                                <td class="small-avatar text-center">
                                    <div class="user-avatar" v-tooltip.bottom-center="'Phase responsible: Sandy Fanning-Choi'"> 
                                        <img src="http://dev.campr.biz/uploads/avatars/60.jpg"/>
                                    </div>                                    
                                </td>
                                <td>
                                    <a href="#" class="btn-icon"><view-icon fill="second-fill"></view-icon></a>
                                    <a gref="#" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                                    <button data-target="#phase-1-delete-modal" data-toggle="modal" type="button" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vue-scrollbar>

            <div class="flex flex-direction-reverse flex-v-center">
                <div class="pagination">
                    <span class="">1</span>
                    <span class="active">2</span>
                    <span class="">1</span>
                </div>
                <div>
                    <span class="pagination-info">Displaying 10 results out of 21</span>
                </div>
            </div>
        </div>
        <!-- /// End Milestones List /// -->
    </div>
</template>

<script>
import VueScrollbar from 'vue2-scrollbar';
import VisTimeline from '../_common/_phases-and-milestones-components/VisTimeline';
import PhaseFilters from '../_common/_phases-and-milestones-components/PhaseFilters';
import MilestoneFilters from '../_common/_phases-and-milestones-components/MilestoneFilters';
import EditIcon from '../_common/_icons/EditIcon';
import DeleteIcon from '../_common/_icons/DeleteIcon';
import ViewIcon from '../_common/_icons/ViewIcon';

export default {
    components: {
        VueScrollbar,
        VisTimeline,
        PhaseFilters,
        MilestoneFilters,
        EditIcon,
        DeleteIcon,
        ViewIcon,
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../css/page-section';

    .table {
        .table {
            background-color: transparent;
            width: 100%;

            &.inner-table {
                width: 370px;
                table-layout: fixed;

                td, th {
                    width: 33.333333% !important;
                }
            }
        }

        .small-cell {
            width: 90px;
            padding-right: 0;
        }
    }
</style>
