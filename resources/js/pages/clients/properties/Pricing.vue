<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="inline_buttonss"  >
            <div class="row mb-3">

                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Property Pricing</h1></div>
                <Navigation></Navigation>
                <div class="col-md-12 col-sm-12 text-end inner_button_full pricing_page_mobile justify-content-end align-items-center" v-if="property">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#copyPriceModel" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mb-2">Copy from General Pricing</button>
                    <router-link v-bind:to="'/client-property-pricing/' + property.id" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mb-2">View Pricing</router-link>
                </div>
            </div>
        </div>
        <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
            <strong>{{ successmsg }}</strong>
        </div>
        <div class="alert alert-danger" v-if="errormsg!==''">
            <strong>{{ errormsg }}</strong>
        </div>
        <div class="details_shorts"  v-if="property">
            <div class="row">
                <div class="col-md-6">
                    <div class="details_shorts_inner">
                        <h6 class="font_14_old text_color_white font_weight_700 font_family_Montserrat">
                            Property Name
                            <small
                                class="text_color_white font_weight_500 font_family_Montserrat">{{property.title}}</small>
                        </h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="details_shorts_inner">
                        <h6 class="font_14_old text_color_white font_weight_700 font_family_Montserrat">
                            Address
                            <small
                                class="text_color_white font_weight_500 font_family_Montserrat">
                                {{property.street1}} {{property.street2}} {{property.city}} {{property.state}}, {{property.country}} {{property.zipcode}}
                            </small>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <form autocomplete="off" @submit.prevent="property_pricing_submit" method="post">
            <!-- start asad code -->
            <div class="tabs_genral_prices" id="etabs_genral_prices">
                <ul class="nav nav-tabs" role="tablist">
                        <li v-for="(item , index) in services" class="nav-item">
                            <a class="nav-link tessin" :class="{ active: index == 0 }" @click="changeCurrentServiceId(item.id,item.category)" data-toggle="tab" v-bind:href="'#tabs-'+item.id" role="tab">{{item.category}}</a>
                            <div  v-if="item.notes && item.notes.length > 0" class="link_view_notes" data-toggle="collapse" v-bind:data-target="'#notesCollapse-'+item.id" role="button" aria-expanded="false" v-bind:aria-controls="'notesCollapse-'+item.id">View Notes <svg class="sidebaricon_chevron"><use xlink:href="#right-chevron"></use></svg></div>

                        </li>
                </ul><!-- Tab panes -->
                <div class="tab-content">
                    <div   v-for="(item , index) in services" class="">
                        <div v-if="item.notes && item.notes.length > 0" class="col-md-12">
                            <div class="collapse notes_box_colleps" data-parent="#etabs_genral_prices" v-bind:id="'notesCollapse-'+item.id" >
                                <div class="" v-for="(note,index) in item.notes">
                                    <p v-if="note.service_id == item.id">{{note.description}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- tab pane start -->
                    <div  v-for="(item , index) in services" class="tab-pane" :class="{ active: index == 0 }" v-bind:id="'tabs-'+item.id" role="tabpanel">
                        <!-- head area -->
                        <div class="head_area">
                            <div class="row">
                                <div class="col name-col">
                                    <h6>{{item.category}}</h6>
                                </div>
                                <div v-if="item.sub_services && item.sub_services.length>0 && item.dependent &&  item1.type.trim().toLowerCase() != 'project'" v-for="(item1 , index1) in appartmentTypes" :key="item1.id" class="col">
                                    <h6>
                                        {{ item1.type }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <!-- end head area -->
                        <!-- body area start -->
                        <div class="body_area">
                            <div class="row " v-for="(item2 , index2) in item.sub_services" :key="item2.id" v-if= "item2.is_independent == 0">
                                <div class="col name-col responsive_before" data-title="Sub Service">
                                    <h6>
                                        <span><input class="checkbox-col" type="checkbox"  v-on:click = "onCheck(item2,$event)" v-model="item2.is_show_to_client"></span>
                                        {{item2.name}}
                                    </h6>
                                </div>
                                <div class="d-flex col responsive_before" :data-title="item3.apartment.type" v-for="(item3 , index3) in property_pricing_form.clientPropertyPricing" v-if="item2.id == item3.sub_service_id &&
                                                    item3.apartment_type_id !==null && appartmentTypes.some(e =>  e.type.trim().toLowerCase() != 'project' && e.id === item3.apartment_type_id)"
                                                        :key="item3.id">
<!--                                    <p class="doller_sign">-->
                                    <input min="0" max="99999" type="number" class="form-control" value="10" v-model.trim="item3.price" v-on:change="changePrice(index3)">
<!--                                    </p>-->
                                </div>
                            </div>

                            <!-- sub service -->
                            <div class="sub_service_area">

                                <div class="row">
                                    <!--<div class="col-12">
                                        <div class="sub_service_heading" v-if="item.sub_services.length>0 && item.independent">
                                            <h4>Sub Services</h4>
                                            <h5>Price in $</h5>
                                        </div>
                                    </div>-->
                                </div>

                                <div class="row">
                                    <div  v-for="(item2 , index2) in item.sub_services" :key="item2.id" v-if= "item2.is_independent == 1" class="col-md-4 col-sm-12">
                                        <div class="row">

                                            <div class="col-6">
                                                <h6>
                                                    <span><input class="col" type="checkbox"  v-on:click = "onCheck(item2,$event)"  v-model="item2.is_show_to_client"></span>
                                                    {{item2.name}}
                                                </h6>
                                            </div>
                                            <div class="col-2 px-0 gen_prices" :data-title="item3.type" v-for="(item3 , index3) in property_pricing_form.clientPropertyPricing" v-if="item2.id == item3.sub_service_id && item3.apartment_type_id == null "
                                                            :key="item3.id">
                                                <input min="0" max="99999" type="number" class="form-control" v-model.trim="item3.price" value="10" v-on:change="changePrice(index3)"/>
                                            </div>
                                            <div class="col-4" :data-title="item3.type" v-for="(item3 , index3) in property_pricing_form.clientPropertyPricing" v-if="item2.id == item3.sub_service_id && item3.apartment_type_id == null"
                                                            >
                                                <input  type="text" class="form-control" maxLength="50" v-model.trim="item3.price_tag"  placeholder=""/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- sub Services end here -->
                        </div>
                        <!-- end body area -->
                    </div>
                    <!-- tab pane end  -->
                </div>
            </div>
            <div class="inline_buttonss">
                <div class="row">
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600"> Save</button>
                        <button type="submit" @click="saveAndClose" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Save &amp; Close</button>
                        <!--<a @click="$router.back()"><button type="cancel" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600"> Cancel</button></a>-->
                    </div>
                </div>
            </div>
            <!-- end asad code -->
        </form>
        <div class="modal fade custom_base_model custom_base_model_small" id="copyPriceModel" tabindex="-1"
                aria-labelledby="copyModel" aria-hidden="true" data-bs-backdrop="static">
            <copyModal @closeEvent="copyRecord" :propertyId="property.id"></copyModal>
        </div>
        <!-- Add Modal -->
        <div class="modal fade custom_base_model custom_base_model_small_medium" id="priceNotesModel" tabindex="-1"
            aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
            <AddPriceNotesModal  @openEvent="openDeleteModal" @deletecloseEvent="deletecloseEvent" @closeEvent="getRecordNotes" :property="property.id" :serviceId="currentServiceId" :notes="notes" :serviceName="currentServiceName" :confirmDelete="confirmDelete"></AddPriceNotesModal>
        </div>
        <div class="modal fade custom_base_model custom_base_model_small" id="confirmDeletePopup" tabindex="-1" aria-labelledby="confirmDeletePopup" aria-hidden="true" data-bs-backdrop="static">
            <DeleteModel @closeEvent="closeDeleteModal" type="Notes Item"></DeleteModel>
        </div>
                <!--Price Notes-->
        <!-- top block end  -->
        <div class="client_prices_area">

            <div class="client_prices_block_top">
                <div class="info_area">
                    <div class="inline_buttonss" >
                            <div class="row mb-3 align-items-center">
                                <div class="col-6 col-sm-6">
                                    <h1 class="h4 heading_text mb_mobile_0">{{this.currentServiceName ? this.currentServiceName : 'Client Price' }} Notes</h1>
                                </div>
                                <div class="col-6 col-sm-6 text-end">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#priceNotesModel" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Add/Edit Notes</button>
                                </div>
                            </div>
                    </div>
                    <div class="row" v-if="notes">
                        <div class="col-md-12">
                            <p class="color" v-for="(note,index) in notes">{{note.description}}</p>
                            <!--<p>1­‐hour of prep is included in price. </p>
                            <p>Additional: White ceilings PLUS: 1br $ 60, 2br $ 85 3br $ 120 </p>
                            <p>Additional: Semi-­gloss baseboards/trims/doors PLUS: 1br $ 90,  2br $ 120, 3 Br $ 180</p>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- info area end  -->
    </div>
</template>

<script>
import axios from "axios";
import {required, minLength, maxLength, email, url, decimal} from 'vuelidate/lib/validators'
import Loader from "../../LoaderSpinner";
import roleData from '../../../data.js';
import copyModal  from './copyPricePopup';
import AddPriceNotesModal from "./AddClientAdditionalPrice";
import DeleteModel from "../../prices/confirmDeletePopup";
import Navigation from "../../../components/Navigation";
export default {
    name: "PaintSpecs",
    components: {
        'Loader': Loader,
        'copyModal':copyModal,
        'AddPriceNotesModal':AddPriceNotesModal,
        'DeleteModel': DeleteModel,
        'Navigation': Navigation,
    },
    data() {
        return {
            role: "",
            roleData : roleData,
            successmsg: "",
            loading: false,
            already_record: false,
            paint_values: [],
            property_pricing_temp: "",
            property_pricing: '',
            services: [],
            appartmentTypes: [],
            property:'',
            property_pricing_form: {
                property_id: this.$route.params.id,
                clientPropertyPricing: [],
            },
            dependent : false,
            independent: false,
            closeFlag:false,
            notes: [],
            // clientId:"",
            errormsg:"",
            currentServiceId:"",
            currentServiceName:"",
            confirmDelete:"",
        }
    },

    mounted() {

        this.loading = true;
        this.getRecord(this.property_pricing_form.property_id);
        // this.loading = false;
        this.role = localStorage.getItem("role");

    },
    methods: {
        changePrice(index) {
            this.property_pricing_form.clientPropertyPricing[index].price = this.property_pricing_form.clientPropertyPricing[index].price;
        },
        property_pricing_submit() {
            this.loading=true;
            axios.post('/api/client-property-pricing', this.property_pricing_form)
                .then(response => {
                    this.loading=false;
                    this.successmsg = "Property Pricing has been updated";
                    if(this.closeFlag){
                        this.$router.push('/client-property/'+this.property.client.id);
                    }else{
                        this.getRecord(this.property.id);
                    }
                    setTimeout(() => {
                        this.successmsg = "";
                        // this.$router.back();
                    }, 3000);

                })
                .catch(err => {
                    this.has_error = true;
                })

        },
        getRecord(id) {
            this.loading=true;
            axios.get('/api/client-property-pricing/' + id + "/edit")
                .then((response) => {
                    // // console.log('pricing', response.data);
                    this.services = response.data.allServices;
                    if(!this.currentServiceId){
                        this.currentServiceId = this.services ? this.services[0].id : "";
                        this.currentServiceName = this.services ? this.services[0].category : "";
                    }
                    this.property_pricing_form = response.data;
                    this.appartmentTypes = response.data.appartmentTypes;
                    this.property = response.data.property;
                    this.getPropertyServiceNotes(id);
                    // this.notes = response.data.notes;
                    // this.clientId = response.data.property.client.id;
                    this.loading = false;
                    this.property_pricing_form.clientPropertyPricing.forEach(data=>{
                        this.appartmentTypes.forEach(ap=>{
                            if(ap.id == data.appartment_type_id){
                                data.type = ap.type;
                            }

                        });
                    });
                    this.services.forEach((service,index) => {
                        this.services[index].category = this.removeFirstWord(service.category);
                        if(service.sub_services && service.sub_services.length > 0){
                            let sub_service  = service.sub_services.find(sub_service => (sub_service.is_independent == 0));
                            let sub_service_independent  = service.sub_services.find(sub_service => (sub_service.is_independent == 1));
                            if(sub_service){
                                service.dependent = true;
                            }
                            if(sub_service_independent){
                                service.independent = true;
                            }
                        }
                    })

                });


        },
        getRecordNotes(){
            this.getRecord(this.property.id);
        },
        copyRecord(val) {
            if (val == 0) {
                return
            }
             axios.get('/api/copy-pricing/' + this.property.id)
                .then((response) => {
                    this.loading=true;
                    this.getRecord(this.property.id);
                });
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },
        saveAndClose(){
            this.closeFlag = true;
        },
        onCheck(item,event){
            this.loading=true;
            var flag = event.currentTarget.checked ? 1 : 0;
            axios.post('/api/udpate-pricing-check/'+item.id, {'flag' : flag})
            .then(response => {
                if(response.data.error){
                    this.errormsg = response.data.error;
                }
                this.getRecord(this.property.id);
                this.loading=false;
                setTimeout(() => {
                    this.errormsg = "";
                }, 3000);
            })
            .catch(err => {
                this.has_error = true;
            })
        },
        changeCurrentServiceId(id,name){
            this.currentServiceId = id;
            this.currentServiceName = name;
        },
        getPropertyServiceNotes(id){
            this.loading=true;
            axios.get('/api/property-service-notes/' + id + "/" +this.currentServiceId)
                .then((response) => {
                    this.notes = response.data.notes;
                    this.loading = false;
                });
        },
        removeFirstWord(str) {
            str = str.replace("PU ", "");
            str = str.replace("PT ", "");
            str = str.replace("CA ", "");
            str = str.replace("RF ", "");
            str = str.replace("JA ", "");
            str = str.replace("FL ", "");
            return str;
        },
        openDeleteModal(){
            $("#confirmDeletePopup").modal('show');
        },
        closeDeleteModal(val){
            if(val == 1){
                this.confirmDelete = true;
            }
        },
        deletecloseEvent(){
            this.confirmDelete = "";
        }
    },
    watch:{
        'currentServiceId'() {
            this.getPropertyServiceNotes(this.property.id);
        }
    }
}
</script>

<style scoped>

</style>
