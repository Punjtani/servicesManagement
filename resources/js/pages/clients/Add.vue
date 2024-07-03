<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row align-items-center mb-3">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 mb-0">{{ client_form.company }}</h1>
                </div>

                <div class="col-sm-6 col-4 text-end  d-flex justify-content-end">

                    <p @click="$router.go(-1)" class="h3 mb-2 cursor-pointer history_back_btn" v-bind:class="{ 'disable-backward': !canGoBack }"><b-icon
                            icon="arrow-left-circle-fill"></b-icon></p>
                    <p @click="$router.go(1)" class="h3 mb-2 cursor-pointer history_farward_btn" v-bind:class="{ 'disable-forward': !canGoForward }"><b-icon
                            icon="arrow-right-circle-fill"></b-icon></p>

                    <!--                    <router-link v-if="lastRoute == 'users'" to="/all-users" class="line_height_1_4 back_btn btn_big_medium  btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                    <!--                    <router-link v-else to="/clients" class="back_btn line_height_1_4 btn_big_medium  btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                </div>
            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''"> <strong>{{ successmsg }}</strong>
            </div>
            <div class="smp__tabs">
                <div v-if="id">
                    <TopNavigation :clientId="id" :currentComponent="currentComponent"></TopNavigation>
                </div>
                <form autocomplete="off" @submit.prevent="client_submit" method="post">
                    <div class="modal-bodys">
                        <!-- ============ -->
                        <div class="info_form">

                            <div class="table_area_head">
                                <h5
                                    class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">
                                    General Info</h5>
                            </div>
                            <!-- ======== -->
                            <div class="row  row_spacing_5 ">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0">Company Info</h6>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="$v.client_form.company.$model" class="form-control"
                                        @keyup="validateCompany" :class="{ 'is-invalid': $v.client_form.company.$error, 'is-valid': !$v.client_form.company.$invalid }"
                                            placeholder="Company Name">
                                        <div class="invalid-feedback"> <span
                                                v-if="!$v.client_form.company.required">Required</span> <span
                                                v-if="!$v.client_form.company.minLength">Field length must be 2
                                                Characters</span> <span v-if="!$v.client_form.company.maxLength">Field
                                                length must not be greater then 60 Characters</span> </div>
                                    </div>
                                </div>
                                <!--<div class="col-md-3">
									<div class="form-group">

                                        <multiselect v-model.trim="$v.client_form.manager.$model" track-by="first_name" :custom-label = "customLabelManager" placeholder="RESS Manager" :select-label="''" :options="managers" :searchable="true" :class="{'is-invalid':$v.client_form.manager.$error, 'is-valid':!$v.client_form.manager.$invalid}"> </multiselect>
										<div class="invalid-feedback"> <span v-if="!$v.client_form.manager.required">Required</span> </div>
                                    </div>
                                </div>-->
                            </div>
                            <!-- ======== -->
                            <div class="row  row_spacing_5 ">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0">Contact</h6>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="$v.client_form.phone.$model"
                                            @keyup="validatePhoneNumber" @input="acceptNumber" class="form-control"
                                            :class="{ 'is-valid': submit && isValidPhoneNumber, 'is-invalid': submit && !isValidPhoneNumber }"
                                            placeholder="(123) 456-7890">
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!isValidPhoneNumber">Invalid phone number!</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="$v.client_form.web.$model" class="form-control"
                                            :class="{ 'is-invalid': $v.client_form.web.$error, 'is-valid': !$v.client_form.web.$invalid }"
                                            placeholder="Website">
                                        <div class="invalid-feedback"> <span v-if="!$v.client_form.web.alpha">Not Valid
                                                Address</span> </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ======== -->
                            <div class="row row_spacing_5">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0">Address</h6>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row row_spacing_5 ">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="text" v-model.trim="$v.client_form.street_address.$model"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': $v.client_form.street_address.$error, 'is-valid': !$v.client_form.street_address.$invalid }"
                                                    placeholder="Street Address">
                                                <div class="invalid-feedback"> <span
                                                        v-if="!$v.client_form.street_address.required">Required</span> <span
                                                        v-if="!$v.client_form.street_address.minLength">Field length must be
                                                        2 Characters</span> <span
                                                        v-if="!$v.client_form.street_address.maxLength">Field length must
                                                        not be greater then 60 Characters</span> </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" v-model.trim="$v.client_form.city.$model"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': $v.client_form.city.$error, 'is-valid': !$v.client_form.city.$invalid }"
                                                    placeholder="City">
                                                <div class="invalid-feedback"> <span
                                                        v-if="!$v.client_form.city.minLength">Field length must be 2
                                                        Characters</span> <span v-if="!$v.client_form.city.maxLength">Field
                                                        length must not be greater then 60 Characters</span> </div>
                                                <!--<multiselect v-model.trim="$v.client_form.city.$model" track-by="name" label="name" placeholder="City" :select-label="''" :options="location.city" :searchable="true" :allow-empty="false" :class="{'is-invalid':$v.client_form.city.$error, 'is-valid':!$v.client_form.city.$invalid}" @select="SelectState($event)"> </multiselect>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row row_spacing_5 ">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <multiselect deselectLabel="" v-model.trim="$v.client_form.state.$model"
                                                    track-by="code" :custom-label="customLabel" label="code"
                                                    placeholder="State" :select-label="''" :options="location.state"
                                                    :searchable="true" :allow-empty="false"
                                                    :class="{ 'is-invalid': $v.client_form.state.$error, 'is-valid': !$v.client_form.state.$invalid }"
                                                    @select="SelectCountry($event)"> </multiselect>
                                                <div class="invalid-feedback"> <span
                                                        v-if="!$v.client_form.state.required">Required</span> </div>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-4">
											<div class="form-group">
												<multiselect deselectLabel="" v-model.trim="$v.client_form.country.$model" track-by="name" label="name" placeholder="Country" :select-label="''" :options="location.country" :searchable="true" :allow-empty="false" :class="{'is-invalid':$v.client_form.country.$error, 'is-valid':!$v.client_form.country.$invalid}"> </multiselect>
												<div class="invalid-feedback"> <span v-if="!$v.client_form.country.required">Required</span> </div>
											</div>
										</div>-->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" v-model.trim="$v.client_form.zipcode.$model"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': $v.client_form.zipcode.$error, 'is-valid': !$v.client_form.zipcode.$invalid }"
                                                    placeholder="Zipcode">
                                                <div class="invalid-feedback"> <span
                                                        v-if="!$v.client_form.zipcode.required">Required</span> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ======== -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0">Notes</h6>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <textarea type="text" v-model.trim="$v.client_form.notes.$model"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.client_form.notes.$error, 'is-valid': !$v.client_form.notes.$invalid }"
                                            placeholder="Notes"> </textarea>
                                        <div class="invalid-feedback"> </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ======== -->
                            <!--<div class="row  row_spacing_5 ">
								<div class="col-md-3">
									<div class="form-group">
										<h6 class="font_family_Montserrat font_weight_600 mg_0">Tax</h6> </div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<input type="number" v-model.trim="$v.client_form.tax_rate.$model" class="form-control" :class="{'is-invalid':$v.client_form.tax_rate.$error, 'is-valid':!$v.client_form.tax_rate.$invalid}" placeholder="Tax Rate">
										<div class="invalid-feedback"> <span v-if="!$v.client_form.tax_rate.required">Required</span> </div>
									</div>
								</div>
								<div class="col-md-3"> </div>
								<div class="col-md-3"> </div>
							</div>-->
                            <!-- ======== -->
                            <div class="row row_spacing_5 ">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0">Contact Person</h6>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="$v.client_form.contact_title.$model"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.client_form.contact_title.$error, 'is-valid': !$v.client_form.contact_title.$invalid }"
                                            placeholder="Salutation">
                                        <div class="invalid-feedback">
                                            <!--<span v-if="!$v.client_form.contact_title.required">Required</span>--> <span
                                                v-if="!$v.client_form.contact_title.minLength">Field length must be 2
                                                Characters</span> <span v-if="!$v.client_form.contact_title.maxLength">Field
                                                length must not be greater then 60 Characters</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" v-model.trim="$v.client_form.contact_name.$model"
                                            class="form-control"
                                            :class="{ 'is-invalid': $v.client_form.contact_name.$error, 'is-valid': !$v.client_form.contact_name.$invalid }"
                                            placeholder="Contact Name">
                                        <div class="invalid-feedback">
                                            <!--<span v-if="!$v.client_form.contact_name.required">Required</span>--> <span
                                                v-if="!$v.client_form.contact_name.minLength">Field length must be 2
                                                Characters</span> <span v-if="!$v.client_form.contact_name.maxLength">Field
                                                length must not be greater then 60 Characters</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <!--<div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">DoB</div>
                                            </div>-->
                                        <!--<input type="date" :max="todayDate" v-model.trim="$v.client_form.date_of_birth.$model" class="form-control" :class="{'is-invalid':$v.client_form.date_of_birth.$error, 'is-valid':!$v.client_form.date_of_birth.$invalid}" placeholder="DoB">-->
                                        <datepicker placeholder="DoB" format="MM-DD-YYYY"
                                            v-model.trim="$v.client_form.date_of_birth.$model" :clearable="false"
                                            :disabled-date="(date) => date >= new Date()"
                                            :disabled-calendar-changer="(date) => date >= new Date()"
                                            value-type="YYYY-MM-DD"
                                            :input-class="{ 'is-invalid': $v.client_form.date_of_birth.$error, 'is-valid': !$v.client_form.date_of_birth.$invalid, 'form-control': true }">
                                        </datepicker>
                                        <!--<div class="invalid-feedback"> <span v-if="$v.client_form.date_of_birth.$error">Required</span> </div>-->
                                        <!--<div class="text-danger"><small v-if="submit && !$v.client_form.date_of_birth.required">Required</small></div>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0">Logo</h6>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <!--<UploadFileComponent ref="uploadFile" :accept="acceptImageTypes" :files="Existingfiles" :multiple="false" @uploaded="fileUploaded" api="/api/file-upload"></UploadFileComponent>-->
                                        <UploadFileComponent ref="uploadFile" :accept="acceptImageTypes"
                                            :files="Existingfiles" :multiple="false" @uploaded="fileUploaded"
                                            @invalid="invalidFileType" api="/api/file-upload"></UploadFileComponent>
                                    </div>
                                </div>
                            </div>
                            <!--<h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">Client Admin Info</h5>-->
                            <div class="row row_spacing_5" v-if="false">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0">Login Info</h6>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="row row_spacing_5 ">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" v-model.trim="$v.client_form.first_name.$model"
                                                    @keyup="validateFirstName" v-on:keypress="isLetter($event)"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': invalidFirstName, 'is-valid': !invalidFirstName }"
                                                    placeholder="First Name">
                                                <div class="invalid-feedback" v-if="submit">
                                                    <span v-if="invalidFirstName">Required</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" v-model.trim="$v.client_form.middle_name.$model"
                                                    v-on:keypress="isLetter($event)" class="form-control"
                                                    :class="{ 'is-invalid': submit && $v.client_form.middle_name.$error, 'is-valid': !$v.client_form.middle_name.$invalid }"
                                                    placeholder="Middle Name">
                                                <div class="invalid-feedback" v-if="submit">
                                                    <span v-if="!$v.client_form.middle_name.required">Required</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" v-model.trim="$v.client_form.last_name.$model"
                                                    @keyup="validateLastName" v-on:keypress="isLetter($event)"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': invalidLastName, 'is-valid': !invalidLastName }"
                                                    placeholder="Last Name">
                                                <div class="invalid-feedback" v-if="submit">
                                                    <span v-if="invalidLastName">Required</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row row_spacing_5">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <input :disabled="disabled" type="email"
                                                    v-model.trim="$v.client_form.email.$model" class="form-control"
                                                    :class="{ 'is-invalid': submit && $v.client_form.email.$error, 'is-valid': !$v.client_form.email.$invalid }"
                                                    placeholder="Email" required>

                                                <div class="invalid-feedback" v-if="submit">
                                                    <span v-if="!$v.client_form.email.required">Required</span>
                                                    <span v-if="!$v.client_form.email.email">This is not valid Email address</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5" v-if="role == roleData.admin || role == roleData.super_admin">
                                            <div class="form-group pos-relative">
                                                <div class="pass-field">
                                                    <input :disabled="disabled" :type="fieldType"
                                                        v-model.trim="$v.client_form.password.$model" class="form-control"
                                                        :class="{ 'is-invalid': submit && $v.client_form.password.$error, 'is-valid': !$v.client_form.password.$invalid }"
                                                        placeholder="Password">
                                                    <span @click="showPasswordToggle" type="button">
                                                        <svg v-if="fieldType === 'password'">
                                                            <use xlink:href="#eye_close" />
                                                        </svg>
                                                        <svg v-if="fieldType === 'text'">
                                                            <use xlink:href="#eye_open" />
                                                        </svg>
                                                    </span>
                                                    <div class="invalid-feedback" v-if="submit">
                                                        <span class="pass-required" v-if="invalidPassword">Required</span>
                                                    </div>
                                                </div>

                                                <label class="labelpass">Password (default password is "<span id="lowercase">password</span>")</label>
                                            </div>
                                        </div>
                                        <!--<div class="col-md-2">
                                            <div class="form-group">
											<button @click="disabled = !disabled" type="button" class="btn  btn_blue border_radius_5 text-uppercase font_14 font_weight_600 btn-block">Enable</button>
                                            </div>
										</div>-->

                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Profile Image</label>
                                            <input type="file" ref="imageInput" name="uploaded_file" class="form-control profile-image" @change="onImageChange" />
                                            <!-- <div class="img__box">
                                                <img :src="'/'+$v.client_form.image">
                                                <span class="del_icon">
                                            <svg class="close_sidebar_icon" style="color: white"><use xlink:href="#remove"/></svg></span>
                                            </div> -->
                                        </div>
                                        </div>
                                    <div class="row row_spacing_5"
                                        v-if="role == roleData.admin || role == roleData.super_admin">
                                        <div class="inline_checkbox checkbox_in_three">
                                            <div class="custom_checkbox">
                                                <label>
                                                    <input type="checkbox" name="status"
                                                        :checked="client_form.user_activated ? 'checked' : ''"
                                                        v-model="client_form.user_activated" value="1">
                                                    <span class="box">Active</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="row row_spacing_5">
                                        <div class="inline_checkbox checkbox_in_three">
                                            <div class="custom_checkbox">
                                            <label>
                                                <input type="checkbox" name="is_po_required" v-model="client_form.is_PO_required" v-bind:true-value="1"  v-bind:false-value="0">
                                                <span class="box">Use PO</span>
                                            </label>
                                            </div>
                                        </div>
                                    </div>-->
                                </div>
                            </div>
                            <!-- ======== -->
                            <div class="row row_spacing_5">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <button type="submit"
                                        class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
                                    <button type="submit" @click="saveAndClose"
                                        class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Save
                                        &amp; Close</button>
                                    <a @click="$router.back()"><button type="button"
                                            class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Cancel</button></a>
                                </div>
                            </div>
                        </div>
                        <!-- ========= -->
                        <div id="success-alert1" class="alert alert-success" style="margin-top: 10px"
                            v-if="successmsg !== ''"> <strong>{{ successmsg }}</strong> </div>
                        <div id="errir-alert" class="alert alert-danger" v-if="errormsg !== ''">
                            <strong>{{ errormsg }}</strong>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { required, minLength, maxLength, email, url, decimal } from 'vuelidate/lib/validators';
