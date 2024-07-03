<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="inline_buttonss"  >
                <div class="row mb-3">
                    <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Yearly Pricing Increment <span v-if="property">({{property.title}})</span></h1></div>
                    <Navigation></Navigation>
                </div>
            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg!==''">
                <strong>{{ deletemsg }}</strong>
            </div>
            <div class="alert alert-danger" v-if="errormsg!==''">
                <strong>{{ errormsg }}</strong>
            </div>
                    <!-- ================ -->
                    <div class="table_area">
                        <div id="appartment_type_table" class="no_more_tables table_to_be_used mg_top_1rem">
                            <div class="custom__search">
                                <div class="row g-0">
                                    <div class="col-sm-8 col-md-8 col-lg-6">
                                        <SingleFieldSearch @changetable="changeTableAppartment('',true)"
                                                                :table_data="table_priceIncrement"></SingleFieldSearch>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-6 text-sm-end">
                                        <button type="button"
                                                class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                                data-bs-toggle="modal" data-bs-target="#priceIncrementAdd">Add new
                                        </button>
<!--                                        <router-link  v-bind:to="'/client-property/'+this.property.client_id" class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <!--<th class="min_max_120">Id</th>-->
                                    <th>Service</th>
                                    <!--<th>Client</th>-->
                                    <th>Year</th>
                                    <th>Increment (%)</th>
                                    <th>Status</th>
                                    <th class="text_end_only_desktop">Action</th>
                                </tr>
                                </thead>
                                <tbody v-if="price_increments.length > 0">
                                <tr v-for="item in price_increments" :key="item.id">
                                    <!--<td data-title="Service Category"  class="min_max_120">{{ item.id }}</td>-->
                                    <td data-title="Year">{{ item.service ? item.service.category : '' }}</td>
                                    <!--<td data-title="Client">{{ item.client ? item.client.company : '' }}</td>-->
                                    <td data-title="Year">{{ item.year }}</td>
                                    <td data-title="Percentage">{{ item.percentage }}%</td>
                                    <td data-title="Percentage">{{ item.is_already_incremented ? 'Incremented' : 'Not Incremented Yet' }}</td>
                                    <!-- <td data-title="Action" class="text_end_only_desktop">
                                        <div class="edit_action afs">
                                            <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" viewBox="0 0 16 16"
                                                class="bi bi-three-dots-vertical no_action">
                                                <path
                                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                </path>
                                            </svg>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <router-link v-if="!item.is_already_incremented"
                                                    to="#" class="table_btn_link font_12 font_weight_700 text_uppercase"
                                        data-bs-toggle="modal" data-bs-target="#priceIncrementEdit"
                                        @click="editIdMethodPriceIncrement(item.id)" v-if="!item.is_already_incremented">Edit</router-link>
                                                    <router-link v-if="!item.is_already_incremented"
                                                    to="#" data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                    @click="deleteRequest(item.id,'Yearly Increment' , 'yearly-increment')"
                                                    class="table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Delete</router-link>
                                                    </div>
                                        </div>

                                          <a href="#"
                                        class="table_btn_link font_12 font_weight_700 text_uppercase"
                                        data-bs-toggle="modal" data-bs-target="#priceIncrementEdit"
                                        @click="editIdMethodPriceIncrement(item.id)" v-if="!item.is_already_incremented">Edit</a>
                                        <span class="seprator" v-if="!item.is_already_incremented">|</span>
                                        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                            class="table_btn_link font_12 font_weight_700 text_uppercase" href="#"
                                            @click="deleteRequest(item.id,'Yearly Increment' , 'yearly-increment')">Delete </a>
                                    </td> -->

                                    <td data-title="Action" class="text-right">

        <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" viewBox="0 0 16 16"
                class="bi bi-three-dots-vertical no_action">
                <path
                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                </path>
            </svg>
        </a>
        <div class="dropdown-menu custom-action-dropdown"
            aria-labelledby="dropdownMenuButton">
            <a href="#"
            class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
        data-bs-toggle="modal" data-bs-target="#priceIncrementEdit"
        @click="editIdMethodPriceIncrement(item.id)" v-if="!item.is_already_incremented">Edit</a>
        <a data-bs-toggle="modal" data-bs-target="#deleteModel"
        class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" href="#"
            @click="deleteRequest(item.id,'Yearly Increment' , 'yearly-increment')">Delete </a>

        </div>
    </td>
                                </tr>
                                </tbody>



                            </table>
                            <div v-if="!loading && price_increments.length <=0">
                                <EmptySearch></EmptySearch>
                            </div>
                            <Pagination v-if="price_increments.length > 0 && table_priceIncrement.last_page > 1" @changetable="changeTableAppartment"
                                        :table_data="table_priceIncrement"></Pagination>

                        </div>

                    <!-- =============== -->
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="priceIncrementAdd" tabindex="-1"
                 aria-labelledby="appartmentTypesAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <AddPriceIncrement @closeEvent="forceRerender" @errorEvent="errorEvent"></AddPriceIncrement>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="priceIncrementEdit" tabindex="-1"
                 aria-labelledby="appartmentTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditPriceIncrement @closeEvent="forceRerender"
                                    :priceIncrementId="priceIncrementId" @popupCloseEvent="popupRerender" @errorEvent="errorEvent"></EditPriceIncrement>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle" @popupCloseEvent="popupRerender"></DeleteModel>
            </div>
        </div>
    </div>
