<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <!-- Asad Ali New Design    -->
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">All PMC ({{ total ? total : "0" }})</h1></div>
                <Navigation></Navigation>

            </div>

            <div class="clients_main_area">
                <div class="head_area">
                    <div class="row align-items-center">
                        <!-- buttons left -->
                        <div class="col-md-4 col-lg-4 col-xl-3 left_status">
                            <div class="client_filters_left_button">
                                <ul>
                                    <!--<li>
                                        <button name="status" @click="table_clients.status_filter = 'all'" value="All" aria-label="status"  :class="{'active': table_clients.status_filter == 'all'}">All</button>
                                    </li>-->
                                    <li>
                                        <button name="status" @click="table_clients.status_filter = 'active'" value="All" aria-label="status" :class="{'active': table_clients.status_filter == 'active'}">Active</button>
                                    </li>
                                    <li>
                                        <button name="status" @click="table_clients.status_filter = 'not active'" value="All" aria-label="status" :class="{'active': table_clients.status_filter == 'not active'}">Inactive</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- end buttons -->
                        <!-- search area -->
                        <div class="col-md-4 col-lg-4 col-xl-6 ipade_6 ">
                            <div class="search_form_clients">
                                <SingleFieldSearch @changetable="getClientsSearch"
                                        :table_data="table_clients"></SingleFieldSearch>
                            </div>
                        </div>
                        <!-- end search area -->
                        <!-- right buttons -->
                        <div class="col-md-4 col-lg-4 col-xl-3 ipade_6 mb-2 mb-md-0">
                            <div class="right_button_clients" v-if="role == roleData.admin || role == roleData.super_admin">

                                    <a href="/qb" @click="showLoading">
                                    <button type="button" style="color:white;"
                                            class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600">
                                        Sync
                                    </button>
                                    </a>
                                    <router-link  to="/add-client" >
                                        <button type="button" style="color:white;"
                                                class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600">
                                        Add New
                                        </button>
                                    </router-link>
                            </div>
                        </div>
                        <!-- end right buttons -->

                    </div>
                </div>
                <div class="body_area pt-4">
                    <!-- <div class="row">
                            <div class="col-md-12 ">
                                <h3 class="sub_heading">Company</h3>
                            </div>
                    </div>   -->
                    <!-- main grid -->
                    <div class="row" v-if="clients.length > 0">
                        <!-- item start -->
                        <div class="col-md-6 col-lg-6 col-xl-4 mb-3" v-for="item in clients" :key="item.id">

                            <div class="card_item">
                                <div class="row align-items-center">
                                <div class="col-12">
                                        <div class="card_left_container">
                                            <router-link v-if="parent == 'null'"  v-bind:to="'/view-client/' + item.id">
                                            <div class="left_card_area">
                                                <div v-if="!item.logo">
                                                    <h5>{{ getFirstLetter(item.company) }}</h5>
                                                </div>
                                                <div v-if="item.logo">
                                                    <img class="client_logo" :src="'/'+item.logo.file_name">
                                                </div>
                                            </div>
                                            </router-link>
                                            <div class="right_card_area" >
                                                <!-- <div v-if="item.client_staff_count > 0 || item.properties_count > 0" @click="getClientDetails(item)" data-bs-toggle="modal" data-bs-target="#clientdetails"><h6 class="card_name">{{item.company}}</h6></div>
                                                <div v-else><h6 class="card_name">{{item.company}}</h6></div> -->
                                                <div class="bottom_flexs">
                                                    <div class="right_cart_lines">
                                                        <div v-if="item.client_staff_count > 0 || item.properties_count > 0" @click="getClientDetails(item)" data-bs-toggle="modal" data-bs-target="#clientdetails"><h6 class="card_name">{{item.company}}</h6></div>
                                                        <div v-else><h6 class="card_name">{{item.company}}</h6></div>
                                                    </div>
                                                    <!-- <div class="right_cart_lines">
                                                        <ul>
                                                            <li><router-link v-bind:to="'/client-property/' + item.id" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Properties ({{item.properties_count}})</router-link></li>
                                                            <li><router-link v-if="parent == 'null'"  v-bind:to="{name:'clientStaff',params:{id:item.id}}" class=" action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">staff ({{item.client_staff_count}})</router-link></li>
                                                            <li><router-link v-bind:to="'/client-quotes/' + item.id" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Quotes ({{item.quotes_count}})</router-link></li>
                                                        </ul>
                                                    </div> -->

                                                    <div class="edit_action">
                                                        <label class="switch client-status-toggle">
                                                            <input type="checkbox" :checked="item.user?.user_activated == 1 ? 'checked' : ''" :value="item.user?.user_activated"  @click="updateClientStatus(item.user.id,$event)">
                                                            <div class="slider round"></div>
                                                        </label>
                                                        <a href="#" id="dropdownMenuButton" class="no_action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <router-link v-bind:to="'/client-property/' + item.id" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Properties ({{item.properties_count}})</router-link>
                                                            <router-link v-if="parent == 'null'"  v-bind:to="{name:'clientStaff',params:{id:item.id}}" class=" action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">staff ({{item.client_staff_count}})</router-link>
                                                            <router-link v-bind:to="'/client-quotes/' + item.id" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Quotes ({{item.qoute_sum}})</router-link>

                                                            <router-link v-bind:to="'/client-billing/' + item.id" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Billing</router-link>
                                                            <router-link v-if="parent == 'null'"  v-bind:to="'/view-client/' + item.id" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Edit</router-link>

                                                            <a class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item" v-if="role == roleData.admin || role == roleData.super_admin" data-bs-toggle="modal" data-bs-target="#deleteModel" href="#" @click="deleteRequest(item.id,'Clients' , 'client')">Delete </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-3">
                                        <div class="edit_action">
                                            <a href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <router-link v-bind:to="'/client-billing/' + item.id" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Billing</router-link>
                                                <router-link v-if="parent == 'null'"  v-bind:to="'/view-client/' + item.id" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Edit</router-link>
                                                <a class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item" v-if="role == roleData.admin || role == roleData.super_admin" data-bs-toggle="modal" data-bs-target="#deleteModel" href="#" @click="deleteRequest(item.id,'Clients' , 'client')">Delete </a>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <!-- item end  -->
                    </div>
                    <div  v-if="!loading && clients.length <=0"><EmptySearch></EmptySearch></div>
                    <Pagination class="pagination_clients" v-if="clients.length > 0 && table_clients.last_page > 1" @changetable="getClients" :table_data="table_clients"></Pagination>
                    <!-- grid end -->
                </div>
            </div>
            <div class="modal fade custom_base_model custom_base_model_large" id="clientdetails" tabindex="-1"
                    aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                    <clientInfoPopup @closeInfoPopup="closeInfoPopup" :client="clientInfo"></clientInfoPopup>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>
            <!-- new design end  -->
         </div>
    </div>
