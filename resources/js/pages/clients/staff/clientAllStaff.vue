<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">{{client.company}}</h1></div>

                <div class="col-sm-6 col-4 text-end  d-flex justify-content-end">

                    <p @click="$router.go(-1)" class="h3 mb-2 cursor-pointer history_back_btn" v-bind:class="{ 'disable-backward': !canGoBack }" ><b-icon icon="arrow-left-circle-fill"></b-icon></p>
                    <p @click="$router.go(1)"class="h3 mb-2 cursor-pointer history_farward_btn" v-bind:class="{ 'disable-forward': !canGoForward }"><b-icon icon="arrow-right-circle-fill"></b-icon></p>
                </div>
            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>
            <div class="smp__tabs">
            <TopNavigation :clientId="id" :currentComponent="currentComponent"></TopNavigation>
                <div class="table_area">
                    <div class="table_area_head">
                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">Staff ({{  total ? total : "0" }})</h5>
                    </div>
                    <div class="custom__search">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <SingleFieldSearch @changetable="getStaffSearch"
                                        :table_data="table_staff"></SingleFieldSearch>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <button v-if="role == roleData.admin || role == roleData.super_admin" @click="getClientId()" type="button" style="color:white"  data-bs-toggle="modal" data-bs-target="#AddOrEdit"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">
                                    Add new
                                </button>
<!--                                <router-link to="/clients" class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                            </div>
                        </div>
                    </div>

                    <div class="no_more_tables table_to_be_used mg_top_0rem">

                        <table class="table" >
                            <thead>
                            <tr>
                                <!--<th>Id</th>-->
                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Staff #</th>
                                <th  class="header-sort" :class="[sortBy == 'first_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('first_name')">First Name</th>
                                <th  class="header-sort" :class="[sortBy == 'middle_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('middle_name')">Middle Name</th>
                                <th  class="header-sort" :class="[sortBy == 'last_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('last_name')">Last Name</th>
                                <th>Staff Role</th>
                                <th>Property</th>
                                <th class="header-sort" :class="[sortBy == 'email' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('email')">Email</th>
                                <!--<th>Phone</th>
                                <th>Date of Birth</th>
                                <th>Notes</th>-->
                                <th v-if="role == roleData.admin || role == roleData.super_admin" class="text_end_only_desktop">Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="staff.length > 0">
                            <tr v-for="item in staff" :key="item.id">
                                <td data-title="Staff #">{{ item.id}}</td>
                                <td data-title="First Name">{{ item.user && item.user.first_name? item.user.first_name : '--'}}</td>
                                <td data-title="Middle Name">{{item.user && item.user.middle_name? item.user.middle_name : '--'}}</td>
                                <td data-title="Last Name">{{item.user && item.user.last_name? item.user.last_name : '--'}}</td>
                                <td data-title="Staff Role" v-if="item.staff_roles">{{item.staff_roles.name}}</td>
                                <td data-title="Staff Role" v-else></td>
                                <td data-title="Property">
                                    <div v-for="item1 in  item.property">
                                        {{item1.title}}
                                    </div>
                                </td>
                                <td data-title="Email">{{item.user && item.user.email? item.user.email : '--'}}</td>
                                <!--<td data-title="Phone">{{item.phone}}</td>
                                <td data-title="Date of Birth">{{item.date_of_birth}}</td>
                                <td data-title="Notes">{{item.notes}}</td>-->
                                <td v-if="role == roleData.admin || role == roleData.super_admin" data-title="Action" class="text_end_only_desktop" >
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
                <AddOrEdit @closeEvent="forceRerender" :clientId="clientId" :staffId="staffId" ></AddOrEdit>
            </div>

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
import AddOrEdit from "./addEditClientStaff";
import TopNavigation from "../TopNavigation";
export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'FilterPage': FilterPage,
        'AddOrEdit': AddOrEdit,
        'TopNavigation':TopNavigation
    },
    data() {
        return {
            total: 0,
            id:"",
            role:"",
            roleData:roleData,
            currentComponent : 'staff',
            successmsg: "",
            deletemsg: "",
            has_error: false,
            filterLoader:false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            staff: [],
            clientId: "",
            staffId:"",
            client:"",
            table_staff:{
                search: "",
                current_page : 1,
                per_page : 25,
                last_page: 0
                // status_filter:"",
                // status_filter_options: ["all", "active", "not active"],
            },
            sortOrder:'desc',
            sortBy:'id',
        }
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
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        getStaff() {
            this.filterLoader = true;
            axios.get('/api/client/'+this.id+'/staff?page='+this.table_staff.current_page+'&sort_order='+this.sortOrder + '&sort_by='+this.sortBy+'&size='+this.table_staff.per_page +'&search='+this.table_staff.search)
                .then((response) => {
                    this.table_staff.last_page=response.data.staff.data.last_page;
                    this.client = response.data.client;
                    this.staff = response.data.staff.data;
                    this.total = response.data.staff.total;
                    // // console.log(this.staff);
                    this.loading=false;
                    this.filterLoader = false;
                });
        },
        forceRerender(msg) {
            this.successmsg = "";
            this.clientId="";
            this.staffId = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
            this.$forceUpdate();
        },
        getClientId(){
            this.clientId = this.id;
        },
        editIdMethod(id){
            this.clientId = this.id;
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
