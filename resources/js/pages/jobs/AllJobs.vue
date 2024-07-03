<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">


            <div class="row align-items-center row_spacing_5 mg_bott_5">
                <div class="col-md-12 d-flex justify-content-end">
                    <p @click="$router.go(-1)"  v-bind:class="{ 'disable-backward': !canGoBack }" class="h3 mb-2 cursor-pointer history_back_btn"><b-icon
                            icon="arrow-left-circle-fill"></b-icon></p>
                    <p @click="$router.go(1)" class="h3 mb-2 cursor-pointer history_farward_btn"  v-bind:class="{ 'disable-forward': !canGoForward }"><b-icon
                            icon="arrow-right-circle-fill"></b-icon></p>
                </div>
                <div class="col-sm-9 col-7">
                    <h1 class="h4 heading_text">{{ currentPageNameC == 'Today' ? "Today's" : currentPageNameC }} Jobs ({{ total || "0" }})</h1>
                </div>

                <div class="col-sm-3 col-5 text-sm-end align-right">
                    <div class="form-group for_small_btn ">
                        <router-link to="/job-add"
                            class="btn_big_medium btn_big btn   btn_orange border_radius_5 text-uppercase font_14 font_weight_600 btn-block ">Add New</router-link>
                    </div>
                </div>
            </div>

            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg !== ''">
                <strong>{{ deletemsg }}</strong>
            </div>

            <div class="table_area">
                <div class="table_area_head">
                    <div class="table_area_head pd_top_0">


                        <div class="row align-items-center row_spacing_5">
                            <div class="col-sm-12 col-md-12 col-lg-7">
                                <div class="row align-items-center row_spacing_5">
                                    <div class="col-sm-5 mb-sm-2-custom">
                                        <div>
                                            <multiselect deselectLabel="" v-model.trim="searchPropertyId" track-by="title"
                                                label="title" placeholder="Select Community" :select-label="''"
                                                :options="properties" :searchable="true" ref="community_multiselect"
                                                :selectedLabel="''" :allow-empty="true">
                                                <template #singleLabel="{ option }">
                                                    <div>
                                                        <span class="multiselect__single-label job-community">{{ option.title }}</span>
                                                        <button v-if="searchPropertyId && searchPropertyId.title != 'All'"
                                                            class="multiselect__clear"
                                                            @mousedown="clearCurrentFilter('community')">
                                                            &#x2715;
                                                        </button>
                                                    </div>
                                                </template>
                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 mb-sm-2-custom">
                                        <div>
                                            <multiselect deselectLabel="" :clearable="false" :clearOnSelect="true"
                                                v-model.trim="searchServiceId" track-by="category" label="category"
                                                placeholder="Select Service" :select-label="''" :options="services"
                                                :searchable="true" ref="service_multiselect" :selectedLabel="''">
                                                <template #singleLabel="{ option }">
                                                    <div>
                                                        <span class="multiselect__single-label unit-size">{{ option.category }}</span>
                                                        <button v-if="searchServiceId && searchServiceId.category != 'All'"
                                                            class="multiselect__clear"
                                                            @mousedown="clearCurrentFilter('service')">
                                                            &#x2715;
                                                        </button>
                                                    </div>
                                                </template>
                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-sm-2-custom">
                                        <div>
                                            <input type="text" v-model.trim="table_jobs.apartment_filter"
                                                class="form-control" placeholder="Unit #">
                                        </div>
                                    </div>
                                    <div class="col-sm-2 mb-sm-2-custom">
                                        <div>
                                            <input type="text" v-model.trim="table_jobs.po_number_filter"
                                                class="form-control" placeholder="PO #">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-5 row_spacing_5 text-sm-end">

                                <div class="row align-items-center row_spacing_5">
                                    <!--<div class="col-sm-3 vvvvvv">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="table_jobs.apartment_filter"  class="form-control" placeholder="Unit No">
                                    </div>
                                </div>-->
                                    <div class="col-sm-6 mb-sm-2-custom">
                                        <datepicker input-class="form-control" format="MM-DD-YYYY" :clearable="false"
                                            :placeholder="rangeCheck ? 'Select date range' : 'Select date'"
                                            v-model="table_jobs.data_filter" width="500" :shortcuts="shortcuts"
                                            :range="rangeCheck"></datepicker>
                                        <!--<div class="form-group">
                                        <datepicker
                                            placeholder = "Select date"
                                            format="MM/dd/yyyy"
                                            input-class="form-control"
                                            v-model.trim="table_jobs.data_filter"
                                            :typeable="true"
                                            >
                                        </datepicker>
                                    </div>-->
                                    </div>
                                    <div class="col-sm-6 vvvvvv ">
                                        <div>
                                            <multiselect class=" text_capitalize" deselectLabel="" :selectedLabel="''"
                                                :allowEmpty="false" v-model.trim="table_jobs.status_filter"
                                                placeholder="Status" :select-label="''"
                                                :options="table_jobs.status_filter_options" :searchable="true"
                                                :allow-empty="true"></multiselect>
                                        </div>
                                    </div>
