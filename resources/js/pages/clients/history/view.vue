<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">Job History ({{ total ? total : "0" }})</h1>
                </div>
                <Navigation></Navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''">
                <strong>{{ successmsg }}</strong>
            </div>

            <!--<div class="smp__tabs">
                <div v-if="id">
                    <TopNavigation :clientId="id" :currentComponent="currentComponent"></TopNavigation>
                </div>
                <form autocomplete="off" @submit.prevent="getJobs" method="post">
                    <div class="modal-bodys" >
                        <div class="form_box form_box_history">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">History</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row align-items-center row_spacing_5 ">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <multiselect  v-model="search_form.property" track-by="title" label="title" placeholder="Property" :select-label="''" :options="properties" :searchable="true" :allow-empty="true">
                                                </multiselect>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <multiselect  v-model="search_form.service" track-by="category" label="category" placeholder="Service" :select-label="''" :options="services" :searchable="true" :allow-empty="true">
                                                </multiselect>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center row_spacing_5 ">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Unit No" v-model="search_form.unit_number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center row_spacing_5 ">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Invoice #" v-model="search_form.invoice_number">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="PO" v-model="search_form.po_number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center row_spacing_5 mg_top_5">
                                        <label class="mg_bot_12">Scheduled Date</label>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control" v-model="search_form.scheduled_date" :max="search_form.scheduled_date_end">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control" v-model="search_form.scheduled_date_end" :min="search_form.scheduled_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center row_spacing_5 mg_top_5">
                                        <label class="mg_bot_12">Completed Date</label>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control" v-model="search_form.completion_date" :max="search_form.completion_date_end">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="date" class="form-control" v-model="search_form.completion_date_end" :min="search_form.completion_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="col-4">
                                                    <button class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600">
                                                        Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="col-4">
                                                    <button type="button" @click="resetForm" class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600">
                                                        Clear
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div  class="no_more_tables table_to_be_used mg_top_half_rem history_table">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Job Details</th>
                                                    <th>Status</th>
                                                    <th>Amount</th>
                                                    <th class="text_end_only_desktop">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="jobs.length > 0">
                                                <tr v-for="item in jobs" :key="item.id">
                                                    <td data-title="Job Details">Job # {{item.id}} {{item.property.title}} {{item.apartment_number}} {{item.po_number}}</td>
                                                    <td data-title="Status">{{item.job_status}}</td>
                                                    <td data-title="Amount">$ {{item.total}}</td>
                                                    <td data-title="Action" class="text_end_only_desktop">
                                                        <router-link v-if="item.invoice" v-bind:to="'/view-invoice/' + item.invoice.id"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="">View Invoice</a></router-link>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div v-if="jobs.length <=0">
                                                <EmptySearch></EmptySearch>
                                        </div>
                                    </div>
                                    <Pagination v-if="jobs.length > 0 && table_jobs.last_page > 1" @changetable="getJobs"
                                        :table_data="table_jobs"></Pagination>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>-->

            <div class="table_area">
                <div class="table_area_head pd_top_10">
                    <div class="row align-items-center row_spacing_5">
                        <div class="col-md-2">
                            <div class="form-group">
                                <multiselect deselectLabel="" v-model="search_form.property" track-by="title" label="title"
                                    placeholder="Select Property" :select-label="''" :options="properties"
                                    :searchable="true" ref="property_multiselect" :selectedLabel="''" :allow-empty="true">
                                    <template #singleLabel="{ option }">
                                        <div>
                                            <span class="multiselect__single-label edit-communit">{{ option.title }}</span>
                                            <button v-if="search_form.property" class="multiselect__clear"
                                                @mousedown="clearCurrentFilter('property')">
                                                &#x2715;
                                            </button>
                                        </div>
                                    </template>
                                </multiselect>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <multiselect deselectLabel="" v-model="search_form.service" track-by="category"
                                    label="category" placeholder="Select Service" :select-label="''" :options="services"
                                    :searchable="true" ref="service_multiselect" :selectedLabel="''" :allow-empty="true">
                                    <template #singleLabel="{ option }">
                                        <div>
                                            <span class="multiselect__single-label">{{ option.category }}</span>
                                            <button v-if="search_form.service" class="multiselect__clear"
                                                @mousedown="clearCurrentFilter('service')">
                                                &#x2715;
                                            </button>
                                        </div>
                                    </template>
                                </multiselect>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Unit #"
                                    v-model="search_form.unit_number">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Invoice #"
                                    v-model="search_form.invoice_number">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="PO #" v-model="search_form.po_number">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <multiselect v-model="search_form.status_filter" deselectLabel=""
                                    placeholder="Select Status" :select-label="''" :options="status_filter_options"
                                    :searchable="true" ref="status_multiselect" :selectedLabel="''" :allow-empty="true">
                                    <template #singleLabel="{ option }">
                                        <div>
                                            <span class="multiselect__single-label">{{ option }}</span>
                                            <button v-if="search_form.status_filter" class="multiselect__clear"
                                                @mousedown="clearCurrentFilter('status')">
                                                &#x2715;
                                            </button>
                                        </div>
                                    </template>
                                </multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center row_spacing_5">
                        <div class="col-sm-6">
                            <label>Scheduled Date</label>
                            <div class="customTooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 48 48">
                                    <circle cx="24" cy="24" r="21" fill="#2196F3" />
                                    <path fill="#fff" d="M22 22h4v11h-4z" />
                                    <circle cx="24" cy="16.5" r="2.5" fill="#fff" />
                                </svg>
                                <span class="tooltiptext">Please select range you want to search scheduled jobs</span>
                            </div>
                            <div class="form-group">
                                <!--<input type="date" class="form-control" v-model="search_form.scheduled_date" :max="search_form.scheduled_date_end">-->
                                <datepicker placeholder="Select date range" format="MM-DD-YYYY" input-class="form-control"
                                    v-model.trim="search_form.scheduled_date" :clearable="true" range>
                                </datepicker>
                            </div>
                        </div>
                        <!--<div class="col-sm-3">
                            <label>Scheduled To</label>
                            <div class="form-group">
                                <input type="date" class="form-control" v-model="search_form.scheduled_date_end" :min="search_form.scheduled_date">
                            </div>
                        </div>-->
                        <div class="col-md-6">
                            <label>Completed Date</label>
                            <div class="customTooltip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 48 48">
                                    <circle cx="24" cy="24" r="21" fill="#2196F3" />
                                    <path fill="#fff" d="M22 22h4v11h-4z" />
                                    <circle cx="24" cy="16.5" r="2.5" fill="#fff" />
                                </svg>
                                <span class="tooltiptext">Please select range you want to search completed jobs</span>
                            </div>
                            <div class="form-group">
                                <!--<input type="date" class="form-control" v-model="search_form.completion_date" :max="search_form.completion_date_end">-->
                                <datepicker placeholder="Select date range" format="MM-DD-YYYY" input-class="form-control"
                                    v-model.trim="search_form.completion_date" :clearable="true" range>
                                </datepicker>
                            </div>
                        </div>
                        <!--<div class="col-md-3">
                            <label>Completed To</label>
                            <div class="form-group">
                                <input type="date" class="form-control" v-model="search_form.completion_date_end" :min="search_form.completion_date">
                            </div>
                        </div>-->
                    </div>
                    <div class="row">
                        <div class="col-md-12 vvvvvv text-end">
                            <div class="form-group">
                                <button type="button" @click="resetForm"
                                    class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="no_more_tables table_to_be_used mg_top_14">
                    <table class="table table-hover sticky_css">
                        <thead>
                            <tr>
                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Job #</th>
                                <th class="header-sort" :class="[sortBy == 'property' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('property')">Property</th>
                                <th class="header-sort" :class="[sortBy == 'apartment_number' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('apartment_number')" >Unit #</th>
                                <th class="header-sort" :class="[sortBy == 'po_number' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('po_number')">PO #</th>
                                <th class="header-sort" :class="[sortBy == 'invoice_no' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('invoice_no')">Invoice #</th>
                                <th >Service Type</th>
                                <th class="header-sort" :class="[sortBy == 'job_status' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('job_status')">Status</th>
                                <th >Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="jobs.length > 0">
                            <tr v-for="item in jobs" :key="item.id">
                                <td data-title="Job #">{{ item.id }} </td>
                                <td data-title="Property" class="property-job-wrap">{{ item.property.title }}</td>
                                <td data-title="Unit #" v-if="item.apartment_number"> {{ item.apartment_number }}</td>
                                <td data-title="Unit #" v-else>--</td>
                                <td data-title="PO #" v-if="item.po_number"> {{ item.po_number }}</td>
                                <td data-title="PO #" v-else> --</td>
                                <td v-if="item.invoice" data-title="Invoice #">{{ item.invoice.id }}</td>
                                <td v-else data-title="Invoice #"> -- </td>
                                <!--<td data-title="Service Type">
                                   <div v-if="item1.service" v-for="item1 in  item.requested_job_service">
                                        {{item1.service.category}}
                                   </div>
                                </td>-->
                                <td data-title="Service Type" class="history-service-type">
                                    <span v-if="item1.service" v-for="(item1, index1) in item.requested_job_service">
                                        <span v-if="index1 == 0">
                                            {{ item1.service.category }}
                                        </span>
                                        <span
                                            v-if="index1 > 0 && (index1 - 1 == 0) && !item.requested_job_service[index1 - 1].service">
                                            {{ item1.service.category }}
                                        </span>
                                        <span
                                            v-if="index1 > 0 && (index1 - 1 == 0) && item.requested_job_service[index1 - 1].service">
                                            , {{ item1.service.category }}
                                        </span>
                                        <span v-if="index1 > 1">
                                            , {{ item1.service.category }}
                                        </span>
                                    </span>
                                </td>
                                <td v-if="!item.job_cancel" data-title="Status">{{ item.job_status }}</td>
                                <td v-if="item.job_cancel" data-title="Status">Cancelled</td>
                                <td data-title="Total">{{ (item ? item.total : 0) | toCurrency }}</td>

                                <td data-title="Action">

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
                                        <router-link v-if="!item.job_cancel"
                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                            v-bind:to="'/edit-job/' + item.id">
                                            Details
                                        </router-link>
                                        <router-link v-if="item.job_cancel"
                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                            v-bind:to="'/cancel-job-details/' + item.id">
                                            Details
                                        </router-link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && jobs.length <= 0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="jobs.length > 0 && table_jobs.last_page > 1" @changetable="getJobs"
                        :table_data="table_jobs"></Pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { required, requiredIf } from 'vuelidate/lib/validators'
