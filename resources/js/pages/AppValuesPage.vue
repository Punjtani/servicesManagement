<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <h1 class="h4 heading_text">App Values</h1>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>

            <div class="smp__tabs">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true">Unit Types
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile" aria-selected="false">Service
                            Categories
                        </button>
                    </li>
<!--                    <li class="nav-item" role="presentation">-->
<!--                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"-->
<!--                                type="button" role="tab" aria-controls="contact" aria-selected="false">Paint Specs-->
<!--                        </button>-->
<!--                    </li>-->
<!--                    <li class="nav-item" role="presentation">-->
<!--                        <button class="nav-link" id="refinishing-tab" data-bs-toggle="tab" data-bs-target="#refinishing"-->
<!--                                type="button" role="tab" aria-controls="refinishing" aria-selected="false">Refinishing-->
<!--                            Specs-->
<!--                        </button>-->
<!--                    </li>-->
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_0 mg_bot_1rem_on_mobiles">
                                            Unit types</h5>
                                    </div>
                                </div>
                            </div>
                            <div id="appartment_type_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                <div class="custom__search">
                                    <div class="row g-0">
                                        <div class="col-sm-8 col-md-8 col-lg-6">
                                            <SingleFieldSearch @changetable="changeTableAppartment('',true)"
                                                                    :table_data="table_appartment"></SingleFieldSearch>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6 text-sm-end">
                                            <button type="button"
                                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                                    data-bs-toggle="modal" data-bs-target="#appartmentTypesAdd">Add new
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <table class="table" v-if="appartment_types.length > 0">
                                    <thead>
                                    <tr>
                                        <th class="min_max_120">Id</th>
                                        <th>Name</th>
                                        <th class="text_end_only_desktop">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in appartment_types" :key="item.id">
                                        <td data-title="Service Category"  class="min_max_120">{{ item.id }}</td>
                                        <td data-title="Name">{{ item.type }}</td>
                                        <td data-title="Action" class="text_end_only_desktop"><a href="#"
                                            class="table_btn_link font_12 font_weight_700 text_uppercase"
                                            data-bs-toggle="modal" data-bs-target="#appartmentTypesEdit"
                                            @click="editIdMethodAppartment(item.id)">Edit</a>
                                            <!--<span class="seprator">|</span>
                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                               class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"
                                               @click="deleteRequest(item.id,'Appartment' , 'appartment-type')">Delete </a>-->
                                        </td>
                                    </tr>
                                    </tbody>



                                </table>
                                <div v-if="!loading && appartment_types.length <= 0">
                                    <EmptySearch></EmptySearch>
                                </div>
                                <Pagination v-if="appartment_types.length > 0" @changetable="changeTableAppartment"
                                            :table_data="table_appartment"></Pagination>

                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_0 mg_bot_1rem_on_mobiles">
                                            Service Categories</h5>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <button type="button"
                                                class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 "
                                                data-bs-toggle="modal" data-bs-target="#serviceTypesAdd">Add new
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="service_type_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Service Category</th>
                                        <th>Short Code</th>
                                        <th>Payroll Type</th>
                                        <th class="text_end_only_desktop">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in service_types" :key="item.id">
                                        <td data-title="ID">{{ item.id }}</td>
                                        <td data-title="Category">{{ item.category }}</td>
                                        <td data-title="Short Code">{{ item.short_code }}</td>
                                        <td data-title="Payroll Type">{{ item.payroll_type }}</td>
                                        <td data-title="Action" class="text_end_only_desktop">
                                            <a href="#" class="table_btn_link font_14 font_weight_700 "
                                            data-bs-toggle="modal" data-bs-target="#serviceTypesEdit" @click="editIdMethodService(item.id)">Edit</a><span class="seprator">|</span>
                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                               class="table_btn_link font_14 font_weight_700 " href="#"
                                               @click="deleteRequest(item.id,'Service' , 'service-type')">Delete </a>
                                                 <span class="seprator">|</span>
                                            <router-link   v-bind:to="'/sub-services/' + item.id" class="table_btn_link font_14 font_weight_700 " href="">Sub Services </router-link>
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
                            <div class="table_area_head">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_0 mg_bot_1rem_on_mobiles">Paint
                                            Specs</h5>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <button
                                            class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">
                                            Add new
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="no_more_tables table_to_be_used mg_top_1rem">
                                <table class="table ">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Color</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                    <div class="tab-pane fade" id="refinishing" role="tabpanel" aria-labelledby="refinishing-tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_0 mg_bot_1rem_on_mobiles">
                                            Refinishing Specs</h5>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <button
                                            class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">
                                            Add new
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="no_more_tables table_to_be_used mg_top_1rem">
                                <table class="table ">
                                    <thead>
                                    <tr>
                                        <th>Job Categories</th>
                                        <th>No of Jobs</th>
                                        <th>Amount in $</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Job Category">Paint</td>
                                        <td data-title="No of Job">12</td>
                                        <td data-title="Amount in $">1,500</td>
                                        <td data-title="Action"><a href="#">View Detail</a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                </div>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="appartmentTypesAdd" tabindex="-1"
                 aria-labelledby="appartmentTypesAddLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <add-appartment-type @closeEvent="forceRerender"></add-appartment-type>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="appartmentTypesEdit" tabindex="-1"
                 aria-labelledby="appartmentTypesEditLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <EditAppartmentType @closeEvent="forceRerender"
                                    :appartmentTypeId="appartmentTypeId"></EditAppartmentType>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>


            <div class="modal fade custom_base_model custom_base_model_small" id="serviceTypesAdd" tabindex="-1"
                 aria-labelledby="serviceTypesAddLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <AddServiceType @closeEvent="forceRerender"></AddServiceType>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="serviceTypesEdit" tabindex="-1"
                 aria-labelledby="serviceTypesEditLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <EditServiceType @closeEvent="forceRerender"
                                    :serviceTypeId="serviceTypeId"></EditServiceType>
            </div>




        </div>
    </div>
