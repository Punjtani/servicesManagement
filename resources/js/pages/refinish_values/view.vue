<template>
    <div>
        <div v-if="loading || searchLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Refinish Colors</h1></div>
                <Navigation></Navigation>

            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>

            <div class="smp__tabs">
                <div class="smp__tabs_ul_wrap">
                    <ul class="nav nav-tabs " id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button @click="setActiveTab('refinish_colors')" :class="[activeTab  == 'refinish_colors'? 'active' : '']" class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#refinish_colors"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Primer Colors
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button @click="setActiveTab('multispect_colors')" :class="[activeTab  == 'multispect_colors'? 'active' : '']" class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#multispect_colors"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Multispect Colors
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade"  :class="[activeTab  == 'refinish_colors'? 'show active' : '']" id="refinish_colors" role="tabpanel" aria-labelledby="home-tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange">Primer Colors</h5>
                            </div>
                            <div class="custom__search">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <SingleFieldSearch @changetable="getRefinishColorsSearch()"
                                                            :table_data="table_refinish_colors"></SingleFieldSearch>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <button type="button" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10" data-bs-toggle="modal" data-bs-target="#RefinishColorAdd">Add new</button>
                                    </div>
                                </div>
                            </div>
                            <div id="paint_colors_table"  class="no_more_tables table_to_be_used mg_top_1rem">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Color #</th>
                                        <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Primer Color Name</th>
                                        <!--<th>Hex</th>-->
                                        <th class="min_max_155 text_end_only_desktop">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="refinish_colors.length > 0">
                                    <tr v-for="item in refinish_colors" :key="item.id">
                                        <td data-title="Color #">{{ item.id }}</td>
                                        <td data-title="Color Name">{{ item.name }}</td>
                                        <!--<td data-title="Color Value">
                                            <div  class="chosen_color" style="" :style="{ backgroundColor: item.hex_value }">  </div>
                                            <div  class="chosen_value">{{ item.hex_value }}</div>
                                        </td>-->
                                        <td data-title="Action" class="min_max_155 text_end_only_desktop">
<!--                                            <a href="#"-->
<!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase"-->
<!--                                            data-bs-toggle="modal" data-bs-target="#RefinishColorEdit"-->
<!--                                            @click="editIdMethod(item.id)">Edit</a>-->
<!--                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
<!--                                                class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
<!--                                                @click="deleteRequest(item.id,'Refinish Color' , 'refinish-color')">Delete </a>-->

                                            <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                            </a>
                                            <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                                <a href="#"
                                                   class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                   data-bs-toggle="modal" data-bs-target="#RefinishColorEdit"
                                                   @click="editIdMethod(item.id)">Edit</a>
                                                <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                   class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" href="#"
                                                   @click="deleteRequest(item.id,'Refinish Color' , 'refinish-color')">Delete </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-if="!loading && refinish_colors.length <=0">
                                    <EmptySearch></EmptySearch>
                                </div>
                                <Pagination v-if="refinish_colors.length > 0 && table_refinish_colors.last_page > 1" @changetable="getRefinishColors"
                                            :table_data="table_refinish_colors"></Pagination>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                    <div class="tab-pane fade" :class="[activeTab  == 'multispect_colors'? 'show active' : '']" id="multispect_colors" role="tabpanel" aria-labelledby="home-tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange">Multispect Colors</h5>
                            </div>
                            <div class="custom__search">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <SingleFieldSearch @changetable="getMultispectColorsSearch()"
                                                            :table_data="table_multispect_colors"></SingleFieldSearch>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <button type="button" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10" data-bs-toggle="modal" data-bs-target="#MultispectColorAdd">Add new</button>
                                    </div>
                                </div>
                            </div>
                            <div id="paint_colors_table" class="no_more_tables table_to_be_used mg_top_1rem">
                                <table class="table" >
                                    <thead>
                                    <tr>
                                        <th class="header-sort widths_one" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Color #</th>
                                        <th class="header-sort" :class="[sortBy == 'name' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('name')">Multispect Color</th>
                                        <!--<th>Hex</th>-->
                                        <th class="min_max_155 text_end_only_desktop">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody v-if="multispect_colors.length > 0">
                                    <tr v-for="item in multispect_colors" :key="item.id">
                                        <td data-title="Color #">{{ item.id }}</td>
                                        <td data-title="Color Name">{{ item.name }}</td>
                                        <!--<td data-title="Color Value">
                                            <div  class="chosen_color" style="" :style="{ backgroundColor: item.hex_value }">  </div>
                                            <div  class="chosen_value">{{ item.hex_value }}</div>
                                        </td>-->
                                        <td data-title="Action" class="min_max_155 text_end_only_desktop">
