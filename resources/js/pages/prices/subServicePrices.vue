<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="row">
            <div class="col-sm-6 col-8"><h1 class="h4 heading_text">General Pricing</h1></div>
            <Navigation></Navigation>

        </div>
        <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
            <strong>{{ successmsg }}</strong>
        </div>
        <form autocomplete="off" @submit.prevent="subService_pricing_submit" method="post">
            <!-- start asad code -->
            <div class="tabs_genral_prices" id="etabs_genral_prices">
                <ul class="nav nav-tabs" role="tablist">
                        <li v-for="(item , index) in services" class="nav-item">
                            <a class="nav-link" :class="{ active: index == 0 }" @click="changeCurrentServiceId(item.id,item.category)" data-toggle="tab" v-bind:href="'#tabs-'+item.id" role="tab">{{item.category}}</a>
                            <!-- <div  v-if="item.notes.length > 0" class="link_view_notes" data-toggle="collapse" v-bind:data-target="'#notesCollapse-'+item.id" role="button" aria-expanded="false" v-bind:aria-controls="'#notesCollapse-'+item.id">View Notes <svg class="sidebaricon_chevron"><use xlink:href="#right-chevron"></use></svg></div> -->
                        </li>
                </ul><!-- Tab panes -->
                <div class="tab-content">
                    <div v-for="(item , index) in services" class="">
                        <div v-if="item.notes.length > 0" class="col-md-12">
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
                                    <h6 >{{item.category}}</h6>
                                </div>
                                <div v-if="item.sub_services.length>0 && item.dependent && item1.type.trim().toLowerCase() != 'project'" v-for="(item1 , index1) in appartmentTypes" :key="item1.id" class="col">
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
                                    <h6>{{item2.name}}</h6>
                                </div>
                                <div class="d-flex col responsive_before" :data-title="item3.type" v-for="(item3 , index3) in subService_pricing_form.subServicePricing" v-if="item2.id == item3.sub_service_id &&
                                                    item3.appartment_type_id !==null && appartmentTypes.some(e =>  e.type.trim().toLowerCase() != 'project' && e.id === item3.appartment_type_id)"
                                                        :key="item3.id">
<!--                                    <p class="doller_sign">-->
                                        <input min="0" max="99999" type="number" class="form-control" value="10" v-on:change="changeSubItemBasePrice(index3)" v-model.trim="item3.base_price">

