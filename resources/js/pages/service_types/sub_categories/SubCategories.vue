<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Sub Services</h1></div>
                <Navigation></Navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>
            <!-- ================ -->
            <div class="table_area">

                <div class="custom__search">
                    <div class="row g-0">
                        <div class="col-sm-8 col-md-8 col-lg-6">
                        <SingleFieldSearch @changetable="changeTableSubCategory('',true)"
                                                        :table_data="table_subcategory"></SingleFieldSearch>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 text-sm-end">
                            <button type="button"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                    data-bs-toggle="modal" data-bs-target="#subCategoryAdd">Add new
                            </button>
<!--                            <router-link  to="/services" ><button class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</button></router-link>-->
                        </div>
                    </div>
                </div>
                <div id="appartment_type_table" class="no_more_tables table_to_be_used mg_top_1rem">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <!--<th class="min_max_80">Id</th>-->
                            <th class="header-sort" @click="sorting('name')">Name</th>
                            <th class="header-sort" @click="sorting('is_independent')">Independent of apartment size</th>
                            <th class="header-sort" @click="sorting('is_free_of_cost')">Free of cost</th>
                            <th class="min_max_155 text_end_only_desktop">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="sub_categories.length > 0">
                        <tr v-for="item in sub_categories" :key="item.id">
                            <!--<td data-title="Id">{{ item.id }}</td>-->
                            <td data-title="Name">{{ item.name }}</td>
                            <td data-title="Apartment Independent" class="mb-sm-2-custom">{{ item.is_independent == 1 ? "Yes" : "No" }}</td>
                            <td data-title="Free of cost">{{ item.is_free_of_cost == 1 ? "Yes" : "No" }}</td>
                            <td data-title="Action" class="min_max_155 text_end_only_desktop">
                                <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                    </a>
                                    <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">

                                        <a href="#" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" data-bs-toggle="modal" data-bs-target="#subCategoryAdd" @click="editIdMethodSubCategory(item.id)">Edit</a>

                                <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" href="#"
                                @click="deleteRequest(item.id,'Sub Service' , 'sub-category')">Delete </a>

                                    </div>

                                <!-- <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase" data-bs-toggle="modal" data-bs-target="#subCategoryAdd" @click="editIdMethodSubCategory(item.id)">Edit</a>

                                <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                    class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"
                                    @click="deleteRequest(item.id,'Sub Service' , 'sub-category')">Delete </a>
                           -->
                         </td>
                        </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && sub_categories.length <= 0">
                                    <EmptySearch></EmptySearch>
                                </div>
                    <Pagination v-if="sub_categories.length > 0 && table_subcategory.last_page > 1" @changetable="changeTableSubCategory"
                                :table_data="table_subcategory"></Pagination>
                </div>
            </div>
            <!-- =============== -->
            <div class="modal fade custom_base_model custom_base_model_small" id="subCategoryAdd" tabindex="-1"
                 aria-labelledby="appartmentTypesAddLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
                <AddSubCategory :parentId="parentId" :subCategoryId="subCategoryId" @closeEvent="forceRerender" @formResetEvent="formReset"></AddSubCategory>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>


        </div>
    </div>
</template>


<script>
import AddSubCategory from "./AddOrEdit";
import EditSubCategory from "./AddOrEdit";


import DeleteModel from "./../../deleteModel";
import Loader from "./../../LoaderSpinner";
import Pagination from "./../../Pagination";
import SingleFieldSearch from "./../../SingleFieldSearch";
import EmptySearch from "./../../EmptySearch";
import Navigation from "../../../components/Navigation";


import axios from "axios";
// import {required, minLength, maxLength, between} from 'vuelidate/lib/validators';
export default {
    components: {
        'AddSubCategory': AddSubCategory,
        // 'EditSubCategory': EditSubCategory,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'Navigation': Navigation,
    },
    data() {
        return {
            parentId:  this.$route.params.id ,
            successmsg: "",
            deletemsg: "",
            sub_categories: [],
            has_error: false,
            loading: true,
            subCategoryId: "",
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            filterLoader:false,
            table_subcategory:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            sortOrder:"desc",
            sortBy:"id",
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
        getSubCategories() {
            this.filterLoader = true;
            axios.get('/api/sub-category/'+this.parentId+'?page='+this.table_subcategory.current_page+'&size='+this.table_subcategory.per_page + '&search='+this.table_subcategory.search+'&sort_order='+this.sortOrder + '&sort_by='+this.sortBy)
                .then((response) => {
                    this.table_subcategory.last_page=response.data.sub_categories.last_page;
                    this.sub_categories = response.data.sub_categories.data;
                    this.loading = false;
                    this.filterLoader = false;
                });
        },

        forceRerender(msg) {
            this.successmsg = "";
            this.subCategoryId="";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);

        },
        formReset(){
            this.subCategoryId="";
        },

        refreshRecord(){
            this.getSubCategories();
        },
        editIdMethodSubCategory(id) {
            this.subCategoryId = id;
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
            axios.delete('/api/' + this.deleteApi + '/' +this.deleteId)
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
        } ,

        changeTableSubCategory(val, search = false){
            this.table_subcategory.current_page = val.current_page;
            if(search){
                this.table_subcategory.current_page = 1;
            }
            this.getSubCategories();
        },

    }
}
</script>
