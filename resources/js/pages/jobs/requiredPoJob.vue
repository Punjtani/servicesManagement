<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">Missing PO's Jobs  ({{ total ? total : "0" }})</h1>
                </div>
                <Navigation></Navigation>

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
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
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
                            <div class="col-sm-3">
                                <div class="form-group">
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
                            <div class="col-sm-3 col-md-2 vvvvvv">
                                <div class="form-group">
                                    <input type="text" v-model.trim="table_jobs.apartment_filter" class="form-control"
                                        placeholder="Unit #">
                                </div>
                            </div>
                            <div class="col-sm-3 col-lg-2 cccccccccccc">
                                <div class="form-group">
                                    <!--<datepicker
                                            placeholder = "Select date"
                                            format="MM/dd/yyyy"
                                            input-class="form-control"
                                            v-model.trim="table_jobs.date_filter"
                                            :typeable="true"
                                            >
                                        </datepicker>-->
                                    <datepicker placeholder="Select date" format="MM-DD-YYYY" input-class="form-control"
                                        v-model.trim="table_jobs.date_filter" :clearable="false" value-type="YYYY-MM-DD">
                                    </datepicker>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-1 vvvvvv">
                                <div class="form-group">
                                    <button type="button" @click="formReset"
                                        class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600 btn-block">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_1rem">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Job #</th>
                                <th class="header-sort"  :class="[sortBy == 'property' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('property')">Property</th>
                                <th class="header-sort"  :class="[sortBy == 'apartment_number' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('apartment_number')">Unit #</th>
                                <th>Service Type</th>

                                <th>Requested For</th>
                                <!--<th>Status</th>-->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="jobs.length > 0">
                            <tr v-for="item in jobs" :key="item.id">
                                <td data-title="Job #">{{ item.id }}</td>
                                <td data-title="Property">{{ item.property.title }}</td>
                                <td data-title="Unit #" v-if="item.apartment_number">{{ item.apartment_number }}</td>
                                <td data-title="Unit #" v-else>--</td>
                                <!--<td data-title="Service Type">
                                <span v-for="(item1, index1) in item.job_services">
                                    <span v-if="index1==0">
                                        {{item1.service.category}} <span v-if="item.job_services.length >  (index1+1)"> , </span>
                                    </span>
                                    <span v-if="index1 > 0 && item1.service.category !== item.job_services[index1-1].service.category ">
                                            {{item1.service.category}} <span v-if="item.job_services.length >  (index1+1)"> , </span>
                                    </span>
                                </span>
                            </td>-->
                                <td data-title="Service Type">
                                    <span v-if="item1.service" v-for="(item1, index1) in item.job_services">
                                        <span v-if="index1 == 0">
                                            {{ removeFirstWord(item1.service.category) }}
                                        </span>
                                        <span
                                            v-if="index1 > 0 && (index1 - 1 == 0) && !item.job_services[index1 - 1].service">
                                            {{ removeFirstWord(item1.service.category) }}
                                        </span>
                                        <span
                                            v-if="index1 > 0 && (index1 - 1 == 0) && item.job_services[index1 - 1].service">
                                            , {{ removeFirstWord(item1.service.category) }}
                                        </span>
                                        <span v-if="index1 > 1">
                                            , {{ removeFirstWord(item1.service.category) }}
                                        </span>
                                    </span>
                                </td>

                                <td data-title="Requested Date" v-if="item.job_services & item.job_services[0] && item.job_services[0].requested_for">
                                    {{ item.job_services[0].requested_for | formatDate }}</td>
                                <td data-title="Requested Date" v-else>--</td>
                                <!--<td data-title="Schedule at"> {{ item.job_status }}</td>-->
                                <td data-title="Action">
                                    <!--                                <router-link  v-bind:to="'/edit-job/' + item.id" class="table_btn_link font_12 font_weight_700 text_uppercase" href="">-->
                                    <!--                                        <span v-if="role == roleData.client">Edit/Request</span>-->
                                    <!--                                        <span v-if="role == roleData.admin || role == roleData.super_admin">Edit/Assign</span>-->
                                    <!--                                </router-link>-->
                                    <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action">
                                            <path
                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                            </path>
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                        <router-link
                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                            v-bind:to="'/edit-job/' + item.id">
                                            <span>Edit</span>
                                        </router-link>
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
import Navigation from "../../components/Navigation";
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
        'Navigation': Navigation,
    },
    data() {
        return {
            total: 0,
            role: "",
            roleData: roleData,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            jobs: [],
            properties: [],
            services: [],
            jobId: "",
            table_jobs: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
                status_filter: "",
                data_filter: "",
                apartment_filter: "",
            },
            searchPropertyId: "",
            searchServiceId: "",
            sortOrder: "desc",
            sortBy: "id",
            filterLoader:false
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        // by default job pages will show all scheduled jobs.
        // this.table_jobs.status_filter = "scheduled";
        this.getInitialData();
        this.refreshRecord();
    },
    methods: {
        optionsToStr(v, type='')
        {
            let trm = [];
            v.forEach(e => {
                if(type=="service")
                {
                    trm.push(e.category)
                } else { trm.push(e.title)}

            })

            return trm.join(" | ");
        },
        clearCurrentFilter(name) {
            if (name == 'service') {

                this.searchServiceId = "";
            } else if (name == 'community') {
                this.searchPropertyId = "";

            }
            this.$refs[name + '_multiselect'].toggle();
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
            this.filterLoader=true;
            let searchProperty_id = [];
            let searchService_id = [];

            // if (this.searchPropertyId.length > 0) {
            //     this.searchPropertyId.forEach((value, index) => {
            //         searchProperty_id.push(value.id);
            //     });
            // } else {
            //     searchProperty_id = "";
            // }

            if (this.searchPropertyId && this.searchPropertyId.id){
                searchProperty_id.push(this.searchPropertyId.id);
            }
            // if (this.searchServiceId.length > 0) {
            //     this.searchServiceId.forEach((value, index) => {
            //         searchService_id.push(value.id);
            //     });

            // } else {
            //     searchService_id = "";
            // }
            if (this.searchServiceId && this.searchServiceId.id){
                searchService_id.push(this.searchServiceId.id);
            }

            let filter_date = "";
            if (this.table_jobs.date_filter) {
                filter_date = this.table_jobs.date_filter;
                // var filter_date_unformatted= new Date(this.table_jobs.date_filter);
                // filter_date= moment(filter_date_unformatted).format('YYYY-MM-DD');
            } else {
                filter_date = "";
            }

            axios.get('/api/required-po-jobs?page=' + this.table_jobs.current_page + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&size=' + this.table_jobs.per_page +
                '&propertyId=' + searchProperty_id + '&serviceId=' + searchService_id + '&date_filter=' + filter_date + '&apartment_filter=' + this.table_jobs.apartment_filter )
                .then((response) => {
                    this.table_jobs.last_page = response.data.jobs.last_page;
                    this.jobs = response.data.jobs.data;
                    this.total = response.data.jobs.total;
                    this.loading = false;
                    this.filterLoader = false;
                    // console.log(this.jobs);
                });
        },
        forceRerender(msg) {
            this.jobId = "";
            this.successmsg = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },



        getJobsSearch() {
            this.table_jobs.current_page = 1;
            this.getJobs();
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
                    if (this.$route.params.service_id) {
                        this.searchServiceId = this.services.find(service => (service.id == this.$route.params.service_id))
                        this.subServiceId = this.$route.params.sub_service_id
                    }
                });
        },
        formReset() {
            this.searchPropertyId = "";
            this.searchServiceId = "";
            this.table_jobs.apartment_filter = "";
            this.table_jobs.date_filter = "";
        },
        getJobsFilter() {
            this.table_jobs.current_page = 1;
            this.getJobs();
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
        'searchPropertyId'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'searchServiceId'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'table_jobs.apartment_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'table_jobs.date_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
    },

}


</script>