<!--                                    </p>-->
                                </div>
                            </div>

                            <!-- sub service -->
                            <div class="sub_service_area">
                                <div class="row">
                                    <div  v-for="(item2 , index2) in item.sub_services" :key="item2.id" v-if= "item2.is_independent == 1" class="col-md-4 col-sm-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <h6>{{item2.name}}</h6>
                                            </div>
                                            <div class="col-2 px-0 gen_prices" :data-title="item3.type" v-for="(item3 , index3) in subService_pricing_form.subServicePricing" v-if="item2.id == item3.sub_service_id && item3.appartment_type_id == null"
                                                            :key="item3.id">
                                                <input min="0" max="99999" type="number" class="form-control" v-on:change="changeSubItemBasePrice(index3)" v-model.trim="item3.base_price" value="10"/>
                                            </div>
                                            <div class="col-4" :data-title="item3.type" v-for="(item3 , index3) in subService_pricing_form.subServicePricing" v-if="item2.id == item3.sub_service_id && item3.appartment_type_id == null"
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
        <!-- Add Modal -->
        <div class="modal fade custom_base_model custom_base_model_small_medium" id="priceNotesModel" tabindex="-1"
            aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
            <AddPriceNotesModal @openEvent="openDeleteModal" @deletecloseEvent="deletecloseEvent" @closeEvent="getRecord" :serviceId="currentServiceId" :serviceName="currentServiceName" :confirmDelete="confirmDelete"></AddPriceNotesModal>
        </div>
        <div class="modal fade custom_base_model custom_base_model_small" id="confirmDeletePopup" tabindex="-1" aria-labelledby="confirmDeletePopup" aria-hidden="true" data-bs-backdrop="static">
            <DeleteModel @closeEvent="closeDeleteModal" type="Notes Item"></DeleteModel>
        </div>
                <!--Price Notes-->
        <!-- top block end  -->
        <div class="client_prices_area">

            <div class="client_prices_block_top">
                <div class="info_area">
                    <div class="inline_buttonss sub_Services_bottom_buttons" >
                            <div class="row mb-3 align-items-center">
                                <div class="col-6 col-sm-6">
                                    <h1 class="h4 heading_text">{{this.currentServiceName ? this.currentServiceName : 'Client Price' }} Notes</h1>
                                </div>
                                <div class="col-6 col-sm-6 text-end">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#priceNotesModel" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Add/Edit Notes</button>
                                </div>
                            </div>
                    </div>
                    <div class="row" v-if="notes">
                        <div class="col-md-12">
                            <p class="color" v-for="(note,index) in notes"><span v-if="note.service_id == currentServiceId">{{note.description}}</span></p>
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
import Loader from "../LoaderSpinner";
import AddPriceNotesModal from "./AddClientAdditionalPrice";
import DeleteModel from "./confirmDeletePopup";
import Navigation from "../../components/Navigation";
export default {
    name: "Pricing",
    components: {
        'Loader': Loader,
        'AddPriceNotesModal':AddPriceNotesModal,
        'DeleteModel': DeleteModel,
        'Navigation': Navigation,
    },
    data() {
        return {
            successmsg: "",
            loading: false,
            services: [],
            appartmentTypes: [],
            subService_pricing_form: {
                subServicePricing: [],
            },
            dependent : false,
            independent: false,
            closeFlag:false,
            notes:[],
            currentServiceId:"",
            currentServiceName:"",
            confirmDelete:"",
        }
    },

    mounted() {

        this.loading = true;
        this.getRecord();
    },
    methods: {
        changeSubItemBasePrice(index) {
				this.subService_pricing_form.subServicePricing[index].base_price = this.subService_pricing_form.subServicePricing[index].base_price;
			},
        subService_pricing_submit() {
            this.loading=true;
            axios.put('/api/subService-prices', this.subService_pricing_form)
            .then(response => {
                this.loading=false;
                // console.log(response);
                this.successmsg = "Pricing has been updated";
                if(this.closeFlag){
                    this.$router.push('/dashboard');
                }else{
                    this.getRecord();
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
        getRecord() {
            this.loading=true;
            axios.get('/api/subService-prices')
            .then((response) => {
            this.services = response.data.services;
            if(!this.currentServiceId){
                this.currentServiceId = this.services ? this.services[0].id : "";
                this.currentServiceName = this.services ? this.services[0].category : "";
            }
            this.services.forEach((service,index)=>{
                this.services[index].category = this.removeFirstWord(service.category);
            });
            this.subService_pricing_form.subServicePricing = response.data.serviceSubCategoryAppartmentPrice;
            this.appartmentTypes = response.data.appartmentTypes;
            this.notes = response.data.notes;
            this.subService_pricing_form.subServicePricing.forEach(data=>{
                this.appartmentTypes.forEach(ap=>{
                    if(ap.id == data.appartment_type_id){
                        data.type = ap.type;
                    }

                });
            });
            this.loading = false;
            this.services.forEach(service => {
                if(service.sub_services.length > 0){
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
        saveAndClose(){
            this.closeFlag = true;
        },
        changeCurrentServiceId(id,name){
            this.currentServiceId = id;
            this.currentServiceName = name;
        },
        removeFirstWord(str) {
            str = str.replace("PU ", "");
            str = str.replace("PT ", "");
            str = str.replace("CA ", "");
            str = str.replace("RF ", "");
            str = str.replace("JA ", "");
            str = str.replace("FL ", "");
            return str;
            // const indexOfSpace = str.indexOf(' ');
            // if (indexOfSpace === -1) {
            //     return '';
            // }
            // return str.substring(indexOfSpace + 1);
        },
        openDeleteModal(val){
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

    }
}
</script>

<style scoped>

</style>
