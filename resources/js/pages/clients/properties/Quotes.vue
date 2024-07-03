<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">{{ clientName }}</h1>
                </div>
                <Navigation></Navigation>

            </div>

            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg !== ''">
                <strong>{{ deletemsg }}</strong>
            </div>

            <div class="smp__tabs">
                <div v-if="clientId">
                    <TopNavigation :clientId="clientId" :currentComponent="currentComponent"></TopNavigation>
                </div>
                <div class="table_area">
                    <div class="table_area_head">
                        <div class="row mb-1">
                            <div class="col-sm-9 col-7">
                                <h5
                                    class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">
                                    Quotes ({{ total ? total : "0" }})</h5>
                            </div>
                            <div class="col-sm-3 col-5 text-sm-end">
                                <div class="form-group for_small_btn ">
                                    <router-link :to="{ path: '/create-quote', query: { clientId: clientId } }"
                                        class="btn_big_medium btn_big btn   btn_orange border_radius_5 text-uppercase font_14 font_weight_600 btn-block ">Add New</router-link>
                                </div>
                            </div>
                        </div>
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
                                                            <span
                                                                class="multiselect__single-label edit-community">{{ option.title }}</span>
                                                            <button v-if="searchPropertyId" class="multiselect__clear"
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
                                                            <span
                                                                class="multiselect__single-label unit-size">{{ option.category }}</span>
                                                            <button v-if="searchServiceId" class="multiselect__clear"
                                                                @mousedown="clearCurrentFilter('service')">
                                                                &#x2715;
                                                            </button>
                                                        </div>
                                                    </template>
                                                </multiselect>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-sm-2-custom">
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
                                        <div class="col-sm-5">
                                            <div>
                                                <multiselect class=" text_capitalize" deselectLabel="" :allowEmpty="false"
                                                    v-model.trim="table_jobs.status_filter" placeholder="Status"
                                                    :select-label="''" :options="table_jobs.status_filter_options"
                                                    :searchable="true" :allow-empty="true" track-by="key" label="key"
                                                    ref="status_multiselect" :selectedLabel="''">
                                                    <template #singleLabel="{ option }">
                                                        <div>
                                                            <span class="multiselect__single-label">{{ option.key }}</span>
                                                            <button v-if="table_jobs.status_filter && option.key != 'All'"
                                                                class="multiselect__clear"
                                                                @mousedown="clearCurrentFilter('status')">
                                                                &#x2715;
                                                            </button>
                                                        </div>
                                                    </template>
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
                                    <th>Service Type</th>
                                    <th class="widths_six ttext_center header-sort" :class="[sortBy == 'quote_status' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('quote_status')">Status</th>
                                    <th class="widths_six ttext_center header-sort" :class="[sortBy == 'created_at' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('created_at')">Created</th>
                                    <th class="widths_two_S ttext_center">Action</th>
                                    </tr>
                                </thead>
                                <tbody v-if="jobs.length > 0">
                                    <tr v-for="item in jobs" :key="item.id">
                                        <td data-title="Quote #">{{ item.id }}</td>
                                        <td data-title="Property">{{ (item.property)? item.property.title : '--' }}</td>
                                        <td data-title="Unit #">{{ item.apartment_number ? item.apartment_number : '--' }}
                                        </td>
                                        <td data-title="Service Type">
                                            <span v-if="item1.service"
                                                v-for="(item1, index1) in item.requested_job_service">
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
                                            {{ item.quote_status | formatTitle }}</td>
                                        <td class="ttext_center" data-title="Created Date">{{ item.created_at | formatDate }}
                                        </td>
                                        <td data-title="Action" class="ttext_center">
                                            <!--                              <router-link class="table_btn_link font_12 font_weight_700 text_uppercase"  v-bind:to="'/edit-quote/' + item.id" >Edit-->
                                            <!--                              </router-link>-->
                                            <!--                              <router-link  to="#" data-bs-toggle="modal" data-bs-target="#ViewOrCopyQuoteModal" @click.native="setQouteId(item.id)"-->
                                            <!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase">View / Copy-->
                                            <!--                              </router-link>-->

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
                                                    v-bind:to="'/edit-quote/' + item.id">Edit
                                                </router-link>
                                                <router-link
                                                    class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                    to="#" data-bs-toggle="modal" data-bs-target="#ViewOrCopyQuoteModal"
                                                    @click.native="setQouteId(item.id)">
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

                <div class="modal fade custom_base_model custom_base_model_large" id="PropertyView" tabindex="-1"
                    aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                    <ViewProperty @closeEvent="forceRerender" :Property="current_property" :name="'quote'"></ViewProperty>
                </div>

                <!--            Edit Models -->

                <div class="modal fade custom_base_model custom_base_model_large" id="AddOrEdit" tabindex="-1"
                    aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                    <AddOrEdit @closeEvent="forceRerender" @formResetEvent="formReset" :clientId="clientId"
                        :PropertyId="propertyId"></AddOrEdit>
                </div>

                <!--            Delete Model-->

                <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                    aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                    <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
                </div>
                <div class="modal fade custom_base_model custom_base_model_xl" id="ViewOrCopyQuoteModal" tabindex="-1"
                    aria-labelledby="ViewOrCopyQuoteModal" aria-hidden="true">
                    <ViewOrCopyQuote @closeEvent="dataReset" :quoteId="quoteId"></ViewOrCopyQuote>
                </div>


            </div>
        </div>
    </div>
</template>


<script>