</template>
<script>
import AddPriceIncrement from "./add";
import EditPriceIncrement from "./edit";
import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import Navigation from "../../components/Navigation";

import axios from "axios";
// import {required, minLength, maxLength, between} from 'vuelidate/lib/validators';
export default {
    components: {
        'AddPriceIncrement': AddPriceIncrement,
        'EditPriceIncrement': EditPriceIncrement,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch':EmptySearch,
        'Navigation':Navigation
    },
    data() {
        return {
            successmsg: "",
            deletemsg: "",
            errormsg: "",
            price_increments: [],
            service_types: [],
            has_error: false,
            loading: true,
            priceIncrementId: "",
            serviceTypeId: "",
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            table_priceIncrement:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            property_id:this.$route.params.id,
            property:[],




        }
    },
    mounted() {
        this.getPropertyInformation();
        this.getPriceIncrement();
    },
    methods: {
        getPriceIncrement() {
            // this.loading = true;
            axios.get('/api/yearly-increment/'+this.property_id+'?page='+this.table_priceIncrement.current_page+'&size='+this.table_priceIncrement.per_page + '&search='+this.table_priceIncrement.search)
                .then((response) => {
                    this.table_priceIncrement.last_page=response.data.price_increments.last_page;
                    this.price_increments = response.data.price_increments.data;
                    this.loading = false;
                });
        },

        forceRerender(msg) {
            this.priceIncrementId="";
            this.successmsg = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        refreshRecord(){
            this.getPriceIncrement();
        },
        editIdMethodPriceIncrement(id) {
            this.priceIncrementId = id;
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

        changeTableAppartment(val,search = false){
         this.table_priceIncrement.current_page = val.current_page;
         if(search){
             this.table_priceIncrement.current_page=1;
         }
         this.getPriceIncrement();
        },
        saveSort(event,id){
            var sort=event.target.value
            axios.put('/api/yearly-increment/'+id,{"sort":sort})
                .then(response => {
                    this.getPriceIncrement()
                })
                .catch(err => {
                    this.has_error = true;
                })

        },
        popupRerender(){
            this.priceIncrementId="";
        },
        errorEvent(msg){
            this.errormsg = msg;
            setTimeout(() => {
                this.errormsg = "";
            }, 4000);
        },
        getPropertyInformation(){
            axios.get('/api/get-property-info/'+ this.property_id)
            .then((response) => {
                this.property = response.data.property;
            });
        }



    }
}
</script>
