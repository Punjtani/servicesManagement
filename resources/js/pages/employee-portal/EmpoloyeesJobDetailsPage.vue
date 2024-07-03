<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="dashboard_content_area" v-if="!loading">

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Job Details</h1></div>
                <Navigation></Navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div id="error-alert" class="alert alert-danger" v-if="errormsg!==''">
                <strong>{{ errormsg }}</strong>
            </div>



            <div class="smp__tabs smp__tabs_three_only new-employee-wrap">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="job_info-tab" data-bs-toggle="tab"
                                data-bs-target="#job_info" type="button" role="tab" aria-controls="job_info"
                                aria-selected="true">Job Details
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                type="button" role="tab" aria-controls="contact" aria-selected="false">Contact
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notes-tab" data-bs-toggle="tab" data-bs-target="#notes"
                                type="button" role="tab" aria-controls="notes" aria-selected="false">Notes
                        </button>
                    </li>
                    <li class="nav-item" role="presentation" v-if="requestedService.service && requestedService.service.category == 'PT Paint'">

                            <button class="nav-link" id="paint-tab"  data-bs-toggle="tab" data-bs-target="#property-paint-specs" type="button"
                            role="tab" aria-controls="paint-tab" aria-selected="false">Paint Specs</button>

                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="job_info" role="tabpanel" aria-labelledby="job_info-tab">

                        <div class="table_area">
                            <div class="table_to_be_used mg_top_1rem table_not_responsive">
                                <table class="table employee-job-table">
                                    <tbody>
                                        <tr>
                                            <th class="min_max_170 emoployee-detail">Property Name</th>
                                            <td>
                                                {{ requestedService.job.property.title }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="min_max_170 emoployee-detail">Service Address</th>
                                            <td>
                                                <p class="mg_0 font_weight_500">{{ requestedService.job.property.street1 }}</p>
                                                <p class="mg_0 font_weight_500">{{requestedService.job.property.street2}} {{requestedService.job.property.city}}  {{requestedService.job.property.state}}, {{requestedService.job.property.zipcode}}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="min_max_170 emoployee-detail">Services</th>
                                            <td>
                                                <div v-for="(item,index) in requestedService.requested_job_sub_service">
                                                    {{item.sub_service.name}}
                                                </div>
                                            </td>
                                        </tr>
                                        <!--<tr>

                                            <th></th>
                                            <td>
                                                {{item.sub_service.name}}
                                            </td>
                                        </tr>-->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">

                        <div class="table_area ">
                            <div class="table_to_be_used mg_top_1rem table_not_responsive">
                                <table class="table employee-job-table">
                                    <tbody>

                                        <tr>
                                            <th data-title="JOB emoployee-detail">Service Address</th>
                                            <td data-title="QTY">
                                                <p class="mg_0 font_weight_500">{{ requestedService.job.property.street1 }}</p>
                                                <p class="mg_0 font_weight_500">{{requestedService.job.property.street2}} {{requestedService.job.property.city}} {{requestedService.job.property.state}}, {{requestedService.job.property.zipcode}}</p>
                                            </td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_to_be_used mg_top_1rem table_not_responsive">
                                <table class="table employee-job-table">
                                    <tbody>
                                        <tr>
                                            <th class="min_max_170 emoployee-detail">Client Name</th>
                                            <td>
                                               <p class="mg_0 font_weight_500">{{ requestedService.job.property.title }}</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="min_max_170 emoployee-detail">Contact Person</th>
                                            <td>
                                                <p v-if="requestedService.job.property_manager" class="mg_0 font_weight_500"> {{ requestedService.job.property_manager.first_name }}
                                                {{ requestedService.job.property_manager.last_name }}</p>
                                                <p v-else class="mg_0 font_weight_500">--</p>
                                            </td>

                                        </tr>
                                        <tr>
                                            <th class="min_max_170 emoployee-detail">Phone Number</th>
                                            <td>
                                                <p v-if="requestedService.job.property_manager" class="mg_0 font_weight_500"> <a :href="'tel:'+requestedService.job.property_manager.phone_no">{{ requestedService.job.property_manager.phone_no }}</a></p>

                                                <p v-else class="mg_0 font_weight_500">--</p>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">

                  <div class="white_box cols_in_5">


                                        <div class="row">
                                            <div class="col-12 cols_12" data-title="Notes">
                                                <h6 class="font_weight_400 color_yellow text_color_orange">Job Notes</h6>
                                                </div>
                                           <div class="col-6 col-md-3 cols_12" data-title="Description">
                                                <textarea disabled style="text-align:left;" class="form-control">{{ requestedService.description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mg_top_1rem" v-if="job_attatchment.length > 0">
                                           <div class="col-12 cols_12" data-title="attachments">
                                            <h6 class="font_weight_400 color_yellow text_color_orange">Job Attachments</h6>
                                            </div>
                                            <div class="col-6 col-md-3 cols_5" v-for="(item, index1) in job_attatchment">
                                                <div class="img__box">
                                                    <img  :src="'/'+item.filename">
                                                </div>
                                            </div>
                                        </div>
                       </div>

                    </div>

                    <div class="tab-pane fade" id="property-paint-specs" role="tabpanel" aria-labelledby="paint-tab">

                         <div class="no_more_tables table_to_be_used mg_top_half_rem add_paint_specs_table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Surface</th>
                                            <th>Manufacturer</th>
                                            <th>Color</th>
                                            <th>Finish</th>
                                            <th>Grade</th>
                                            <th>Coat</th>
                                            <th>Method</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="paint_spec.length > 0">
                                        <tr v-for="(item , index) in paint_spec" :key="item.id">
                                            <td data-title="Surface"><span v-if="item.paint_surface">{{ item.paint_surface.name }}</span><span v-else></span></td>
                                            <td data-title="Manufacturer"><span v-if="item.paint_manufacturer">{{ item.paint_manufacturer.name }}</span><span v-else></span></td>
                                            <td data-title="Color"><span v-if="item.paint_color">{{ item.paint_color.name }}</span><span v-else></span></td>
                                            <td data-title="Finish"><span v-if="item.paint_finish">{{ item.paint_finish.name }}</span><span v-else></span></td>
                                            <td data-title="Grade"><span v-if="item.paint_grade">{{ item.paint_grade.name }}</span><span v-else></span></td>
                                            <td data-title="Coat" class="min_max_80"><span v-if="item.coats">{{ item.coats }}</span><span v-else></span></td>
                                            <td data-title="Method"><span v-if="item.paint_method">{{ item.paint_method.name }}</span><span v-else></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div v-if="!loading && paint_spec.length <=0">
                                    <EmptySearch></EmptySearch>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
            <div class="row mg_top_bot_40_on_desktop row_spacing_5_on_mobiles row_spacing_5">
                <div class="col-sm-4 col-md-4 mg_bot_5">
                    <button data-bs-toggle="modal" data-bs-target="#modalClock" @click="currentTime" :disabled="alreadyStarted || requestedService.end_date != null || otherJobStarted"
                       class="btn btn_medium btn_orange border_radius_5  font_14_on_desktop_and_10_mobiles font_weight_600 btn__Block">Start Time</button>
                </div>
                <div class="col-sm-4 col-md-4 mg_bot_5">
                    <button data-bs-toggle="modal" data-bs-target="#modalClockOut" @click="currentTime" :disabled="!alreadyStarted || requestedService.end_date != null"
                       class="btn btn_medium btn_orange border_radius_5  font_14_on_desktop_and_10_mobiles font_weight_600 btn__Block">End Time</button>
                </div>
                <div class="col-sm-4 col-md-4 mg_bot_5">
                    <button data-bs-toggle="modal" data-bs-target="#report" @click="getReportId"
                       class="btn btn_medium btn_orange border_radius_5  font_14_on_desktop_and_10_mobiles font_weight_600 btn__Block">Report And Job Complete</button>
                </div>

            </div>

            <!--clock in modal-->
            <div class="modal fade custom_base_model custom_base_model_small" id="modalClock" tabindex="-1"
                 aria-labelledby="clockInModal" aria-hidden="true" data-bs-backdrop="static">
                <ClockIn @closeEvent="forceRerender" :requestId = "requestId" :timeNow = "timeNow"></ClockIn>
            </div>
            <!--modal end-->

            <!--clock out modal-->
            <div class="modal fade custom_base_model custom_base_model_small" id="modalClockOut" tabindex="-1"
                 aria-labelledby="clockOutModal" aria-hidden="true" data-bs-backdrop="static">
                <ClockOut @closeEvent="forceRerender" :progressId = "progressId" :startTime = "start_time"  :timeNow = "timeNow"></ClockOut>
            </div>
            <!--modal end-->

            <!--report modal-->
            <div class="modal fade custom_base_model custom_base_model_small" id="report" tabindex="-1"
                 aria-labelledby="reportModal" aria-hidden="true" data-bs-backdrop="static">
                <Report @closeEvent="forceRerender" :requestId = "requestId" ></Report>
            </div>
            <!--modal end-->
            <!--Enlarge Image Popup-->
            <div class="modal fade custom_base_model custom_base_model_large" id="imagePopup" tabindex="-1" aria-labelledby="imagePopup" aria-hidden="true" data-bs-backdrop="static">
                <ImagePopup @closeEvent="clearPopupImagePath" :imagePath= "popupImagePath"></ImagePopup>
            </div>



            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
        </div>
    </div>
</template>
<script>
import axios from "axios";
import {required, minLength, maxLength, email, url, decimal} from 'vuelidate/lib/validators'
import Loader from "../LoaderSpinner";
import moment from 'moment';
import EmptySearch from "../EmptySearch";
import ViewNotes from "../employee/ViewNotes";
import ClockIn from "./jobProgressDetails/clockIn";
import ClockOut from "./jobProgressDetails/clockOut";
import Report from "./jobProgressDetails/report";
import ImagePopup from "../../components/EnlargeImage";
import Navigation from "../../components/Navigation";
export default {
    name: "Add",
    components: {
        'Loader': Loader,
        'ViewNotes': ViewNotes,
        'ClockIn': ClockIn,
        'ClockOut': ClockOut,
        'Report':Report,
        'EmptySearch': EmptySearch,
        'ImagePopup': ImagePopup,
        'Navigation': Navigation,

    },
    data() {
        return {
            acceptImageTypes: ['image/png','image/jpeg'],
            id: this.$route.params.id,
            successmsg: "",
            errormsg: "",
            loading: true,
            otherJobStarted: false,
            requestedService: "",
            timeNow: "",
            alreadyStarted: false,
            currentTimerInfo : "",
            progressId: null,
            start_time:"",
            Existingfiles : [],
            paint_spec:"",
            canMarkComplete: false,
            requestId:"",
            popupImagePath :"",
            job_attatchment:[],

        }
    },

    mounted() {
        this.getRecord(this.id);
    },
    methods: {

        getRecord(id) {
            axios.get('/api/employee/requested-service/' + id)
            .then((response) => {

                this.requestedService = response.data.requestedService;
                this.job_attatchment = this.requestedService.job.requested_job_service.find(rjs => (rjs.id == this.requestedService.id)).job_attatchment;
                this.otherJobStarted = response.data.otherJobStarted;
                this.paint_spec = response.data.paintSpec;
                this.loading = false;
                (response.data.progress).forEach(item =>{
                    if(item.start_time != null && item.end_time == null){
                        this.alreadyStarted = true;
                        this.currentTimerInfo = item;
                        // console.log(this.currentTimerInfo);
                    }
                })

                if (this.requestedService.job.property.staff && this.requestedService.job.property.staff.length > 0){

                    let managerData = {};
                    this.requestedService.job.property.staff.forEach(function( staff ) {
                        if (staff.staff_roles){
                            if(staff.staff_roles.name == 'Property Manager'){
                                if (staff.user){
                                    let firstName = staff.user.first_name ? staff.user.first_name : "";
                                    let lastName =   staff.user.last_name ? staff.user.last_name : "";
                                    managerData = {
                                        email:staff.user.email,
                                        first_name:firstName,
                                        last_name:lastName,
                                        phone_no:staff.phone ? staff.phone : "",
                                    }
                                }

                            }else {
                                // console.log("property manager not found")
                            }
                        }
                    });
                    this.requestedService.job.property_manager = managerData;
                    // // console.log("this.requestedService",this.requestedService)
                }

            });

        },
        currentTime() {
            var today = new Date();
            this.timeNow = today.getHours() + ":" + today.getMinutes();
            if(!this.alreadyStarted){
                this.requestId = this.requestedService.id;
            }else{
                this.progressId = this.currentTimerInfo.id;
                this.start_time = moment(this.currentTimerInfo.start_time).format('MMMM Do YYYY, h:mm a');
            }

        },
        getReportId(){
            this.requestId = this.requestedService.id;
        },

       forceRerender() {

            this.progressId = null;
            this.start_time = '';
            this.requestId = null;
            this.timeNow = '';
            this.loading = true;
            this.alreadyStarted=false;
            this.getRecord(this.id);
		},
        onImageClick(i){
            this.popupImagePath = i.filename;
            $("#imagePopup").modal('show');
        },
        clearPopupImagePath(){
            this.popupImagePath = "";
        }


    }
}
</script>