<!--                                    <div class="col-sm-2 vvvvvv mobile_Css  ">-->
<!--                                        <div>-->
<!--                                            <button type="button" @click="clearFilter"-->
<!--                                                class="btn btn-block btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600">-->
<!--                                                Clear-->
<!--                                            </button>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                </div>

                            </div>

                                <div class="text-end mobile_Css mt-1">
                                    <div class="form-group">
                                        <button type="button" @click="clearFilter"
                                                class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">
                                            Clear
                                        </button>
                                    </div>
                                </div>

                        </div>
                    </div>

                    <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_1rem">
                        <table class="table table-hover">
                            <thead>
                                <tr>


                                    <th class="widths_one header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Job # </th>
                                    <th class="widths_two_S header-sort" :class="[sortBy == 'property' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('property')">Property</th>
                                    <th class="widths_two header-sort"  :class="[sortBy == 'apartment_number' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('apartment_number')">Unit #</th>
                                    <th class="widths_two header-sort" :class="[sortBy == 'invoice_no' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('invoice_no')">Invoice #</th>
                                    <th class="widths_two_S">Service Type</th>
                                    <th class="widths_two_S header-sort ttext_center" :class="[sortBy == 'requested_on' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('requested_on')">Requested For</th>
                                    <th class="widths_two_S  header-sort ttext_center date-job" :class="[sortBy == 'completed_on' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('completed_on')">Completed On</th>
                                    <th class="widths_six header-sort ttext_center header-sort" :class="[sortBy == 'job_status' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('job_status')">Status</th>
                                    <th class="widths_seven  ttext_center header-sort" :class="[sortBy == 'is_billed' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('is_billed')">Billing</th>
                                    <th class="widths_seven ttext_center header-sort" :class="[sortBy == 'payment' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('payment')">Payment</th>
                                    <th class="widths_nine ttext_center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="jobs.length > 0">
                                <tr v-for="item in jobs" :key="item.id">
                                    <td data-title="Job #">{{ item.id }}</td>
                                    <td data-title="Property">{{ item.property.title }}</td>
                                    <td data-title="Unit #">{{ item.apartment_number ? item.apartment_number : '--' }}</td>
                                    <td data-title="Invoice #">
                                        <span v-if="item.invoice_no">{{ item.invoice_no }}</span>
                                        <span v-else>--</span>
                                    </td>
                                    <td data-title="Service Type">
                                        <div v-if="item1.service" v-for="item1 in  item.requested_job_service">
                                            {{ removeFirstWord(item1.service.category) }}
                                        </div>
                                        <!-- <span v-if="item1.service" v-for="(item1, index1) in item.requested_job_service">
                                            <span v-if="index1 == 0">
                                                {{ removeFirstWord(item1.service.category) }}
                                            </span>
                                            <span
                                                v-if="index1 > 0 && (index1 - 1 == 0) && !item.requested_job_service[index1 - 1].service">
                                                {{ removeFirstWord(item1.service.category) }}
                                            </span>
                                            <span
                                                v-if="index1 > 0 && (index1 - 1 == 0) && item.requested_job_service[index1 - 1].service">
                                                 {{ removeFirstWord(item1.service.category) }}
                                            </span>
                                            <span v-if="index1 > 1">
                                                 {{ removeFirstWord(item1.service.category) }}
                                            </span>
                                        </span> -->
                                    </td>
                                    <td class="ttext_center" data-title="Requested On" v-if="item.requested_job_service[0] ">
                                        {{ item.requested_job_service[0].requested_for | formatDate}}
                                    </td>
                                    <td class="ttext_center" data-title="Requested Date" v-else>
                                        --
                                    </td>
                                    <td class="ttext_center" data-title="Completed Date"
                                        v-if="item.requested_job_service[0] && item.requested_job_service[0].end_date">
                                        {{ item.requested_job_service[0].end_date | formatDate }}
                                    </td>
                                    <td class="ttext_center" data-title="Completed Date" v-else>--</td>
                                    <td data-title="Status" class="text_capitalize ttext_center"
                                        v-if="item.job_status == 'not scheduled'"> Unscheduled</td>
                                    <td data-title="Status" class="text_capitalize ttext_center" v-else>
                                        {{ item.job_status }}
                                    </td>
                                    <td class="ttext_center" data-title="Billed">
                                        <span v-if="item.job_status == 'completed' && item.is_billed == 2">Ready To
                                            Bill</span>
                                        <span v-else-if="item.is_billed == 1">Billed</span>
                                        <span v-else>Not Ready To Bill</span>
                                    </td>
                                    <td class="ttext_center" data-title="Paid">
                                        {{ item.invoice && item.invoice.is_paid == 1 ? 'Paid' : 'Unpaid' }}
                                    </td>
                                    <td data-title="Action" class="ttext_center">
                                        <!-- <router-link class="table_btn_link font_12 font_weight_700 text_uppercase" v-if="role==roleData.admin || role==roleData.super_admin" v-bind:to="'/edit-job/' + item.id" >

                                    Edit/Assign

                                </router-link> -->

                                        <!-- <router-link v-if="role==roleData.client" v-bind:to="'/edit-job/' + item.id"
                                class="table_btn_link font_12 font_weight_700 text_uppercase">Edit/Request</router-link> -->
                                        <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" viewBox="0 0 16 16"
                                                class="bi bi-three-dots-vertical no_action">
                                                <path
                                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                </path>
                                            </svg>
                                        </a>
                                        <div class="dropdown-menu custom-action-dropdown"
                                            aria-labelledby="dropdownMenuButton">
                                            <router-link
                                                class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                v-if="role == roleData.admin || role == roleData.super_admin"
                                                v-bind:to="'/edit-job/' + item.id">

                                                Edit/Assign

                                            </router-link>
                                            <router-link v-if="role == roleData.client" v-bind:to="'/edit-job/' + item.id"
                                                class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar">Edit/Request</router-link>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                        <div v-if="!loading && jobs.length <= 0">
                            <EmptySearch></EmptySearch>
                        </div>
                    </div>
                    <Pagination v-if="jobs.length > 0 && table_jobs.last_page > 1" @changetable="getJobs"
                        :table_data="table_jobs"></Pagination>
                </div>
            </div>
            <!--            Delete Model-->
            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                aria-labelledby="deleteModel" aria-hidden="true">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>
            <!--            Edit Models -->
        </div>
    </div>
