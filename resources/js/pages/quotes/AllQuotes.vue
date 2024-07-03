<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">


            <div class="row align-items-center row_spacing_5 mg_bott_5">
                <div class="col-md-12 d-flex justify-content-end">
                    <p @click="$router.go(-1)" class="h3 mb-2 cursor-pointer history_back_btn" v-bind:class="{ 'disable-backward': !canGoBack }"><b-icon
                            icon="arrow-left-circle-fill"></b-icon></p>
                    <p @click="$router.go(1)" class="h3 mb-2 cursor-pointer history_farward_btn" v-bind:class="{ 'disable-forward': !canGoForward }" ><b-icon
                            icon="arrow-right-circle-fill"></b-icon></p>
                </div>
                <div class="col-sm-9 col-7">
                    <h1 class="h4 heading_text">All Quotes ({{ total ? total : "0" }})</h1>
                </div>

                <div class="col-sm-3 col-5 text-sm-end">
                    <div class="form-group for_small_btn ">
                        <router-link to="/create-quote"
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
                                            <multiselect deselectLabel="" :clearOnSelect="true"
                                                v-model.trim="searchPropertyId" track-by="title" label="title"
                                                placeholder="Select Community" :select-label="''" :options="properties"
                                                :searchable="true" ref="community_multiselect" :selectedLabel="''">
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
                                    <div class="col-sm-4 mb-sm-2-custom">
                                        <div>
                                            <multiselect deselectLabel="" :clearable="true" :clearOnSelect="true"
                                                v-model.trim="searchServiceId" track-by="category" label="category"
                                                placeholder="Select Service" :select-label="''" :options="services"
                                                :searchable="true" ref="service_multiselect" :selectedLabel="''">

                                                <template #singleLabel="{ option }">
                                                    <div>
                                                        <span class="multiselect__single-label">{{ option.category }}</span>
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
                                    <div class="col-sm-3 mb-sm-2-custom">
                                        <div>
                                            <input type="text" v-model.trim="table_jobs.apartment_filter"
                                                class="form-control" placeholder="Unit #">
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
                                    <div class="col-sm-4 mb-sm-2-custom">
                                        <datepicker input-class="form-control" format="MM-DD-YYYY" :clearable="false"
                                            :placeholder="rangeCheck ? 'Select date range' : 'Select date'"
                                            v-model="table_jobs.data_filter" width="500" :shortcuts="shortcuts"
                                            :range="rangeCheck"></datepicker>

                                    </div>
                                    <div class="col-sm-5">
                                        <div>
                                            <multiselect class=" text_capitalize" deselectLabel="" :allowEmpty="false"
                                                v-model.trim="table_jobs.status_filter" placeholder="Select Status"
                                                :select-label="''" :options="table_jobs.status_filter_options"
                                                :searchable="true" :allow-empty="true" track-by="key" label="key">
                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 vvvvvv mobile_Css  ">
                                        <div>
                                            <button type="button" @click="clearFilter"
                                                class="btn btn-block btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600">
                                                Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_1rem">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="widths_one header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Quote #</th>
                                    <th class="widths_two_S header-sort" :class="[sortBy == 'property' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('property')">Property</th>
                                    <th class="widths_two_S header-sort" :class="[sortBy == 'apartment_number' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('apartment_number')">Unit #</th>
                                    <th class="widths_two_S" >Service Type</th>
                                    <th class="widths_six ttext_center header-sort" :class="[sortBy == 'quote_status' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('quote_status')">Status</th>
                                    <th class="widths_six ttext_center header-sort" :class="[sortBy == 'created_at' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('created_at')">Created</th>
                                    <th class="widths_six ttext_center">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="jobs.length > 0">
                                <tr v-for="item in jobs" :key="item.id">
                                    <td data-title="Quote #">{{ item.id }}</td>
                                    <td data-title="Property">{{ item.property.title }}</td>
                                    <td data-title="Unit #">{{ item.apartment_number ? item.apartment_number : '--' }}</td>
                                    <td data-title="Service Type">
                                        <span v-if="item1.service" v-for="(item1, index1) in item.requested_job_service">
                                            <span v-if="index1 == 0">
                                                {{ removeFirstWord(item1.service.category) }}
                                            </span>
                                            <span
                                                v-if="index1 > 0 && (index1 - 1 == 0) && !item.requested_job_service[index1 - 1].service">
                                                {{ removeFirstWord(item1.service.category) }}
                                            </span>
                                            <span
                                                v-if="index1 > 0 && (index1 - 1 == 0) && item.requested_job_service[index1 - 1].service">
                                                , {{ removeFirstWord(item1.service.category) }}
                                            </span>
                                            <span v-if="index1 > 1">
                                                , {{ removeFirstWord(item1.service.category) }}
                                            </span>
                                        </span>
                                    </td>

                                    <td data-title="Status" class="text_capitalize ttext_center">
                                        {{ item.quote_status | formatTitle }}
                                    </td>
                                    <td class="ttext_center" data-title="Created Date">{{ item.created_at | formatDate }}
                                    </td>
                                    <td data-title="Action" class="ttext_center">
                                        <!--                                <router-link class="table_btn_link font_12 font_weight_700 text_uppercase"  v-bind:to="'/edit-quote/' + item.id" >Edit-->
                                        <!--                                </router-link>-->
                                        <!--                                <router-link  to="#" data-bs-toggle="modal" data-bs-target="#ViewOrCopyQuoteModal" @click.native="setQouteId(item.id)"-->
                                        <!--                                              class="table_btn_link font_12 font_weight_700 text_uppercase">View / Copy-->
                                        <!--                                </router-link>-->
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
                                                v-bind:to="'/edit-quote/' + item.id">
                                                Edit
                                            </router-link>
                                            <router-link @click.native="setQouteId(item.id)"
                                                class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                to="#" data-bs-toggle="modal" data-bs-target="#ViewOrCopyQuoteModal">
                                                View / Copy
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
                    <Pagination v-if="jobs.length > 0 && table_jobs.last_page > 1" @changetable="getQuotes"
                        :table_data="table_jobs"></Pagination>
                </div>
            </div>
            <!--            Delete Model-->
            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                aria-labelledby="deleteModel" aria-hidden="true">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>
            <!--            Edit Models -->
            <!--            copy quote Model-->
            <div class="modal fade custom_base_model custom_base_model_xl" id="ViewOrCopyQuoteModal" tabindex="-1"
                aria-labelledby="ViewOrCopyQuoteModal" aria-hidden="true">
                <ViewOrCopyQuote @closeEvent="dataReset" :quoteId="quoteId"></ViewOrCopyQuote>
            </div>
            <!--            copy quote -->
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
import ViewOrCopyQuote from "./ViewOrCopyQuote";
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
        'ViewOrCopyQuote': ViewOrCopyQuote,

    },
    data() {
        return {
            total: 0,
            quoteId: "",
            role: "",
            clientId: null,
            roleData: roleData,
            filterLoader: false,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            jobs: [],
            properties: [{ 'title': 'All', 'id': 'all' }],
            services: [{ 'category': 'All', 'id': 'all' }],
            jobId: "",
            searchPropertyId: { 'title': 'All', 'id': 'all' },
            searchServiceId: { 'category': 'All', 'id': 'all' },
            job_id: "",
            table_jobs: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
                status_filter: { 'key': 'All', 'value': 'all' },
                status_filter_options: [
                    { 'key': 'All', 'value': 'all' },
                    { 'key': 'Draft', 'value': 'draft' },
                    { 'key': 'Awaiting Response', 'value': 'awaiting_response' },
                    { 'key': 'Changes Requested', 'value': 'changes_requested' },
                    { 'key': 'Converted', 'value': 'converted' },
                    { 'key': 'Approved', 'value': 'approved' },
                    { 'key': 'Archived', 'value': 'archived' },

                ],
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
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        // by default job pages will show all scheduled jobs.
        // this.table_jobs.status_filter = "scheduled";
        this.getInitialData();
        this.refreshRecord();
    },
    computed: {
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
    methods: {
        setQouteId(id) {
            this.quoteId = id
        },
        dataReset() {
            this.quoteId = ""
        },
        refreshRecord() {
            this.loading = true;
            this.getQuotes();
            // this.loading=false;
        },
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        getQuotes() {
            this.filterLoader = true;
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
            if (this.$route.params.id) {
                this.clientId = this.$route.params.id
            }
            axios.get('/api/quotelist?page=' + this.table_jobs.current_page + '&size=' + this.table_jobs.per_page +
                '&propertyId=' + searchProperty_id + '&serviceId=' + searchService_id + '&jobId=' + this.job_id +
                '&quote_status_filter=' + this.table_jobs.status_filter.value + '&data_filter=' + filter_date + '&apartment_filter=' + this.table_jobs.apartment_filter + '&po_number_filter=' + this.table_jobs.po_number_filter + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&clientId=' + this.clientId)
                .then((response) => {
                    this.table_jobs.last_page = response.data.quotes.last_page;
                    this.jobs = response.data.quotes.data;
                    this.total = response.data.quotes.total;
                    this.loading = false;
                    this.filterLoader = false;
                    // // console.log(this.jobs);
                }).catch(e => this.filterLoader = false);
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
            this.getQuotes();
        },
        getJobsFilter() {
            this.table_jobs.current_page = 1;
            this.getQuotes();
        },
        getInitialData() {
            if (this.$route.params.id) {
                this.clientId = this.$route.params.id
            }
            axios.get('/api/job-initial-data?clientId=' + this.clientId)
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
                    if (this.$route.params.job_id) {
                        this.job_id = this.$route.params.job_id

                    }
                });
        },
        formReset() {

            this.table_jobs = {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
                status_filter: { 'key': 'All', 'value': 'all' },
                status_filter_options: [
                    { 'key': 'All', 'value': 'all' },
                    { 'key': 'Draft', 'value': 'draft' },
                    { 'key': 'Awaiting Response', 'value': 'awaiting_response' },
                    { 'key': 'Changes Requested', 'value': 'changes_requested' },
                    { 'key': 'Converted', 'value': 'converted' },
                    { 'key': 'Approved', 'value': 'approved' },
                    { 'key': 'Archived', 'value': 'archived' },
                ],
                data_filter: "",
                data_filter_options: ["daily", "weekly", "monthly", "yearly"],
            },
                this.searchServiceId = "";
            this.job_id = "";
            this.propertyId = "";
            this.$forceUpdate();
        },
        clearFilter() {
            this.searchPropertyId = "";
            this.searchServiceId = "";
            this.table_jobs.apartment_filter = "";
            this.table_jobs.data_filter = "";
            this.table_jobs.status_filter = { 'key': 'All', 'value': 'all' };
            this.table_jobs.po_number_filter = "";
        },
        clearCurrentFilter(name) {
            if (name == 'service') {
                this.searchServiceId = { 'category': 'All', 'id': 'all' };

            } else if (name == 'community') {
                this.searchPropertyId = { 'title': 'All', 'id': 'all' };

            }
            this.$refs[name + '_multiselect'].toggle();

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
            }
        },
        'searchPropertyId'(val) {
            this.table_jobs.current_page = 1;
            this.getQuotes();
        },
        'searchServiceId'(val) {
            this.table_jobs.current_page = 1;
            this.getQuotes();
        },
        'job_id'(val) {
            this.table_jobs.current_page = 1;
            this.getQuotes();

        },
        'table_jobs.data_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getQuotes();
        },
        'table_jobs.status_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getQuotes();
        },
        'table_jobs.apartment_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getQuotes();
        },
        'table_jobs.po_number_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getQuotes();
        },
    },
}


</script>


