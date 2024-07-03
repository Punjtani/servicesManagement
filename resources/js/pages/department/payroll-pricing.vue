<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>
            <div id="errir-alert" class="alert alert-danger" v-if="errormsg!=='' && successmsg == ''">
                <strong>{{ errormsg }}</strong>
            </div>
            <form autocomplete="off" @submit.prevent="subService_pricing_submit" method="post">
                <div class="modal-bodys">
                    <div class="form_box">
                        <div class="table_area_heads">
                            <div class="row">
                                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Payroll Values</h1></div>
                                <Navigation></Navigation>

                            </div>

                        </div>
                        <div class="row align-items-center" v-if="service">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">{{ service.category }} </h6>
                                    </div>
                                </div>
                                <div class="no_more_tables table_to_be_used mg_top_half_rem add_paint_specs_table">

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th v-if="service.sub_services.length>0 && service.dependent">Sub Services</th>
                                            <th v-if="service.sub_services.length>0 && service.dependent" v-for="(item1 , index1) in appartmentTypes" :key="item1.id">
                                                {{ item1.type }} ($)
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item2 , index2) in service.sub_services" :key="item2.id" v-if= "item2.is_independent == 0">
                                        <td data-title="Sub_Services" >{{ item2.name }}</td>
                                            <td data-title="Surface"
                                            v-for="(item3 , index3) in payroll_pricing_form.payrollPricing" v-if="item2.id == item3.sub_service_id && item3.appartment_type_id !==null"
                                                :key="item3.id">
                                                <div class="form-group" v-for = "item4 in appartmentTypes" v-if="item4.id == item3.appartment_type_id">
                                                    <input  min="0" class="form-control" type="number"
                                                        v-model.trim="item3.payroll_price">
                                                </div>

                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                    <table  class="table">

                                        <thead>
                                            <tr>
                                                <th v-if="service.sub_services.length>0 && service.independent">Sub Services</th>
                                                <th v-if="service.sub_services.length>0 && service.independent">Value ($)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item2 , index2) in service.sub_services" :key="item2.id" v-if= "item2.is_independent == 1">
                                            <td data-title="Sub_Services" >{{ item2.name }}</td>
                                                <td data-title="Surface"
                                                v-for="(item3 , index3) in payroll_pricing_form.payrollPricing" v-if="item2.id == item3.sub_service_id && item3.appartment_type_id == null"
                                                    :key="item3.id">
                                                    <div class="form-group">
                                                        <input  min="0" class="form-control" type="number"
                                                            v-model.trim="item3.payroll_price">
                                                    </div>

                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                        </div>
                        <div class="inline_buttonss" v-if="service">
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <button type="submit" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600"> Save</button>
                                    <button type="submit" @click="saveAndClose" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Save &amp; Close</button>
                                    <!--<a @click="$router.back()"><button type="cancel" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600"> Cancel</button></a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {required, minLength, maxLength, email, url, decimal} from 'vuelidate/lib/validators'
import Loader from "../LoaderSpinner";
import Navigation from "../../components/Navigation";

export default {
    name: "PaintSpecs",
    components: {
        'Loader': Loader,
        'Navigation': Navigation,
    },
    data() {
        return {
            errormsg: "",
            successmsg: "",
            loading: true,
            already_record: false,
            services: [],
            appartmentTypes: [],
            payroll_pricing_form: {
                payrollPricing: [],
            },
            id: this.$route.params.id,
            dependent : false,
            independent: false,
            closeFlag:false,
        }
    },

    mounted() {

        this.loading = true;
        this.getRecord();
    },
    methods: {
        subService_pricing_submit() {
            this.loading=true;
            axios.put('/api/payroll-prices', this.payroll_pricing_form)
                .then(response => {
                    this.loading=false;
                    // console.log(response);
                    this.successmsg = "Payroll Pricing has been updated";
                    if(this.closeFlag){
                        this.$router.push('/department');
                    }else{
                        this.getRecord(this.id);
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
            axios.get('/api/payroll-prices/'+ this.id)
                .then((response) => {
                    if (response.data.error){
                        this.errormsg = response.data.error;
                        this.loading = false;
                    }
                    this.service = response.data.services;
                    this.payroll_pricing_form.payrollPricing = response.data.serviceSubCategoryAppartmentPrice;
                    this.appartmentTypes = response.data.appartmentTypes;
                        if(this.service && this.service.sub_services.length > 0){
                            let sub_service  = this.service.sub_services.find(sub_service => (sub_service.is_independent == 0));
                            let sub_service_independent  = this.service.sub_services.find(sub_service => (sub_service.is_independent == 1));
                            if(sub_service){
                                this.service.dependent = true;
                            }
                            if(sub_service_independent){
                                this.service.independent = true;
                            }
                        }
                    this.loading = false;
                });


        },
        saveAndClose(){
            this.closeFlag = true;
        },

    }
}
</script>

<style scoped>

</style>
