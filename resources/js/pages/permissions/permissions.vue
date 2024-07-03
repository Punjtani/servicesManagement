<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <h1 class="h4 heading_text">Permissions</h1>
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
                            <SingleFieldSearch @changetable="getPermissionSearch"
                                       :table_data="table_permissions"></SingleFieldSearch>
                        </div>

                        <div class="col-sm-6 text-sm-end">
                            <button v-if="role == roleData.super_admin" type="button"
                                    class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600"
                                    data-bs-toggle="modal" data-bs-target="#PermAdd">Add new
                            </button>
                        </div>
                    </div>
                    </div>
                </div>
                <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_1rem">




                    <table class="table">
                        <thead>
                        <tr>
                            <!--<th>Id</th>-->
                            <th>Name</th>
                            <th>Slug</th>
                            <th v-if="role == roleData.super_admin">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="permissions.length > 0">
                        <tr v-for="item in permissions" :key="item.id">
                            <!--<td data-title="Id">{{ item.id }}</td>-->
                            <td data-title="Permission Name">{{ item.name }}</td>
                            <td data-title="Permission Name">{{ item.slug }}</td>
                            <td v-if="role == roleData.super_admin" data-title="Action"><a href="#"
                                class="table_btn_link font_12 font_weight_700 text_uppercase"
                                data-bs-toggle="modal" data-bs-target="#PermEdit"
                                @click="editIdMethod(item.id)">Edit</a>
                                <!--<span class="seprator">|</span>

                                <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                   class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"
                                   @click="deleteRequest(item.id,'Permission' , 'permission')">Delete </a>-->
                            </td>

                        </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && permissions.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="permissions.length > 0 && table_permissions.last_page > 1" @changetable="getPermissions"
                                :table_data="table_permissions"></Pagination>

                </div>
            </div>


            <div class="modal fade custom_base_model custom_base_model_small" id="PermAdd" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <AddPermission @closeEvent="forceRerender"></AddPermission>
            </div>

            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>


            <!--            Edit Models -->


            <div class="modal fade custom_base_model custom_base_model_small" id="PermEdit" tabindex="-1"
                 aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
               <EditDepartment @closeEvent="forceRerender"
                           :permId="permissionId"></EditDepartment>
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
import roleData from '../../data.js'
export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'AddPermission': AddOrEdit,
        'EditDepartment': AddOrEdit,
        'EmptySearch': EmptySearch
    },
    data() {
        return {
            role: "",
            roleData : roleData,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            permissions: [],
            permissionId: "",
            table_permissions:{
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
            this.loading=true;
            this.getPermissions();
            // this.loading=false;
        },

        getPermissions() {
            axios.get('/api/permissions?page='+this.table_permissions.current_page+'&size='+this.table_permissions.per_page + '&search='+this.table_permissions.search)
                .then((response) => {
                    this.table_permissions.last_page=response.data.permissions.last_page;
                    this.permissions = response.data.permissions.data;
                    this.loading=false;
                });
        },
        forceRerender(msg) {
            this.permissionId="";
            this.successmsg = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        editIdMethod(id){

                this.permissionId = id;

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
        getPermissionSearch(){
            this.table_permissions.current_page = 1;
            this.getPermissions();
        }

    }
}


</script>
