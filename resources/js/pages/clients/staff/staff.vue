<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">{{property.title}}</h1></div>
                <Navigation></Navigation>
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
                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">Staff</h5>
                    </div>
                    <div class="custom__search">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <SingleFieldSearch @changetable="getStaffSearch"
                                        :table_data="table_staff"></SingleFieldSearch>
                            </div>
                            <div v-if="role == roleData.admin || role == roleData.super_admin" class="col-sm-6 text-sm-end">
                                <button @click="getPropertyId()" type="button" data-bs-toggle="modal" data-bs-target="#AddOrEdit"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">
                                    Add New
                                </button>
                                <!--<button type="button" @click="getProperty()" data-bs-toggle="modal" data-bs-target="#ManagerView"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">
                                    Assign Staff
                                </button>-->
<!--                                <router-link  v-bind:to="'/client-property/'+property.client.id" class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                            </div>
                        </div>
                    </div>

                    <div class="no_more_tables table_to_be_used mg_top_0rem">

                        <table class="table" >
                            <thead>
                            <tr>
                                <!--<th>Id</th>-->
                                <th class="header-sort" @click="sortListByColumn('first_name')">First Name</th>
                                <th>Middle Name</th>
                                <th class="header-sort" @click="sortListByColumn('last_name')">Last Name</th>
                                <th>Email</th>
                                <th>Staff Role</th>
                                <th v-if="role == roleData.admin || role == roleData.super_admin" class="text_end_only_desktop">Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="staff.length > 0">
                            <tr v-for="item in staff" :key="item.id">
                                <!--<td data-title="Id">{{ item.id}}</td>-->
                                <td data-title="First Name">{{ item.user.first_name? item.user.first_name : '--'}}</td>
                                <td data-title="Middle Name">{{item.user.middle_name? item.user.middle_name : '--'}}</td>
                                <td data-title="Last Name">{{item.user.last_name? item.user.last_name : '--'}}</td>
                                <td data-title="Email">{{item.user.email}}</td>
                                <td data-title="Staff Role" v-if="item.staff_roles">{{item.staff_roles.name}}</td>
                                <td data-title="Staff Role" v-else></td>
                                <td v-if="role == roleData.admin || role == roleData.super_admin" data-title="Action" class="text_end_only_desktop">
<!--                                    <a @click="editIdMethod(item.id)" data-bs-toggle="modal" data-bs-target="#AddOrEdit"-->
<!--                                        class="table_btn_link font_12 font_weight_700 text_uppercase">-->
<!--                                        Edit-->
<!--                                    </a>-->
<!--                                    <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
<!--                                        class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
<!--                                        @click="deleteRequest(item.id,'Staff' , 'staff')">Delete </a>-->

                                    <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                    </a>
                                    <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                        <a @click="editIdMethod(item.id)" data-bs-toggle="modal" data-bs-target="#AddOrEdit"
                                           class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar">
                                            Edit
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                           class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" href="#"
                                           @click="deleteRequest(item.id,'Staff' , 'staff')">Delete </a>
                                    </div>

                                    <!--<div class="dropdown">
                                        <span  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg>
                                        </span>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <router-link  :to="{name:'clientViewUser',params:{id:id,userId:item.id}}" class="table_btn_link font_12 font_weight_700 text_uppercase dropdown-item" href="">Edit </router-link>
                                        <span class="seprator">|</span>
                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                        class="table_btn_link font_12 font_weight_700 text_uppercase dropdown-item" href="#"
                                        @click="deleteRequest(item.id,'User' , 'user')">Delete </a>
                                    </div>
                                    </div>-->
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-if="!loading && staff.length <=0">
                            <EmptySearch></EmptySearch>
                        </div>

                    </div>
                </div>
            </div>
            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>

            <div class="modal fade custom_base_model custom_base_model_large" id="AddOrEdit" tabindex="-1"
                 aria-labelledby="AddStaff" aria-hidden="true" data-bs-backdrop="static">
                <AddOrEdit @closeEvent="forceRerender" :propertyId="propertyId" :staffId="staffId" :clientId="clientId" ></AddOrEdit>
            </div>
            <!--<div class="modal fade custom_base_model custom_base_model_small" id="ManagerView" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <assignStaff @closeEvent="forceAssignRerender" @addStaff = "addNewStaff()" :Property="current_property" ></assignStaff>
            </div>-->

        </div>
    </div>
</template>


<script>

import DeleteModel from "../../deleteModel";
import Loader from "../../LoaderSpinner";
import axios from "axios";
import SingleFieldSearch from "../../SingleFieldSearch";
import EmptySearch from "../../EmptySearch";
import roleData from '../../../data.js';
import FilterPage from "../../FilterPage";
import AddOrEdit from "./addEdit";
import Navigation from "../../../components/Navigation";
// import assignStaff from "./assignStaff";
export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'FilterPage': FilterPage,
        'AddOrEdit': AddOrEdit,
        'Navigation': Navigation,
        // 'assignStaff':assignStaff
    },
    data() {
        return {
            id:"",
            role:"",
            roleData:roleData,
            // currentComponent : 'staff',
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            staff: [],
            propertyId: "",
            current_property:"",
            staffId:"",
            clientId:"",
            property:"",
            table_staff:{
                search: "",
                // status_filter:"",
                // status_filter_options: ["all", "active", "not active"],
            },
            sortDirection:'',
            currentSortColumn:'',
        }
    },
    mounted() {
        this.id=this.$route.params.id
        this.role = localStorage.getItem("role");
        this.refreshRecord();
    },
    methods: {
        refreshRecord() {
            this.loading=true;
            this.getStaff();
            // this.loading=false;
        },
        getStaff() {
            axios.get('/api/property/'+this.id+'/staff?search='+this.table_staff.search+'&sort_column='+this.currentSortColumn+'&sort_direction='+this.sortDirection)
                .then((response) => {
                    this.property = response.data.property;
                    this.staff = response.data.staff;
                    this.loading=false;
                });
        },
        forceRerender(msg) {
            // this.clientId="";
            this.successmsg = "";
            this.propertyId="";
            this.clientId = "";
            this.staffId = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
            this.$forceUpdate();
        },
        forceAssignRerender (msg){
             // this.clientId="";
            this.successmsg = "";
            this.current_property="";
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
            this.$forceUpdate();
        },
        addNewStaff(){
            this.current_property="";
            $('#AddOrEdit').modal('show')
            this.getPropertyId();
        },
        getProperty(){
            this.current_property = this.property;
        },
        getPropertyId(){
            this.propertyId = this.property.id;
            this.clientId = this.property.client_id;
        },
        editIdMethod(id){
            this.propertyId = this.property.id;
            this.clientId = this.property.client_id;
            this.staffId = id;
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

        getStaffSearch(){
            this.table_staff.current_page=1;
            this.getStaff();

        },
        // getUsersFilter(){
        //     this.table_staff.current_page=1;
        //     this.getStaff();

        // },
        sortListByColumn(col){
            if(this.sortDirection !== ''){
                this.sortDirection = this.sortDirection == 'ASC' ? 'DESC' : 'ASC';
            }else{
                this.sortDirection = 'ASC';
            }
            this.currentSortColumn = col;
            this.getStaff();
        }

    }
}


</script>
