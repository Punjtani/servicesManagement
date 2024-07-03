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
                        <div class="table_area">
                            <div class="table_area_head">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_bot_1rem_on_mobiles">Crew ({{ total ? total : "0" }})</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="custom__search">
                                <div class="row g-0">
                                    <div class="col-sm-8 col-md-8 col-lg-6">
                                            <SingleFieldSearch
                                        @changetable="search"
                                        :table_data="filters"
                                        ></SingleFieldSearch>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-6 text-sm-end">
                                            <button
                                        type="button"
                                        class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                        data-bs-toggle="modal"
                                        data-bs-target="#employee-crew-members-add-modal"
                                        >
                                        Add New
                                        </button>
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
                                <div class="col-sm-2 text-sm-end">
                                <div class="form-group">
                                    <button type="button" @click="resetForm" class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600 btn-block">Clear</button>
                                </div>
                            </div>
                            </div>
                            <div class="no_more_tables table_to_be_used mg_top_1rem">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Crew #</th>
                                            <th class="header-sort"  :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Name</th>
                                            <th>Division</th>
                                            <th>Members</th>
                                            <th class="text_end_only_desktop">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="list.length > 0">
                                        <tr v-for="item in list" :key="item.id">
                                            <td data-title="Crew #">{{ item.id }}</td>
                                            <td data-title="Crew">{{ item.name }}</td>
                                            <td data-title="Division">{{ item.department.name }}</td>
                                            <td data-title="Members">
<!--                                                <span v-for = "(item1,index1) in item.employee_crew">-->
                                                <span v-for = "(item1,index1) in item.employe">

                                                    <span v-if="index1 == 0">
                                                        {{item1.user.first_name}} {{item1.user.middle_name}} {{item1.user.last_name}}
                                                    </span>
                                                    <span v-if="index1 !== 0">
                                                        , {{item1.user.first_name}} {{item1.user.middle_name}} {{item1.user.last_name}}
                                                    </span>
                                                </span>
                                            </td>
                                            <td data-title="Action" class="text_end_only_desktop">
<!--                                                <a  href="#" data-bs-toggle="modal" data-bs-target="#employee-crew-members-edit-modal"-->
<!--                                                class="table_btn_link font_12 font_weight_700 text_uppercase" @click="edit(item.id)">Edit</a>-->
<!--                                                <a class="table_btn_link font_12 font_weight_700 text_uppercase" data-bs-toggle="modal" data-bs-target="#deleteModel" href="#" @click="deleteRequest(item.id,'Crew' , 'crew')">Delete </a>-->

                                                <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                                </a>
                                                <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                                    <a  href="#" data-bs-toggle="modal" data-bs-target="#employee-crew-members-edit-modal"
                                                        class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" @click="edit(item.id)">Edit</a>

                                                    <a class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" data-bs-toggle="modal" data-bs-target="#deleteModel" href="#" @click="deleteRequest(item.id,'Crew' , 'crew')">Delete </a>

                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div v-if="!loading && list.length <=0">
                                    <EmptySearch></EmptySearch>
                                </div>
                                <Pagination v-if="list.length > 0 && filters.last_page > 1" @changetable="load" :table_data="filters"></Pagination>
                            </div>
                            <!-- Add Modal -->
                            <div class="modal fade custom_base_model custom_base_model_small_medium" id="employee-crew-members-add-modal" tabindex="-1"
                                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                                <AddModal @closeEvent="reRender"></AddModal>
                            </div>
                    

                            <!-- Edit Models -->
                            <div class="modal fade custom_base_model custom_base_model_small_medium" id="employee-crew-members-edit-modal" tabindex="-1"
                                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                                <EditModal @closeEvent="reRender" :crewId="crewId"></EditModal>
                            </div>
                            <!--            Delete Model-->
                            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                                    aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
/** Importing components */
import DeleteModel from "../../deleteModel";
import Loader from "../../LoaderSpinner";
import Pagination from "../../Pagination"
import SingleFieldSearch from "../../SingleFieldSearch"
import AddModal from "./AddModal"
import ViewModal from "./ViewModal"
import EmptySearch from "../../EmptySearch";
import TopNavigation from "../TopNavigation";
import Navigation from "../../../components/Navigation";
import Multiselect from 'vue-multiselect';
export default {
    /** Using components */
    components: {
        'Loader':Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'AddModal': AddModal,
        'EditModal': AddModal,
        'Multiselect': Multiselect,
        'ViewModal': ViewModal,
        'EmptySearch': EmptySearch,
        'DeleteModel': DeleteModel,
        'TopNavigation':TopNavigation,
        'Navigation':Navigation
    },
    /** Mounted hook */
    mounted() {
        this.load();
        this.getDepartments();
    },
    watch: {
        'table_employee.departmentId'(val) {
            this.table_employee.current_page=1;
            this.load();
        },
    },
    /** Data */
    data() {
        return {
            total:0,
            currentComponent: "crew",
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            loading: false,
            departments: [],
            filterLoader:false,
            list: [],
            crewId: "",
            filters:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            table_employee:{
                departmentId: "",
            },
            sortOrder:"desc",
            sortBy:"id",
        }
    },
    /** Methods */
    methods: {
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.load();
        },
        reRender(msg) {
            this.crewId = "";
            this.successmsg = ''
            this.load()
            this.successmsg = msg
            setTimeout(() => {
                this.successmsg = ''
            }, 3000)
        },
        getDepartments(){
            axios.get('/api/employe/create')
                .then((response) => {
                    this.departments = response.data.departments
                });
        },
        refreshRecord() {
            this.loading=true;
            this.load();
            // this.loading=false;
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
        search() {
            this.filters.current_page= 1
            this.load(true)
        },
        edit(id) {
            this.crewId = id;
        },
        load(search = false) {
            if (!search) this.loading=true;
            this.filterLoader=true;
            let searchDepartmentId;
            if(this.table_employee.departmentId){
                searchDepartmentId = this.table_employee.departmentId.id;
            }else{
                searchDepartmentId = "";
            }
            axios.get('/api/crew?page='+this.filters.current_page+'&size='+this.filters.per_page +'&sort_order='+this.sortOrder + '&sort_by='+this.sortBy + '&search='+this.filters.search + '&department_filter='+searchDepartmentId)
            .then((response) => {
                this.filters.last_page=response.data.crews.last_page
                this.list = response.data.crews.data;
                this.total = response.data.crews.total;
                this.loading=false;
                this.filterLoader=false;
            }).catch(e => { this.loading =  false; this.filterLoader=false;})
        },
        resetForm(){
            this.table_employee.status_filter = "";
            this.table_employee.payroll_filter = "";
            this.table_employee.departmentId = "";
            this.table_employee.search ="";
        },
        clearCurrentFilter(name) {
            if (name == 'devision') {
                this.table_employee.departmentId = "";
            }             
            // this.$refs[name + '_multiselect'].toggle();
        },
    }
}
</script>
