<template>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Staff</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModel()"></button>
</div>
<form autocomplete="off" @submit.prevent="staff_submit" method="post">

            <div class="modal_content_inner">
                    <div v-if="loading">
                        <loader></loader>
                    </div>
                    <div class="modal-bodys">
                        <div class="form_box">
                            <div class="row">
                                <div  class="col-sm-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" v-model.trim="$v.staff_form.first_name.$model" v-on:keypress="isLetter($event)" class="form-control"
                                               :class="{'is-invalid':submit && $v.staff_form.first_name.$error, 'is-valid':!$v.staff_form.first_name.$invalid}"
                                               placeholder="First Name">
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!$v.staff_form.first_name.required">Required</span>
                                        </div>
                                    </div>
                                </div>

                                <div   class="col-sm-6">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" v-model.trim="$v.staff_form.middle_name.$model" v-on:keypress="isLetter($event)"  class="form-control"
                                               :class="{'is-invalid':submit && $v.staff_form.middle_name.$error, 'is-valid':!$v.staff_form.middle_name.$invalid}"
                                               placeholder="Middle Name">
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!$v.staff_form.middle_name.required">Required</span>
                                        </div>
                                    </div>
                                </div>

                                <div   class="col-sm-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" v-model.trim="$v.staff_form.last_name.$model" v-on:keypress="isLetter($event)"  class="form-control"
                                               :class="{'is-invalid':submit && $v.staff_form.last_name.$error, 'is-valid':!$v.staff_form.last_name.$invalid}"
                                               placeholder="Last Name">
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!$v.staff_form.last_name.required">Required</span>
                                        </div>
                                    </div>
                                </div>

                                <div   class="col-sm-6">
                                    <div class="form-group">
                                        <label>Staff Role</label>
                                        <!--<select name="staff_role" v-model.trim = "$v.staff_form.staff_role.$model" :class="{'is-invalid':submit && $v.staff_form.staff_role.$error, 'is-valid':!$v.staff_form.staff_role.$invalid}" id="staff_role" class="form-select">
                                            <option v-for="staff_role in this.staff_roles" :key="staff_role.id" :value="staff_role.id">{{staff_role.name}}</option>
                                        </select>-->
                                        <multiselect
                                            deselectLabel=""
                                            :taggable="true"
                                            @tag="addNewRole"
                                            tag-placeholder="Add new Role"
                                            :class="{'is-invalid':submit && $v.staff_form.staff_role.$error, 'is-valid':!$v.staff_form.staff_role.$invalid}"
                                            :clearOnSelect="true"
                                            v-model.trim="$v.staff_form.staff_role.$model"
                                            track-by="name"
                                            label="name"
                                            placeholder="Select Role or Type New Role"
                                            :select-label="''"
                                            :options="staff_roles"
                                            :searchable="true">
                                        </multiselect>
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!$v.staff_form.staff_role.required">Required</span>
                                        </div>
                                    </div>
                                </div>
                                <div   class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" v-model.trim="$v.staff_form.phone.$model"  class="form-control" @input="acceptNumber" @keyup="validatePhoneNumber"
                                               :class="{ 'is-valid': submit && isValidPhoneNumber, 'is-invalid': submit && !isValidPhoneNumber }"
                                               placeholder="(123) 456-7890">
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!isValidPhoneNumber">Invalid phone number!</span>
                                        </div>
                                    </div>
                                </div>
                                <div   class="col-sm-6">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <!--<input type="date" :max="todayDate" v-model.trim="$v.staff_form.date_of_birth.$model" class="form-control" placeholder="DoB">-->
                                        <datepicker
                                            placeholder = "DoB"
                                            format="MM-DD-YYYY"
                                            :input-class="{'is-invalid':$v.staff_form.date_of_birth.$error, 'is-valid':!$v.staff_form.date_of_birth.$invalid,'form-control':true}"
                                            v-model.trim="$v.staff_form.date_of_birth.$model"
                                            :clearable="false"
                                            :disabled-date="(date) => date >= new Date()"
                                            :disabled-calendar-changer="(date) => date >= new Date()"
                                            value-type="YYYY-MM-DD"
                                        >
                                        </datepicker>
                                    </div>
                                </div>
                                <div  class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input :disabled="disabled" type="email" v-model.trim="$v.staff_form.email.$model" class="form-control"
                                               :class="{'is-invalid':submit && $v.staff_form.email.$error, 'is-valid':!$v.staff_form.email.$invalid}"
                                               placeholder="Email">

                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!$v.staff_form.email.required">Required</span>
                                            <span v-if="!$v.staff_form.email.email">This is not valid Email address</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password </label>
                                        <div class="pass-field">
                                            <input :disabled="disabled" :type="fieldType" v-model.trim="$v.staff_form.password.$model" class="form-control"
                                                   :class="{'is-invalid':submit && $v.staff_form.password.$error, 'is-valid':!$v.staff_form.password.$invalid}"
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
                                                <span v-if="!$v.staff_form.password.required">Required</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="inline_checkbox checkbox_in_three">
                                        <div class="custom_checkbox w-100">
                                            <label>
                                                <input type="checkbox" name="isEmailEnabled" v-model.trim="$v.staff_form.is_email_enabled.$model"  v-bind:true-value="1"  v-bind:false-value="0">
                                                <span class="box">Enable email notification</span>
                                            </label>
                                        </div>
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!$v.staff_form.is_email_enabled.required">Required</span>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="col-sm-6">
                                     <div class="form-group text-right">
                                        <label></label>
                                        <button @click="disabled = !disabled" type="button" class="btn  btn_orange border_radius_5 text-uppercase font_14 font_weight_600 btn-inline-block">Enable</button>
                                    </div>
                                </div>-->
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea type="text" v-model.trim="$v.staff_form.notes.$model" class="form-control"
                                              placeholder="Add Notes">  </textarea>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.staff_form.notes.maxLength">Field length must not be greater then 500 Characters</span>
                                    </div>
                                </div>
                            </div>
                            <h6 v-if="staffId" class="font_weight_600 font_family_Montserrat text_color_orange">Assigned Properties</h6>
                            <div class="row" v-if="staffId">
                                <div  class="col-sm-12">
                                    <div class="form-group">
                                        <multiselect
                                            disabled="disabled"
                                            v-model.trim="staff_form.property"
                                            :show-labels="false"
                                            track-by="title"
                                            label="title"
                                            placeholder="Select Community"
                                            :select-label="''"
                                            :options="properties"
                                            :searchable="true"
                                            :multiple="true"
                                        >
                                        </multiselect>
                                        <div class="invalid-feedback" v-if="submit">
                                            <span v-if="!$v.staff_form.first_name.required">Required</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div id="success-alert1" class="alert alert-success" style="margin-top: 10px" v-if="successmsg!==''"> <strong>{{ successmsg }}</strong> </div>
                        <div id="errir-alert" class="alert alert-danger" v-if="errormsg!==''">
                            <strong>{{ errormsg }}</strong>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                <div class="inline_buttonss">
                                <div class="row">
                                    <div class="col-sm-12 text-end">
                                        <button type="submit"
                                                class="btn btn_blue btn_medium border_radius_5 font_14 text_uppercase font_weight_600">
                                            Save
                                        </button>
                                        <button type="button" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600" @click = "closeModel()" aria-label="Close">Cancel</button>
                                        <!--<router-link  :to="{name:'clientUsers',params:{id:this.clientId>0?this.clientId:this.id}}" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Cancel</router-link>-->
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
import Multiselect from 'vue-multiselect';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {
    name: "Add",
    components : {
        'Loader': Loader,
        'Multiselect': Multiselect,
        'Datepicker': Datepicker,
    },
    props: ['clientId', "staffId"],
    data() {
        return {
            successmsg: "",
            errormsg: "",
            loading: false,
            disabled : false,
            isValidPhoneNumber: true,
            staff_roles: [],
            properties: [],
            property_staff: false,
            staff_form : {
                email: "",
                password : "",
                first_name : "",
                middle_name : "",
                last_name : "",
                staff_role : "",
                date_of_birth:"",
                phone:"",
                notes:"",
                is_email_enabled:0,
                property:"",
            },
            submit: false,
            todayDate:"",
            closeFlag:false,
            fieldType:"password",
            staff_id : "",
        }
    },
    validations: {
        staff_form: {
            email: {
                required,
                email
            },
            first_name: {
                required
            },

            last_name: {
                required
            },
            staff_role: {
                required
            },
            middle_name: {},
            password: {},
            is_email_enabled:{
                required
            },
            date_of_birth: {},
            phone: {},
            notes: {
                maxLength: maxLength(500)
            },
        }
    },
    watch: {
        'staffId'(val,old) {
            this.id =val
            this.getRecord();

        },
        'clientId'(val){
            this.getRolesAndProperties();
        }
    },
    mounted() {
        const yourDate = new Date();
        this.todayDate = yourDate.toISOString().split('T')[0];
    },
    methods: {
        validatePhoneNumber() {
            const validationRegex = /^(\([0-9]{3}\)) [0-9]{3}-[0-9]{4}$/;
            if (this.staff_form.phone.match(validationRegex)) {
                this.isValidPhoneNumber = true;
            } else if(this.staff_form.phone.length <= 0){
                this.isValidPhoneNumber = true;
            } else {
                this.isValidPhoneNumber = false;
            }
        },
        staff_submit() {
            this.submit=true;
            this.$v.staff_form.$touch();
            if (this.$v.staff_form.$anyError) {
                return;
            }
            if(this.isValidPhoneNumber == false){ return; }
            if(this.id){
                this.loading = true;
                axios.put('/api/client/'+this.clientId+'/staff/'+this.id, this.staff_form)
                    .then(response => {
                        if(response.data.error){
                            this.errormsg = response.data.error;
                            this.loading = false;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {
                            this.successmsg = "Staff has been updated";
                            this.$emit('closeEvent', "Staff has been updated successfully")
                            this.closeModel();
                            setTimeout(() => {
                                this.successmsg = "";
                            }, 3000);
                            this.submit=false
                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            } else {
                this.loading = true;
                axios.post('/api/client/'+this.clientId+'/staff', this.staff_form)
                    .then(response => {
                        if(response.data.error){
                            this.errormsg = response.data.error;
                            this.loading = false;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {
                            this.successmsg = "Staff has been added";
                            this.$emit('closeEvent', "Staff has been added successfully")
                            this.closeModel();
                            setTimeout(() => {
                                this.successmsg = "";
                            }, 3000);
                            this.submit=false
                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }
        },
        getRecord() {
            axios.get('/api/client/staff/'+this.id)
                .then((response) => {
                    let staff = response.data.staff;
                    if(staff.staff_roles && staff.staff_roles.is_property_level == true){
                        this.property_staff = true;
                    }
                    this.staff_roles = response.data.staff_roles;
                    this.staff_form ={
                        first_name : staff.user.first_name,
                        middle_name : staff.user.middle_name,
                        last_name : staff.user.last_name,
                        email : staff.user.email,
                        password : '',
                        is_email_enabled : staff.user.is_email_enabled,
                        staff_role : staff.staff_roles,
                        property : staff.property,
                        date_of_birth : staff.date_of_birth,
                        phone : staff.phone,
                        notes : staff.notes
                    }
                    this.loading = false;
                });
        },
        closeModel(){
            this.$v.staff_form.$reset();
            this.submit = false;
            this.staff_form = {
                first_name: "",
                last_name: "",
                middle_name: "",
                staff_role: "",
                email: "",
                password: "",
                is_email_enabled:0,
                property: "",
                date_of_birth:"",
                phone:"",
                notes:""
            }
            this.property_staff = false;
            this.$forceUpdate();
            this.$emit('closeEvent', "");
            $("#addEditStaff").modal('hide');
        },
        getRolesAndProperties(){
            axios.get('/api/staff/roles_properties/'+this.clientId)
                .then((response) => {
                    if(this.staffId){
                        this.properties = response.data.properties
                    }else{
                        this.staff_roles = response.data.staff_roles;
                        this.properties = response.data.properties
                    }
                });
        },
        isLetter(e) {
            let char = String.fromCharCode(e.keyCode);
            if (/^[A-Za-z -]+$/.test(char)) return true;
            else e.preventDefault();
        },
        saveAndClose(){
            this.closeFlag = true;
        },
        addNewRole(newTag){
            const newRole = {'name' : this.capFirstLetter(newTag)};
            this.staff_roles.push(newRole);
            this.staff_form.staff_role = newRole;
        },
        showPasswordToggle(){
            if (this.fieldType == "password") {
                this.fieldType = "text";
            } else{
                this.fieldType = "password";
            }
        },
        acceptNumber() {
            var x = this.staff_form.phone.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.staff_form.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        },
        capFirstLetter(str) {
            return str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
        }
    }
}
</script>

<style scoped>

</style>
