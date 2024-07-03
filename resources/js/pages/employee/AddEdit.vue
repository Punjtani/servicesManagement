<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>
            <div id="errir-alert" class="alert alert-danger" v-if="errormsg!==''">
                        <strong>{{ errormsg }}</strong>
                    </div>
            <div v-if="id">
                <!--                <TopNavigation :employeeId="id" :currentComponent="currentComponent"></TopNavigation>-->
            </div>
            <form autocomplete="off" @submit.prevent="employee_submit" method="post" enctype="multipart/form-data">
                <div class="modal-bodys">
                    <div class="form_box">
                        <div class="row">
                            <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Employee</h1></div>
                            <Navigation></Navigation>

                        </div>
                        <!--                        <div class="row align-items-center">-->
                        <!--                            <div class="col-sm-6 col-8 table_area_heads"><h1 class="h4 mb-0">Employee</h1></div>-->
                        <!--                            <div class="col-sm-6 col-4 text-end">-->
                        <!--                                <router-link v-if="lastRoute == 'users'" to="/all-users" class="line_height_1_4 back_btn line_height_1_4 btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 back_btn">Back</router-link>-->
                        <!--                                <router-link v-else to="/employees" class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 back_btn line_height_1_4 ">Back</router-link>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input :disabled="disabled" type="email"
                                           v-model.trim="$v.employee_form.email.$model" class="form-control"
                                           :class="{'is-invalid':submit && $v.employee_form.email.$error, 'is-valid':!$v.employee_form.email.$invalid}"
                                           placeholder="Email">

                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.email.required">Required</span>
                                        <span
                                            v-if="!$v.employee_form.email.email">This is not valid Email address</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="pass-field">
                                        <input :disabled="disabled" :type="fieldType"
                                               v-model.trim="$v.employee_form.password.$model" class="form-control"
                                               :class="{'is-invalid':submit && $v.employee_form.password.$error, 'is-valid':!$v.employee_form.password.$invalid}"
                                               placeholder="Password">
                                        <span @click="showPasswordToggle" type="button">
                                            <svg v-if="fieldType==='password'">
                                                <use xlink:href="#eye_close"/>
                                            </svg>
                                            <svg v-if="fieldType==='text'">
                                                <use xlink:href="#eye_open"/>
                                            </svg>
                                        </span>
                                        <small>Default password is password</small>
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!$v.employee_form.password.required">Required</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Status</label>
                                <div class="form-group mt-3">
                                    <div class="inline_checkbox checkbox_in_three">
                                        <div class="custom_checkbox">
                                            <label>
                                                <input type="checkbox" name="status"
                                                       :checked="employee_form.user_activated?'checked':''"
                                                       v-model="employee_form.user_activated" value="1">
                                                <span class="box">Active</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-sm-4">
                                 <div class="form-group">
                                <label><span style="opacity:0;">hidden text</span></label>
                                <button @click="disabled = !disabled" type="button" class="btn-block btn  btn_blue border_radius_5 text-uppercase font_14 font_weight_600 btn-block">Enable</button>
                                </div>
                            </div>-->
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" v-model.trim="$v.employee_form.first_name.$model"
                                           v-on:keypress="isLetter($event)" class="form-control"
                                           :class="{'is-invalid':submit && $v.employee_form.first_name.$error, 'is-valid':!$v.employee_form.first_name.$invalid}"
                                           placeholder="First Name">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.first_name.required">Required</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input type="text" v-model.trim="$v.employee_form.middle_name.$model"
                                           v-on:keypress="isLetter($event)" class="form-control"
                                           :class="{'is-invalid':submit && $v.employee_form.middle_name.$error, 'is-valid':!$v.employee_form.middle_name.$invalid}"
                                           placeholder="Middle Name">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.middle_name.required">Required</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" v-model.trim="$v.employee_form.last_name.$model"
                                           v-on:keypress="isLetter($event)" class="form-control"
                                           :class="{'is-invalid':submit && $v.employee_form.last_name.$error, 'is-valid':!$v.employee_form.last_name.$invalid}"
                                           placeholder="Last Name">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.last_name.required">Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Profile Image</label>
                                    <input type="file" ref="imageInput" name="uploaded_file" class="form-control profile-image" @change="onImageChange" />
                                    <!-- <div class="img__box">
                                        <img :src="'/'+$v.employee_form.image">
                                        <span class="del_icon">
                                    <svg class="close_sidebar_icon" style="color: white"><use xlink:href="#remove"/></svg></span>
                                    </div> -->
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Hire Date</label>
                                    <!--<input type="date" :max="employee_form.contract_end_date" v-model.trim = "$v.employee_form.contract_start_date.$model" class="form-control" :class="{'is-invalid':submit && $v.employee_form.contract_start_date.$error, 'is-valid':!$v.employee_form.contract_start_date.$invalid}" >-->
                                    <datepicker
                                        placeholder="Hire date"
                                        format="MM-DD-YYYY"
                                        v-model.trim="$v.employee_form.contract_start_date.$model"
                                        :clearable="false"
                                        value-type="YYYY-MM-DD"
                                        :disabled-date="disableHireDate"
                                        :disabled-calendar-changer="disableHireDate"
                                        :input-class="{'is-invalid':$v.employee_form.contract_start_date.$error, 'is-valid':!$v.employee_form.contract_start_date.$invalid,'form-control':true}"
                                    >
                                    </datepicker>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.contract_start_date.required">Required</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <!--<input type="date" :min="employee_form.contract_start_date ? employee_form.contract_start_date : todayDate " v-model.trim = "$v.employee_form.contract_end_date.$model" class="form-control" :class="{'is-invalid':submit && $v.employee_form.contract_end_date.$error, 'is-valid':!$v.employee_form.contract_end_date.$invalid}" >-->
                                    <datepicker
                                        placeholder="End date"
                                        format="MM-DD-YYYY"
                                        v-model.trim="$v.employee_form.contract_end_date.$model"
                                        :clearable="false"
                                        value-type="YYYY-MM-DD"
                                        :disabled-date="disableEndDate"
                                        :disabled-calendar-changer="disableEndDate"
                                        :input-class="{'is-invalid':$v.employee_form.contract_end_date.$error, 'is-valid':!$v.employee_form.contract_end_date.$invalid,'form-control':true}"
                                    >
                                    </datepicker>
                                    <!--<div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.contract_end_date.required">Required</span>
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>T-Shirt Size</label>
                                <div class="form-group">
                                    <multiselect deselectLabel="" v-model.trim="employee_form.t_shirt_size"
                                                 :select-label="''" :options="t_shirt_size_options" :searchable="true"
                                                 :allow-empty="false"
                                                 :class="{'is-invalid':$v.employee_form.t_shirt_size.$error, 'is-valid':!$v.employee_form.t_shirt_size.$invalid}"></multiselect>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.t_shirt_size.required">Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Division</label>
                                <div class="form-group">
                                    <!--<select v-model.trim="employee_form.department_id"
                                            :class="{'is-invalid':submit && $v.employee_form.department_id.$error, 'is-valid':!$v.employee_form.department_id.$invalid}"        class="form-select">
                                        <option v-for="(item, index) in departments"
                                            :key="index" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>-->
                                    <multiselect deselectLabel="" v-model.trim="employee_form.department_id"
                                                 @input="changeSalaryType($event)" track-by="name" label="name"
                                                 :select-label="''" :options="departments" :searchable="true"
                                                 :allow-empty="false"
                                                 :class="{'is-invalid':$v.employee_form.department_id.$error, 'is-valid':!$v.employee_form.department_id.$invalid}"></multiselect>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.department_id.required">Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Payroll Type</label>
                                <div class="form-group">
                                    <!--<select v-model.trim="$v.employee_form.salary_type.$model"
                                            :class="{'is-invalid':submit && $v.employee_form.salary_type.$error, 'is-valid':!$v.employee_form.salary_type.$invalid}"        class="form-select">
                                        <option value="fixed">Fixed</option>
                                        <option value="hourly">Hourly</option>
                                        <option value="percentage">Percentage</option>
                                    </select>-->
                                    <multiselect v-model.trim="employee_form.salary_type" :select-label="''"
                                                 :options="salary_Type_options" :searchable="true" :allow-empty="true"
                                                 :class="{'is-invalid':$v.employee_form.salary_type.$error, 'is-valid':!$v.employee_form.salary_type.$invalid}"></multiselect>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.salary_type.required">Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4"
                                 v-if="(employee_form.salary_type != 'Fixed') && (employee_form.salary_type != '')">
                                <label v-if="employee_form.salary_type == 'Percentage'">Payroll Percentage</label>
                                <label v-if="employee_form.salary_type == 'Hourly'">Hourly Value</label>
                                <label v-if="employee_form.salary_type == 'Salary'">Salary Value</label>
                                <div class="form-group" :class="employee_form.salary_type !='Percentage' ? 'doller_signs' : ''">
                                    <div class="dollar-div">
                                        <span class="fieldAffix-item">$</span>
                                    <input min="0" type="number" v-model.trim="$v.employee_form.salary_value.$model"
                                           class="form-control"
                                           :class="{'is-invalid':submit && $v.employee_form.salary_value.$error, 'is-valid':!$v.employee_form.salary_value.$invalid}"></div>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.salary_value.required">Required</span>
                                    </div>
                                </div>
                            </div>

                            <div v-if="employee_form.salary_type == 'Salary'" class="col-sm-4">
                                <label>Salary Term</label>
                                <div class="form-group">
                                    <multiselect v-model.trim="employee_form.salary_term" :select-label="''"
                                                 deselectLabel="" :options="salary_Terms" :allow-empty="false"
                                                 :class="{'is-invalid':$v.employee_form.salary_term.$error, 'is-valid':!$v.employee_form.salary_term.$invalid}"></multiselect>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.salary_term.required">Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4"
                                 v-if="(employee_form.salary_type == 'Salary') && (employee_form.salary_type != '')">
                                <label v-if="employee_form.salary_term == 'Yearly'">No of Years</label>
                                <label v-if="employee_form.salary_term == 'Monthly'"># of Months</label>
                                <label v-if="employee_form.salary_term == 'Weekly'">No of Weeks</label>
                                <div class="form-group">
                                    <input min="1" type="number" v-model.trim="$v.employee_form.salary_period.$model"
                                           class="form-control"
                                           :class="{'is-invalid':submit && $v.employee_form.salary_period.$error, 'is-valid':!$v.employee_form.salary_period.$invalid}">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.salary_period.required">Required</span>
                                    </div>
                                </div>
                            </div>
                            <div v-if="employee_form.salary_type == 'Salary'" class="col-sm-4">
                                <label>Weekly Salary will be {{ calculatSalary }}</label>
                                <div class="form-group doller_signs">
                                    <div class="dollar-div">
                                        <span class="fieldAffix-item">$</span>
                                    <input min="1" disabled type="number" v-model.trim="calculatedSalary"
                                           class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Date of Birth</label>
                                <div class="form-group">
                                    <!--<input type="date" :max="todayDate" v-model.trim="$v.employee_form.date_of_birth.$model" class="form-control" :class="{'is-invalid':$v.employee_form.date_of_birth.$error, 'is-valid':!$v.employee_form.date_of_birth.$invalid}" placeholder="DoB">-->
                                    <datepicker
                                        placeholder="DoB"
                                        format="MM-DD-YYYY"
                                        v-model.trim="$v.employee_form.date_of_birth.$model"
                                        :clearable="false"
                                        :disabled-date="(date) => date >= new Date()"
                                        :disabled-calendar-changer="(date) => date >= new Date()"
                                        value-type="YYYY-MM-DD"
                                        :input-class="{'is-invalid':$v.employee_form.date_of_birth.$error, 'is-valid':!$v.employee_form.date_of_birth.$invalid,'form-control':true}"
                                    >
                                    </datepicker>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <!--<div class="col-sm-4">
                                 <label>Status</label>
                                 <div class="form-group mt-3">
                                <div class="inline_checkbox checkbox_in_three">
                                    <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" name="status" :checked="employee_form.user_activated?'checked':''" v-model="employee_form.user_activated" value="1" >
                                        <span class="box">Active</span>
                                    </label>
                                    </div>
                                </div>
                                </div>
                            </div>-->
                            <div class="col-sm-4">
                                <label>Country</label>
                                <div class="form-group">
                                    <multiselect deselectLabel="" v-model.trim="$v.employee_form.country.$model"
                                                 track-by="name" label="name" placeholder="Country" :select-label="''"
                                                 :options="location.country" :searchable="true" :allow-empty="false"
                                                 :class="{'is-invalid':$v.employee_form.country.$error, 'is-valid':!$v.employee_form.country.$invalid}"></multiselect>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <label>Street Address</label>
                                <div class="form-group">
                                    <input type="text" v-model.trim="$v.employee_form.address.$model"
                                           class="form-control"
                                           :class="{'is-invalid':submit && $v.employee_form.address.$error, 'is-valid':!$v.employee_form.address.$invalid}"
                                           placeholder="Street Address">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.employee_form.address.required">Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Number</label>
                                <div class="form-group">
                                    <input type="text" v-model.trim="$v.employee_form.apartment_no.$model"
                                           class="form-control"
                                           :class="{'is-invalid':$v.employee_form.apartment_no.$error, 'is-valid':!$v.employee_form.apartment_no.$invalid}"
                                           placeholder="Apartment No">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>City</label>
                                <div class="form-group">
                                    <input type="text" v-model.trim="$v.employee_form.city.$model" class="form-control"
                                           :class="{'is-invalid':$v.employee_form.city.$error, 'is-valid':!$v.employee_form.city.$invalid}"
                                           placeholder="City">
                                    <div class="invalid-feedback"><span v-if="!$v.employee_form.city.minLength">Field length must be 2 Characters</span>
                                        <span v-if="!$v.employee_form.city.maxLength">Field length must not be greater then 60 Characters</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>State</label>
                                <div class="form-group">
                                    <multiselect deselectLabel="" v-model.trim="$v.employee_form.state.$model"
                                                 track-by="code" :custom-label="customLabel" label="code"
                                                 placeholder="State" :select-label="''" :options="location.state"
                                                 :searchable="true" :allow-empty="false"
                                                 :class="{'is-invalid':$v.employee_form.state.$error, 'is-valid':!$v.employee_form.state.$invalid}"
                                                 @select="SelectCountry($event)"></multiselect>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Zipcode</label>
                                <div class="form-group">
                                    <input type="text" v-model.trim="$v.employee_form.zipcode.$model"
                                           class="form-control"
                                           :class="{'is-invalid':$v.employee_form.zipcode.$error, 'is-valid':!$v.employee_form.zipcode.$invalid}"
                                           placeholder="Zipcode">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea type="text" v-model.trim="$v.employee_form.notes.$model"
                                              class="form-control"
                                              :class="{'is-invalid':submit && $v.employee_form.notes.$error, 'is-valid':!$v.employee_form.notes.$invalid}"
                                              placeholder="Notes"> </textarea>
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Work Authorization</label>
                                    <textarea type="text" v-model.trim="$v.employee_form.visa_information.$model"
                                              class="form-control"
                                              :class="{'is-invalid':submit && $v.employee_form.visa_information.$error, 'is-valid':!$v.employee_form.visa_information.$invalid}"
                                              placeholder="Work Authorization"> </textarea>
                                    <div class="invalid-feedback">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="custom__hr">


                        <div class="inline_buttonss">
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <button type="submit"
                                            class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                        Save
                                    </button>
                                    <button type="submit" @click="saveAndClose"
                                            class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                        Save &amp; Close
                                    </button>
                                    <a @click="$router.back()">
                                        <button
                                            class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Cancel
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--                    <div id="success-alert1" class="alert alert-success" style="margin-top: 10px" v-if="successmsg!==''">-->
                    <!--                        <strong>{{ successmsg }}</strong>-->
                    <!--                    </div>-->



                </div>
            </form>


        </div>
    </div>
