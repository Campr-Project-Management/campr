<template>
    <div class="row">        
        <div class="col-md-6 col-md-push-6">
            <!-- /// Project Opportunities /// -->
            <div class="ro-grid-wrapper clearfix">
                <!-- /// Project Opportunities Grid /// -->
                <div class="ro-grid">
                    <div class="ro-grid-header vertical-axis-header">
                        <div class="big-header">{{ translateText('message.impact') }}</div>
                        <div class="small-headers clearfix">
                            <div class="small-header">{{ translateText('message.very_low') }}</div>
                            <div class="small-header">{{ translateText('message.low') }}</div>
                            <div class="small-header">{{ translateText('message.high') }}</div>
                            <div class="small-header">{{ translateText('message.very_high') }}</div>
                        </div>
                    </div> 
                    <div class="ro-grid-items clearfix">
                        <div class="ro-grid-item medium"></div>
                        <div class="ro-grid-item low"></div>
                        <div class="ro-grid-item very-low"></div>
                        <div class="ro-grid-item very-low active"></div>
                        <!-- ================================= -->
                        <div class="ro-grid-item high"></div>
                        <div class="ro-grid-item medium"></div>
                        <div class="ro-grid-item low"></div>
                        <div class="ro-grid-item very-low"></div>
                        <!-- ================================= -->
                        <div class="ro-grid-item very-high"></div>
                        <div class="ro-grid-item high"></div>
                        <div class="ro-grid-item medium"></div>
                        <div class="ro-grid-item low"></div>
                        <!-- ================================= -->
                        <div class="ro-grid-item very-high"></div>
                        <div class="ro-grid-item very-high"></div>
                        <div class="ro-grid-item high"></div>
                        <div class="ro-grid-item medium"></div>
                    </div>
                    <div class="ro-grid-header horizontal-axis-header">                            
                        <div class="small-headers clearfix">
                            <div class="small-header">{{ translateText('message.very_low') }}</div>
                            <div class="small-header">{{ translateText('message.low') }}</div>
                            <div class="small-header">{{ translateText('message.high') }}</div>
                            <div class="small-header">{{ translateText('message.very_high') }}</div>
                        </div>
                        <div class="big-header">Probability</div>
                    </div>
                    <div class=""></div>
                </div>
                <!-- /// End Project Opportunities Grid /// -->
            </div>

            <!-- /// Project Risks Summary /// -->
            <div class="ro-summary">
                <div class="text-center flex flex-center">
                    <div class="text-right">
                        <p>{{ translateText('message.priority') }}:</p>
                    </div>
                    <div class="text-left">
                        <p><b>{{ translateText('message.very_high') }}</b></p>
                    </div>
                </div>
            </div>
            <!-- /// End Project Risks Summary /// -->
        </div>
        <div class="col-md-6 col-md-pull-6">
            <div class="page-section">
                <!-- /// Header /// -->
                <div class="header flex-v-center">
                    <div>
                        <router-link :to="{name: 'project-risks-and-opportunities'}" class="small-link">
                            <i class="fa fa-angle-left"></i>
                            {{ translateText('message.back_to_risks_and_opportunities') }}
                        </router-link>
                        <h1>Self Landing Supply Rockets</h1>
                    </div>
                    <div>
                        <div class="header-buttons">
                            <a href="javascript:void(0)" class="btn-icon"><edit-icon fill="second-fill"></edit-icon></a>
                            <a href="javascript:void(0)" class="btn-icon"><delete-icon fill="danger-fill"></delete-icon></a>
                        </div>
                    </div>
                </div>
                <div class="row ro-details">
                    <div class="col-md-12">
                        <div class="ro-info">
                            <p>{{ translateText('message.priority') }}: <b>Very High</b></p>
                            <p>{{ translateText('message.strategy') }}: <b>Increase</b></p>
                            <p>{{ translateText('message.status') }}: <b>Initiated</b></p>
                        </div>
                        
                        <div class="ro-info">
                            <p>{{ translateText('message.budget_saved') }}: <b>$18.500</b></p>
                            <p>{{ translateText('message.time_saved') }}: <b>60 Days</b></p>
                            <p>{{ translateText('message.due_date') }}: <b>31.12.2017</b></p>
                        </div>
                        
                        <div class="ro-info">
                            <p>{{ translateText('message.measures') }}: <b>3</b></p>
                            <p>{{ translateText('message.measures_cost') }}: <b>$8.780</b></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translateText('message.created_on') }} 24.01.2017, 15:33 {{ translateText('message.by') }}
                            <div class="user-avatar"> 
                                <img src="http://dev.campr.biz/uploads/avatars/40.jpg" :alt="'Nelson Carr'"/>
                                <b>Nelson Carr</b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="status-info">
                            {{ translateText('message.responsible') }}: 
                            <div class="user-avatar"> 
                                <img src="http://dev.campr.biz/uploads/avatars/41.jpg" :alt="'Michael Martins'"/>
                                <b>Michael Martins</b>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /// End Header /// -->

                <hr class="double">

                <!-- /// Descripton /// -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis at dolor sollicitudin, interdum nibh quis, faucibus justo. Suspendisse sed nisi id mi aliquam finibus ac sem. Suspendisse in massa in ligula suscipit vulputate. Sed finibus massa nec est rutrum malesuada a et eros. Cras volutpat leo eu lorem viverra ornare.</p>
                <!-- /// End Description /// -->

                <hr class="double">

                <!-- ///  Impact /// -->
                <div class="range-slider-wrapper">
                    <range-slider
                    v-bind:title="message.impact"
                    min="0"
                    max="100"
                    minSuffix=" %"
                    type="single"
                    v-model="opportunityImpact"
                    v-bind:value="opportunityImpact" />
                    <div class="slider-indicator">
                        <indicator-icon fill="middle-fill" position="77" title="Average Impact Value of all opportunities is"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Impact /// -->

                <!-- /// Probability /// -->
                <div class="range-slider-wrapper">
                    <range-slider
                    v-bind:title="message.probability"
                    min="0"
                    max="100"
                    minSuffix=" %"
                    type="single"
                    v-model="opportunityProbability"
                    v-bind:value="opportunityProbability" />
                    <div class="slider-indicator">
                        <indicator-icon fill="middle-fill" position="61" title="Average Probability Value of all opportunities is"></indicator-icon>
                    </div>
                </div>
                <!-- /// End Probability /// -->

                <!-- /// Measures /// -->
                <h3>3 {{ translateText('message.measures') }}</h3>
                <hr>

                <!-- /// Measure /// -->
                <div class="measure" id="measure-1">
                    <!-- /// Comment /// -->
                    <div class="comment">
                        <div class="comment-header flex flex-space-between flex-v-center">
                            <div>
                                <div class="user-avatar"> 
                                    <img src="http://dev.campr.biz/uploads/avatars/58ae8e1f2c465.jpeg" :alt="'John Doe'"/>
                                    <b>John Doe</b>
                                </div>
                                <a href="#link-to-member-page" class="simple-link">@john.d</a>
                                {{ translateText('message.added_a_measure') }} 3 {{ translateText('message.days_ago') }} | {{ translateText('message.edited') }} 4 {{ translateText('message.hours_ago') }}
                            </div>
                            <div class="comment-buttons">
                                <button data-target="#measure-1-edit" class="btn btn-rounded second-bg btn-auto btn-md" data-toggle="modal" type="button">edit</button>
                                <button type="button" data-target="#measure-1-new-comment" class="btn btn-rounded btn-empty btn-auto btn-md go-to" data-toggle="collapse" data-parent="#measure-1" aria-expanded="false">{{ translateText('message.comment') }}</button>
                            </div>
                        </div>
                        <div class="comment-body">
                            <b class="title">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices</b>
                            <p class="cost">{{ translateText('message.cost') }}: <b>$ 2.780</b></p>
                            <p>Morbi lectus massa, sollicitudin quis luctus non, pulvinar sed nibh. Suspendisse id dui a sem tempus pretium. Nunc a ornare lacus. Fusce eleifend enim id euismod scelerisque. Maecenas eu consequat ligula, id mollis mauris. Mauris ac mauris sed lorem vulputate bibendum id ut orci. Maecenas lacinia eget ipsum vitae tincidunt.</p>
                            <ul>
                                <li>Morbi at diam congue ante auctor tincidunt</li>
                                <li>Pellentesque arcu odio</li>
                                <li>Fusce malesuada magna et tincidunt vulputate</li>
                            </ul>
                        </div>  
                        <div class="comment-footer">
                            <attach-icon fill="second-fill"></attach-icon>
                            <ul class="comment-attachments">
                                <li><a href="#" title="Download Attachment">Measure-requirements.docx</a></li>
                                <li><a href="#" title="Download Attachment">Measure-objectives.xls</a></li>
                            </ul>
                        </div>

                        <!-- /// Comments /// -->
                        <div class="comments">
                            <!-- /// Comment /// -->
                            <div class="comment">
                                <div class="comment-header flex flex-space-between flex-v-center">
                                    <div>
                                        <div class="user-avatar"> 
                                            <img src="http://dev.campr.biz/uploads/avatars/64.jpg" :alt="'Anna Doyle'"/>
                                            <b>Anna Doyle</b>
                                        </div>
                                        <a href="#link-to-member-page" class="simple-link">@anna.d</a>
                                        {{ translateText('message.commented') }} 2 {{ translateText('message.days_ago') }}
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p>Morbi sem ligula, cursus id placerat in, convallis non magna. Pellentesque finibus eros nisl, sit amet commodo arcu porttitor eget. Sed vel tincidunt est, sit amet accumsan nibh. Donec lobortis scelerisque purus, quis rhoncus purus congue vel. Proin consectetur mollis feugiat. Suspendisse at faucibus orci. Duis lacus quam, bibendum ut velit non, maximus consectetur velit. Aenean hendrerit suscipit tristique. Suspendisse vitae pulvinar quam, in aliquet mauris.</p>

                                    <p>Suspendisse suscipit mauris sed metus maximus sodales. Sed vitae pretium lectus. Duis metus urna, sodales at rhoncus non, ultricies et libero. Phasellus a iaculis libero. Vestibulum convallis turpis non blandit tincidunt. Donec ullamcorper, magna eget commodo tincidunt, elit tellus fermentum sem, nec volutpat tortor nibh sed nisl. In hac habitasse platea dictumst.</p>
                                </div> 
                            </div>
                            <!-- /// End Comment /// -->

                            <!-- /// Comment /// -->
                            <div class="comment">
                                <div class="comment-header flex flex-space-between flex-v-center">
                                    <div>
                                        <div class="user-avatar"> 
                                            <img src="http://dev.campr.biz/uploads/avatars/60.jpg" :alt="'Christine Wong'"/>
                                            <b>Christine Wong</b>
                                        </div>
                                        <a href="#link-to-member-page" class="simple-link">@christine.w</a>
                                        {{ translateText('message.commented') }} 1 {{ translateText('message.hour_ago') }}
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p>Fusce eleifend enim id euismod scelerisque. Maecenas eu consequat ligula, id mollis mauris. Mauris ac mauris sed lorem vulputate bibendum id ut orci. Maecenas lacinia eget ipsum vitae tincidunt.</p>
                                    <img src="http://www.spacex.com/sites/spacex/files/landingleg_0.jpg" alt="Landing Leg">
                                </div>  
                                <div class="comment-footer">
                                    <attach-icon fill="second-fill"></attach-icon>
                                    <ul class="comment-attachments">
                                        <li><a href="#" title="Download Attachment">landing-legs.jpg</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /// End Comment /// -->
                        </div>
                        <!-- /// End Comments /// -->

                        <!-- /// New Comment /// -->
                        <div class="new-comment collapse" id="measure-1-new-comment">
                            <div class="new-comment-body">
                                <div class="vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('message.new_comment') }}</div>
                                    <Vueditor></Vueditor>
                                </div>
                                <div class="footer-buttons flex flex-space-between">
                                    <a href="javascript:void(0)" class="btn-rounded btn-auto btn-md second-bg">{{ translateText('message.add_comment') }}</a>
                                    <button type="button" data-target="#measure-1-new-comment" class="btn btn-rounded btn-empty btn-auto btn-md" data-toggle="collapse" data-parent="#measure-1" aria-expanded="false">{{ translateText('message.close') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /// End New Comment /// -->
                    </div>
                    <!-- /// End Comment /// -->
                </div>
                <!-- /// End Measure /// -->

                <!-- /// Measure /// -->
                <div class="measure" id="measure-2">
                    <!-- /// Comment /// -->
                    <div class="comment">
                        <div class="comment-header flex flex-space-between flex-v-center">
                            <div>
                                <div class="user-avatar"> 
                                    <img src="http://dev.campr.biz/uploads/avatars/64.jpg" :alt="'Anna Doyle'"/>
                                    <b>Anna Doyle</b>
                                </div>
                                <a href="#link-to-member-page" class="simple-link">@anna.d</a>
                                {{ translateText('message.added_a_measure') }} 2 {{ translateText('message.days_ago') }}
                            </div>
                            <div class="comment-buttons">
                                <button type="button" data-target="#measure-2-new-comment" class="btn btn-rounded btn-empty btn-auto btn-md go-to" data-toggle="collapse" data-parent="#measure-2" aria-expanded="false">{{ translateText('message.comment') }}</button>
                            </div>
                        </div>
                        <div class="comment-body">
                            <b class="title">Nam malesuada dui ut tincidunt ultricies</b>
                            <p class="cost">{{ translateText('message.cost') }}: <b>$ 5.000</b></p>
                            <p>Morbi sem ligula, cursus id placerat in, convallis non magna. Pellentesque finibus eros nisl, sit amet commodo arcu porttitor eget. Sed vel tincidunt est, sit amet accumsan nibh. Donec lobortis scelerisque purus, quis rhoncus purus congue vel. Proin consectetur mollis feugiat. Suspendisse at faucibus orci. Duis lacus quam, bibendum ut velit non, maximus consectetur velit. Aenean hendrerit suscipit tristique. Suspendisse vitae pulvinar quam, in aliquet mauris.</p>

                            <p>Suspendisse suscipit mauris sed metus maximus sodales. Sed vitae pretium lectus. Duis metus urna, sodales at rhoncus non, ultricies et libero. Phasellus a iaculis libero. Vestibulum convallis turpis non blandit tincidunt. Donec ullamcorper, magna eget commodo tincidunt, elit tellus fermentum sem, nec volutpat tortor nibh sed nisl. In hac habitasse platea dictumst.</p>
                        </div>  

                        <!-- /// New Comment /// -->
                        <div class="new-comment collapse" id="measure-2-new-comment">
                            <div class="new-comment-body">
                                <div class="vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('message.new_comment') }}</div>
                                    <Vueditor></Vueditor>
                                </div>
                                <div class="footer-buttons flex flex-space-between">
                                    <a href="javascript:void(0)" class="btn-rounded btn-auto btn-md second-bg">{{ translateText('message.add_comment') }}</a>
                                    <button type="button" data-target="#measure-2-new-comment" class="btn btn-rounded btn-empty btn-auto btn-md" data-toggle="collapse" data-parent="#measure-2" aria-expanded="false">{{ translateText('message.close') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /// End New Comment /// -->
                    </div>
                    <!-- /// End Comment /// -->
                </div>
                <!-- /// End Measure /// -->

                <!-- /// Measure /// -->
                <div class="measure" id="measure-3">
                    <!-- /// Comment /// -->
                    <div class="comment">
                        <div class="comment-header flex flex-space-between flex-v-center">
                            <div>
                                <div class="user-avatar"> 
                                    <img src="http://dev.campr.biz/uploads/avatars/20.jpg" :alt="'Michael Martins'"/>
                                    <b>Michael Martins</b>
                                </div>
                                <a href="#link-to-member-page" class="simple-link">@michael.m</a>
                                {{ translateText('message.added_a_measure') }} 1 {{ translateText('message.day_ago') }}
                            </div>
                            <div class="comment-buttons">
                                <button type="button" data-target="#measure-3-new-comment" class="btn btn-rounded btn-empty btn-auto btn-md go-to" data-toggle="collapse" data-parent="#measure-3" aria-expanded="false">{{ translateText('message.comment') }}</button>
                            </div>
                        </div>
                        <div class="comment-body">
                            <b class="title">Quisque malesuada tellus</b>
                            <p class="cost">{{ translateText('message.cost') }}: <b>$ 1.000</b></p>
                            <p>Sed rutrum mattis elit, eu sodales leo semper et. Nam id elit ipsum. Nam maximus porttitor neque, vitae iaculis mauris auctor vel. Mauris nec ipsum at enim vulputate pellentesque:</p>

                            <ol>
                                <li>Ut faucibus tellus in dui aliquet</li>
                                <li>Pellentesque maximus viverra lacus in egestas</li>
                                <li>Quisque consequat vel sem in euismod</li>
                            </ol>
                        </div> 

                        <!-- /// Comments /// -->
                        <div class="comments">
                            <!-- /// Comment /// -->
                            <div class="comment">
                                <div class="comment-header flex flex-space-between flex-v-center">
                                    <div>
                                        <div class="user-avatar"> 
                                            <img src="http://dev.campr.biz/uploads/avatars/61.jpg" :alt="'Timmy Talbot'"/>
                                            <b>Timmy Talbot</b>
                                        </div>
                                        <a href="#link-to-member-page" class="simple-link">@timmy.t</a>
                                        {{ translateText('message.commented') }} 10 {{ translateText('message.minutes_ago') }}
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p><a href="#link-to-member-page" class="simple-link">@michael.m</a> Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                </div>  
                            </div>
                            <!-- /// End Comment /// -->
                            
                            <!-- /// Comment /// -->
                            <div class="comment">
                                <div class="comment-header flex flex-space-between flex-v-center">
                                    <div>
                                        <div class="user-avatar"> 
                                            <img src="http://dev.campr.biz/uploads/avatars/20.jpg" :alt="'Michael Martins'"/>
                                            <b>Michael Martins</b>
                                        </div>
                                        <a href="#link-to-member-page" class="simple-link">@michael.m</a>
                                        {{ translateText('message.commented') }} 1 {{ translateText('message.day_ago') }}
                                    </div>
                                </div>
                                <div class="comment-body">
                                    <p><a href="#link-to-member-page" class="simple-link">@timmy.t</a> Mauris nec ipsum at enim vulputate pellentesque</p>
                                    <p>Phasellus a iaculis libero. Vestibulum convallis turpis non blandit tincidunt. Donec ullamcorper, magna eget commodo tincidunt, elit tellus fermentum sem, nec volutpat tortor nibh sed nisl. In hac habitasse platea dictumst.</p>
                                </div> 
                            </div>
                            <!-- /// End Comment /// -->
                        </div>
                        <!-- /// End Comments /// -->

                        <!-- /// New Comment /// -->
                        <div class="new-comment collapse" id="measure-3-new-comment">
                            <div class="new-comment-body">
                                <div class="vueditor-holder">
                                    <div class="vueditor-header">{{ translateText('message.new_comment') }}</div>
                                    <Vueditor></Vueditor>
                                </div>
                                <div class="footer-buttons flex flex-space-between">
                                    <a href="javascript:void(0)" class="btn-rounded btn-auto btn-md second-bg">{{ translateText('message.add_comment') }}</a>
                                    <button type="button" data-target="#measure-3-new-comment" class="btn btn-rounded btn-empty btn-auto btn-md" data-toggle="collapse" data-parent="#measure-3" aria-expanded="false">{{ translateText('message.close') }}</button>
                                </div>
                            </div>
                        </div>
                        <!-- /// End New Comment /// -->
                    </div>
                    <!-- /// End Comment /// -->
                </div>
                <!-- /// End Measure /// -->
                <!-- /// End Measures /// -->

                <!-- /// New Measure /// -->
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="vueditor-holder measure-vueditor-holder">
                                <div class="vueditor-header">{{ translateText('placeholder.new_measure') }}</div>
                                <Vueditor ref="content" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group last-form-group">
                        <div class="flex flex-space-between">
                            <div class="col-md-4">
                                <input-field type="text" v-bind:label="translateText('placeholder.measure_cost')" v-model="measureCost" v-bind:content="measureCost" />
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="#" class="btn-rounded btn-auto">{{ translateText('button.add_new_measure') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /// End New Measure /// -->
            </div>
        </div>
    </div>
</template>

<script>
import EditIcon from '../../_common/_icons/EditIcon';
import DeleteIcon from '../../_common/_icons/DeleteIcon';
import AttachIcon from '../../_common/_icons/AttachIcon';
import InputField from '../../_common/_form-components/InputField';
import IndicatorIcon from '../../_common/_icons/IndicatorIcon';
import RangeSlider from '../../_common/_form-components/RangeSlider';

export default {
    components: {
        EditIcon,
        DeleteIcon,
        AttachIcon,
        InputField,
        IndicatorIcon,
        RangeSlider,
    },
    methods: {
        translateText: function(text) {
            return this.translate(text);
        },
    },
    mounted() {
        $('.new-comment').on('shown.bs.collapse', function(e) {
            let $scrollTo = $(this);
            $('html,body').animate({
                scrollTop: $scrollTo.offset().top - 30,
            }, 500);
        });
    },
    data: function() {
        const stepData = 2;

        return {
            measureCost: '',
            message: {
                impact: Translator.trans('message.impact'),
                probability: Translator.trans('message.probability'),
            },
            opportunityImpact: stepData ? stepData.opportunityImpact : 0,
            opportunityProbability: stepData ? stepData.opportunityProbability : 0,
        };
    },
};
</script>

<style lang="scss">
    .tooltip {
        .tooltip-content {
            text-transform: none;
            letter-spacing: 0;
            font-size: 12px;
        }
    }
</style>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
    @import '../../../css/_variables';
    @import '../../../css/_mixins';  
    @import '../../../css/risks-and-opportunities/view';
</style>
