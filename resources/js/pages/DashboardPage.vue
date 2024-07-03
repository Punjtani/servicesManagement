<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div>
            <div class="dashboard_content_area">
                <div class="row align-items-center  mg_bot_45">
                    <div class="col-sm-3">
                        <h1>Dashboard</h1>
                    </div>
                    <div class="col-sm-9 text-sm-end" v-if="role != roleData.employee">
                        <div class="form-groups row row_spacing_5 justify-content-end">
                            <div class="col-md-3 mb-2">
                                <router-link to="/job-add" type="button"
                                    class="btn_big_medium btn  btn_blue border_radius_5 text-uppercase font_14 font_weight_600 btn-block">Add
                                    new Job
                                </router-link>
                            </div>
                            <div class="col-md-3">
                                <router-link to="/jobs-calendar" type="button"
                                    class="btn_big_medium btn  btn_blue border_radius_5 text-uppercase font_14 font_weight_600 btn-block">View
                                    Calendar
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard_status_boxes">
                    <div class="row">
                        <div class="col-md-3 dashboard_status_boxe"
                            v-if="role == roleData.admin || role == roleData.super_admin || role == roleData.client || role == roleData.employee"
                            @click="table_dashboard.status_filter = 'all'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#deadline" />
                                </svg>
                            </div>
                            <h6>{{ counts.all_jobs }}
                                <small>All Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe"
                            v-if="role == roleData.admin || role == roleData.super_admin"
                            @click="table_dashboard.status_filter = 'not scheduled'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#deadline" />
                                </svg>
                            </div>
                            <h6>{{ counts.not_scheduled }}
                                <small>Unscheduled Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe" @click="table_dashboard.status_filter = 'today'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#calendar" />
                                </svg>
                            </div>
                            <h6>{{ counts.today }}
                                <small>Today's Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe"
                            v-if="role == roleData.admin || role == roleData.super_admin"
                            @click="table_dashboard.status_filter = 'not assigned'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#user-1" />
                                </svg>
                            </div>
                            <h6>{{ counts.assigned }}
                                <small>Unassigned Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe" v-if="role != roleData.client"
                            @click="table_dashboard.status_filter = 'overdue'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#alarm-clock-of-circular-shape" />
                                </svg>
                            </div>
                            <h6>{{ counts.overdue }}
                                <small>Overdue Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe" @click="table_dashboard.status_filter = 'scheduled'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#weekly-calendar-page-symbol" />
                                </svg>
                            </div>
                            <h6>{{ counts.scheduled }}
                                <small>Scheduled Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe"
                             v-if="role == roleData.admin || role == roleData.super_admin"
                             @click="table_dashboard.status_filter = 'not_ready_to_bill'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#shopping-list" />
                                </svg>
                            </div>
                            <h6>{{ counts.not_ready_to_bill }}
                                <small>Not Ready To Bill Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe" v-if="role != roleData.client"
                            @click="table_dashboard.status_filter = (role == roleData.admin || role == roleData.super_admin) ? 'completed not ready to bill' : 'completed'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#invoice-1" />
                                </svg>
                            </div>
                            <h6>{{ counts.completed }}
                                <small v-if="role == roleData.admin || role == roleData.super_admin">Completed Not Ready To
                                Bill Jobs</small>
                                <small v-if="role == roleData.employee">Completed Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe" v-if="role == roleData.employee"
                            @click="table_dashboard.status_filter = 'not completed'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#invoice-1" />
                                </svg>
                            </div>
                            <h6>{{ counts.not_completed }}
                                <small>Not Completed Jobs</small>
                            </h6>
                        </div>

                        <!-- <div class="col-md-3 dashboard_status_boxe" v-if="role == roleData.admin || role == roleData.super_admin || role == roleData.client"
                            @click="table_dashboard.status_filter = 'missing_pos'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#shopping-list" />
                                </svg>
                            </div>
                            <h6>{{ counts.missing_pos }}
                                <small>Missing POs</small>
                            </h6>
                        </div> -->
                        <div class="col-md-3 dashboard_status_boxe" v-if="role == roleData.admin || role == roleData.super_admin || role == roleData.client"
                            @click="table_dashboard.status_filter = 'cancelled'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#shopping-list" />
                                </svg>
                            </div>
                            <h6>{{ counts.cancelled }}
                                <small>Cancelled Jobs</small>
                            </h6>
                        </div>


                        <div class="col-md-3 dashboard_status_boxe"
                            v-if="role == roleData.admin || role == roleData.super_admin"
                            @click="table_dashboard.status_filter = 'ready_to_bill'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#shopping-list" />
                                </svg>
                            </div>
                            <h6>{{ counts.ready_to_bill }}
                                <small>Completed Ready To Bill Jobs</small>
                            </h6>
                        </div>


                        <div class="col-md-3 dashboard_status_boxe"
                             v-if="role == roleData.admin || role == roleData.super_admin"
                             @click="table_dashboard.status_filter = 'partial_scheduled'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#shopping-list" />
                                </svg>
                            </div>
                            <h6>{{ counts.partial_scheduled }}
                                <small>Partial Scheduled Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe"
                            v-if="role == roleData.admin || role == roleData.super_admin"
                            @click="table_dashboard.status_filter = 'completed_billed_jobs'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#shopping-list" />
                                </svg>
                            </div>
                            <h6>{{ counts.completed_billed_jobs }}
                                <small>Completed Billed Jobs</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe"
                             v-if="role == roleData.admin || role == roleData.super_admin || role == roleData.client"
                             @click="table_dashboard.status_filter = 'request'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#shopping-list" />
                                </svg>
                            </div>
                            <h6>{{ counts.request }}
                                <small>Requests</small>
                            </h6>
                        </div>
                        <div class="col-md-3 dashboard_status_boxe"
                             v-if="role == roleData.admin || role == roleData.super_admin || role == roleData.client"
                             @click="table_dashboard.status_filter = 'missing_pos'">
                            <div class="dashboard_status_icon">
                                <svg class="dashboard_status_icon_svg">
                                    <use xlink:href="#shopping-list" />
                                </svg>
                            </div>
                            <h6>{{ counts.missing_pos }}
                                <small>Missing PO's Jobs</small>
                            </h6>
                        </div>
                    </div>

                </div>
                <div class="table_area">
                    <div class="table_area_head">
                        <div class="row align-items-center row_spacing_5 mg_bott_5">
                            <div class="col-sm-9">
                                <h4 class="font_weight_400 color_yellow text_color_orange"
                                    v-if="table_dashboard.status_filter != ''">
                                    <span v-if="table_dashboard.status_filter == 'scheduled'">Scheduled Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'not scheduled'">Unscheduled Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'request'">Requests  ({{ total ? total : "0"}})</span>
                                    <span
                                        v-if="(table_dashboard.status_filter == 'completed' || table_dashboard.status_filter == 'completed not ready to bill') && role != roleData.employee">Completed Not Ready To Bill Jobs  ({{ total ? total : "0"}})</span>
                                    <span
                                        v-if="(table_dashboard.status_filter == 'completed' || table_dashboard.status_filter == 'completed not ready to bill') && role == roleData.employee">Completed
                                        Jobs ({{ counts.completed }})</span>
                                    <span v-if="table_dashboard.status_filter == 'not completed'">Not Completed Jobs ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'overdue'">Overdue Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'not assigned'">Unassigned Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'today'">Today's Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'missing_pos'">Missing POs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'partial_scheduled'">Partial Scheduled Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'ready_to_bill'">Completed Ready To Bill Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'not_ready_to_bill'">Not Ready To Bill Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'completed_billed_jobs'">Completed Billed Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'cancelled'">Cancelled Jobs  ({{ total ? total : "0"}})</span>
                                    <span v-if="table_dashboard.status_filter == 'all'">All Jobs  ({{ total ? total : "0"}})</span>
                                </h4>
                                <h4 class="font_weight_400 color_yellow text_color_orange" v-else>
                                </h4>
                            </div>
                            <!-- <div class="col-sm-3 text-sm-end" v-if="role != roleData.employee">
                                <div class="form-group">
                                    <router-link  to="/job-add" type="button" class="btn  btn_orange border_radius_5 text-uppercase font_14 font_weight_600 ">Add new Job
                                    </router-link>
                                </div>
                            </div> -->
                            <div v-if="(table_dashboard.status_filter !== '')">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="row align-items-center row_spacing_5">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <!--<multiselect :closeOnSelect="true" deselectLabel="" :clearOnSelect="true" :multiple="true" v-model.trim="searchPropertyId" track-by="title" label="title" placeholder="Select Community" :select-label="''" :options="properties" :searchable="true">
                                                    <template slot="tag">{{ '' }}</template>
                                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                </multiselect>-->
                                                <multiselect deselectLabel="" :clearOnSelect="true"
                                                    v-model.trim="searchPropertyId" track-by="title" label="title"
                                                    placeholder="Select Community" :select-label="''" :options="properties"
                                                    :searchable="true" ref="community_multiselect" :selectedLabel="''">
                                                    <template #singleLabel="{ option }">
                                                        <div>
                                                            <span
                                                                class="multiselect__single-label job-edit">{{ option.title }}</span>
                                                            <button v-if="searchPropertyId" class="multiselect__clear"
                                                                @mousedown="clearCurrentFilter('community')">
                                                                &#x2715;
                                                            </button>
                                                        </div>
                                                    </template>
                                                </multiselect>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <!--<multiselect :closeOnSelect="true" deselectLabel="" :clearOnSelect="true" :multiple="true"  v-model.trim="searchServiceId" track-by="category" label="category" placeholder="Select Service" :select-label="''" :options="services" :searchable="true">
                                                    <template slot="tag">{{ '' }}</template>
                                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                                                </multiselect>-->
                                                <multiselect deselectLabel="" :clearable="false" :clearOnSelect="true"
                                                    v-model.trim="searchServiceId" track-by="category" label="category"
                                                    placeholder="Select Service" :select-label="''" :options="services"
                                                    :searchable="true" ref="service_multiselect" :selectedLabel="''">
                                                    <template #singleLabel="{ option }">
                                                        <div>
                                                            <span
                                                                class="multiselect__single-label job-edit">{{ option.category }}</span>
                                                            <button v-if="searchServiceId" class="multiselect__clear"
                                                                @mousedown="clearCurrentFilter('service')">
                                                                &#x2715;
                                                            </button>
                                                        </div>
                                                    </template>
                                                </multiselect>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="no_more_tables table_to_be_used" v-if="table_dashboard.status_filter !== ''">
                        <table class="table table_with_upper_heading" ref="jobtable" >
                            <thead>
                                <tr>
                                    <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Job #</th>
                                    <th >Property</th>
                                    <th class="header-sort"  :class="[sortBy == 'apartment_number' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('apartment_number')">Unit #</th>
                                    <th>Service Type</th>
                                    <th v-if="(table_dashboard.status_filter == 'request') && (role == roleData.admin || role == roleData.super_admin)">
                                        Requested By</th>
                                    <th class="header-sort" :class="[sortBy == 'scheduled_date' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('scheduled_date')" v-if="table_dashboard.status_filter == 'scheduled'">Scheduled Date</th>
                                    <th  v-if="role != roleData.employee" class="amount-wrap">Amount</th>
                                    <th class="text_end_only_desktop">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="jobs.length > 0" >
                                <tr v-if="!loading" v-for="item in jobs">
                                    <td data-title="Job #">{{ item.id }}</td>
                                    <td data-title="Client Name">{{ item.property.title }}</td>
                                    <td data-title="Unit #" v-if="item.apartment_number">{{ item.apartment_number }}</td>
                                    <td data-title="Unit #" v-else> -- </td>
                                    <td data-title="Service Type">
                                        <div v-if="item1.service" v-for="item1 in  item.requested_job_service">
                                            {{ removeFirstWord(item1.service.category) }}
                                        </div>
                                    </td>
                                    <td v-if="(table_dashboard.status_filter == 'request') && (role == roleData.admin || role == roleData.super_admin)"
                                        data-title=""><span v-if="item.requested_by">{{ item.requested_by.first_name }}
                                            {{ item.requested_by.middle_name }}
                                            {{ item.requested_by.last_name }}</span><span v-else>--</span></td>
                                    <td v-if="table_dashboard.status_filter == 'scheduled'" data-title="Service Type">
                                        <div v-for="item1 in  item.requested_job_service">
                                            {{ item1.scheduled_date | formatDate }}
                                        </div>
                                    </td>
                                    <!--<td data-title="Amount">${{item.total}}</td>-->
                                    <td data-title="Total Amount" v-if="role != roleData.employee" class="amount-wrap">
                                        <div v-for="item1 in  item.requested_job_service">
                                            {{ item1?.requested_job_sub_service[0]?.total | toCurrency }}
                                        </div>
                                    </td>
                                    <td data-title="Action" class="text_end_only_desktop">
                                        <!--                                    <router-link class="table_btn_link font_12 font_weight_700 text_uppercase" v-if="role != roleData.employee" v-bind:to="'/edit-job/' + item.id">View Detail</router-link>-->
                                        <!--                                    <router-link class="table_btn_link font_12 font_weight_700 text_uppercase" v-if="role == roleData.employee" v-bind:to="'/my-jobs/' + item.id" >View Detail</router-link>-->

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
                                                v-if="role != roleData.employee" v-bind:to="'/edit-job/' + item.id">
                                                View Detail
                                            </router-link>
                                            <router-link
                                                class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                v-if="role == roleData.employee" v-bind:to="'/my-jobs/' + item.id">
                                                View Detail
                                            </router-link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div v-if="!loading && jobs.length <= 0 && table_dashboard.status_filter !== ''" class="dashboard-pageination">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="jobs.length > 0 && table_dashboard.last_page > 1" @changetable="getDashboardValues"
                        :table_data="table_dashboard"></Pagination>
                </div>
            </div>
        </div>

        <!--edit transation modal-->
        <div @click="openModal" class="modal fade custom_base_model custom_base_model_small" id="updatePasswordModal"
            tabindex="-1" aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
            <UpdatePassword></UpdatePassword>
        </div>
    </div>