</template>

<script>
import axios from "axios";
import {required, minLength, maxLength, email, url, decimal, requiredIf} from 'vuelidate/lib/validators'
import Loader from "../LoaderSpinner";
import TopNavigation from "../clients/TopNavigation";
import Multiselect from 'vue-multiselect';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Navigation from "../../components/Navigation";
export default {
    name: "Add",
    components: {
        'Loader': Loader,
        'TopNavigation': TopNavigation,
        'Multiselect': Multiselect,
        'Datepicker': Datepicker,
        'Navigation': Navigation,
    },
    data() {
        return {
            currentComponent: 'employeeEdit',
            id: this.$route.params.id,
            calculatedSalary: 0.00,
            disabled: false,
            successmsg: "",
            errormsg: "",
            loading: false,
            already_user: false,
            departments: [],
            salary_Type_options: ["Salary", "Fixed", "Hourly", "Percentage"],
            salary_Terms: ["Yearly", "Monthly", "Weekly"],
            t_shirt_size_options: ["Small", "Medium", "Large", "X-Large", "XX-Large"],
            employee_form: {
                department_id: "",
                contract_start_date: "",
                contract_end_date: "",
                salary_type: "",
                salary_term: "Monthly",
                salary_period: 1,
                salary_value: "",
                t_shirt_size: "",
                address: "",
                notes: "",
                email: "",
                image: null,
                password: "",
                first_name: "",
                middle_name: "",
                last_name: "",
                visa_information: "",
                date_of_birth: "",
                // is_active:true,
                user_activated: true,
                city: "",
                state: "",
                country: "",
                zipcode: "",
                apartment_no: "",
            },
            submit: false,
            todayDate: "",
            closeFlag: false,
            fieldType: "password",
            location: {
                city: [],
                state: [],
                country: []
            },
            lastRoute: "",
        }
    },
    validations: {
        employee_form: {
            email: {
                required,
                email
            },
            department_id: {
                required,
            },
            contract_start_date: {
                // required,
            },

            contract_end_date: {
                // required,
            },
            salary_type: {
                // required,
            },
            salary_period: {
                // required,
            },
            salary_value: {
                // required: requiredIf(function (employee_form) {
                //     {
                //         return employee_form.salary_type != 'Fixed'
                //     }
                // })
            },
            salary_term: {
                // required: requiredIf(function (employee_form) {
                //     {
                //         return employee_form.salary_type == 'Salary'
                //     }
                // })
            },
            t_shirt_size: {
                // required,
            },
            address: {
                // required
            },
            first_name: {
                required
            },
            last_name: {
                // required
            },
            middle_name: {},
            notes: {},
            password: {},
            visa_information: {},
            date_of_birth: {},
            city: {
                minLength: minLength(2),
                maxLength: maxLength(60),
            },
            state: {
                // required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },
            country: {
                // required,
                minLength: minLength(2),
                maxLength: maxLength(60),
            },
            zipcode: {
                // required,
            },
            apartment_no: {},

        }
    },

    computed: {
        'calculatSalary'() {
            if (this.employee_form.salary_type == "Salary") {
                if (this.employee_form.salary_term == "Monthly") {
                    // months × 4.348125
                    let monthlyWeeks = this.employee_form.salary_period * 4
                    this.calculatedSalary = (this.employee_form.salary_value / monthlyWeeks).toFixed(2);
                } else if (this.employee_form.salary_term == "Weekly") {
                    this.calculatedSalary = (this.employee_form.salary_value / this.employee_form.salary_period).toFixed(2);
                } else if (this.employee_form.salary_term == "Yearly") {
                    let Yealyweeks = this.employee_form.salary_period * 52
                    this.calculatedSalary = (this.employee_form.salary_value / Yealyweeks).toFixed(2);
                }
            }

        }
    },
    watch: {
        'employee_form.salary_value'() {

            this.employee_form.salary_value = (Math.round(this.employee_form.salary_value * 100) / 100).toFixed(2)
        }
    },
    mounted() {
        this.lastRoute = localStorage.getItem("lastRoute");
        this.getCityStateData();
        const yourDate = new Date();
        this.todayDate = yourDate.toISOString().split('T')[0];
        if (this.id) {
            this.loading = true;
            this.getRecord(this.id);
        } else {
            this.getInitialValues();
            // console.log('new record');
        }

    },
    methods: {
        onImageChange(event) {
            this.employee_form.image = event.target.files[0];
        },
        employee_submit() {
            window.scrollTo(0, top);
            console.log("submit");
            this.submit = true;
            this.loading = true;
            this.$v.employee_form.$touch();
            console.log(  this.$v.employee_form);
            if (this.$v.employee_form.$anyError) {
                this.loading = false;
                return;
            }
            if (this.employee_form?.salary_type == 'Fixed') {
                this.employee_form.salary_value = null;
            }
            let data = this.employee_form?.department_id;
            let state = this.employee_form?.state;
            let country = this.employee_form?.country;
            this.employee_form.department_id = this.employee_form?.department_id?.id;
            this.employee_form.state = this.employee_form.state ? this.employee_form.state.code : "";
            this.employee_form.country = this.employee_form.country.name;
            if (this.id) {
                axios.post('/api/employe/' + this.id, (function() {
                            const formData = new FormData();
                            // console.log(this.employee_form);
                            Object.keys(this.employee_form).forEach((key) => {
                            const value = this.employee_form[key];

                            // If the value is a File object (e.g., image), append it directly
                            if (value instanceof File) {
                                formData.append(key, value);
                            } else {
                                // If the value is an object or array, convert it to a string using JSON.stringify()
                                formData.append(key, value);
                            }
                            });
                            // formData.append('data', JSON.stringify(this.employee_form));
                            formData.append('image', this.employee_form.image);
                            return formData;
                            }).call(this), {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                            })
                    .then(response => {
                        if (response.data.error) {
                            this.errormsg = response.data.error;
                            this.employee_form.department_id = data;
                            this.employee_form.state = state;
                            this.employee_form.country = country;
                            this.employee_form.image = country;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        }
                       else
                       {
                        this.loading = false;
                        this.successmsg = "Employee has been updated";
                        this.employee_form.department_id = data;
                        this.employee_form.state = state;
                        this.employee_form.country = country;
                       }
                        if (this.closeFlag) {
                            this.$router.push('/employees');
                        } else {
                            this.getRecord(this.id);
                        }
                        setTimeout(() => {
                            this.successmsg = "";
                            // this.$router.back()
                        }, 3000);
                        this.submit = false
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            } else {
                axios.post('/api/employe', (function() {
                            const formData = new FormData();
                            Object.keys(this.employee_form).forEach((key) => {
                            const value = this.employee_form[key];

                            // If the value is a File object (e.g., image), append it directly
                            if (value instanceof File) {
                                formData.append(key, value);
                            } else {
                                // If the value is an object or array, convert it to a string using JSON.stringify()
                                formData.append(key, value);
                            }
                            });
                            // formData.append('data', JSON.stringify(this.employee_form));
                            formData.append('image', this.employee_form.image);
                            return formData;
                            }).call(this), {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                            })
                    .then(response => {
                        this.loading = false;
                        if (response.data.error) {
                            this.errormsg = response.data.error;
                            this.employee_form.department_id = data;
                            this.employee_form.state = state;
                            this.employee_form.country = country;
                            this.employee_form.image = country;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {

                            this.successmsg = "Employee has been added";
                            this.employee_form.department_id = data;
                            this.employee_form.state = state;
                            this.employee_form.country = country;
                            if (this.closeFlag) {
                                this.$router.push('/employees');
                            } else {
                                this.id = response.data.employee_id;
                                this.$router.push('/view-employe/' + this.id);
                                this.getRecord(this.id);
                            }
                            setTimeout(() => {
                                this.successmsg = "";
                                // this.$router.back()
                                // this.$router.push(this.$router.back())
                            }, 3000);
                            this.submit = false
                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }
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
            axios.get('/api/employe/' + id + "/edit")
                .then((response) => {
                    this.employee_form = response.data.employee;
                    // console.log("edit");
                    //  console.log(this.employee_form.contract_start_date);
                    //  alert(this.isValidDate(this.employee_form.contract_start_date));
                    this.employee_form.first_name = this.employee_form.user.first_name;
                     if(!this.isValidDate(this.employee_form.contract_start_date))
                      {
                        this.employee_form.contract_start_date = this.employee_form.user.contract_start_date;
                      }
                    if(!this.isValidDate(this.employee_form.contract_end_date))
                    {
                    this.employee_form.contract_end_date = this.employee_form.user.contract_end_date;
                    }

                    // this.employee_form.contract_end_date = (this.employee_form.user.contract_end_date === "0000-00-00") ? this.employee_form.user.contract_end_date : ;
                    this.employee_form.email = this.employee_form.user.email;
                    this.employee_form.city = (this.employee_form.city != "null") ? this.employee_form.city : '';
                    this.employee_form.date_of_birth = (this.employee_form.date_of_birth != "0000-00-00") ? this.employee_form.date_of_birth : '';
                    this.employee_form.notes = (this.employee_form.notes != "null") ? this.employee_form.notes : '';
                    this.employee_form.visa_information = (this.employee_form.visa_information != "null") ? this.employee_form.visa_information : '';
                    this.employee_form.zipcode = (this.employee_form.zipcode != "null") ? this.employee_form.zipcode : '';
                    this.employee_form.apartment_no = (this.employee_form.apartment_no != "null") ? this.employee_form.apartment_no : '';

                    this.employee_form.password= this.employee_form.user.encrypted_password;
                    this.employee_form.last_name = this.employee_form.user.last_name;
                    this.employee_form.middle_name = (this.employee_form.user.middle_name) ? this.employee_form.user.middle_name : '';
                    this.employee_form.image = this.employee_form.user.profile_image;
                    this.employee_form['user_id'] = this.employee_form.user.id;
                    this.employee_form.user_activated = this.employee_form.user.user_activated ? true : false;
                    this.employee_form.state = this.location.state.find(state => (state.code == this.employee_form.state));
                    this.employee_form.salary_type = this.salary_Type_options.find(option => (option == this.employee_form?.salary_type?.charAt(0).toUpperCase() + this.employee_form.salary_type?.slice(1)));
                    this.employee_form.salary_term = this.salary_Terms.find(option => (option == this.employee_form?.salary_term));
                    this.employee_form.country = this.location.country[0];
                    if (!this.employee_form.state) {
                        this.employee_form.state = this.location.state.find(state => (state.code == this.employee_form.state));
                    }
                    this.loading = false;
                    this.getInitialValues();
                });
        },

        getInitialValues() {
            axios.get('/api/employe/create')
                .then((response) => {
                    this.departments = response.data.departments;
                    if (this.id) {
                        this.employee_form.department_id = this.departments.find(dept => (dept.id == this.employee_form.department_id));
                        // this.employee_form.salary_type = this.employee_form.department_id.payroll_type;
                    }
                });
        },
        isLetter(e) {
            let char = String.fromCharCode(e.keyCode);
            if (/^[A-Za-z -]+$/.test(char)) return true;
            else e.preventDefault();
        },
        changeSalaryType(e) {
            this.employee_form.salary_type = e.payroll_type;
        },
        saveAndClose() {
            this.closeFlag = true;
        },
        disableHireDate(date) {
            if (this.employee_form.contract_end_date) {
                return date >= new Date(this.employee_form.contract_end_date);
            }
        },
        disableEndDate(date) {
            if (this.employee_form.contract_start_date) {
                return date <= new Date(this.employee_form.contract_start_date);
            }
        },
        showPasswordToggle() {
            if (this.fieldType == "password") {
                this.fieldType = "text";
            } else {
                this.fieldType = "password";
            }
        },
        getCityStateData() {
            axios.get('/api/city-state')
                .then((response) => {
                    // this.location.city = response.data.cities;
                    this.location.state = response.data.states;
                    this.location.country = response.data.countries;
                    this.employee_form.country = this.location.country[0];
                });
        },
        SelectCountry(selectedState) {
            this.employee_form.country = this.location.country.find(country => (country.id == selectedState.country_id))
        },
        customLabel(state) {
            return `${state.code}`;
        },

    }
}
</script>

<style scoped>

</style>
