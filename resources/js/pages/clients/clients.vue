<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <h1 class="h4 heading_text">All PMC</h1>
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
                             <SingleFieldSearch @changetable="getClientsSearch"
                                       :table_data="table_clients"></SingleFieldSearch>
                        </div>
                        <div class="col-sm-6 text-sm-end" v-if="role == roleData.admin || role == roleData.super_admin">
                            <a href="/qb" @click="showLoading">
                             <button type="button" style="color:white;"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">
                               {{txtSync}}
                            </button>
                            </a>
                            <router-link  to="/add-client" >  <button type="button" style="color:white;"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">
                               Add
                            </button> </router-link>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center" v-if="role == roleData.admin || role == roleData.super_admin">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 text-sm-end">
                        <FilterPage @changetable="getClientsFilter" :table_data="table_clients"></FilterPage>
                    </div>
                </div>
                <div id="paint_grades_table" class="no_more_tables table_to_be_used mg_top_0rem">
                    <table class="table" >
                        <thead>
                        <tr>
                            <!--<th>Id</th>-->
                            <th>Company</th>
                            <th class="text_end_only_desktop">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="clients.length > 0">
                        <tr v-for="item in clients" :key="item.id">
                            <!--<td data-title="Id">{{ item.id }}</td>-->
                            <td data-title="Company">
                                <a class="table_btn_links font_12 font_weight_700 text_uppercase"
                                   data-bs-toggle="modal" data-bs-target="#ClientView" href="#"
                                   @click="getClientDetails(item)">
                                   {{ item.company }}
                                </a>
                            </td>
                            <td data-title="Action" class="text_end_only_desktop">
                                <div class="dropdown">
                                    <span  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                        </svg>
                                    </span>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <router-link v-if="parent == 'null'"  v-bind:to="{name:'clientStaff',params:{id:item.id}}" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Staff ({{item.client_staff_count}})</router-link>
                                    <router-link   v-bind:to="'/client-property/' + item.id" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Properties ({{item.properties_count}})</router-link>
                                    <router-link   v-bind:to="'/client-quotes/' + item.id" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Quotes ({{item.quotes_count}})</router-link>
                                    <router-link   v-bind:to="'/client-billing/' + item.id" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Billing</router-link>
                                    <router-link v-if="parent == 'null'"  v-bind:to="'/view-client/' + item.id" class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Edit</router-link>
                                    <a class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item" v-if="role == roleData.admin || role == roleData.super_admin" data-bs-toggle="modal" data-bs-target="#deleteModel" href="#" @click="deleteRequest(item.id,'Clients' , 'client')">Delete </a>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && clients.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="clients.length > 0 && table_clients.last_page > 1" @changetable="getClients"
                                :table_data="table_clients"></Pagination>

                </div>
            </div>
            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>
            <!--            Details Models -->
            <div class="modal fade custom_base_model custom_base_model_large" id="ClientView" tabindex="-1"
                aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <viewClient @closeEvent="forceRerender" :client="clientInfo"></viewClient>
            </div>

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
export default {
    components: {
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'FilterPage': FilterPage,
        'viewClient': viewClient,
    },
    data() {
        return {
            roleData : roleData,
            role: "",
            parent:"",
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
            table_clients:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
                status_filter:"",
                status_filter_options: ["all", "active", "not active"],
            },
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        this.parent = localStorage.getItem("parent");
        this.refreshRecord();
        this.txtSync="Sync with QB";
    },
    methods: {
        refreshRecord() {
            this.loading=true;
            this.getClients();
            this.checkSyncStatus();
            setTimeout(()=>{this.loading=false;}, 1500);
        },

        getClients() {
            axios.get('/api/client?page='+this.table_clients.current_page+'&size='+this.table_clients.per_page +
             '&search='+this.table_clients.search +'&status_filter='+this.table_clients.status_filter)
                .then((response) => {
                    this.table_clients.last_page=response.data.clients.last_page;
                    this.clients = response.data.clients.data;
                    // console.log(this.clients);

                });
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
            this.getClients();

        },
        getClientsFilter(){
            this.table_clients.current_page=1;
            this.getClients();

        },
        showLoading(){
            this.txtSync="Syncing..."
            this.loading=true
        },
        checkSyncStatus(){
            axios.get('/api/client/check-qb/status')
                .then((response) => {
                    if(response.data.isSynced){
                        this.txtSync="Synced"
                    }else{
                        this.txtSync="Sync with QB"
                    }
                    setTimeout(()=>{this.loading=false;}, 1500);
                });
        }

    }
}


</script>
