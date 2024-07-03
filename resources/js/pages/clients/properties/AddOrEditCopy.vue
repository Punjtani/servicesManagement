<template>
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title" v-if="(role == roleData.admin) || (role == roleData.super_admin)">Property</h4>
<h4 class="modal-title" v-if="role == roleData.client">Quote</h4>                        
 <button type="button" class="btn-close" @click = "closeModel()" aria-label="Close"></button>
</div>
<form ref="propertyForm" autocomplete="off" @submit.prevent="property_update" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="form_box">
                            <div class="row align-items-center">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Property Name</label>
                                        <input type="text" v-model.trim="$v.property_form.title.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.property_form.title.$error, 'is-valid':!$v.property_form.title.$invalid}"
                                               placeholder="Property Name">
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.title.required">This Field is Required</span>
                                            <span v-if="!$v.property_form.title.minLength">This Field length must be 2 Characters</span>
                                            <span v-if="!$v.property_form.title.maxLength">This Field length must not be greater then 60 Characters</span>
                                        </div>
                                    </div>
                                </div>

                                <label>Service Address</label>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <!--<input type="text" v-model.trim="$v.property_form.street1.$model"
                                               class="form-control"
                                               :class="{'is-invalid':$v.property_form.street1.$error, 'is-valid':!$v.property_form.street1.$invalid}"
                                               placeholder="Street address">-->
                                        <input type="text" v-model.trim="$v.property_form.street1.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.property_form.street1.$error, 'is-valid':!$v.property_form.street1.$invalid}"
                                               placeholder="Street 1"></input>
                                        <div class="invalid-feedback">
                                            <span
                                                v-if="!$v.property_form.street1.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="$v.property_form.street2.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.property_form.street2.$error, 'is-valid':!$v.property_form.street2.$invalid}"
                                               placeholder="Street 2">
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.street2.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>city</label>
                                        <input type="text" v-model.trim="$v.property_form.city.$model"
                                               class="form-control form_control_small"
                                               :class="{'is-invalid':$v.property_form.city.$error, 'is-valid':!$v.property_form.city.$invalid}"
                                               placeholder="city">
                                        <!--<multiselect  v-model.trim="$v.property_form.city.$model" track-by="name" label="name" placeholder="City" :select-label="''" :options="location.city" :searchable="true" :allow-empty="false"
                                                    :class="{'is-invalid':$v.property_form.city.$error, 'is-valid':!$v.property_form.city.$invalid}" @select = "SelectState($event)">
                                                    </multiselect>-->
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.city.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>state</label>
                                        <!--<input type="text" v-model.trim="$v.property_form.state.$model"
                                               class="form-control form_control_small"
                                               :class="{'is-invalid':$v.property_form.state.$error, 'is-valid':!$v.property_form.state.$invalid}"
                                               placeholder="state">-->
                                        <multiselect  deselectLabel="" v-model.trim="$v.property_form.state.$model" track-by="name"  :custom-label = "customLabel" placeholder="State" :select-label="''" :options="location.state" :searchable="true" :allow-empty="false"
                                                :class="{'is-invalid':$v.property_form.state.$error, 'is-valid':!$v.property_form.state.$invalid}" @select = "SelectCountry($event)">
                                                    </multiselect>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.state.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="col-sm-6">
                                    <div class="form-group">
                                        <label>country</label>
                                        <multiselect deselectLabel="" v-model.trim="$v.property_form.country.$model" track-by="name" label="name" placeholder="Country" :select-label="''" :options="location.country" :searchable="true" :allow-empty="false"
                                            :class="{'is-invalid':$v.property_form.country.$error, 'is-valid':!$v.property_form.country.$invalid}">
                                            </multiselect>
                                        <div class="invalid-feedback">
                                            <span
                                                v-if="!$v.property_form.country.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>-->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>zipcode</label>
                                        <input type="text" v-model.trim="$v.property_form.zipcode.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.property_form.zipcode.$error, 'is-valid':!$v.property_form.zipcode.$invalid}"
                                               placeholder="zipcode">
                                        <div class="invalid-feedback">
                                            <span
                                                v-if="!$v.property_form.zipcode.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>

                                <label>Billing address</label>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                            <input type="checkbox"  @change="sameAddressCheck($event)" v-model.trim="$v.property_form.is_same_address.$model"  v-bind:true-value="1"  v-bind:false-value="0" >
                                            <label>Same as Service Address</label>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="$v.property_form.billing_address.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.property_form.billing_address.$error, 'is-valid':!$v.property_form.billing_address.$invalid}"
                                               placeholder="Street 1" :disabled="property_form.is_same_address == 1"></input>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.billing_address.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="$v.property_form.billing_address_2.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.property_form.billing_address_2.$error, 'is-valid':!$v.property_form.billing_address.$invalid}"
                                               placeholder="Street 2" :disabled="property_form.is_same_address == 1"></input>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.billing_address_2.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>city</label>
                                        <input type="text" v-model.trim="$v.property_form.billing_city.$model"
                                               class="form-control form_control_small"
                                               :class="{'is-invalid':$v.property_form.billing_city.$error, 'is-valid':!$v.property_form.billing_city.$invalid}"
                                               placeholder="city" :disabled="property_form.is_same_address == 1">
                                        <!--<multiselect  v-model.trim="$v.property_form.city.$model" track-by="name" label="name" placeholder="City" :select-label="''" :options="location.city" :searchable="true" :allow-empty="false"
                                                    :class="{'is-invalid':$v.property_form.city.$error, 'is-valid':!$v.property_form.city.$invalid}" @select = "SelectState($event)">
                                                    </multiselect>-->
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.billing_city.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>state</label>
                                        <!--<input type="text" v-model.trim="$v.property_form.state.$model"
                                               class="form-control form_control_small"
                                               :class="{'is-invalid':$v.property_form.state.$error, 'is-valid':!$v.property_form.state.$invalid}"
                                               placeholder="state">-->
                                        <multiselect  deselectLabel="" v-model.trim="$v.property_form.billing_state.$model" track-by="name"  :custom-label = "customLabel" placeholder="State" :select-label="''" :options="location.state" :searchable="true" :allow-empty="false"
                                                :class="{'is-invalid':$v.property_form.billing_state.$error, 'is-valid':!$v.property_form.billing_state.$invalid}" @select = "SelectCountry($event)" :disabled="property_form.is_same_address == 1">
                                                    </multiselect>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.billing_state.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="col-sm-6">
                                    <div class="form-group">
                                        <label>country</label>
                                        <multiselect  deselectLabel="" v-model.trim="$v.property_form.billing_country.$model" track-by="name" label="name" placeholder="Country" :select-label="''" :options="location.country" :searchable="true" :allow-empty="false"
                                            :class="{'is-invalid':$v.property_form.billing_country.$error, 'is-valid':!$v.property_form.billing_country.$invalid}" :disabled="property_form.is_same_address == 1">
                                            </multiselect>
                                        <div class="invalid-feedback">
                                            <span
                                                v-if="!$v.property_form.billing_country.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>-->
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>zipcode</label>
                                        <input type="text" v-model.trim="$v.property_form.billing_zipcode.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.property_form.billing_zipcode.$error, 'is-valid':!$v.property_form.billing_zipcode.$invalid}"
                                               placeholder="zipcode" :disabled="property_form.is_same_address == 1">
                                        <div class="invalid-feedback">
                                            <span
                                                v-if="!$v.property_form.billing_zipcode.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" v-model.trim="$v.property_form.phone.$model"
                                               class="form-control "
                                               @input="acceptNumber"
                                               @keyup="validatePhoneNumber"
                                               :class="{ 'is-valid': submit && isValidPhoneNumber, 'is-invalid': submit && !isValidPhoneNumber }" placeholder="(123) 456-7890">
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!isValidPhoneNumber">Invalid phone number!</span>
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Manager</label>
                                        <multiselect v-model.trim="$v.property_form.manager.$model" track-by="first_name" :custom-label = "customLabelManager" placeholder="Select Manager" :select-label="''" :options="managers" :searchable="true" :class="{'is-invalid':$v.property_form.manager.$error, 'is-valid':!$v.property_form.manager.$invalid}"> </multiselect>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.manager.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>-->

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>State Tax</label>
                                        <multiselect deselectLabel="" @select = "SelectTaxRate($event)"  v-model.trim="$v.property_form.tax_type_id.$model" track-by="name" label="name" placeholder="Select Value" :select-label="''" :options="tax_types" :searchable="true" :allow-empty="false"
                                            :class="{'is-invalid':$v.property_form.tax_type_id.$error, 'is-valid':!$v.property_form.tax_type_id.$invalid}">
                                            </multiselect>
                                        <div class="invalid-feedback">
                                            <span
                                                v-if="!$v.property_form.tax_type_id.required">Tax Type is Required</span>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tax Rate ( % )</label>
                                        <input type="number" v-model.trim="$v.property_form.tax_rate.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.property_form.tax_rate.$error, 'is-valid':!$v.property_form.tax_rate.$invalid}"
                                               placeholder="Tax Rate" >
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.tax_rate.required">Tax Type is Required</span>
                                            <span v-if="!$v.property_form.tax_rate.decimal">Please enter decimal value</span>
                                        </div>

                                    </div>
                                </div>-->
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Supplier Details</label>
                                        <textarea type="text" v-model.trim="$v.property_form.suppliers.$model"
                                                class="form-control"
                                                :class="{'is-invalid':$v.property_form.suppliers.$error, 'is-valid':!$v.property_form.suppliers.$invalid}"
                                                placeholder="Supplier details ...">  </textarea>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.property_form.suppliers.required">Supplier Details is Required</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                            <label>Unit types</label>
                                        <div class="inline_checkbox checkbox_in_three">
                                            <div class="custom_checkbox" v-for="(item, index) in appartment_types" :key="index" :value="item.id">
                                                <label>
                                                    <input
                                                        type="checkbox" :value="item.id"
                                                        v-model.trim="$v.property_form.appartment_types.$model">
                                                    <span class="box">{{ item.type }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <span v-if="submit && $v.property_form.appartment_types.$anyError" style="color: #e3342f">Appartment Types is Required</span>
                                    </div>
                                </div>
                                <div class="col-sm-12" v-if="role == roleData.client">
                                    <label>Attachments</label>
                                    <UploadFileComponent ref="uploadFile" :accept="acceptImageTypes" :files="Existingfiles" :multiple="true" @uploaded="fileUploaded" api="/api/file-upload"></UploadFileComponent>
                                </div>
                               
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
            <div class="inline_buttonss">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600">Save</button>
                                            <button type="button" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600" @click = "closeModel()" aria-label="Close">Cancel</button>
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
import {required, minLength, maxLength, between, decimal, requiredIf} from 'vuelidate/lib/validators';
import UploadFileComponent from "../../../components/UploadFileComponent";
import Multiselect from 'vue-multiselect';
import roleData from '../../../data.js';

export default {
    name: "Add",
    components : {
        'Multiselect': Multiselect,
        'roleData': roleData,
        UploadFileComponent:UploadFileComponent,
    },
    props: ['clientId', 'PropertyId'],
    data() {
        return {
            role: "",
			roleData: roleData,
            tax_types: [],
            appartment_types: [],
            Existingfiles : [],
            id: "",
            acceptImageTypes: ['image/png','image/jpeg'],
            submit:false,
            property_form: {
                client_id: "",
                title: "",
                street1: "",
                street2: "",
                city: "",
                state: "",
                zipcode: "",
                country: "",
                tax_type_id: "",
                tax_rate: "",
                suppliers: "",
                appartment_types:[],
                billing_address: "",
                billing_address_2: "",
                billing_city: "",
                billing_state: "",
                billing_zipcode: "",
                billing_country: "",
                is_same_address: 0,
                phone: "",
                // manager: "",
                filePaths: []
            },

            location:{
                city:[],
                state:[],
                country:[]
            },
            // managers: [{'id':1,'first_name':'Property','last_name':'Manager'},{'id':2,'first_name':'Maintenance','last_name':'Supervisor'}],
            isValidPhoneNumber: true,
        }
    },
    validations: {
        property_form: {
            title: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },
            appartment_types: {
                required
            },
            street1: {
                required,

            },
            street2: {
                // required,
            },
            city: {
                required,
            },
            state: {
                required,
            },
            zipcode: {
                required,
            },
            country: {
                // required,
            },
            tax_type_id: {
                required,
            },
            // tax_rate: {
            //     required,
            //     decimal
            // },
            suppliers: {
                // required,
            },
            billing_address: {
                required: requiredIf(function (property_form) {
                    return property_form.is_same_address == 0;
                })
            },
            phone: {},
            manager:{
                // required,
            },
            billing_address_2: {
                // required: requiredIf(function (property_form) {
                //     return property_form.is_same_address == 0;
                // })
            },
            billing_city: {
                required: requiredIf(function (property_form) {
                    return property_form.is_same_address == 0;
                })
            },
            billing_state: {
                required: requiredIf(function (property_form) {
                    return property_form.is_same_address == 0;
                })
            },
            billing_zipcode: {
                required: requiredIf(function (property_form) {
                    return property_form.is_same_address == 0;
                })
            },
            billing_country: {
                // required: requiredIf(function (property_form) {
                //     return property_form.is_same_address == 0;
                // })
            },
            is_same_address:{
                required: requiredIf(function (property_form) {
                    return property_form.is_same_address == 0;
                })
            }
        }
    },

    watch: {
        'PropertyId'(val,old) {
            if(this.id!= val) {
                this.id = val;

                // console.log('Modal open id: ', val, "-", old," form:", this.property_form);

                if (val) {

                    this.getRecord(val);
                }
            }
        }
    },

    mounted() {
        this.role = localStorage.getItem("role");
        // console.log(this.role == roleData.client);
        this.getCityStateData();
        this.get_tax_types();
    },
    methods: {
        get_tax_types() {
            axios.get('/api/property/create')
                .then((response) => {
                    // console.log(response);
                    this.tax_types = response.data.tax_types;
                    this.appartment_types = response.data.appartment_types;
                });
        },
        getRecord(id) {
            // console.log('getRecord');
            axios.get('/api/property/' + id + "/edit")
                .then((response) => {
                    this.property_form = response.data.property;
                    this.property_form.tax_type_id = this.tax_types.find(tax=>(tax.id==this.property_form.tax_type_id));
                    this.property_form.country = this.location.country.find(country=>(country.name==this.property_form.country))
                    this.property_form.state = this.location.state.find(state=>(state.code==this.property_form.state))
                    this.property_form.billing_state = this.location.state.find(state=>(state.code==this.property_form.billing_state))
                    this.property_form.billing_country = this.location.country.find(country=>(country.name==this.property_form.billing_country))
                    // this.property_form.manager = this.managers.find(manager=>(manager.id==this.property_form.manager));
                    // this.property_form.city = this.location.city.find(city=>(city.name==this.property_form.city))
                    let appartment_types = this.property_form.appartment_types;
                    this.property_form.appartment_types=[];
                    appartment_types.forEach((data) => {
                        this.property_form.appartment_types.push(data.id);
                    });
                    this.property_form.quote_attatchment.forEach((file) => {
                        // console.log(file);
                        this.Existingfiles.push(file.file_name);
                    });
                    // console.log(this.Existingfiles);
                    // console.log(this.property_form);
                });
        },
        fileUploaded(filenames){
            this.property_form.filePaths = filenames;
        },
        property_update() {
            this.submit = true;
            this.$v.property_form.$touch();
            if (this.$v.property_form.$anyError) {
                // console.log(this.$v.property_form.appartment_types.$anyError)
                return;
            }
            if(this.isValidPhoneNumber == false){ return; }
            let property_data = this.property_form;
            property_data.client_id = this.clientId;
            property_data.data_tax_type_id = this.property_form.tax_type_id.id;
            // property_data.city = this.property_form.city.name;
            property_data.data_state = this.property_form.state.code;
            property_data.country = this.property_form.country.name;
            if(property_data.is_same_address){
                property_data.data_billing_state   = this.property_form.state.code;;
                property_data.billing_country = property_data.country;
                property_data.billing_city   = this.property_form.city;
                property_data.billing_zipcode = this.property_form.zipcode;
                property_data.billing_address = this.property_form.street1;
                property_data.billing_address_2 = this.property_form.street2;
            }else{
                property_data.data_billing_state = this.property_form.billing_state.code;
                property_data.billing_country = this.property_form.billing_country.name;
            }
            // property_data.manager = this.property_form.manager.id;
            if(this.role == roleData.client){
                property_data.is_quote = 1;
                if (this.id) {
                    axios.put('/api/property/' + this.id, property_data)
                    .then(response => {
                        this.loading=true;
                        this.$emit('closeEvent', "Quote has been Updated successfully")
                        this.closeModel();

                    })
                    .catch(err => {
                        this.has_error = true;
                    })
                } else {
                    axios.post('/api/property', property_data)
                        .then(response => {
                            this.$emit('closeEvent', "Quote has been added successfully")
                            this.closeModel();

                        })
                        .catch(err => {
                            this.has_error = true;
                        })
                }
            }else{
                if (this.id) {
                    axios.put('/api/property/' + this.id, property_data)
                    .then(response => {
                        this.loading=true;
                        this.$emit('closeEvent', "Property has been Updated successfully")
                        this.closeModel();

                    })
                    .catch(err => {
                        this.has_error = true;
                    })
                } else {
                    axios.post('/api/property', property_data)
                        .then(response => {
                            this.$emit('closeEvent', "Property has been added successfully")
                            this.closeModel();

                        })
                        .catch(err => {
                            this.has_error = true;
                        })
                }
            }



        },
        getCityStateData(){
            axios.get('/api/city-state')
                .then((response) => {
                    // this.location.city = response.data.cities;
                    this.location.state = response.data.states;
                    this.location.country = response.data.countries;
                    this.property_form.country = this.location.country[0];
                    this.property_form.billing_country = this.location.country[0];
            });
        },
        // SelectState(selectedCity){
        //    this.property_form.state =  this.location.state.find(state => (state.id == selectedCity.state_id))
        //    this.property_form.country = this.location.country.find(country => (country.id == this.property_form.state.country_id))
        // },
        SelectCountry(selectedState){
            // axios.get('/api/state/' + selectedState.id)
            //     .then((response) => {
            //         this.location.city = response.data.cities;
            //     });
            // this.property_form.city = "";
            this.property_form.country = this.location.country.find(country => (country.id == selectedState.country_id))
        },
        SelectTaxRate(selectedTax){
            this.property_form.tax_rate = selectedTax.rate;
        },
        closeModel(){
            this.$v.property_form.$reset();
            this.submit = false;
            this.property_form = {
                client_id: "",
                title: "",
                street1: "",
                street2: "",
                city: "",
                state: "",
                zipcode: "",
                country: "",
                tax_type_id: "",
                filePaths:[],

            }
            if(this.$refs.uploadFile){
                this.$refs.uploadFile.mergedFiles=null;
            }
            this.Existingfiles=null;
            this.$forceUpdate();
            this.$emit('formResetEvent', "");
            // console.log('model closed',this.property_form)
            $("#AddOrEdit").modal('hide');
        },
        customLabel(state){

            return `${state.name}, ${state.code}`;
        },
        // customLabelManager(manager){
        //     return `${manager.first_name} ${manager.last_name}`;
        // },
        sameAddressCheck(e){
            if(e.target.checked){
                this.property_form.billing_state   = this.property_form.state;
                this.property_form.billing_country = this.property_form.country;
                this.property_form.billing_city   = this.property_form.city;
                this.property_form.billing_zipcode = this.property_form.zipcode;
                this.property_form.billing_address = this.property_form.street1;
                this.property_form.billing_address_2 = this.property_form.street2;
            }
        },
        acceptNumber() {
            var x = this.property_form.phone.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.property_form.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        },
        validatePhoneNumber() {
            const validationRegex = /^(\([0-9]{3}\)) [0-9]{3}-[0-9]{4}$/;
            if (this.property_form.phone.match(validationRegex)) {
                this.isValidPhoneNumber = true;
            } else if(this.property_form.phone.length <= 0){
                 this.isValidPhoneNumber = true;
            } else {
                this.isValidPhoneNumber = false;
            }
        },
    }
}
</script>

<style scoped>

</style>