</template>


<script>
import AddAppartmentType from "./appartment_types/Add";
import EditAppartmentType from "./appartment_types/Edit";
import AddServiceType from "./service_types/Add";
import EditServiceType from "./service_types/Edit";

import DeleteModel from "./deleteModel";
import Loader from "./LoaderSpinner";
import Pagination from "./Pagination";
import SingleFieldSearch from "./SingleFieldSearch";
import EmptySearch from "./EmptySearch";

import axios from "axios";
// import {required, minLength, maxLength, between} from 'vuelidate/lib/validators';
export default {
    components: {
        'AddAppartmentType': AddAppartmentType,
        'EditAppartmentType': EditAppartmentType,
        'AddServiceType': AddServiceType,
        'EditServiceType': EditServiceType,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch' : EmptySearch
    },
    data() {
        return {
            successmsg: "",
            deletemsg: "",
            appartment_types: [],
            service_types: [],
            has_error: false,
            loading: true,
            appartmentTypeId: "",
            serviceTypeId: "",
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            filterLoader: false,
            table_appartment:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },




        }
    },
    mounted() {
        this.refreshRecord();
    },
    methods: {
        getAppartmentTypes() {
            this.filterLoader = true;
            axios.get('/api/appartment-type?page='+this.table_appartment.current_page+'&size='+this.table_appartment.per_page + '&search='+this.table_appartment.search)
                .then((response) => {
                    this.table_appartment.last_page=response.data.appartment_types.last_page;
                    this.appartment_types = response.data.appartment_types.data;
                    this.filterLoader  = false;
                }).catch( e =>  this.filterLoader  = false);
        },
        getServiceTypes() {
            axios.get('/api/service-type')
                .then((response) => {
                    this.service_types = response.data.service_types;
                });
        },
        forceRerender(msg) {
            this.appartmentTypeId="";
            this.successmsg = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        refreshRecord(){
            this.loading = true;
            this.getAppartmentTypes();
            this.getServiceTypes();
            this.loading = false;
        },
        editIdMethodAppartment(id) {
            this.appartmentTypeId = id;
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
                    this.refreshRecord();
                });
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        } ,

        changeTableAppartment(val,search = false){
         this.table_appartment.current_page = val.current_page;
         if(search){
             this.table_appartment.current_page=1;
         }
         this.getAppartmentTypes();
        },



    }
}
</script>