</template>


<script>

import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";
import viewClient from "./clientView";
import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import FilterPage from "../FilterPage";
import roleData from '../../data.js';
import clientInfoPopup from './clientInfoPopup';
import Navigation from "../../components/Navigation";
import jQuery from 'jquery';
import moment from "moment";

export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'FilterPage': FilterPage,
        'viewClient': viewClient,
        'clientInfoPopup': clientInfoPopup,
        'Navigation': Navigation,
    },
    data() {
        return {
            total: 0,
            roleData : roleData,
            role: "",
            successmsg: "",
            deletemsg: "",
            txtSync:"",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            clients: [],
            clientId: "",
            clientInfo:"",
            filterLoader: false,
            table_clients:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
                status_filter:"active",
                status_filter_options: ["all", "active", "not active"],
            },
            parent:"",
            isActive:false,
            userStatus: false,
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        this.parent = localStorage.getItem("parent");
        this.refreshRecord();
        this.txtSync="Sync with QB";
        this.matchingHeighFunc("card_item");
    },
    methods: {
        updateClientStatus(userID,e) {

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
        matchingHeighFunc(class_name){
            setTimeout(function(){
               var highestBox = 0;
                $('.'+class_name).each(function(){


                        // If this box is higher than the cached highest then store it
                        if($(this).height() > highestBox) {
                        highestBox = $(this).height();
                        }

                    });
                // console.log($('.'+class_name));
                $(document).find("."+class_name).height(highestBox);
            },3000);
        }
        ,
        getFirstLetter(item){
            if (item)
                return item.slice(0, 1);
        },
        async refreshRecord() {
            await this.getClients();
            await this.checkSyncStatus();
        },

       async  getClients(withLoader = true) {
        this.loading=true && withLoader;
        this.filterLoader = true;
            try {
                const response = await axios.get('/api/client?page='+this.table_clients.current_page+'&size='+this.table_clients.per_page +'&search='+this.table_clients.search +'&status_filter='+this.table_clients.status_filter);
                this.table_clients.last_page=response.data.clients.last_page;
                this.clients = response.data.clients.data;
                this.total = response.data.clients.total;
                await this.clients.forEach(async (value,index) => {
                    let getSum = 0;
                    await value.properties.forEach((prop,index) => {
                        if (prop.isActive == 1 && prop.is_quote ==0){
                            getSum += prop.total_quotes;
                        }
                    });
                    value.qoute_sum = getSum;
                });
                // console.log(this.clients);
            }catch(e){}
            this.loading=false;
            this.filterLoader =false
        },
        getClientDetails(item){
            this.clientInfo = item;
        },
        forceRerender(msg) {
            this.clientInfo="";
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

        getClientsSearch(){
            this.table_clients.current_page=1;
            this.getClients(false);

        },
        getClientsFilter(){
            this.table_clients.current_page=1;
            this.getClients();

        },
        showLoading(){
            this.txtSync="Syncing..."
            this.loading=true
        },
        async checkSyncStatus(){
            try{
                const response = await axios.get('/api/client/check-qb/status');
                if(response.data.isSynced){
                    this.txtSync="Synced"
                }else{
                    this.txtSync="Sync with QB"
                }
            } catch(e){}
            this.loading=false;
        },
        closeInfoPopup(){
            this.clientId = "";
        },

    },
    watch: {
        'table_clients.status_filter'(val) {
            this.table_clients.current_page=1;
            this.isActive = !this.isActive;
            this.getClients();
        },
    },
}

// jQuery(document).ready(function(){


//     jQuery(document).on("click",".card_item",function(e){

// // console.log(jQuery(this).data('mod'));
//         if($(e.target).hasClass("action_link") || $(e.target).hasClass("edit_action") || $(e.target).hasClass("no_action") ){
//             // console.log("action");

//         }else {
//             $("#clientdetails").modal('show');
//         }
//     });
// });
</script>
