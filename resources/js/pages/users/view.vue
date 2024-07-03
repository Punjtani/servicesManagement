<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">All Users  ({{ total ? total : "0" }})</h1></div>
                <Navigation></Navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>
            <div class="table_area">
                <div class="table_area_head">
                <div class="custom__search">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <SingleFieldSearch @changetable="getUsersSearch"
                                               :table_data="table_users"></SingleFieldSearch>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <!--<router-link  to="/add-user" >  <button type="button" style="color:white;"
                                    class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">
                               Add
                            </button> </router-link>-->
                            <!--                            <button v-if="role == roleData.super_admin"  type="button"-->
                            <!--                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"-->
                            <!--                                    data-bs-toggle="modal" data-bs-target="#roleAdd">Add new-->
                            <!--                            </button>-->
                        </div>

                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 text-sm-end">
                        <FilterPage @changetable="getUsersFilter" :table_data="table_users"></FilterPage>
                    </div>
                </div>

                <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_0rem">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">User #</th>
                            <th class="header-sort" :class="[sortBy == 'first_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('first_name')">First Name</th>
                            <th class="header-sort"  :class="[sortBy == 'middle_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('middle_name')" >Middle Name</th>
                            <th class="header-sort"  :class="[sortBy == 'last_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('last_name')">Last Name</th>
                            <th class="header-sort"  :class="[sortBy == 'email' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('email')">Email</th>
                            <!--<th>Contract</th>-->
                            <th class="header-sort"  :class="[sortBy == 'staff_roles' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('staff_roles')">Role</th>
                            <th class="header-sort"  :class="[sortBy == 'user_activated' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('user_activated')">Active / Inactive</th>
                            <th v-if="role == roleData.super_admin" class="text_end_only_desktop">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="users.length > 0">
                        <tr v-for="item in users" :key="item.id">
                            <td data-title="User #">{{ item.id}}</td>
                            <td data-title="First Name">{{ item.first_name? item.first_name : '--'}}</td>
                            <td data-title="Middle Name">{{item.middle_name? item.middle_name : '--'}}</td>
                            <td data-title="Last Name">{{item.last_name? item.last_name : '--'}}</td>
                            <td data-title="Email">{{item.email && item.email.toLowerCase().trim() != "null" ? item.email : "--"  }}</td>
                            <td data-title="Role" v-if="item.staff && item.staff.staff_roles">{{item.staff.staff_roles.name}}</td>
                            <td data-title="Role" v-else>{{item.roles.name}}</td>
                            <td data-title="Is Active">
                                <label class="switch mt-lg-1">
                                    <input type="checkbox" :checked="item.user_activated == 1 ? 'checked' : ''" :value="item.user_activated" @click="updateStatus(item.id,$event)">
                                    <div class="slider round"></div>
                                </label></td>
                            <td v-if="role == roleData.super_admin" data-title="Action" class="text_end_only_desktop">

<!--                                <router-link v-if="item.role == roleData.admin ||item.role == roleData.super_admin "  v-bind:to="'/view-user/' + item.id"  class="table_btn_link font_12 font_weight_700 text_uppercase" href="">Edit </router-link>-->
<!--                                <router-link v-if="item.role == roleData.client && item.client"  v-bind:to="'/view-client/' + item.client.id" class="table_btn_link font_12 font_weight_700 text_uppercase" href="">Edit </router-link>-->
<!--                                <a v-if="item.staff" @click="setId(item.staff.id,item.id)" data-bs-toggle="modal" data-bs-target="#addEditStaff"-->
<!--                                   class="table_btn_link font_12 font_weight_700 text_uppercase">-->
<!--                                    Edit-->
<!--                                </a>-->

