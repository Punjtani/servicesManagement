<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8"></div>
                <Navigation></Navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>


            <!-- ================ -->
            <div class="table_area">
                <div class="table_area_head">
                    <div class="row align-items-center">

                        <div class="col-sm-6">
                            <h1 class="h4 heading_text">Services</h1>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <button type="button"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 "
                                    data-bs-toggle="modal" data-bs-target="#serviceTypesAdd">Add new
                            </button>
                        </div>
                    </div>
                </div>
                <div id="service_type_table" class="no_more_tables table_to_be_used mg_top_1rem">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="header-sort min_max_80" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Service #</th>
                            <th>Services</th>

                            <th class="text_end_only_desktop">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in service_types" :key="item.id">
                            <td data-title="Service #">{{ item.id }}</td>
                            <td data-title="Category"><span class="rectangle" :style= "[ item.color ? {'color' : item.color} : {'color' : 'white'}]">&#9724;</span>{{ item.category }}</td>
                            <!--<td data-title="Color" class="rectangle" :style= "[ item.color ? {'color' : item.color} : {'color' : 'white'}]">&#9724;</td>
                            <td data-title="Payroll Type">{{ item.payroll_type }}</td>-->
                            <td data-title="Action" class="text_end_only_desktop">
<!--                                <a href="#" class="table_btn_link font_14 font_weight_700 "-->
<!--                                data-bs-toggle="modal" data-bs-target="#serviceTypesEdit" @click="editIdMethodService(item.id)">Edit</a>-->
<!--                                <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
<!--                                    class="table_btn_link font_14 font_weight_700 " href="#"-->
<!--                                    @click="deleteRequest(item.id,'Service' , 'service-type')">Delete </a>-->
<!--                                <router-link   v-bind:to="'/sub-services/' + item.id" class="table_btn_link font_14 font_weight_700 " href="">Sub Services </router-link>-->

                                <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                </a>
                                <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                    <router-link class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"  v-bind:to="'/sub-services/' + item.id" >
                                        Sub Services
                                    </router-link>
                                    <a href="#" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                       data-bs-toggle="modal" data-bs-target="#serviceTypesEdit" @click="editIdMethodService(item.id)">Edit</a>
                                    <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                       class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" href="#"
                                       @click="deleteRequest(item.id,'Service' , 'service-type')">Delete </a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- =============== -->



            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>


            <div class="modal fade custom_base_model custom_base_model_small" id="serviceTypesAdd" tabindex="-1"
                 aria-labelledby="serviceTypesAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <AddServiceType @closeEvent="forceRerender"></AddServiceType>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="serviceTypesEdit" tabindex="-1"
                 aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditServiceType @closeEvent="forceRerender"
                                    :serviceTypeId="serviceTypeId"></EditServiceType>
            </div>




        </div>
    </div>
</template>


<script>

import AddServiceType from "./Add";
import EditServiceType from "./Edit";

import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import Navigation from "../../components/Navigation";


import axios from "axios";
// import {required, minLength, maxLength, between} from 'vuelidate/lib/validators';
export default {
    components: {
        'AddServiceType': AddServiceType,
        'EditServiceType': EditServiceType,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'Navigation': Navigation,
    },
    data() {
        return {
            successmsg: "",
            deletemsg: "",
            service_types: [],
            has_error: false,
            loading: true,
            serviceTypeId: "",
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            table_appartment:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            sortOrder: "desc",
            sortBy: "service_type",




        }
    },
    mounted() {
        this.getServiceTypes();
    },
    methods: {

        getServiceTypes() {
            this.loading = true;
            axios.get('/api/service-type'+'?sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy )
                .then((response) => {
                    this.service_types = response.data.service_types;
                    this.loading = false;
                });
        },
        forceRerender(msg) {
            this.successmsg = "";
            this.serviceTypeId="";
            // console.log(msg);
            if(msg!="no-refresh")
            {
                this.refreshRecord();
                this.successmsg = msg;
            }

            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        refreshRecord(){
            this.getServiceTypes();
        },
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        editIdMethodService(id){
            this.serviceTypeId = id;
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
            axios.delete('/api/' + this.deleteApi + '/' +this.deleteId)
                .then((response) => {
                    this.deletemsg = response.data.msg;
                    if(response.data.error){
                        this.deletemsg =response.data.error
                    }
                    this.refreshRecord();
                });
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        } ,



    }
}
</script>
