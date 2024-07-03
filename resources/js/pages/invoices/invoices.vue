<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">All Invoices  ({{ total ? total : "0" }})</h1>
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
                    <div class="table_area_head pd_top_10">
                        <div class="row align-items-center row_spacing_5">

                            <div class="col-lg-6">
                                <div class="row align-items-center row_spacing_5">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <multiselect deselectLabel="" :closeOnSelect="true" :clearOnSelect="true"
                                                :multiple="false" v-model.trim="table_invoices.property_filter"
                                                track-by="title" label="title" placeholder="Select Community"
                                                :select-label="''" :options="properties" :searchable="true"
                                                ref="community_multiselect" :selectedLabel="''">
                                                <template #singleLabel="{ option }">
                                                    <div>
                                                        <span class="multiselect__single-label job-community">{{ option.title }}</span>
                                                        <button v-if="table_invoices.property_filter"
                                                            class="multiselect__clear"
                                                            @mousedown="clearCurrentFilter('community')">
                                                            &#x2715;
                                                        </button>
                                                    </div>
                                                </template>

                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <input type="text" v-model.trim="table_invoices.apartment_filter"
                                                class="form-control" placeholder="Unit #">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <input type="text" v-model.trim="table_invoices.po_number_filter"
                                                class="form-control" placeholder="PO #">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <input type="text" v-model.trim="table_invoices.invoice_no_filter"
                                                class="form-control" placeholder="Invoice #">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="row text-sm-end row_spacing_5">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <datepicker placeholder="Created On" format="MM-DD-YYYY"
                                                input-class="form-control" v-model.trim="table_invoices.date_filter"
                                                :clearable="false">
                                            </datepicker>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <datepicker placeholder="Due date" format="MM-DD-YYYY"
                                                input-class="form-control" v-model.trim="table_invoices.due_date_filter"
                                                :clearable="false">
                                            </datepicker>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <multiselect class=" text_capitalize" deselectLabel=""
                                                v-model.trim="table_invoices.status_filter" placeholder="Status"
                                                :select-label="''" :options="table_invoices.status_filter_options"
                                                :searchable="true" :allow-empty="true" ref="status_multiselect"
                                                :selectedLabel="''">
                                                <template #singleLabel="{ option }">
                                                    <div>
                                                        <span class="multiselect__single-label">{{ option }}</span>
                                                        <button v-if="table_invoices.status_filter_options"
                                                            class="multiselect__clear"
                                                            @mousedown="clearCurrentFilter('status')">
                                                            &#x2715;
                                                        </button>
                                                    </div>
                                                </template>

                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <button type="button" @click="resetForm"
                                                class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600 btn-block">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="no_more_tables table_to_be_used mg_top_14">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Invoice #</th>
                                    <th >Property</th>
                                    <th class="header-sort"  :class="[sortBy == 'apartment_number' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('apartment_number')">Unit #</th>
                                    <!--<th>Apartment Type</th>-->
                                    <th class="header-sort"  :class="[sortBy == 'job_id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('job_id')">Job #</th>
                                    <!--<th>Client</th>-->
                                    <th class="header-sort"  :class="[sortBy == 'created_at' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('created_at')">Created On</th>
                                    <th class="header-sort"  :class="[sortBy == 'due_date' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('due_date')">Due Date</th>
                                    <!--                            <th>Bill</th>-->
                                    <th class="header-sort"  :class="[sortBy == 'status' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('status')">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="invoices.length > 0">
                                <tr v-for="item in invoices" :key="item.id">
                                    <td data-title="Invoice #">{{ item.id }}</td>
                                    <td data-title="Property">{{ item.job.property.title }}</td>
                                    <td data-title="Unit #" v-if="item.job.apartment_number">
                                        {{ item.job.apartment_number }}
                                    </td>
                                    <td data-title="Unit #" v-else> -- </td>
                                    <td data-title="Job #"> {{ item.job.id }}</td>
                                    <td data-title="Invoice Date">{{ item.created_at | formatDate }}</td>
                                    <td data-title="Due Date">{{ item.due_date | formatDate }}</td>
                                    <td v-if="item.is_cancelled" data-title="Status">Cancelled</td>
                                    <td data-title="Status" v-if="item.is_paid == 0 && item.is_cancelled == 0">UnPaid</td>
                                    <td data-title="Status" v-if="item.is_paid == 1 && item.is_cancelled == 0">Paid</td>
                                    <td data-title="Status" v-if="item.is_paid == 2 && item.is_cancelled == 0">Partial Paid
                                    </td>

                                    <!--                            <td v-if ="item.is_cancelled" data-title="Action">No Details Available</td>-->
                                    <td data-title="Action">
                                        <!--                                <router-link v-if="role==roleData.admin || role==roleData.super_admin"   v-bind:to="'/view-invoice/' + item.id" href="#" class="font_weight_700 table_btn_link">Details-->
                                        <!--                                </router-link>-->
                                        <!--                                <a @click="viewInvoice(item.id)"  data-bs-toggle="modal" data-bs-target="#viewInvoice" class="font_weight_700 table_btn_link">View PDF-->
                                        <!--                                </a>-->
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
                                                v-bind:to="'/view-invoice/' + item.id">
                                                Details
                                            </router-link>
                                            <a @click="viewInvoice(item.id)"

                                                class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar">View PDF</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="!loading && invoices.length <= 0">
                            <EmptySearch></EmptySearch>
                        </div>
                        <Pagination v-if="invoices.length > 0 && table_invoices.last_page > 1" @changetable="getInvoices"
                            :table_data="table_invoices"></Pagination>
                        <!--to view pdf-->
                        <div class="modal fade custom_base_model custom_base_model_large" id="viewInvoice" tabindex="-1"
                            aria-labelledby="lineItem" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
                            <viewPdf :url="url" @closeEvent="ClosePdf"></viewPdf>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>\