import Loader from "../../LoaderSpinner";
import TopNavigation from "../../clients/TopNavigation";
import Multiselect from 'vue-multiselect';
import Pagination from "../../Pagination";
import EmptySearch from "../../EmptySearch";
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from 'moment';
import Navigation from "../../../components/Navigation";

export default {
    name: "history",
    components: {
        'Loader': Loader,
        'TopNavigation': TopNavigation,
        'Multiselect': Multiselect,
        'Pagination': Pagination,
        'EmptySearch': EmptySearch,
        'Datepicker': Datepicker,
        'Navigation': Navigation,
    },
    data() {
        return {
            total:0,
            currentComponent: 'history',
            successmsg: "",
            loading: false,
            filterLoader:false,
            search_form: {
                property: "",
                service: "",
                scheduled_date: "",
                completion_date: "",
                unit_number: "",

                po_number: "",
                // job_number:"",
                invoice_number: "",
                propertyId: "",
                serviceId: "",
                // scheduled_date_end: "",
                // completion_date_end: "",
                status_filter: "",
            },
            properties: [],
            services: [],
            status_filter_options: ["cancelled", "completed"],
            jobs: [],
            table_jobs: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
            },
            submit: false,
            sortOrder: "desc",
            sortBy: "id",
        }
    },
    validations: {
        search_form: {
            scheduled_date: {
                // required: requiredIf(function (search_form){
                //     return search_form.scheduled_date_end;
                // }),
            },
            // scheduled_date_end:{
            //     // required: requiredIf(function (search_form){
            //     //     return search_form.scheduled_date;
            //     // }),
            // },
            completion_date: {
                // required: requiredIf(function (search_form){
                //     return search_form.completion_date_end;
                // }),
            },
            // completion_date_end:{
            //     // required: requiredIf(function (search_form){
            //     //     return search_form.completion_date;
            //     // }),
            // }
        }
    },
    mounted() {
        this.getPropertiesandServices();
        this.getJobs();
    },
    methods: {
        clearCurrentFilter(name) {
            if (name == 'property') {
                this.search_form.property = "";

            } else if (name == 'service') {
                this.search_form.service = "";

            } else if (name == 'status') {
                this.search_form.status_filter = "";

            }
            this.$refs[name + '_multiselect'].toggle();
        },
        getPropertiesandServices() {
            this.loading = true;
            axios.get('/api/client-history')
                .then((response) => {
                    // $(response.data.properties).each(function(key, value) {
                    //     let client_name = "";
                    //     client_name = (response.data.properties[key].client != null)? " ("+response.data.properties[key].client.company+")" : "";
                    //     response.data.properties[key].title = response.data.properties[key].title+ client_name;
                    // });
                    this.properties = response.data.properties;
                    this.services = response.data.services;
                    // this.loading = false;
                });
        },
        sorting(sortby) {
            this.loading = true;
            this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.getJobs();
            this.loading = false;
        },
        getJobs() {
            // this.loading=true;
            this.filterLoader = true;
            this.submit = true;
            this.$v.search_form.$touch();
            if (this.$v.search_form.$anyError) {
                // this.loading=false;
                return;
            }
            this.search_form.propertyId = this.search_form.property ? this.search_form.property.id : '';
            this.search_form.serviceId = this.search_form.service ? this.search_form.service.id : '';
            var scheduled_date_filter = [];
            var completed_date_filter = [];
            if (this.search_form.scheduled_date) {
                this.search_form.scheduled_date.forEach(data => {
                    var filter_date_unformatted = new Date(data);
                    scheduled_date_filter.push(moment(filter_date_unformatted).format('YYYY-MM-DD'));
                });
                this.search_form.scheduled_date_filter = scheduled_date_filter;
            }
            if (this.search_form.completion_date) {
                this.search_form.completion_date.forEach(data => {
                    var filter_date_unformatted = new Date(data);
                    completed_date_filter.push(moment(filter_date_unformatted).format('YYYY-MM-DD'));
                });
                this.search_form.completed_date_filter = completed_date_filter;
            }
            // console.log(this.search_form);
            axios.post('/api/search-client-history?page=' + this.table_jobs.current_page  + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&size=' + this.table_jobs.per_page, this.search_form)
                .then((response) => {
                    this.table_jobs.last_page = response.data.jobs.last_page;
                    this.jobs = response.data.jobs.data;
                    this.total = response.data.jobs.total;
                    this.jobs.forEach(item => {
                        item.total = 0
                        item.requested_job_service.forEach(item1 => {
                            try {
                                item.total += Number(item1.requested_job_sub_service[0].total)
                            } catch(e){}
                        });
                    })
                    // // console.log(this.jobs);
                    this.loading=false;
                    this.submit = false;
                    this.filterLoader = false;
                });
        },
        resetForm() {
            this.search_form = {
                property: "",
                service: "",
                scheduled_date: "",
                completion_date: "",
                unit_number: "",
                po_number: "",
                // job_number:"",
                invoice_number: "",
                propertyId: "",
                serviceId: "",
                // scheduled_date_end: "",
                // completion_date_end: "",
                status_filter: "",
            }
        }


    },
    watch: {
        'search_form.property'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'search_form.service'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'search_form.unit_number'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'search_form.invoice_number'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'search_form.po_number'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        'search_form.scheduled_date'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        // 'search_form.scheduled_date_end'(val) {
        //         this.getJobs();
        // },
        'search_form.completion_date'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
        // 'search_form.completion_date_end'(val) {
        //     this.getJobs();
        // },
        'search_form.status_filter'(val) {
            this.table_jobs.current_page = 1;
            this.getJobs();
        },
    },
}
</script>

<style scoped></style>