<!--                                            <a href="#"-->
<!--                                            class="table_btn_link font_12 font_weight_700 text_uppercase"-->
<!--                                            data-bs-toggle="modal" data-bs-target="#MultispectColorEdit"-->
<!--                                            @click="multispectEditMethod(item.id)">Edit</a>-->
<!--                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"-->
<!--                                                class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"-->
<!--                                                @click="deleteRequest(item.id,'MultiSpect Color' , 'multispect-color')">Delete </a>-->

                                            <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                            </a>
                                            <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                                <a href="#"
                                                   class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                   data-bs-toggle="modal" data-bs-target="#MultispectColorEdit"
                                                   @click="multispectEditMethod(item.id)">Edit</a>
                                                <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                   class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" href="#"
                                                   @click="deleteRequest(item.id,'MultiSpect Color' , 'multispect-color')">Delete </a>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div v-if="!loading && multispect_colors.length <=0">
                                    <EmptySearch></EmptySearch>
                                </div>
                                <Pagination v-if="multispect_colors.length > 0 && multispect_colors.last_page > 1" @changetable="getMultispectColors"
                                            :table_data="table_multispect_colors"></Pagination>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                </div>
            </div>
<!--            Add Record Models-->

            <div class="modal fade custom_base_model custom_base_model_small" data-bs-backdrop="static" data-bs-keyboard="false" id="RefinishColorAdd" tabindex="-1"
                 aria-labelledby="apaintColorAddAddLabel" aria-hidden="true">
                <AddColor @closeEvent="forceRerender"></AddColor>
            </div>

<!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>


<!--            Edit Models -->


            <div class="modal fade custom_base_model custom_base_model_small" id="RefinishColorEdit" tabindex="-1"
                 aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditColor @closeEvent="forceRerender"
                                 :refinishColorId="refinishColorId"></EditColor>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" data-bs-backdrop="static" data-bs-keyboard="false" id="MultispectColorAdd" tabindex="-1"
                 aria-labelledby="apaintColorAddAddLabel" aria-hidden="true">
                <AddMultispectColor @closeEvent="forceRerender"></AddMultispectColor>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="MultispectColorEdit" tabindex="-1"
                 aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditMultispectColor @closeEvent="forceRerender"
                                 :multispectColorId="multispectColorId"></EditMultispectColor>
            </div>

        </div>
    </div>
</template>


<script>

import AddColor from "./refinish_color/Add";
import EditColor from "./refinish_color/Edit";

import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";
import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import AddMultispectColor from "./multispect_color/Add";
import EditMultispectColor from "./multispect_color/Edit";
import Navigation from "../../components/Navigation";
export default {
    components: {
        'AddColor': AddColor,
        'EditColor': EditColor,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'AddMultispectColor': AddMultispectColor,
        'EditMultispectColor': EditMultispectColor,
        'Navigation': Navigation,
    },
    data() {
        return {
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            searchLoader:false,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            activeTab:'refinish_colors',
            refinish_colors: [],
            refinishColorId: "",

            table_refinish_colors:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            table_multispect_colors:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            multispectColorId: "",
            multispect_colors: [],
            sortOrder:"asc",
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
        setActiveTab(tab) {
            this.loading = true;
            this.activeTab = tab;
            setTimeout(() => {
                this.loading = false;
            }, 1000);
        },
        refreshRecord() {
            this.loading=true;
            this.getRefinishColors();
            this.getMultispectColors();


        },


        getRefinishColorsSearch() {
            this.searchLoader = true;
            this.table_refinish_colors.current_page=1;
            this.getRefinishColors();
        },
        getMultispectColorsSearch() {
            this.searchLoader = true;
            this.table_multispect_colors.current_page=1;
            this.getMultispectColors();
        },
        getRefinishColors() {
            axios.get('/api/refinish-color?page='+this.table_refinish_colors.current_page+'&size='+this.table_refinish_colors.per_page + '&search='+this.table_refinish_colors.search+'&sort_order='+this.sortOrder + '&sort_by='+this.sortBy)
                .then((response) => {
                    this.table_refinish_colors.last_page=response.data.refinish_colors.last_page;
                    this.refinish_colors = response.data.refinish_colors.data;
                    this.loading=false;
                    this.searchLoader = false;
                });
        },
        getMultispectColors(){
            axios.get('/api/multispect-color?page='+this.table_multispect_colors.current_page+'&size='+this.table_multispect_colors.per_page + '&search='+this.table_multispect_colors.search+'&sort_order='+this.sortOrder + '&sort_by='+this.sortBy)
                .then((response) => {
                    this.table_multispect_colors.last_page=response.data.multispect_colors.last_page;
                    this.multispect_colors = response.data.multispect_colors.data;
                    this.searchLoader = false;
                });
        },


        forceRerender(msg) {
            this.refinishColorId= "",
            this.multispectColorId= "",
            this.successmsg = "";
            if(msg!="no-refresh")
            {
                this.refreshRecord();
                this.successmsg = msg;
            }
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        editIdMethod(id){
            this.refinishColorId = id;
        },
        multispectEditMethod(id){
            this.multispectColorId = id;
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

    }
}


</script>
