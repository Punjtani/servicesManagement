<template>
    <div>
        <div class="dashboard_content_area">

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Time Sheet</h1></div>
                <Navigation></Navigation>

            </div>
            <div class="row mg_top_bot_40_on_desktop row_spacing_2_on_mobiles">
                <div class="col-12 col-sm-6 col-md-4">
                    <!--<input type="date" class="form-control" v-model="fromDate">-->
                    <datepicker
                        placeholder = "Select date range"
                        format="MM-DD-YYYY"
                        input-class="form-control"
                        v-model.trim="dateRange"
                        :clearable="true"
                        range>
                    </datepicker>
                </div>
                <!--<div class="col-6 col-sm-6 col-md-4">
                    <input type="date" class="form-control" v-model="toDate">
                </div>-->
            </div>
            <div class="table_area all__jobs__table">
                <div  class="no_more_tables table_to_be_used mg_top_0rem table_with_tr_space">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Timesheet # </th>
                            <th>Unit #</th>
                            <th class="text_center_for_md " >Job Detail</th>
                            <th class="text_center_for_md " style="width:50px">Hours:Minutes</th>
                            <th class="text_center_for_md header-sort" :class="[sortBy == 'start_time' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('start_time')">Start Time</th>
                            <th class="text_center_for_md header-sort" :class="[sortBy == 'end_time' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('end_time')">End Time</th>
                            <th v-if="(role == roleData.admin) || (role == roleData.super_admin)" class="text_right_for_md">Action</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in timeRecords" :key="item.id">
                            <td data-title="Timesheet # ">{{ item.id }}</td>
                            <td data-title="Unit #">{{ item.requested_job_service.job && item.requested_job_service.job.property && item.requested_job_service.job.property.title || ''}} {{ item.requested_job_service.job && item.requested_job_service.job.apartment_number || ''}} {{item.requested_job_service.job && item.requested_job_service.job.apartment_type && item.requested_job_service.job.apartment_type.type || ""}}</td>

                            <td data-title="Job Detail" class="text_center_for_md">{{item.requested_job_service.sub_service? item.requested_job_service.sub_service.name : item.requested_job_service.service.category}}</td>

                            <td data-title="Hours:Minutes" style="width:50px" class="text_center_for_md">{{item.hours}}</td>

                            <td data-title="Start Time" class="text_center_for_md">{{item.start_time ? item.start_time.replace(/:[^:]*$/,'') : ''}}</td>

                            <td data-title="End Time" class="text_center_for_md">{{item.end_time ? item.end_time.replace(/:[^:]*$/,''): ''}}</td>

                            <td data-title="Action" v-if="(role == roleData.admin) || (role == roleData.super_admin)" class="text_right_for_md">
<!--                                <a v-if="item.end_time" href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"-->
<!--                                            data-bs-toggle="modal" data-bs-target="#EditTimeSheet"-->
<!--                                            @click="editMethod(item.id)">-->
<!--                                            <span >Edit</span>-->
<!--                                </a>-->
                                <a v-if="item.end_time" href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                </a>
                                <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                    <a v-if="item.end_time" href="#" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                       data-bs-toggle="modal" data-bs-target="#EditTimeSheet"
                                       @click="editMethod(item.id)">
                                        <span >Edit</span>
                                    </a>
                                </div>
                            </td>

                        </tr>

                        </tbody>
                        <tfoot class="bg_grey tfoot_bold_text" v-if=" timeRecords &&  timeRecords.length > 0">
                        <tr>
                            <td class="display_on_desktop"></td>
                            <td class="display_on_desktop"></td>
                            <td class="display_on_desktop text_center_for_md" data-title="Invoice Total">Total Time</td>
                            <td data-title="Total Time" class="text_center_for_md"> {{ totalHours }}</td>
                            <td class="display_on_desktop"></td>
                            <td class="display_on_desktop"></td>

                            <td class="display_on_desktop" v-if="(role == roleData.admin) || (role == roleData.super_admin)"></td>
                        </tr>
                        </tfoot>


                    </table>
                    <div v-if="!loading &&  timeRecords.length <= 0">
                        <EmptySearch></EmptySearch>
                    </div>

                </div>
            </div>
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- Edit Timesheet Model -->
            <div class="modal fade custom_base_model custom_base_model_small" id="EditTimeSheet" tabindex="-1"
                 aria-labelledby="timeSheetEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditTimeSheet @closeEvent="forceRerender"
                           :sheetId="sheetId"></EditTimeSheet>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="ViewNotes" tabindex="-1"
                 aria-labelledby="viewNotes" aria-hidden="true" data-bs-backdrop="static">
                <ViewNotes @closeEvent="forceRerender"
                           :notesId="notesId"></ViewNotes>
            </div>

        </div>
    </div>
