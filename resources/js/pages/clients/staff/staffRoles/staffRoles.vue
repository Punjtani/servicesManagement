<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Staff Roles</h1></div>

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
                <div v-if="clientId">
					<TopNavigation :clientId="clientId" :currentComponent="currentComponent"></TopNavigation>
				</div>
                <div class="table_area">
                    <div class="table_area_head">
                        <div class="custom__search">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <SingleFieldSearch @changetable="getStaffRoleSearch" :table_data="table_staff_roles"></SingleFieldSearch>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <!--<button type="button" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10" data-bs-toggle="modal" data-bs-target="#RoleAdd">Add new</button>-->
<!--                                <router-link  to="/clients" class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                            </div>
                        </div>
                        </div>
                    </div>
                    <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_1rem">
                        <table class="table" >
                            <thead>
                            <tr>
                                <th>Role Name</th>
                                <!--<th>Role Type</th>-->
                                <th v-if="role == roleData.admin || role == roleData.super_admin" class="text_end_only_desktop">Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="staff_roles.length > 0">
                            <tr v-for="item in staff_roles" :key="item.id">
                                <td data-title="Color Name">{{ item.name }}</td>
                                <!--<td data-title="Role Type">{{ item.is_property_level ? 'Property' : 'Client' }}</td>-->
                                <td v-if="role == roleData.admin || role == roleData.super_admin" data-title="Action" class=" text_end_only_desktop">
                                    <a href="#"
                                    class="table_btn_link font_12 font_weight_700 text_uppercase"
                                    data-bs-toggle="modal" data-bs-target="#RoleEdit"
                                    @click="editIdMethod(item.id)">Edit</a>
                                    <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                    class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"
                                    @click="deleteRequest(item.id,'Staff Role' , 'staff-roles')">Delete </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div v-if="!loading && staff_roles.length <=0">
                            <EmptySearch></EmptySearch>
                        </div>
                        <Pagination v-if="staff_roles.length > 0 && table_staff_roles.last_page > 1" @changetable="getStaffRoles" :table_data="table_staff_roles"></Pagination>
                    </div>
                </div>
            </div>

            <!-- Add Model-->
            <div class="modal fade custom_base_model custom_base_model_small" id="RoleAdd" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <AddStaffRole @closeEvent="forceRerender"></AddStaffRole>
            </div>

            <!-- Delete Model-->
            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>

            <!-- Edit Model -->
            <div class="modal fade custom_base_model custom_base_model_small" id="RoleEdit" tabindex="-1"
                 aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditStaffRole @closeEvent="forceRerender" :roleId="role_id"></EditStaffRole>
            </div>

        </div>
    </div>
</template>


<script>
import DeleteModel from "../../../deleteModel";
import Loader from "../../../LoaderSpinner";
import axios from "axios";
import Pagination from "../../../Pagination";
import SingleFieldSearch from "../../../SingleFieldSearch";
import AddOrEdit from "./addEdit";
import EmptySearch from "../../../EmptySearch";
import TopNavigation from "../../TopNavigation";
import roleData from '../../../../data.js';
export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'AddStaffRole': AddOrEdit,
        'EditStaffRole': AddOrEdit,
        'EmptySearch':EmptySearch,
        'TopNavigation': TopNavigation,
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
            staff_roles: [],
            role_id: "",
            table_staff_roles:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            clientId:  this.$route.params.id,
            currentComponent : 'StaffRoles',
            role:"",
            roleData:roleData,
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
        this.role = localStorage.getItem("role");
        this.refreshRecord();
    },
    methods: {
        refreshRecord() {
            this.loading=true;
            this.getStaffRoles();
        },

        getStaffRoles() {
            axios.get('/api/staff-roles?page='+this.table_staff_roles.current_page+'&size='+this.table_staff_roles.per_page + '&search='+this.table_staff_roles.search)
                .then((response) => {
                    this.table_staff_roles.last_page=response.data.staff_roles.last_page;
                    this.staff_roles = response.data.staff_roles.data;
                    this.loading = false;
                });
        },
        forceRerender(msg) {
            this.role_id="";
            this.successmsg = "";
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        editIdMethod(id){
            this.role_id = id;
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
        getStaffRoleSearch(){
            this.table_staff_roles.current_page = 1;
            this.getStaffRoles();
        }
    }
}


</script>
