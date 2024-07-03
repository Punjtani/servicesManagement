<template>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Yearly Pricing Increment</h4>
                <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

                    <form autocomplete="off" @submit.prevent="price_increment_submit" method="post">
                        <div class="modal_content_inner">
                        <div class="modal-body">

                            <div class="form_box">
                                <div class="form-group">
                                    <label>Year</label>
                                    <!--<datepicker
                                    placeholder = "Select Year"
                                    format="yyyy"
                                    minimum-view="year"
                                    input-class="form-control"
                                    v-model.trim="$v.price_increment_form.year.$model"
                                    :class="{'is-invalid':submit && $v.price_increment_form.year.$error, 'is-valid':!$v.price_increment_form.year.$invalid}">
                                </datepicker>-->
                                    <datepicker
                                        placeholder = "Select Year"
                                        format="YYYY"
                                        v-model.trim="$v.price_increment_form.year.$model"
                                        :clearable="false"
                                        value-type="YYYY"
                                        :disabled-date="(date)=> date.getFullYear() < new Date().getFullYear()"
                                        :disabled-calendar-changer="(date) => date.getFullYear() < new Date().getFullYear()"
                                        type="year"
                                        :input-class="{'is-invalid':$v.price_increment_form.year.$error, 'is-valid':!$v.price_increment_form.year.$invalid,'form-control':true}"
                                        >
                                    </datepicker>
                                    <div class="text-danger" v-if="submit"><small v-if="!$v.price_increment_form.year.required">Year is Required</small></div>
                                </div>
                                <!--<div class="form-group">
                                    <label>Client</label>
                                    <multiselect deselectLabel="" :clearable="false" :clearOnSelect="true" v-model.trim="$v.price_increment_form.client_id.$model" track-by="company" label="company" placeholder="Select Client" :select-label="''" :options="clients" :searchable="true">

                                    </multiselect>
                                    <div class="text-danger" v-if="submit"><small v-if="!$v.price_increment_form.client_id.required">Client is Required</small></div>
                                </div>-->
                                <div class="form-group">
                                    <label>Service</label>
                                    <multiselect deselectLabel="" :clearable="false" :clearOnSelect="true" v-model.trim="$v.price_increment_form.service_id.$model" track-by="category" label="category" placeholder="Select Service" :select-label="''" :options="services" :searchable="true">
                                        <!--<template slot="tag">{{ '' }}</template>
                                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>-->
                                    </multiselect>
                                    <div class="text-danger" v-if="submit"><small v-if="!$v.price_increment_form.service_id.required">Service is Required</small></div>
                                </div>
                                <div class="form-group">
                                    <label>Increment(%)</label>
                                    <input type="text" step="any" v-model.trim = "$v.price_increment_form.percentage.$model" class="form-control" :class="{'is-invalid':submit && $v.price_increment_form.percentage.$error, 'is-valid':!$v.price_increment_form.percentage.$invalid}" placeholder="Enter Increment Percentage">
                                    <div class="invalid-feedback" v-if="submit">

                                        <span v-if="!$v.price_increment_form.percentage.required">Increment percentage is Required</span>
                                        <span v-if="$v.price_increment_form.percentage.required && $v.price_increment_form.percentage.isNumber && !$v.price_increment_form.percentage.between">Increment percentage must be between 0 and 100</span>
                                        <span v-if="$v.price_increment_form.percentage.required && !$v.price_increment_form.percentage.isNumber">Increment percentage must be a valid number</span>
                                        <!-- <span v-if="$v.price_increment_form.percentage.minValue">Increment percentage must be greater than or equal to 0</span>
                                          <span v-if="$v.price_increment_form.percentage.maxValue">Increment percentage must be less than or equal to 100</span> -->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <div class="inline_buttons">
                                    <div class="row">
                                        <div class="col-6 col-sm-6">
                                            <button type="submit"
                                                    class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                                Save
                                            </button>
                                        </div>
                                        <div class="col-6 col-sm-6">
                                            <button  type="button" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close" class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                                Cancel
                                            </button>
                                        </div>
                                    </div></div>
                                </div>
                    </form>

            </div>
        </div>
</template>

<script>
import axios from "axios";
import { required, minLength,maxLength, between,numeric } from 'vuelidate/lib/validators';
// import Datepicker from 'vuejs-datepicker';
import Multiselect from 'vue-multiselect';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
export default {
    name: "Add",
    components: {
        'Datepicker': Datepicker,
        'Multiselect': Multiselect,
    },
    data() {
        return {
            price_increment_form : {
                year: "",
                percentage: "",
                service_id:"",
                // client_id:"",
                property_id:this.$route.params.id,
            },
            services:[],
            clients:[],
            submit: false
        }
    },
    validations: {
        price_increment_form : {
            year: {
                required,
            },
            percentage: {
                required,
                between: between(0, 100),
                isNumber(value) {
                // Custom validator to check if the value is a valid number
                  return !isNaN(parseFloat(value));
                },
            },
            service_id: {
                required
            },
            // client_id: {
            //     required
            // }
        }
    },
    mounted() {
        this.getService();
    },
    methods: {
        price_increment_submit() {
            this.submit = true;
            this.$v.price_increment_form.$touch();
            if (this.$v.price_increment_form.$anyError) {
                return;
            }
            if(this.price_increment_form.service_id){
                this.price_increment_form.service_id = this.price_increment_form.service_id.id;
            }
            // if(this.price_increment_form.client_id){
            //     this.price_increment_form.client_id = this.price_increment_form.client_id.id;
            // }
            // console.log('reached', this.price_increment_form);
            axios.post('/api/yearly-increment', this.price_increment_form)
                .then(response => {
                    if(response.data.error){
                        this.$emit('errorEvent',response.data.error)

                    }else{
                        this.$emit('closeEvent',"Yearly Price Increment has been added successfully")
                    }
                    this.resetForm();
                })
                .catch(err => {
                    this.has_error = true;
                })
            $("#priceIncrementAdd").modal('hide');
        },
        resetForm(){
            this.$v.price_increment_form.$reset();
            // this.price_increment_form.appartment_type_name = "";
            this.price_increment_form.percentage = "";
            this.price_increment_form.year = "";
            this.price_increment_form.service_id = "";
            // this.price_increment_form.client_id = "";
            this.submit=false;
            this.$forceUpdate();
        },
        getService(){
            axios.get('/api/services')
            .then(response => {
                // console.log(response.data);
                this.services = response.data.services;
                this.clients = response.data.clients;
            })
            .catch(err => {
                this.has_error = true;
            })
        }
    }
}
</script>

<style scoped>

</style>
