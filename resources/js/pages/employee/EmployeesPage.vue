<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div class="dashboard_content_area" v-if="!loading">
            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">All Employees</h1></div>
                <Navigation></Navigation>

            </div>
            <div class="smp__tabs">
            <TopNavigation :currentComponent="currentComponent"></TopNavigation>
                <!--<ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Employee Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Crew Management</button>
                    </li>
                </ul>-->
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">Employees ({{ total ? total : "0" }})</h5>
                                    </div>
                                    <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                                        <strong>{{ successmsg }}</strong>
                                    </div>

                                    <div class="alert alert-danger" v-if="deletemsg!==''">
                                        <strong>{{ deletemsg }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="custom__search">
                                <div class="row g-0">
                                    <div class="col-sm-8 col-md-8 col-lg-6">
                                        <SingleFieldSearch @changetable="getemployeesSearch"
                                        :table_data="table_employee"></SingleFieldSearch>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 text-sm-end">
                                        <div class="row align-items-center">
                                                <div class="col-lg-8"></div>
                                                <div class="col-lg-4 ">
                                                        <router-link  to="/add-employee"  type="button" style="color:white;"
                                                    class="btn_big_medium btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10 btn-block">
                                                    Add New
                                                     </router-link>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <multiselect deselectLabel=""
                                                     :clearOnSelect="true" v-model.trim="table_employee.departmentId"
                                                     track-by="name" label="name" placeholder="Select Division"
                                                     :select-label="''" :options="departments" :searchable="true"
                                                     ref="devision_multiselect" :selectedLabel="''"
                                                     :allow-empty="true">
                                            <template #singleLabel="{ option }">
                                                <div>
                                                    <span class="multiselect__single-label">{{ option.name }}</span>
                                                    <button v-if="table_employee.departmentId" class="multiselect__clear" @mousedown="clearCurrentFilter('devision')">
                                                        &#x2715;
                                                    </button>
                                                </div>
                                            </template>
                                        </multiselect>
<!--                                        <multiselect deselectLabel="" class="text_capitalize" v-model="table_employee.departmentId"  placeholder="Select Division" :select-label="''" track-by="name" label="name" :options="departments" :searchable="true" :allow-empty="true"></multiselect>-->
                                    </div>
                                </div>
                                <div class="col-sm-4 text-sm-end">
                                    <div class="form-group">
                                        <multiselect deselectLabel="" class="text_capitalize" v-model="table_employee.payroll_filter"
                                                     placeholder="Select Payroll Type" :select-label="''" :options="payroll_type_options"
                                                     :searchable="true"
                                                     ref="payroll_multiselect" :selectedLabel="''"
                                                     :allow-empty="true">
                                            <template #singleLabel="{ option }">
                                                <div>
                                                    <span class="multiselect__single-label">{{ option }}</span>
                                                    <button v-if="table_employee.payroll_filter" class="multiselect__clear" @mousedown="clearCurrentFilter('payroll')">
                                                        &#x2715;
                                                    </button>
                                                </div>
                                            </template>

                                        </multiselect>
                                    </div>
                                </div>
                                <!--<div class="col-sm-4 text-sm-end">
                                    <FilterPage @changetable="getEmployeeFilter" :table_data="table_employee"></FilterPage>
                                </div>-->
                                <div class="col-sm-2 text-sm-end">
                                    <div class="form-group">
                                        <multiselect  class=" text_capitalize" deselectLabel="" v-model.trim="table_employee.status_filter"
                                                      placeholder="Status" :select-label="''" :options="table_employee.status_filter_options"
                                                      :searchable="true" ref="status_multiselect" :selectedLabel="''"
                                                      :allow-empty="true">
                                            <template #singleLabel="{ option }">
                                                <div>
                                                    <span class="multiselect__single-label">{{ option }}</span>
                                                    <button v-if="table_employee.status_filter" class="multiselect__clear" @mousedown="clearCurrentFilter('status')">
                                                        &#x2715;
                                                    </button>
                                                </div>
                                            </template>

                                        </multiselect>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-sm-end">
                                <div class="form-group">
                                    <button type="button" @click="resetForm" class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600 btn-block">Clear</button>
                                </div>
                            </div>
                            </div>

                            <div  class="no_more_tables table_to_be_used mg_top_1rem">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <!--<th>ID</th>-->
                                            <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Employee #</th>
                                            <th class="header-sort" :class="[sortBy == 'first_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('first_name')">First Name</th>
                                            <th class="header-sort"  :class="[sortBy == 'middle_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('middle_name')" >Middle Name</th>
                                            <th class="header-sort"  :class="[sortBy == 'last_name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('last_name')">Last Name</th>
                                            <th >Division</th>
                                            <!--<th>Contract</th>-->
                                            <th class="header-sort"  :class="[sortBy == 'salary_type' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('salary_type')">Payroll Type</th>
                                            <th class="text_end_only_desktop">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="employee.length > 0">
                                        <tr v-for="item in employee" :key="item.id">
                                            <!--<td data-title="ID">{{item.id}}</td>-->
                                            <td data-title="Employee #">{{item.id}}</td>
                                            <td data-title="First Name">{{item.user.first_name}}</td>
                                            <td data-title="Middle Name">{{item.user.middle_name ? item.user.middle_name : '--'}}</td>
                                            <td data-title="Last Name">{{item.user.last_name}}</td>
                                            <td data-title="Division">{{ item.department.name }}</td>
                                            <!--<td data-title="Contract">({{ item.contract_start_date }}) - ({{ item.contract_end_date?item.contract_end_date:'--' }})</td>-->
                                            <td data-title="Salared">{{item.salary_type}}</td>
                                            <td data-title="Action" class="text_end_only_desktop">

<!--                                                <router-link  v-bind:to="'/employee-doc/' + item.id" class="table_btn_link font_12 font_weight_700 text_uppercase" href="">Documents</router-link>-->
<!--                                                 <router-link   v-bind:to="'/my-time-sheet-admin/' + item.id" class="table_btn_link font_12 font_weight_700 text_uppercase" href="">Timesheet</router-link>-->
<!--                                                <router-link   v-bind:to="'/all-payroll/' + item.id"  class="table_btn_link font_12 font_weight_700 text_uppercase" href="">Payroll</router-link>-->
<!--                                                <router-link   v-bind:to="'/view-employe/' + item.id" class="table_btn_link font_12 font_weight_700 text_uppercase" href="">Edit</router-link>-->
<!--                                                <a class="table_btn_link font_12 font_weight_700 text_uppercase" data-bs-toggle="modal" data-bs-target="#deleteModel" href="#" @click="deleteRequest(item.id,'Employee' , 'employe')">Delete </a>-->
                                                <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                                </a>
                                                <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                                    <router-link class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" v-bind:to="'/employee-doc/' + item.id"> Documents </router-link>
                                                    <router-link class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"  v-bind:to="'/my-time-sheet-admin/' + item.id"> Timesheet </router-link>
                                                    <router-link class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"  v-bind:to="'/all-payroll/' + item.id"> Payroll </router-link>
                                                    <router-link class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"  v-bind:to="'/view-employe/' + item.id"> Edit </router-link>
                                                    <a class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" data-bs-toggle="modal" data-bs-target="#deleteModel" href="#" @click="deleteRequest(item.id,'Employee' , 'employe')">Delete </a>

                                                </div>

                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                                <div v-if="!loading && employee.length <=0">
                                    <EmptySearch></EmptySearch>
                                </div>
                                <Pagination  v-if="employee.length > 0 && table_employee.last_page > 1" @changetable="getemployees"
                                            :table_data="table_employee"></Pagination>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                    <!--            Delete Model-->
                    <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                            aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                        <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <!-- ================ -->
                        <CrewManagementList></CrewManagementList>
                        <!-- =============== -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";
import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import List from "./crew-management/List.vue";
import FilterPage from "../FilterPage";
import Multiselect from 'vue-multiselect';
import TopNavigation from "./TopNavigation";
import Navigation from "../../components/Navigation";

export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'CrewManagementList': List,
        'EmptySearch': EmptySearch,
        'FilterPage': FilterPage,
        'Multiselect': Multiselect,
        'TopNavigation': TopNavigation,
        'Navigation': Navigation,
    },
    data() {
        return {
            total:0,
            currentComponent : 'employee',
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: false,
            filterLoader:false,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            employee: [],
            crews : [],
            emplyeId: "",
            departments: [],
            table_employee:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
                status_filter:"",
                status_filter_options: ["active", "inactive"],
                payroll_filter:"",
                departmentId: "",
            },
            table_crew:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            payroll_type_options: ["Fixed", "Hourly", "Percentage"],
            sortDirection:'',
            currentSortColumn:'',
            sortOrder: "desc",
            sortBy: "id",
        }
    },

        mounted() {
            this.refreshRecord();
            this.getDepartments();
        },
    watch: {
        'table_employee.payroll_filter'(val){
            if(val == null){
                this.table_employee.payroll_filter = '';
            }
            this.table_employee.current_page=1;
            this.getemployees();
        },
        'table_employee.status_filter'(val) {
            this.table_employee.current_page=1;
            this.getemployees();
        },
        'table_employee.departmentId'(val) {
            this.table_employee.current_page=1;
            this.getemployees();
        },
    },
    methods: {
        clearCurrentFilter(name){
            if (name=='devision'){
                this.table_employee.departmentId = "";

            }else if (name=='payroll'){
                this.table_employee.payroll_filter = "";

            }else if (name=='status'){
                this.table_employee.status_filter = "";

            }
            this.$refs[name+'_multiselect'].toggle();
        },
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        refreshRecord() {
            this.loading=true;
            this.getemployees();
            // this.loading=false;
        },

        getemployees() {
            this.filterLoader = true;
            let searchDepartmentId;
            if(this.table_employee.departmentId){
                searchDepartmentId = this.table_employee.departmentId.id;
            }else{
                searchDepartmentId = "";
            }
            axios.get('/api/employe?page='+this.table_employee.current_page+'&size='+this.table_employee.per_page + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy +
             '&search='+this.table_employee.search+'&status_filter='+this.table_employee.status_filter+'&department_filter='+searchDepartmentId+'&payroll_filter='+this.table_employee.payroll_filter)
                .then((response) => {
                    // // console.log('response',response)
                    this.table_employee.last_page=response.data.employees.last_page;
                    this.employee = response.data.employees.data;
                    this.total = response.data.employees.total;
                    this.employee.forEach((data)=>{
                        const capitalizedFirst = data.salary_type[0].toUpperCase();
                        const rest = data.salary_type.slice(1).toLowerCase();
                        data.salary_type =  capitalizedFirst + rest;
                    })
                    this.filterLoader = false;
                    this.loading=false;
                }).catch((e)=>{this.filterLoader = false; this.loading=false;});
            // this.loading=false;
        },



        forceRerender(msg) {
            this.emplyeId = "";
            this.successmsg = "";
            // // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        editIdMethod(type,id){
            if(type=="employe"){
                this.emplyeId = id;
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
                    this.deletemsg = response.data.msg;
                    if(response.data.error){
                        this.deletemsg =response.data.error
                    }
                    this.refreshRecord();
                });
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        },
        getemployeesSearch(){
            this.table_employee.current_page=1;
            this.getemployees();
        },

        getCrewsSearch(){
            this.table_crew.current_page= 1;
            this.getcrews();

        },
        getEmployeeFilter(){
            this.table_employee.current_page=1;
            this.getemployees();

        },
        getDepartments(){
            axios.get('/api/employe/create')
                .then((response) => {
                    this.departments = response.data.departments
                });
        },
        // departmentFilter(){
        //     this.table_employee.current_page=1;
        //     this.getemployees();
        // },
        // sortListByColumn(col){
        //     // console.log("here");
        //     this.currentSortColumn = col;
        //     this.employee.sort( function( a, b ){
        //         if( this.sortDirection == 'ASC' ){
        //             return ( ( a.user[col].toLowerCase() == b.user[col].toLowerCase() ) ? 0 : ( ( a.user[col].toLowerCase() > b.user[col].toLowerCase() ) ? 1 : -1 ) );
        //         }
        //         if( this.sortDirection == 'DESC' ){
        //             return ( (  a.user[col].toLowerCase() == b.user[col].toLowerCase() ) ? 0 : ( (  a.user[col].toLowerCase() <  b.user[col].toLowerCase() ) ? 1 : -1 ) );
        //         }
        //     }.bind(this));
        //     this.sortDirection = this.sortDirection == 'ASC' ? 'DESC' : 'ASC';
        // },
        sortListByColumn(col){
            this.currentSortColumn = col;
            if(this.sortDirection){
                this.sortDirection = this.sortDirection == 'ASC' ? 'DESC' : 'ASC';
            }else{
                this.sortDirection = 'ASC';
            }
            this.getemployees();
        },
        resetForm(){
            this.table_employee.status_filter = "";
            this.table_employee.payroll_filter = "";
            this.table_employee.departmentId = "";
            this.table_employee.search ="";
        }

    }
    }
</script>