<script>

import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";
import EmptySearch from "../EmptySearch";
import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import FilterPage from "../FilterPage";
import Multiselect from 'vue-multiselect';
import moment from 'moment';
// import Datepicker from 'vuejs-datepicker';
import viewPdf from './viewPdf';
import roleData from '../../data.js';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Navigation from "../../components/Navigation";
export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'FilterPage': FilterPage,
        'Multiselect': Multiselect,
        'Datepicker': Datepicker,
        'viewPdf': viewPdf,
        'Navigation': Navigation,
    },
    data() {
        return {
            total:0,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            invoices: [],
            filterLoader:false,
            departmentId: "",
            role: "",
            roleData: roleData,
            table_invoices: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
                status_filter: "",
                status_filter_options: ["all", "paid", "unpaid", "cancelled", "partial paid"],
                property_filter: "",
                apartment_filter: "",
                invoice_no_filter: "",
                date_filter: "",
                due_date_filter: "",
                po_number_filter: "",

            },
            url: '',
            properties: [],
            sortOrder: "desc",
            sortBy: "id",
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        this.refreshRecord();
        this.getInitialData();
    },
    methods: {
        clearCurrentFilter(name) {
            if (name == 'community') {
                this.table_invoices.property_filter = "";
            }
            if (name == 'status') {
                this.table_invoices.status_filter = "";
            }
            this.$refs[name + '_multiselect'].toggle();

        },
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        refreshRecord() {
            this.loading = true;
            this.getInvoices();
            // this.loading=false;
        },

        getInvoices() {
            // // console.log(this.table_invoices.property_filter);
            this.filterLoader=true;
            var propertyId = "";
            var filter_date = "";
            var filter_due_date = "";
            if (this.table_invoices.property_filter) {
                propertyId = this.table_invoices.property_filter.id;
            }
            if (this.table_invoices.date_filter) {
                console.log(this.table_invoices.date_filter);
                var filter_date_unformatted = new Date(this.table_invoices.date_filter);
                filter_date = moment(filter_date_unformatted).format('YYYY-MM-DD');
            }
            if (this.table_invoices.due_date_filter) {
                var filter_date_unformatted = new Date(this.table_invoices.due_date_filter);
                filter_due_date = moment(filter_date_unformatted).format('YYYY-MM-DD');
            }
            axios.get('/api/invoice?page=' + this.table_invoices.current_page  + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&size=' + this.table_invoices.per_page + '&search=' + this.table_invoices.search + '&status_filter=' + this.table_invoices.status_filter + '&apartment_filter=' + this.table_invoices.apartment_filter + '&property_filter=' + propertyId + '&invoice_number=' + this.table_invoices.invoice_no_filter + '&date_filter=' + filter_date + '&due_date_filter=' + filter_due_date + '&po_number_filter=' + this.table_invoices.po_number_filter + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.table_invoices.last_page = response.data.invoices.last_page;
                    this.invoices = response.data.invoices.data;
                    this.total = response.data.invoices.total;
                    // this.invoices = this.sortFunc(this.invoices);
                    this.loading = false;
                    // // console.log(this.invoices);
                    this.filterLoader = false;

                });
        },
        sortFunc: function (array) {
            return array.slice().sort(function (a, b) {
                return (a.job.requested_job_service[0].end_date < b.job.requested_job_service[0].end_date) ? 1 : -1;
            });
        },
        forceRerender(msg) {
            this.departmentId = "";
            this.successmsg = "";
            // // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },
        viewInvoice(id) {
            axios.get('/api/download-invoice/' + id, {
                responseType: 'blob'
            }).then((response) => {
                var url = URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
                this.url = url;
                $("#viewInvoice").modal("show");
            });
        },
        editIdMethod(id) {
            this.departmentId = id;
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
        getInvoicesSearch() {
            this.table_invoices.current_page = 1;
            this.getInvoices();
        },
        getInvoicesFilter() {
            this.table_invoices.current_page = 1;
            this.getInvoices();
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
                });
        },
        resetForm() {
            this.table_invoices.status_filter = "";
            this.table_invoices.property_filter = "";
            this.table_invoices.apartment_filter = "";
            this.table_invoices.invoice_no_filter = "";
            this.table_invoices.date_filter = "";
            this.table_invoices.po_number_filter = "";
            this.table_invoices.due_date_filter = "";
        },
        ClosePdf() {
            this.url = '';
        }

    },

    watch: {
        'table_invoices.property_filter'(val) {
            this.table_invoices.current_page = 1;
            this.getInvoices();
        },
        'table_invoices.apartment_filter'(val) {
            this.table_invoices.current_page = 1;
            this.getInvoices();
        },
        'table_invoices.invoice_no_filter'(val) {
            this.table_invoices.current_page = 1;
            // if(val.length > 0 ){
            this.getInvoices();
            // }
        },
        'table_invoices.date_filter'(val) {
            this.table_invoices.current_page = 1;
            this.getInvoices();
        },
        'table_invoices.status_filter'(val) {
            this.table_invoices.current_page = 1;
            this.getInvoices();
        },
        'table_invoices.po_number_filter'(val) {
            this.table_invoices.current_page = 1;
            this.getInvoices();
        },
        'table_invoices.due_date_filter'(val) {
            this.table_invoices.current_page = 1;
            this.getInvoices();
        },
    },

}
</script>