</template>
<script>

import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";

import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import Multiselect from 'vue-multiselect';
import FilterPage from "../FilterPage";
import EmptySearch from "../EmptySearch";
import roleData from '../../data.js';
import moment from 'moment';
// import Datepicker from 'vuejs-datepicker';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'FilterPage': FilterPage,
        'Multiselect': Multiselect,
        'EmptySearch': EmptySearch,
        'Datepicker': Datepicker,

    },
    data() {
        return {
            total: "0",
            currentPageName: "All",
            role: "",
            roleData: roleData,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            jobs: [],
            properties: [],
            services: [],
            jobId: "",
            searchPropertyId: "",
            searchServiceId: "",
            job_id: "",
            table_jobs: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
                status_filter: "",
                status_filter_options: ["all", "scheduled", "unscheduled", "unassigned", "partial scheduled", "completed","completed billed", "completed ready to bill", "not ready to bill","completed not ready to bill","today","overdue"],
                data_filter: "",
                data_filter_options: ["daily", "weekly", "monthly", "yearly"],
                apartment_filter: "",
                po_number_filter: "",

            },
            rangeCheck: false,
            shortcuts: [
                {
                    text: 'Range/Date',
                    onClick: () => {
                        this.rangeCheck = !this.rangeCheck;
                    }
                },
            ],
            sortOrder: "desc",
            sortBy: "id",
            filterLoader:false,
        }
    },
    computed: {
        currentPageNameC() {
           return this.toTitleCase(this.table_jobs.status_filter || 'All');
        },
        canGoForward() {
           let canGoForw = false;
try {
    canGoForw =  window.navigation.canGoForward;
} catch (error) {
      if (window.history && typeof window.history.goForward === 'function') {         canGoForw = window.history.goForward();
    } else if (window.history && (window.history.length > 1 && window.history.length > (history.state && history.state.index || 0))) {
        canGoForw = true;
    } else {
        canGoForw = false;
    }
}
return canGoForw;
        },
        canGoBack() {
            let canGo = false;
try {
    canGo =  window.navigation.canGoBack;
} catch (error) {
    if (window.history && typeof window.history.goBack === 'function') {
        canGo =  true;
    } else if (window.history && window.history.length > 1) {
        canGo = true;
    } else {
        canGo = false;
    }
}
return canGo;
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        // by default job pages will show all scheduled jobs.
        // this.table_jobs.status_filter = "scheduled";
        this.job_id = this.$route?.params.job_id ?  this.$route?.params.job_id : ""
        this.getInitialData();
        if (this.$route.query && this.$route.query.status) {
            if(this.table_jobs.status_filter_options.includes((this.$route.query.status.replaceAll('-',' ')).toLowerCase()))
            {
                this.table_jobs.status_filter = (this.$route.query.status.replaceAll('-',' ')).toLowerCase();
                this.currentPageName = this.toTitleCase(this.table_jobs.status_filter);
            } else if (this.$route.query && this.$route.query.status) {
                // this.job_id = this.$route.query.status
                this.currentPageName = "All";
            }
        } else {
            this.refreshRecord();
            this.currentPageName = "All";
        }

    },


    methods: {
        clearCurrentFilter(name) {
            if (name == 'service') {
                this.searchServiceId = "";

            } else if (name == 'community') {
                this.searchPropertyId = "";

            }
            this.$refs[name + '_multiselect'].toggle();
        },
        toTitleCase(str) {
            return str.toLowerCase().split(' ').map(function(word) {
                return word.charAt(0).toUpperCase() + word.slice(1);
            }).join(' ');
        },
        refreshRecord() {
            this.loading = true;
            this.getJobs();
            // this.loading=false;
        },
        sorting(sortby) {
            this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        getJobs() {

            this.filterLoader = true;
            // let searchProperty_id = [];
            // let searchService_id = [];
            // if(this.searchPropertyId.length > 0){
            //     this.searchPropertyId.forEach((value,index) => {
            //         searchProperty_id.push(value.id);
            //     });
            // }else{
            //     searchProperty_id = "";
            // }

            // if(this.searchServiceId.length > 0){
            //     this.searchServiceId.forEach((value,index) => {
            //         searchService_id.push(value.id);
            //     });

            // }else{
            //     searchService_id = "";
            // }
            let searchProperty_id = "";
            let searchService_id = "";
            if (this.searchPropertyId) {
                searchProperty_id = this.searchPropertyId.id;
            }
            if (this.searchServiceId) {
                searchService_id = (this.searchServiceId.id);
            }
            // let filter_date = "";
            let filter_date = [];
            if (this.table_jobs.data_filter) {
                if (this.table_jobs.data_filter.length > 1) {
                    this.table_jobs.data_filter.forEach((value, index) => {
                        var filter_date_unformatted = new Date(value);
                        filter_date.push(moment(filter_date_unformatted).format('YYYY-MM-DD'));
                    });
                } else {
                    var filter_date_unformatted = new Date(this.table_jobs.data_filter);
                    filter_date = moment(filter_date_unformatted).format('YYYY-MM-DD');
                }
            }
            let url = '/api/job?page=' + this.table_jobs.current_page  + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&size=' + this.table_jobs.per_page +
                '&propertyId=' + searchProperty_id + '&serviceId=' + searchService_id + '&jobId=' + this.job_id +
                '&status_filter=' + this.table_jobs.status_filter + '&data_filter=' + filter_date + '&apartment_filter=' + this.table_jobs.apartment_filter + '&po_number_filter=' + this.table_jobs.po_number_filter;
            url = url.replaceAll('undefined', '');
            url = url.replaceAll('unassigned','not assigned');
            axios.get(url)
                .then((response) => {
                    this.table_jobs.last_page = response.data.jobs.last_page;
                    this.jobs = response.data.jobs.data;
                    this.total = response.data.jobs.total;
                    this.loading = false;
                    this.filterLoader = false;


                    // // console.log(this.jobs);
                });
        },
        forceRerender(msg) {
            this.jobId = "";
            this.successmsg = "";
            // // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        editIdMethod(id) {
            this.jobId = id;
        },

        deleteRequest(id, title, api) {
            this.deleteId = id;
            this.deleteTitle = title;
            this.deleteApi = api;
        },
        deleteRecord(val) {
            if (val == 0) {
                this.deleteId = "";
                this.deleteTitle = "";
                this.deleteApi = "";
                return
            }
            axios.delete('/api/' + this.deleteApi + '/' + this.deleteId)
                .then((response) => {
                    this.deletemsg = response.data.msg;
                    this.refreshRecord();
                });
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        },
        getJobsSearch() {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        getJobsFilter() {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        getInitialData() {

            axios.get('/api/job-initial-data')
                .then((response) => {
                    // this.properties = response.data.properties;
                    // this.services = response.data.serviceCategories;


                    // $(response.data.properties).each(function(key, value) {
                    //     let client_name = "";
                    //     client_name = (response.data.properties[key].client != null)? " ("+response.data.properties[key].client.company+")" : "";
                    //     response.data.properties[key].title = response.data.properties[key].title+ client_name;
                    // });

                    response.data.properties.forEach((value, index) => {
                        this.properties.push(value);
                    });
                    response.data.serviceCategories.forEach((value, index) => {
                        this.services.push(value);
                    });
                });
        },
        formReset() {

            this.table_jobs = {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
                status_filter: "",
                status_filter_options: ["all", "scheduled", "unscheduled", "unassigned", "partial scheduled", "completed","completed billed", "completed ready to bill", "not ready to bill","completed not ready to bill", "today","overdue"],
                data_filter: "",
                data_filter_options: ["daily", "weekly", "monthly", "yearly"],
            },
            this.searchServiceId = "";
          //  this.job_id = "";
            this.propertyId = "";
            this.$forceUpdate();
        },
        clearFilter() {
            this.searchPropertyId = "";
            this.searchServiceId = "";
            this.table_jobs.apartment_filter = "";
            this.table_jobs.data_filter = "";
            this.table_jobs.status_filter = "all";
            this.table_jobs.po_number_filter = "";
            // this.$router.push('/all-jobs');
        },
        removeFirstWord(str) {
            str = str.replace("PU ", "");
            str = str.replace("PT ", "");
            str = str.replace("CA ", "");
            str = str.replace("RF ", "");
            str = str.replace("JA ", "");
            str = str.replace("FL ", "");
            return str;
        },
    },
    watch: {
        '$route.params.job_id'(val) {
            if (!val) {
                this.formReset();
            } else {
                this.loading = true;
              //  this.clearFilter();
                this.formReset();
                if(this.$route.params.job_id) {
                    this.job_id = this.$route.params.job_id
                }
            }
        },
        '$route.query.reload'(val){

        },
        '$route.query'(val) {
            if(val && val.reload == 1  ) {
                this.formReset();
                this.clearFilter();
                // this.$nextTick(() => {
                // //this.$router.replace({ query: {...this.$route.query, "reload": '0' } });
                // });
            }
            if (!val || !val.status) {
                this.formReset();
            } else {
                this.loading = true;
               // this.clearFilter();
               // this.formReset();
                if(this.table_jobs.status_filter_options.includes((this.$route.query.status.replaceAll('-',' ')).toLowerCase()))
                {
                    this.table_jobs.status_filter = (this.$route.query.status.replaceAll('-',' ')).toLowerCase();
                    this.currentPageName = this.toTitleCase(this.table_jobs.status_filter);
                } else if (this.$route.query && this.$route.query.status) {
                    // this.job_id = this.$route.query.status
                }
            }
        },
        'searchPropertyId'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'searchServiceId'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'job_id'(val) {
            this.getJobs();

        },
        'table_jobs.data_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'table_jobs.status_filter'(val) {
         //   this.getJobs();
         this.table_jobs.current_page = 1;
         let sts = val.toLowerCase().replaceAll(" ","-");

         this.$nextTick(() => {
                if (!this.$route.query || sts !== this.$route.query.status) {
                    this.$router.replace({ query: {...this.$route.query,  "status": sts, "reload": '0'  } });
                    this.getJobs();

                } else { this.getJobs(); }
        });

        },
        'table_jobs.apartment_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'table_jobs.po_number_filter'(val) {
            // console.log("");
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
    },
}


</script>