</template>

<script>


import Loader from "../LoaderSpinner";

import axios from "axios";
import Pagination from "../Pagination";
// import Datepicker from 'vuejs-datepicker';

import SingleFieldSearch from "../SingleFieldSearch";
import roleData from '../../data.js';
import AddOrEdit from "../employee/EditEmployeesTimeSheet";
import ViewNotes from "../employee/ViewNotes";
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from 'moment';
import Navigation from "../../components/Navigation";
import EmptySearch from "../../../js/pages/EmptySearch.vue"
export default {
    components: {
        'Loader': Loader,
        'Pagination': Pagination,
        'Datepicker': Datepicker,
        'SingleFieldSearch': SingleFieldSearch,
        'EditTimeSheet': AddOrEdit,
        'ViewNotes': ViewNotes,
        'Navigation': Navigation,
        'EmptySearch':EmptySearch
    },
    data() {
        return {
            role: "",
            roleData : roleData,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            fromDate: "",
            toDate: "",
            timeRecords: [],
            totalHours : 0,
            table_jobs:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            employeeId:  this.$route.params.id ,
            url : "",
            sheetId : "",
            notesId:"",
            dateRange:"",
            sortOrder: "desc",
            sortBy: "id",
        }
    },
    mounted() {
        const yourDate = new Date()

        // this.fromDate = yourDate.toISOString().split('T')[0];
        // this.toDate = yourDate.toISOString().split('T')[0];
        // this.fromDate = new Date(yourDate.setDate(yourDate.getDate() - yourDate.getDay())).toISOString().split('T')[0];

        var first = yourDate.getDate() - yourDate.getDay(); // First day is the day of the month - the day of the week
        var last = first + 6; // last day is the first day + 6
        var firstday = new Date(yourDate.setDate(first));
        var lastday = new Date(yourDate.setDate(last));
        // console.log(this.fromDate);
        // this.refreshRecord();
        this.role = localStorage.getItem("role");
        this.dateRange =  [firstday, lastday];
    },
    watch: {
        'fromDate'(val) {
                this.refreshRecord();
        },
        'toDate'(val) {
            this.refreshRecord();
        },
        'dateRange'(val) {
            this.refreshRecord();
        },

    },
    methods: {
        refreshRecord() {
            this.loading=true;
            this.getTimeSheets();
            this.loading=false;
        },
        sorting(sortby) {
            this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },

        getTimeSheets() {
            var date_filter = [];
            if(this.dateRange){
                this.dateRange.forEach(data=>{
                    var filter_date_unformatted= new Date(data);
                    date_filter.push(moment(filter_date_unformatted).format('YYYY-MM-DD'));
                });
            }
            if(this.employeeId){
                this.url = '/api/employee/time-sheet/' + this.employeeId + '?page='+this.table_jobs.current_page+'&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy+'&size='+this.table_jobs.per_page + '&search='+this.table_jobs.search+ '&startDate='+this.fromDate+ '&endDate='+this.toDate+ '&date_filter='+date_filter;
            }else{
                this.url = '/api/employee/time-sheet?page='+this.table_jobs.current_page+'&size='+this.table_jobs.per_page + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&search='+this.table_jobs.search+ '&startDate='+this.fromDate+ '&date_filter='+date_filter;
            }
            axios.get(this.url)
                .then((response) => {
                    this.timeRecords = response.data.timeRecords;
                    this.totalHours = response.data.totalHours;
                });
        },
        forceRerender(msg) {
            this.successmsg = "";
            this.sheetId = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },
        editMethod(id){
            this.sheetId = id;
        },
        viewNotesMethod(id){
            this.notesId = id;
        }

    }
}


</script>