</template>

<script>
import jQuery from 'jquery';
import axios from "axios";
import roleData from '../data.js';
import Loader from "./LoaderSpinner";
import Pagination from "./Pagination";
import EmptySearch from "./EmptySearch";
import Multiselect from 'vue-multiselect';
import UpdatePassword from "./UpdatePassword";



export default {
    components: {
        'Loader': Loader,
        'Pagination': Pagination,
        'EmptySearch': EmptySearch,
        'Multiselect': Multiselect,
        'UpdatePassword': UpdatePassword,
    },
    data() {
        return {
            total: 0,
            role: "",
            roleData: roleData,
            jobs: [],
            counts: [],
            has_error: false,
            loading: true,
            // status_filter: "",
            table_dashboard: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                status_filter: "",
            },
            properties: [],
            services: [],
            searchPropertyId: "",
            searchServiceId: "",
            sortOrder: "desc",
            sortBy: "id",
        }
    },
    computed: {
        myValue() { return this.$store.state.token }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        //show password update modal
        // if (this.role == this.roleData.client || this.role == this.roleData.employee){
        //     setTimeout(function (){
        //         let  sswordStatus = localStorage.getItem('password_status');
        //         // // console.log(passwordStatus)
        //         if (passwordStatus == 0){
        //             document.getElementById("updatePasswordModal").click();
        //         }
        //     },1000);
        // }

        // this.loading=true;
        if (this.table_dashboard.status_filter !== "") {
            this.getDashboardValues();
        }
        this.getDashboardCounts();
        this.getInitialData();

    },
    watch: {
        'table_dashboard.status_filter'(val) {
            this.sortOrder = "desc";
            this.sortBy = "id";
            this.resetFilter();
            this.searchJobs();
        },
        'searchPropertyId'(val) {
            this.table_dashboard.current_page = 1;
            this.searchJobs();
        },
        'searchServiceId'(val) {
            this.table_dashboard.current_page = 1;
            this.searchJobs();
        },
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
        scrollToTable() {
            try {
                this.$refs["jobtable"].scrollIntoView({ behavior: "smooth" })
            } catch(e){}
        },
        resetFilter() {
            this.searchServiceId = "";
            this.searchPropertyId = "";
        },
        getDashboardValues() {
            let searchProperty_id = "";
            let searchService_id = "";
            if (this.searchPropertyId) {
                searchProperty_id = this.searchPropertyId.id;
            }
            if (this.searchServiceId) {
                searchService_id = (this.searchServiceId.id);
            }
            axios.get('/api/dashboard-stats?page=' + this.table_dashboard.current_page + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&size=' + this.table_dashboard.per_page + '&status_filter=' + this.table_dashboard.status_filter + '&propertyId=' + searchProperty_id + '&serviceId=' + searchService_id )
                .then((response) => {
                    this.table_dashboard.last_page = response.data.jobs.last_page;
                    this.total = response.data.jobs.total;
                    if (this.table_dashboard.status_filter === 'scheduled') {
                        this.jobs = response.data.jobs.data;
                    } else {
                        this.jobs = response.data.jobs.data;
                    }
                    // this.loading = false;
                    this.jobs.forEach(item => {
                        item.total = 0
                        // item.scheduled_date = ''
                        item.requested_job_service.forEach(item1 => {
                           try {
                             item.total += Number(item1.requested_job_sub_service[0].total)
                           } catch(e){}
                            // item.scheduled_date = item1.scheduled_date > item.scheduled_date ? item1.scheduled_date : item.scheduled_date
                        });
                    })
                    this.loading = false;
                    this.scrollToTable();
                });
        },
        sortFunc: function (array) {
            return array.slice().sort(function (a, b) {
                return (a.requested_job_service[0].scheduled_date > b.requested_job_service[0].scheduled_date) ? 1 : -1;
            });
        },
        getDashboardCounts() {
            this.loading = true;
            axios.get('/api/dashboard-counts')
                .then((response) => {
                    this.counts = response.data;
                    this.loading = false;
                });
        },
        searchJobs() {
            this.loading = true;
            this.table_dashboard.current_page = 1;
            this.getDashboardValues();
        },
        sorting(sortby) {
            this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.searchJobs();
        },
        getInitialData() {
            axios.get('/api/job-initial-data')
                .then((response) => {
                    // $(response.data.properties).each(function(key, value) {
                    //     let client_name = "";
                    //     client_name = (response.data.properties[key].client != null)? " ("+response.data.properties[key].client.company+")" : "";
                    //     response.data.properties[key].title = response.data.properties[key].title+ client_name;
                    // });
                    this.properties = response.data.properties;
                    this.services = response.data.serviceCategories;
                });
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
        openModal() {
            $("#updatePasswordModal").modal('show');

        },
    },

}
// jQuery(document).ready(function(){
    // // console.log("main ready hun");
// });
</script>