import { helpers } from "vuelidate/lib/validators";
import Loader from "../LoaderSpinner";
import TopNavigation from "./TopNavigation";
import Multiselect from 'vue-multiselect';
import UploadFileComponent from "../../components/UploadFileComponent";
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import roleData from "../../data";

const alpha = helpers.regex("alpha", /^((https?|ftp|smtp):\/\/)?(www\.)[a-z0-9]+(\.[a-z]{2,}){1,3}(#?\/?[a-zA-Z0-9#]+)*\/?(\?[a-zA-Z0-9-_]+=[a-zA-Z0-9-%]+&?)?$/);
export default {
    name: "Add",
    components: {
        'Loader': Loader,
        'TopNavigation': TopNavigation,
        'Multiselect': Multiselect,
        UploadFileComponent: UploadFileComponent,
        'Datepicker': Datepicker,
    },
    data() {
        return {
            currentComponent: 'clientEdit',
            id: this.$route.params.id,
            disabled: false,
            successmsg: "",
            errormsg: "",
            loading: false,
            already_user: false,
            client_form: {
                user_id: 2,
                // apartment_name: "",
                company: "",
                street_address: "",
                notes: "",
                city: "",
                state: "",
                country: "",
                zipcode: "",
                phone: "",
                web: "",
                // tax_rate: "",
                contact_title: "",
                contact_name: "",
                date_of_birth: "",
                email: "",
                password: "",
                first_name: "",
                middle_name: "",
                last_name: "",
                image : null,
                // manager:"",
                // is_active:true,
                user_activated: true,
                // is_PO_required:0,
            },
            submit: false,
            // managers:[],
            location: {
                city: [],
                state: [],
                country: []
            },
            todayDate: "",
            closeFlag: false,
            fieldType: "password",
            acceptImageTypes: ['image/png', 'image/jpeg'],
            Existingfiles: [],
            fieldType: "password",
            isValidPhoneNumber: true,
            invalidFirstName: false,
            invalidLastName: false,
            invalidPassword: false,
            invalidEmail: false,
            lastRoute: "",
            role: "",
            roleData: roleData,
        }
    },
    validations: {
        client_form: {
            // apartment_name: {
            //     required,
            //     minLength: minLength(2),
            //     maxLength: maxLength(60),
            // },
            company: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },

            email: {
                // required,
                // email
            },
            // manager: {
            //     required,
            // },
            street_address: {
               // required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },
            city: {
                minLength: minLength(2),
                maxLength: maxLength(60),
            },
            state: {
              //  required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },
            country: {
                // required,
                // minLength: minLength(2),
                // maxLength: maxLength(60),
            },
            zipcode: {
               // required,
            },
            password: {
               // required,
            },

            phone: {
                // required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },

            web: {
                alpha,
                minLength: minLength(2),
            },

            // tax_rate: {
            //     required,
            //     decimal
            // },

            contact_title: {
                // required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },

            contact_name: {
                // required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },
            date_of_birth: {
                // required
                // minValue: value => value < new Date().toISOString()
            },
            notes: {},
            first_name: {
               //  required
            },
            last_name: {
                // required
            },
            middle_name: {},
            logoModified: {}
           // password: {},
            // is_PO_required: {},

        }
    },
    created() {
        this.getCityStateData();
    },
    async mounted() {
        this.role = localStorage.getItem("role");
        this.lastRoute = localStorage.getItem("lastRoute");
        // this.getManager();
        if (this.id) {
            this.loading = true;
            this.getRecord(this.id);
        } else {
            // console.log('new record');
        }
        const yourDate = new Date();
        this.todayDate = yourDate.toISOString().split('T')[0];

    },

    computed: {
        canGoForward() {
           let canGoForw = false;
try {
    canGoForw =  window.navigation.canGoForward;
} catch (error) {
      if (window.history && typeof window.history.goForward === 'function') {         canGoForw = window.history.goForward();
    } else if (window.history && (window.history.length > 1 && window.history.length > (history.state && history.state.index || 0))) {
        canGoForw = true;
    } else {
        canGoForw = false;
    }
}
return canGoForw;
        },
        canGoBack() {
            let canGo = false;
try {
    canGo =  window.navigation.canGoBack;
} catch (error) {
    if (window.history && typeof window.history.goBack === 'function') {
        canGo =  true;
    } else if (window.history && window.history.length > 1) {
        canGo = true;
    } else {
        canGo = false;
    }
}
return canGo;
        }
    },

    methods: {
        onImageChange(event) {
            this.client_form.image = event.target.files[0];
        },
        client_submit() {
            window.scrollTo(0, top);
            this.submit = true
            this.loading = true;
            // if (this.client_form.first_name == "" || this.client_form.first_name == null) {
            //     this.invalidFirstName = true;
            //     this.loading = false;
            // }
            if (this.client_form.company == "" || this.client_form.company == null) {
                this.invalidCompany = true;
                this.loading = false;
            }
            // if (this.client_form.password == "" || this.client_form.password == null) {
            //     this.invalidPassword = true;
            //     this.loading = false;
            // }
            // if (this.client_form.email == "" || this.client_form.email == null) {
            //     this.invalidEmail = true;
            //     this.loading = false;
            //     return;
            // }
            this.$v.client_form.$touch();
            // if (this.$v.client_form.$anyError) {
            //     this.loading = false;
            //     return;
            // }
            if (this.isValidPhoneNumber == false) { return; }
            // if (this.invalidFirstName || this.invalidPassword) { return; }
            if (this.invalidCompany) { return; }
            // console.log(this.client_form);
            let client_data = this.client_form;
            // client_data.city = this.client_form.city.name;
            client_data.state = (this.client_form.state != null)? this.client_form.state.code : "";
            client_data.country = (this.client_form.country.name != null)? this.client_form.country.name : "";
            // client_data.manager = this.client_form.manager.id;
            if (this.id) {
                axios.post('/api/client/' + this.id, (function() {
                            const formData = new FormData();
                            // console.log(this.client_form);
                            Object.keys(this.client_form).forEach((key) => {
                            const value = this.client_form[key];

                            // If the value is a File object (e.g., image), append it directly
                            if (value instanceof File) {
                                formData.append(key, value);
                            } else {
                                // If the value is an object or array, convert it to a string using JSON.stringify()
                                formData.append(key, value);
                            }
                            });
                            // formData.append('data', JSON.stringify(this.client_form));
                            formData.append('image', this.client_form.image);
                            return formData;
                            }).call(this), {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                            })
                    .then(response => {
                        this.loading = false;
                        this.successmsg = "Client has been updated";
                        if (this.closeFlag) {
                            this.$router.push('/clients');
                        } else {
                            this.client_form.filePaths = [];
                            this.$refs.uploadFile = null;
                            this.Existingfiles = [];
                            this.getRecord(this.id);
                        }
                        setTimeout(() => {
                            this.successmsg = "";
                            this.submit = false
                            // this.$router.push('/clients')
                        }, 3000);

                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            } else {
                axios.post('/api/client', (function() {
                            const formData = new FormData();
                            // console.log(this.client_form);
                            Object.keys(this.client_form).forEach((key) => {
                            const value = this.client_form[key];

                            // If the value is a File object (e.g., image), append it directly
                            if (value instanceof File) {
                                formData.append(key, value);
                            } else {
                                // If the value is an object or array, convert it to a string using JSON.stringify()
                                formData.append(key, value);
                            }
                            });
                            // formData.append('data', JSON.stringify(this.client_form));
                            formData.append('image', this.client_form.image);
                            return formData;
                            }).call(this), {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                            })
                    .then(response => {
                        //console.log(response);
                        this.loading = false;
                        if (response.data.error) {
                            this.errormsg = response.data.error;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else if (response.request.status == 201) {
                            this.errormsg = response.data.error;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {

                            this.successmsg = "Client has been added";
                            if (this.closeFlag) {
                                this.$router.push('/clients');
                            } else {
                                this.id = response.data.client_id;
                                this.$router.push('/view-client/' + response.data.client_id);
                                this.getRecord(this.id);
                            }
                            setTimeout(() => {
                                this.successmsg = "";
                                this.submit = false;
                                // // console.log(this.$router.back());
                                // this.$router.push('/clients')
                            }, 3000);

                        }
                    })
                    .catch(err => {
                        console.log("error");
                        this.has_error = true;
                    })
            }
        },
        getCityStateData() {
            axios.get('/api/city-state')
                .then((response) => {
                    // this.location.city = response.data.cities;
                    this.location.state = response.data.states;
                    this.location.country = response.data.countries;
                    this.client_form.country = this.location.country[0];
                });
        },
        isValidDate(dateString) {
            // Create a new Date object from the input dateString
            const dateObject = new Date(dateString);

            // Check if the dateObject is valid
            // The getTime() method will return NaN for an invalid date
            // So, we can check if it's a valid date by checking if getTime() is not NaN
            return !isNaN(dateObject.getTime());
            },
        getRecord(id) {
            axios.get('/api/client/' + id + "/edit")
                .then((response) => {

                    this.client_form = response.data.client;
                    this.client_form.country = this.location.country.find(country => (country.name == this.client_form.country))
                    if(this.client_form.state != null)
                        this.client_form.state = this.location.state.find(state => state.code == this.client_form.state)
                        if(!this.isValidDate(this.client_form.date_of_birth))
                      {
                        this.client_form.date_of_birth = this.client_form.user.date_of_birth;
                      }
                    this.client_form.email = this.client_form.user.email;
                    this.client_form.last_name = this.client_form.user.last_name;
                    this.client_form.first_name = this.client_form.user.first_name;
                    this.client_form.middle_name = (this.client_form.user.middle_name != "null") ? this.client_form.user.middle_name : '';
                    this.client_form.image = this.client_form.user.profile_image;
                    this.client_form.web = (this.client_form.web != "null") ?  this.client_form.web : '';
                    this.client_form.phone = (this.client_form.phone != "null") ?  this.client_form.phone : '';
                    this.client_form.notes = (this.client_form.notes != "null") ? this.client_form.notes : '';
                    this.client_form.contact_title = (this.client_form.user.contact_title != "null") ? this.client_form.user.contact_title : '';
                    this.client_form.password= this.client_form.user.encrypted_password;
                    this.client_form.country = this.location.country[0];
                    this.client_form['user_id'] = this.client_form.user.id;
                    this.client_form.user_activated = this.client_form.user.user_activated ? true : false;
                    if (this.client_form.logo) {
                        this.Existingfiles.push(this.client_form.logo.file_name);
                        this.$forceUpdate();
                    }
                    this.loading = false;
                });
        },
        customLabel(user) {
            return `${user.first_name ? user.first_name : ''} ${user.middle_name ? user.middle_name : ''} ${user.last_name ? user.last_name : ''}`;
        },
        getManager() {
            axios.get('/api/client/managers')
                .then((response) => {
                    this.managers = response.data.managers;
                    // console.log(this.managers);
                });
        },
        // SelectState(selectedCity){
        //    this.client_form.state =  this.location.state.find(state => (state.id == selectedCity.state_id))
        //    this.client_form.country = this.location.country.find(country => (country.id == this.client_form.state.country_id))
        // },
        SelectCountry(selectedState) {
            // axios.get('/api/state/' + selectedState.id)
            //     .then((response) => {
            //         this.location.city = response.data.cities;
            //     });
            // this.client_form.city = "";
            this.client_form.country = this.location.country.find(country => (country.id == selectedState.country_id))
        },
        isLetter(e) {
            let char = String.fromCharCode(e.keyCode);
            if (/^[A-Za-z -]+$/.test(char)) return true;
            else e.preventDefault();
        },
        customLabel(state) {
            if (state)
                return `${state.code}`;
        },
        customLabelManager(manager) {
            return `${manager.first_name ? manager.first_name : ''} ${manager.middle_name ? manager.middle_name : ''} ${manager.last_name ? manager.last_name : ''}`;
        },
        saveAndClose() {
            this.closeFlag = true;
        },
        acceptNumber() {
            var x = this.client_form.phone.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.client_form.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        },
        showPasswordToggle() {
            if (this.fieldType == "password") {
                this.fieldType = "text";
            } else {
                this.fieldType = "password";
            }
        },
        fileUploaded(filenames) {
            this.client_form.filePaths = filenames;
            this.client_form.logoModified = "modified";
        },
        invalidFileType(msg) {
            this.errormsg = msg;
            setTimeout(() => {
                this.errormsg = "";
            }, 3000);
        },
        showPasswordToggle() {
            if (this.fieldType == "password") {
                this.fieldType = "text";
            } else {
                this.fieldType = "password";
            }
        },
        validatePhoneNumber() {
            const validationRegex = /^(\([0-9]{3}\)) [0-9]{3}-[0-9]{4}$/;
            if (this.client_form.phone.match(validationRegex)) {
                this.isValidPhoneNumber = true;
            } else if (this.client_form.phone.length <= 0) {
                this.isValidPhoneNumber = true;
            } else {
                this.isValidPhoneNumber = false;
            }
        },
        validateFirstName() {
            if (this.client_form.first_name == "" || this.client_form.first_name == null) {
                this.invalidFirstName = true;
            } else {
                this.invalidFirstName = false;
            }
        },
        validateLastName() {
            if (this.client_form.last_name == "" || this.client_form.last_name == null) {
                this.invalidLastName = true;
            } else {
                this.invalidLastName = false;
            }
        },
        validateCompany() {
            if (this.client_form.company == "" || this.client_form.company == null) {
                this.invalidCompany = true;
            } else {
                this.invalidCompany = false;
            }
        },
    },

}

</script>

<style scoped></style>
