<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">Division</h1>
                </div>
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
                                <SingleFieldSearch @changetable="getDepartmentsSearch" :table_data="table_departments">
                                </SingleFieldSearch>
                            </div>

                            <div class="col-sm-6 text-sm-end">
                                <button type="button"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                    data-bs-toggle="modal" data-bs-target="#DeptAdd">Add new
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_1rem">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <!--<th class="min_max_80">Id</th>-->
                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Department #</th>
                                <th  class="header-sort"  :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Division Name</th>
                                <th  class="header-sort"  :class="[sortBy == 'payroll_type' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('payroll_type')">Payroll Type</th>
                                <th >Service Name</th>

                                <th class="text_end_only_desktop">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="departments.length > 0">
                            <tr v-if="!loading" v-for="item in departments" :key="item.id">
                                <td data-title="Department #" >{{ item.id }}</td>
                                <td data-title="Division Name">{{ item.name }}</td>
                                <td data-title="Payroll Type">{{ item.payroll_type }}</td>
                                <td data-title="Service" v-if="item.deparment_service"><span class="rectangle"
                                        :style="[ item.deparment_service.color ? {'color' : item.deparment_service.color} : {'color' : 'white'}]">&#9724;</span>{{ item.deparment_service.category }}
                                </td>
                                <td data-title="Service" v-else>--</td>

                                <td data-title="Action" class=" text_end_only_desktop">
                                    <!-- Payroll Pricing -->
                                    <!--                                <router-link  v-bind:to="'/payroll-prices/'+ item.id" v-if="item.payroll_type == 'Fixed'" href="#" class="table_btn_link font_12 font_weight_700 text_uppercase">Payroll Value-->
                                    <!--                                </router-link>-->

                                    <!--                                <a href="#"-->
                                    <!--                                class="table_btn_link font_12 font_weight_700 text_uppercase"-->
                                    <!--                                data-bs-toggle="modal" data-bs-target="#DeptEdit"-->
                                    <!--                                @click="editIdMethod(item.id)">Edit</a>-->
                                    <!--                                &lt;!&ndash;<span class="seprator">|</span>&ndash;&gt;-->

                                    <!--                                <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
                                    <!--                                   class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
                                    <!--                                   @click="deleteRequest(item.id,'Department' , 'department')">Delete </a>-->

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
                                        <router-link
                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                            v-bind:to="'/payroll-prices/'+ item.id" v-if="item.payroll_type == 'Fixed'">
                                            Payroll Value
                                        </router-link>
                                        <a href="#"
                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                            data-bs-toggle="modal" data-bs-target="#DeptEdit"
                                            @click="editIdMethod(item.id)">Edit</a>

                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                            href="#" @click="deleteRequest(item.id,'Department' , 'department')">Delete </a>
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && departments.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>

                    <Pagination v-if="departments.length > 0 && table_departments.last_page > 1"
                        @changetable="getDepartments" :table_data="table_departments"></Pagination>

                </div>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="DeptAdd" tabindex="-1"
                aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <AddDepartment @closeEvent="forceRerender"></AddDepartment>
            </div>

            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>


            <!--            Edit Models -->


            <div class="modal fade custom_base_model custom_base_model_small" id="DeptEdit" tabindex="-1"
                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditDepartment @closeEvent="forceRerender" :deptId="departmentId"></EditDepartment>
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
import AddOrEdit from "./AddOrEdit";
import EmptySearch from "../EmptySearch";
import Navigation from "../../components/Navigation";

export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'AddDepartment': AddOrEdit,
        'EditDepartment': AddOrEdit,
        'EmptySearch': EmptySearch,
        'Navigation': Navigation,
    },
    data() {
        return {
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            departments: [],
            departmentId: "",
            table_departments: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },
            sortOrder: "desc",
            sortBy: "id",
        }
    },
    mounted() {

        this.refreshRecord();
    },
    methods: {
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        refreshRecord() {
            this.loading = true;
            this.getDepartments();
            // this.loading=false;
        },

        getDepartments() {
            axios.get('/api/department?page=' + this.table_departments.current_page +'&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&size=' + this.table_departments.per_page + '&search=' + this.table_departments.search + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.table_departments.last_page = response.data.departments.last_page;
                    this.departments = response.data.departments.data;
                    this.loading = false;
                });
        },
        forceRerender(msg) {
            this.departmentId = "";
            this.successmsg = "";
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
                    if (response.data.error) {
                        this.deletemsg = response.data.error
                    }
                    this.refreshRecord();
                });
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        },
        getDepartmentsSearch() {
            this.loading = true;
            this.table_departments.current_page = 1;
            this.getDepartments();
        }

    }
}


</script>
