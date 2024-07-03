<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">{{clientName}}</h1></div>
                <Navigation></Navigation>

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
                    <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">Quotes</h5>
                </div>

                <div class="custom__search">
                    <div class="row align-items-center">

                        <div class="col-sm-6">
                             <SingleFieldSearch @changetable="getQuotesSearch"
                                       :table_data="table_quotes"></SingleFieldSearch>
                        </div>

                        <div class="col-sm-6 text-sm-end">
                            <button v-if="role == roleData.client" type="button" class="btn btn_big_medium btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                    data-bs-toggle="modal" data-bs-target="#AddOrEdit">Request Quote</button>
<!--                            <router-link v-if="clientId" to="/clients" class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                        </div>
                    </div>
                </div>

                <div id="paint_grades_table"  class="no_more_tables table_to_be_used mg_top_1rem">
                    <table class="table">
                        <thead>
                        <tr>
                            <!--<th>Id</th>-->
                            <th>Property Name</th>
                            <th v-if="(role == roleData.admin) || (role == roleData.super_admin)">Company</th>
                            <th class="text_end_only_desktop">Action</th>
                        </tr>
                        </thead>
                        <tbody v-if="quotes.length > 0">
                        <tr v-for="item in quotes" :key="item.id">
                            <!--<td data-title="Id">{{ item.id }}</td>-->
                            <td data-title="Title">{{ item.title }}</td>
                            <td v-if="(role == roleData.admin) || (role == roleData.super_admin)" data-title="Company">{{item.client?.company}}</td>
                            <td data-title="Action" class="text_end_only_desktop">
                                <a class="table_btn_link font_12 font_weight_700 text_uppercase"
                                   data-bs-toggle="modal" data-bs-target="#PropertyView" href="#"
                                   @click="currentProperty(item)">View</a>


                                <a v-if="(role == roleData.admin) || (role == roleData.super_admin)" href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"
                                    @click="ApproveQuote(item)">Approve
                                </a>
                                <a v-if="(role == roleData.admin) || (role == roleData.super_admin)" class="table_btn_link font_12 font_weight_700 text_uppercase"
                                   data-bs-toggle="modal" data-bs-target="#deleteModel" href="#"
                                   @click="deleteRequest(item.id,'Quote','property')">Delete</a>


                                <a v-if="role == roleData.client" class="table_btn_link font_12 font_weight_700 text_uppercase"
                                   data-bs-toggle="modal" data-bs-target="#AddOrEdit" href="#"
                                   @click="editIdMethod('property',item.id)">Edit</a>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && quotes.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="quotes.length > 0 && table_quotes.last_page > 1" @changetable="getQuotes"
                                :table_data="table_quotes"></Pagination>

                </div>
            </div>

  </div>

            <div class="modal fade custom_base_model custom_base_model_large" id="PropertyView" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <ViewProperty @closeEvent="forceRerender"  :Property="current_property" :name="'quote'"></ViewProperty>
            </div>

            <!--            Edit Models -->

            <div class="modal fade custom_base_model custom_base_model_large" id="AddOrEdit" tabindex="-1"
                 aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <AddOrEdit @closeEvent="forceRerender" @formResetEvent="formReset"  :clientId="clientId" :PropertyId="propertyId" ></AddOrEdit>
            </div>

            <!--            Delete Model-->

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>






        </div>
    </div>
</template>


<script>

import DeleteModel from "../../deleteModel";
import Loader from "../../LoaderSpinner";
import roleData from '../../../data.js';
import axios from "axios";
import Pagination from "../../Pagination";
import AddOrEdit from "../properties/AddOrEdit";
import ViewProperty from "../properties/ViewProperty";
import Billing from "../properties/billing.vue";
import SingleFieldSearch from "../../SingleFieldSearch";
import EmptySearch from "../../EmptySearch";
import TopNavigation from "../TopNavigation";
import Navigation from "../../../components/Navigation";
export default {

    components: {
        'roleData': roleData,
        'AddOrEdit': AddOrEdit,
        'ViewProperty': ViewProperty,
        'Billing': Billing,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'TopNavigation': TopNavigation,
        'EmptySearch':EmptySearch,
        'Navigation':Navigation,
    },
    data() {
        return {
            clientName:"",
            role: "",
			roleData: roleData,
            currentComponent : 'quotes',
            clientId:  this.$route.params.id ,
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            quotes: [],
            current_property : "",
            propertyId: "",
            table_quotes:{
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
            // console.log('REFRESH REC')

            this.loading=true;
            this.getQuotes();
            // this.loading=false;
        },

        getQuotes() {
            if(this.clientId){

            axios.get('/api/client-quote/'+this.clientId+'?page='+this.table_quotes.current_page+'&size='+this.table_quotes.per_page + '&search='+this.table_quotes.search)
                .then((response) => {
                    this.table_quotes.last_page=response.data.quotes.last_page;
                    this.quotes = response.data.quotes.data;
                    this.loading=false;
                });
                this.getRecord(this.clientId);
            }
            else{
                 axios.get('/api/client-quote?page='+this.table_quotes.current_page+'&size='+this.table_quotes.per_page + '&search='+this.table_quotes.search)
                .then((response) => {
                    this.table_quotes.last_page=response.data.quotes.last_page;
                    this.quotes = response.data.quotes.data;
                    this.loading=false;
                });
            }
        },
        getRecord(id) {
            axios.get('/api/client/' + id + "/edit")
                .then((response) => {
                    this.loading = false;
                    this.clientName = response.data.client.company;

                });
        },
        forceRerender(msg) {
            this.propertyId="";
            this.successmsg = "";
            // console.log(msg,'here is mssge');
            this.refreshRecord();
            if(msg) {
                this.successmsg = msg;
                setTimeout(() => {
                    this.successmsg = "";
                }, 3000);
            }
            this.$forceUpdate();
        },
        formReset(){
            this.propertyId="";
        },

        editIdMethod(type,id){

            // console.log(
                'Edit method called'
            );

            this.propertyId = id;

            // this.$forceUpdate();
        },
        currentProperty(val){

          this.current_property = val;
            // console.log(this.current_property)
        },
        ApproveQuote(property){
            axios.get('/api/client-quote-approve/' + property.id).then((response) => {
                // console.log(response);
                this.successmsg = "Quote has been approved";
                this.refreshRecord();
            //    this.$emit('closeEvent', "Quote has been approved");
            //    $("#PropertyView").modal('hide');
            });
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
        getQuotesSearch(){
            this.table_quotes.current_page=1;
            this.getQuotes();
        },


    }
}


</script>
