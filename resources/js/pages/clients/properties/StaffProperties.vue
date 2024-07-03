<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6"><h1 class="h4 heading_text">Client</h1></div>
                <!--<div class="col-sm-6 text-end">
                    <router-link  to="/clients" ><button class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Back</button></router-link>
                </div> -->
            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>

  <div class="smp__tabs">
            <div class="table_area">
                <div class="table_area_head">
                            <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">Properties</h5>
                        </div>

                <div class="custom__search">
                    <div class="row align-items-center">

                        <div class="col-sm-6">
                             <SingleFieldSearch @changetable="getPropertiesSearch"
                                       :table_data="table_properties"></SingleFieldSearch>
                        </div>

                        <div class="col-sm-6 text-sm-end" v-if="(role == roleData.admin) || (role == roleData.super_admin)">
                            <button type="button"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                    data-bs-toggle="modal" data-bs-target="#AddOrEdit">Add new
                            </button>
                            <router-link  to="/clients" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>
                        </div>
                    </div>
                </div>

                <div id="paint_grades_table"  class="no_more_tables table_to_be_used mg_top_1rem">
                    <table class="table">
                        <thead>
                        <tr>
                            <!--<th>Id</th>-->
                            <th>Property Name</th>
                            <!--<th>Sales Tax</th>-->
                            <th class="text_end_only_desktop">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="properties.length > 0">
                        <tr v-for="item in properties" :key="item.id">
                            <!--<td data-title="Id">{{ item.id }}</td>-->
                              <td data-title="Title">
                                <a class="table_btn_link font_12 font_weight_700 text_uppercase"
                                   data-bs-toggle="modal" data-bs-target="#PropertyView" href="#"
                                   @click="currentProperty(item)">{{ item.title }}</a>
                                </td>
                            <!--<td data-title="Tax-type">{{item.tax_type.name}}</td>-->
                            <td data-title="Action" class="text_end_only_desktop">
                                <div class="dropdown no_action custom-action-bar-icon">
                                    <span class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                        </svg>
                                    </span>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a v-if="(role == roleData.admin) || (role == roleData.super_admin)" class="table_btn_link font_12 font_weight_700 text_uppercase dropdown-item"
                                   data-bs-toggle="modal" data-bs-target="#AddOrEdit" href="#"
                                   @click="editIdMethod('property',item.id)">Edit</a>
                                   <router-link   v-bind:to="'/property-paint-specs/' + item.id" class="table_btn_link font_12 font_weight_700 text_uppercase dropdown-item" href="property-paint-specs">Paint Specs </router-link>
                                   <router-link   v-bind:to="'/property-pricing/' + item.id" class="table_btn_link font_12 font_weight_700 text_uppercase dropdown-item" href="property-paint-specs">Pricing </router-link>
                                    <a class="table_btn_link font_12 font_weight_700 text_uppercase dropdown-item"
                                    data-bs-toggle="modal" data-bs-target="#AttributeView" href="#"
                                    @click="currentProperty(item)">Billing</a>
                                    <!--<a v-if="(role == roleData.admin) || (role == roleData.super_admin)" data-bs-toggle="modal" data-bs-target="#deleteModel"
                                    class="table_btn_link font_12 font_weight_700 text_uppercase dropdown-item" href="#"
                                    @click="deleteRequest(item.id,'Property' , 'property')">Delete </a>-->

                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && properties.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="properties.length > 0 && table_properties.last_page > 1" @changetable="getProperties"
                                :table_data="table_properties"></Pagination>

                </div>
            </div>

  </div>

            <!--<div class="modal fade custom_base_model custom_base_model_large" id="ManagerView" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <ViewManager @closeEvent="forceRerender"  :Property="current_property" ></ViewManager>
            </div>-->
            <div class="modal fade custom_base_model custom_base_model_large" id="PropertyView" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <ViewProperty @closeEvent="forceRerender"  :Property="current_property" ></ViewProperty>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="AttributeView" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <Billing @closeEvent="forceRerender" @closeEventBilling="forceRerenderBilling"  :Property="current_property" ></Billing>
            </div>


            <div class="modal fade custom_base_model custom_base_model_large" id="AddOrEdit" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <AddOrEdit @closeEvent="forceRerender" @formResetEvent="formReset"  :clientId="clientId" :PropertyId="propertyId" ></AddOrEdit>
            </div>

            <!--            Edit Models -->

<!--            <div  class="modal fade custom_base_model custom_base_model_large" id="PropertyEdit" tabindex="-1"-->
<!--                  aria-labelledby="serviceTypesEditLabel" aria-hidden="true">-->
<!--                <AddOrEdit @closeEvent="forceRerender"-->
<!--                             :clientId="clientId"  :PropertyId="propertyId"></AddOrEdit>-->
<!--            </div>-->

            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
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
// import ViewManager from "../properties/ViewManager";
import Billing from "./billing.vue";
import SingleFieldSearch from "../../SingleFieldSearch";
import EmptySearch from "../../EmptySearch";
export default {

    components: {
        'roleData': roleData,
        'AddOrEdit': AddOrEdit,
        'ViewProperty': ViewProperty,
        // 'ViewManager': ViewManager,
        'Billing': Billing,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch':EmptySearch,
    },
    data() {
        return {
            role: "",
			roleData: roleData,
            currentComponent : 'properties',
            clientId:  this.$route.params.id ,
            successmsg: "",
            deletemsg: "",
            filterLoader:false,
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            properties: [],
            current_property : "",
            propertyId: "",
            table_properties:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        this.refreshRecord();
    },
    methods: {
        refreshRecord() {
            // console.log('REFRESH REC')

            this.loading=true;
            this.getProperties();
            // this.loading=false;
        },

        getProperties() {
            this.filterLoader = true;
            axios.get('/api/staff-property/?page='+this.table_properties.current_page+'&size='+this.table_properties.per_page + '&search='+this.table_properties.search)
                .then((response) => {
                    // console.log(response.data.properties);
                    this.table_properties.last_page=response.data.properties.last_page;
                    this.properties = response.data.properties.data;
                    this.loading=false;
                    this.filterLoader = false;
                });
        },
        forceRerender(msg) {
            this.propertyId="";
            this.successmsg = "";
            // console.log(msg);
            this.refreshRecord();
            if(msg) {
                this.successmsg = msg;
                setTimeout(() => {
                    this.successmsg = "";
                }, 3000);
            }
            this.$forceUpdate();
        },
        formReset(){
            this.propertyId="";
        },

        editIdMethod(type,id){



            this.propertyId = id;

            // this.$forceUpdate();
        },
        currentProperty(val){

          this.current_property = val;
            // console.log(this.current_property)
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
                    if(response.data.error){
                        this.deletemsg =response.data.error
                    }
                    this.refreshRecord();
                }).catch(err => {
                this.has_error = true;
            })
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        },
        getPropertiesSearch(){
            this.table_properties.current_page=1;
            this.getProperties();
        },
        forceRerenderBilling(){
            this.current_property = "";
        }

    }
}


</script>
