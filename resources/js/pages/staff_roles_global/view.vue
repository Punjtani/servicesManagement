<template>
    <div>
        <div v-if="loading || searchLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Staff Roles</h1></div>
                <Navigation></Navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>
            <div class="table_area">
                 <div class="custom__search">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                             <SingleFieldSearch @changetable="getUsersSearch"
                                       :table_data="table_roles"></SingleFieldSearch>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 text-sm-end">
                            <button  type="button"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                    data-bs-toggle="modal" data-bs-target="#roleAdd">Add new
                            </button>
                        </div>
                    </div>
                </div>
                <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_0rem">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Role #</th>
                            <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Name</th>
                            <th class="header-sort" :class="[sortBy == 'is_property_level' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('is_property_level')">Property Level</th>
                            <th class="header-sort" :class="[sortBy == 'isActive' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('isActive')">Active / Inactive</th>
                            <th class="text_end_only_desktop">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="roles.length > 0">
                        <tr v-for="item in roles" :key="item.id">
                            <td data-title="Role #">{{ item.id}}</td>
                            <td data-title="Name">{{ item.name}}</td>
                            <td data-title="Property Level">
                                <label class="switch mt-lg-1">
                                    <input type="checkbox" data-bs-toggle="modal" data-bs-target="#confirmModel" v-model="item.is_property_level " v-bind:id="item.is_property_level " :checked="item.is_property_level ? 'checked' : ''" :value="item.is_property_level" @click="updateRoleLevel(item.id, item,$event)">
                                    <div class="slider round"></div>
                                </label></td>
                            <td data-title="Active / Inactive">
                                <label class="switch mt-lg-1">
                                    <input type="checkbox" :checked="item.isActive == 1 ? 'checked' : ''" :value="item.isActive" @click="updateStatus(item.id,$event)">
                                    <div class="slider round"></div>
                                </label></td>

                            <td data-title="Action" class="text_end_only_desktop" >
<!--                                <span v-if="item.id!=4">-->
<!--                                <a href="#"-->
<!--                                class="table_btn_link font_12 font_weight_700 text_uppercase"-->
<!--                                data-bs-toggle="modal" data-bs-target="#roleEdit"-->
<!--                                @click="editIdMethodRole(item.id)">Edit</a>-->

<!--                                <router-link   v-bind:to="'/role-permissions/' + item.id" class="table_btn_link font_12 font_weight_700 text_uppercase" href="">Permissions</router-link>-->

<!--                                <a  data-bs-toggle="modal" data-bs-target="#deleteModel"-->
<!--                                    class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
<!--                                    @click="deleteRequest(item.id,'Role' , 'deletestaffrole')">Delete </a>-->
<!--                                </span>-->

                                <a v-if="item.id == 4" disabled="disabled" class="no_action custom-action-bar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>

                                    </a>
                                <a v-if="item.id!=4" href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                </a>
                                <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                    <a href="#"
                                       class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                       data-bs-toggle="modal" data-bs-target="#roleEdit"
                                       @click="editIdMethodRole(item.id)">Edit</a>

                                    <a  data-bs-toggle="modal" data-bs-target="#deleteModel"
                                        class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" href="#"
                                        @click="deleteRequest(item.id,'Role' , 'deletestaffrole')">Delete </a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && roles.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="roles.length > 0 && table_roles.last_page > 1" @changetable="getRoles"
                                :table_data="table_roles"></Pagination>

                </div>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="roleAdd" tabindex="-1"
                 aria-labelledby="roleAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <add-role @closeEvent="forceRerender"></add-role>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="roleEdit" tabindex="-1"
                 aria-labelledby="roleEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditRole @closeEvent="forceRerender"
                                    :roleId="roleId"></EditRole>
            </div>

            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="confirmModel" tabindex="-1"
                 aria-labelledby="confirmModel" aria-hidden="true" data-bs-backdrop="static">
                <confirmModel @closeEvent="convertRole" type="convert this role"></confirmModel>
            </div>

            <!--            Edit Models -->



        </div>
    </div>
</template>


<script>

import DeleteModel from "../deleteModel";
import confirmModel from "../confirmModel";
import Loader from "../LoaderSpinner";
import AddRole from "./Add";
import EditRole from "./Edit";
import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import Navigation from "../../components/Navigation";

export default {
    components: {
        'AddRole': AddRole,
        'EditRole': EditRole,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'Navigation': Navigation,
        'confirmModel': confirmModel,
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
            roles: [],
            roleId: "",
            searchLoader: false,
            confirmItem:{},
            newRoleLevel:null,
            confirmRoleId:null,
            table_roles:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            global_roles:[1,2,3,4],
            sortOrder:"desc",
            sortBy:"id",
            userStatus: false,
        }
    },
    mounted() {

        this.refreshRecord();
    },
    methods: {
        updateStatus(itemId,e) {

            // // console.log(e.target.value)
            this.userStatus = e.target.value == 1 ? 'false' : 'true';
            // // console.log("this.userStatus",this.userStatus)

            axios.post('/api/updaterolestatus/' + this.userStatus + '/' + itemId )
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
        updateRoleLevel(itemId,item,e) {
            this.confirmItem = item;
            this.confirmRoleId = itemId;
        },
        async convertRole(confirm) {
            if(!confirm) {
                this.confirmItem.is_property_level = !this.confirmItem.is_property_level;
                return
            };
            this.loading = true
            try {
                const response = await axios.put('/api/toggleStaffRoleLevel/'+this.confirmRoleId);
                this.successmsg = response.data.message;
                setTimeout(() => {
                        this.successmsg = "";
                    }, 3000);
            } catch(e){}
            this.refreshRecord();
        },
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        refreshRecord() {
            this.loading=true;
            this.getRoles();
            // this.loading=false;
        },

        getRoles() {
            axios.get('/api/staffrolesglobal?page='+this.table_roles.current_page+'&size='+this.table_roles.per_page + '&search='+this.table_roles.search+'&sort_order='+this.sortOrder + '&sort_by='+this.sortBy)
                .then((response) => {
                    this.table_roles.last_page=response.data.roles.last_page;
                    this.roles = response.data.roles.data;
                    this.loading=false;
                    this.searchLoader = false;
                });
        },
        forceRerender(msg) {

            this.roleId = "";

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

        editIdMethodRole(id) {
            this.roleId = id;
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
            this.searchLoader = true;
            this.table_roles.current_page=1;
            this.getRoles();

        }

    }
}


</script>