<!--                                <router-link v-if="item.role == roleData.employee"   v-bind:to="'/view-employe/' + item.employee.id"  class="table_btn_link font_12 font_weight_700 text_uppercase" href="">Edit </router-link>-->
<!--                                &lt;!&ndash;<span class="seprator">|</span>&ndash;&gt;-->
<!--                                <a v-if="item.role == roleData.employee || role == roleData.super_admin" data-bs-toggle="modal" data-bs-target="#deleteModel"-->
<!--                                   class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
<!--                                   @click="deleteRequest(item.employee.id,'Employee' , 'employe')">Delete </a>-->

                                <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                </a>
                                <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                    <router-link class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" v-if="item.role == roleData.admin ||item.role == roleData.super_admin "  v-bind:to="'/view-user/' + item.id">Edit </router-link>
                                    <router-link class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" v-if="item.role == roleData.client && item.client"  v-bind:to="'/view-client/' + item.client.id">Edit </router-link>
                                    <router-link class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" v-if="item.role == roleData.employee"   v-bind:to="'/view-employe/' + item?.employee?.id">Edit </router-link>
                                    <a v-if="item.staff" @click="setId(item.staff.id,item.id)" data-bs-toggle="modal" data-bs-target="#AddOrEdit"
                                       class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar">
                                        Edit
                                    </a>
                                    <!-- <a v-if="item.role == roleData.employee && role == roleData.super_admin" data-bs-toggle="modal" data-bs-target="#deleteModel"
                                       class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" href="#"
                                       @click="deleteRequest(item?.employee.id || item?.staff?.id, 'Employee' , 'employe')">Delete </a> -->
                                </div>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading &&  users.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="users.length > 0 && table_users.last_page > 1" @changetable="getUsers"
                                :table_data="table_users"></Pagination>

                </div>
            </div>
            </div>


            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>


            <!--            Edit Models -->
            <div class="modal fade custom_base_model custom_base_model_small" id="roleAdd" tabindex="-1"
                 aria-labelledby="roleAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <add-role @closeEvent="forceRerender"></add-role>
            </div>
                        <div class="modal fade custom_base_model custom_base_model_large" id="AddOrEdit" tabindex="-1"
                             aria-labelledby="AddOrEdit" aria-hidden="true" data-bs-backdrop="static">
                            <addEditStaff @closeEvent="forceRerender" :clientId="clientId" :staffId="staffId" ></addEditStaff>
                        </div>

        </div>
    </div>
</template>


<script>

import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";
import AddRole from "./selectRole";
import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import roleData from '../../data.js';
import FilterPage from "../FilterPage";
import Navigation from "../../components/Navigation";
import { BButton,BTooltip} from 'bootstrap-vue'
import addEditStaff from "../clients/staff/addEdit.vue";
export default {
    components: {
        'DeleteModel': DeleteModel,
        'AddRole': AddRole,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'FilterPage': FilterPage,
        'Navigation': Navigation,
        'addEditStaff': addEditStaff,
    },
    data() {
        return {
            total: 0,
            userStatus: false,
            role:"",
            roleData:roleData,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            users: [],
            staffId: "",
            clientId: "",
            filterLoader: false,
            table_users:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
                status_filter:"",
                status_filter_options: ["active", "inactive"],
            },
            sortDirection:'',
            currentSortColumn:'',
            sortOrder: "desc",
            sortBy: "id",
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        this.refreshRecord();
    },
    methods: {
        setId(staff_id,id){
            this.staffId = staff_id;
            this.clientId = id;
        },
        updateStatus(userID,e) {

            // // console.log(e.target.value)
            this.userStatus = e.target.value == 1 ? 'false' : 'true';
            // // console.log("this.userStatus",this.userStatus)

            axios.post('/api/updateuserstatus/' + this.userStatus + '/' + userID )
                .then((response) => {
                    this.successmsg = response.data.msg;
                    setTimeout(() => {
                        this.successmsg = "";
                    }, 3000);
                    this.refreshRecord();
                }).catch(err => {
                this.has_error = true;
            })
        },
        refreshRecord() {
            this.loading=true;
            this.getUsers();
            // this.loading=false;
        },

        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },

        // getClients() {
        //     axios.get('/api/client?page='+this.table_clients.current_page+'&size='+this.table_clients.per_page + '&search='+this.table_clients.search)
        //         .then((response) => {
        //             this.table_clients.last_page=response.data.clients.last_page;
        //             this.clients = response.data.clients.data;
        //         });
        // },
        getUsers() {
            this.filterLoader = true;
            axios.get('/api/users?page='+this.table_users.current_page+'&size='+this.table_users.per_page + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy +
                '&search='+this.table_users.search +'&status_filter='+this.table_users.status_filter)
                .then((response) => {
                    this.table_users.last_page=response.data.users.last_page;
                    this.users = response.data.users.data;
                    this.total = response.data.users.total;
                    this.loading=false;
                    this.filterLoader = false;
                });
        },
        forceRerender(msg) {
            this.clientId="";
            this.staffId="";
            this.successmsg = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        editIdMethod(type,id){
            if(type=="client"){
                this.clientId = id;
            }
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

        getUsersSearch(){
            this.table_users.current_page=1;
            this.getUsers();

        },
        getUsersFilter(){
            this.table_users.current_page=1;
            this.getUsers();

        },
        sortListByColumn(col){
            if(this.sortDirection !== ''){
                this.sortDirection = this.sortDirection == 'ASC' ? 'DESC' : 'ASC';
            }else{
                this.sortDirection = 'ASC';
            }
            this.currentSortColumn = col;
            this.getUsers();
        }

    }
}


</script>