import DeleteModel from "../../deleteModel";
import Loader from "../../LoaderSpinner";
import roleData from '../../../data.js';
import axios from "axios";
import Pagination from "../../Pagination";
import AddOrEdit from "../properties/AddOrEdit";
import ViewProperty from "../properties/ViewProperty";
import Billing from "../properties/billing.vue";
import SingleFieldSearch from "../../SingleFieldSearch";
import EmptySearch from "../../EmptySearch";
import TopNavigation from "../TopNavigation";
import Navigation from "../../../components/Navigation";
import moment from "moment";

import Multiselect from 'vue-multiselect';
import FilterPage from "../../FilterPage";

// import Datepicker from 'vuejs-datepicker';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import ViewOrCopyQuote from "../../quotes/ViewOrCopyQuote";
export default {

    components: {
        'roleData': roleData,
        'AddOrEdit': AddOrEdit,
        'ViewProperty': ViewProperty,
        'Billing': Billing,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'TopNavigation': TopNavigation,
        'EmptySearch': EmptySearch,
        'Navigation': Navigation,
        'FilterPage': FilterPage,
        'Multiselect': Multiselect,
        'Datepicker': Datepicker,
        'ViewOrCopyQuote': ViewOrCopyQuote,
    },
    data() {
        return {
            total : 0,
            quoteId: "",
            clientId: null,
            clientName: "",
            role: "",
            roleData: roleData,
            currentComponent: 'quotes',
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            quotes: [],
            current_property: "",
            propertyId: "",
            jobs: [],
            properties: [],
            services: [],
            jobId: "",
            searchPropertyId: "",
            searchServiceId: "",
            job_id: "",
            filterLoader: false,
            table_quotes: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },
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
    mounted() {
        this.role = localStorage.getItem("role");
        this.getInitialData();
        this.refreshRecord();
    },
    methods: {
        clearCurrentFilter(name) {
            if (name == 'community') {
                this.searchPropertyId = "";
            } else if (name == 'service') {
                this.searchServiceId = "";
            } else if (name == 'status') {
                this.table_jobs.status_filter = { 'key': 'All', 'value': 'all' };
            }
            this.$refs[name + '_multiselect'].toggle();
        },
        clearFilter() {
            this.searchPropertyId = "";
            this.searchServiceId = "";
            this.table_jobs.apartment_filter = "";
            this.table_jobs.data_filter = "";
            this.table_jobs.status_filter = { 'key': 'All', 'value': 'all' };
            this.table_jobs.po_number_filter = "";
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
            axios.get('/api/quotelist?page=' + this.table_jobs.current_page  + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&size=' + this.table_jobs.per_page +
                '&propertyId=' + searchProperty_id + '&serviceId=' + searchService_id + '&jobId=' + this.job_id +
                '&quote_status_filter=' + this.table_jobs.status_filter.value + '&data_filter=' + filter_date + '&apartment_filter=' + this.table_jobs.apartment_filter + '&po_number_filter=' + this.table_jobs.po_number_filter +  '&clientId=' + this.clientId)
                .then((response) => {
                    this.table_jobs.last_page = response.data.quotes.last_page;
                    this.jobs = response.data.quotes.data;
                    this.total = response.data.quotes.total;
                    this.loading = false;
                    this.getRecord(this.clientId);
                    this.filterLoader = false;
                    // // console.log(this.jobs);
                }).catch(e => this.filterLoader = false);
        },
        getInitialData() {
            if (this.$route.params.id) {
                this.clientId = this.$route.params.id
            }
            axios.get('/api/job-initial-data?clientId=' + this.clientId)
                .then((response) => {

                    // $(response.data.properties).each(function(key, value) {
                    //     let client_name = "";
                    //     client_name = (response.data.properties[key].client != null)? " ("+response.data.properties[key].client?.company+")" : "";
                    //     response.data.properties[key].title = response.data.properties[key].title+ client_name;
                    // });

                    this.properties = response.data.properties;
                    this.services = response.data.serviceCategories;
                    if (this.$route.params.job_id) {
                        this.job_id = this.$route.params.job_id

                    }
                });
        },
        getRecord(id) {
            axios.get('/api/client/' + id + "/edit")
                .then((response) => {
                    this.loading = false;
                    this.clientName = response.data.client?.company;

                });
        },
        forceRerender(msg) {
            this.propertyId = "";
            this.successmsg = "";
            // console.log(msg, 'here is mssge');
            this.refreshRecord();
            if (msg) {
                this.successmsg = msg;
                setTimeout(() => {
                    this.successmsg = "";
                }, 3000);
            }
            this.$forceUpdate();
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

        editIdMethod(type, id) {



            this.propertyId = id;

            // this.$forceUpdate();
        },
        currentProperty(val) {

            this.current_property = val;
            // console.log(this.current_property)
        },
        ApproveQuote(property) {
            axios.get('/api/client-quote-approve/' + property.id).then((response) => {
                // console.log(response);
                this.successmsg = "Quote has been approved";
                this.refreshRecord();
                //    this.$emit('closeEvent', "Quote has been approved");
                //    $("#PropertyView").modal('hide');
            });
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
                    // console.log(response);
                    this.deletemsg = response.data.msg;
                    if (response.data.error) {
                        this.deletemsg = response.data.error
                    }
                    this.refreshRecord();
                }).catch(err => {
                    this.has_error = true;
                })
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        },
        getQuotesSearch() {
            this.table_quotes.current_page = 1;
            this.table_jobs.current_page = 1;
            this.getQuotes();
        },


    }
}


</script>
