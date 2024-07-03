<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>
            <!-- <div class="row">
                <div class="col-sm-6">
                    <div class="table_area_heads">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_bot_2rem mg_top_half_rem">User Information</h5>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <Navigation></Navigation>
                    <router-link  to="/all-users" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Back</router-link>
                </div>
            </div> -->
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">User Information</h1>
                </div>
                <Navigation></Navigation>

            </div>
            <form autocomplete="off" @submit.prevent="user_submit" method="post">
                <div class="modal-bodys">
                    <div class="form_box">
                        <div class="row">


                            <div  class="col-sm-4">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" v-model.trim="$v.user_form.first_name.$model" v-on:keypress="isLetter($event)" class="form-control"
                                           :class="{'is-invalid':submit && $v.user_form.first_name.$error, 'is-valid':!$v.user_form.first_name.$invalid}"
                                           placeholder="First Name">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.user_form.first_name.required">Required</span>
                                    </div>
                                </div>
                            </div>

                            <div   class="col-sm-4">
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input type="text" v-model.trim="$v.user_form.middle_name.$model" v-on:keypress="isLetter($event)"  class="form-control"
                                           :class="{'is-invalid':submit && $v.user_form.middle_name.$error, 'is-valid':!$v.user_form.middle_name.$invalid}"
                                           placeholder="Middle Name">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.user_form.middle_name.required">Required</span>
                                    </div>
                                </div>
                            </div>

                            <div   class="col-sm-4">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" v-model.trim="$v.user_form.last_name.$model" v-on:keypress="isLetter($event)"  class="form-control"
                                           :class="{'is-invalid':submit && $v.user_form.last_name.$error, 'is-valid':!$v.user_form.last_name.$invalid}"
                                           placeholder="Last Name">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.user_form.last_name.required">Required</span>
                                    </div>
                                </div>
                            </div>


                            <div  class="col-sm-5">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input :disabled="disabled" type="email" v-model.trim="$v.user_form.email.$model" class="form-control"
                                           :class="{'is-invalid':submit && $v.user_form.email.$error, 'is-valid':!$v.user_form.email.$invalid}"
                                           placeholder="Email">

                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.user_form.email.required">Required</span>
                                        <span v-if="!$v.user_form.email.email">This is not valid Email address</span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label>Password </label>
                                    <input :disabled="disabled" type="password" v-model.trim="$v.user_form.password.$model" class="form-control"
                                           :class="{'is-invalid':submit && $v.user_form.password.$error, 'is-valid':!$v.user_form.password.$invalid}"
                                           placeholder="Password">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.user_form.password.required">Required</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                 <div class="form-group">
                                    <label></label>
                                    <button @click="disabled = !disabled" type="button" class="btn  btn_orange border_radius_5 text-uppercase font_14 font_weight_600 btn-block add-user">Enable</button>
                                </div>
                            </div>
                        </div>
                        <div class="inline_buttonss">
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <button type="submit"
                                            class="btn btn_blue btn_medium border_radius_5 font_14 text_uppercase font_weight_600">
                                        Save
                                    </button>
                                    <button type="submit" @click="saveAndClose" class="btn btn_blue btn_medium border_radius_5 font_14 text_uppercase font_weight_600">Save &amp; Close</button>
                                    <router-link  to="/all-users"  class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Cancel</router-link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="success-alert1" class="alert alert-success" style="margin-top: 10px" v-if="successmsg!==''"> <strong>{{ successmsg }}</strong> </div>
                    <div id="errir-alert" class="alert alert-danger" v-if="errormsg!==''">
                        <strong>{{ errormsg }}</strong>
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
    name: "Add",
    components : {
        'Loader': Loader,
        'Navigation': Navigation,
    },
    data() {
        return {
            id:  this.$route.params.id ,
            successmsg: "",
            errormsg: "",
            loading: false,
            already_user: false,
            disabled : true,
            roles: [],
            user_form : {
                email: "",
                password : "",
                first_name : "",
                middle_name : "",
                last_name : "",
            },
            submit: false,
            closeFlag:false,
        }
    },
    validations: {
        user_form: {
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
            middle_name: {},
            password: {},

        }
    },
    mounted() {
        if (this.id) {
            this.loading = true;
            this.getRecord(this.id);
        } else {
            // console.log('new record');
        }

    },
    methods: {
        user_submit() {
            this.submit=true;
            this.loading = true;
            // console.log('i m here',this.user_form);
            this.$v.user_form.$touch();
            if (this.$v.user_form.$anyError) {
                this.loading = false;
                return;
            }

            if(this.id){
                axios.put('/api/user/'+this.id, this.user_form)
                    .then(response => {
                        this.loading = false;
                        this.successmsg = "User has been updated";
                        if(this.closeFlag){
                            this.$router.push('/all-users');
                        }else{
                            this.getRecord(this.id);
                        }
                        setTimeout(() => {
                            this.successmsg = "";
                        }, 3000);
                        this.submit=false
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            } else {
                axios.post('/api/user', this.user_form)
                    .then(response => {
                        this.loading = false;
                        if(response.data.error){
                            this.errormsg = response.data.error;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {
                            this.successmsg = "User has been added";
                            if(this.closeFlag){
                                this.$router.push('/all-users');
                            }else{
                                this.id = response.data.user_id;
                                this.$router.push('/view-user/'+this.id);
                                this.getRecord(this.id);
                            }
                            setTimeout(() => {
                                this.successmsg = "";
                                // this.$router.push('/employees')
                            }, 3000);
                            this.submit=false
                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }
        },
        getRecord(id) {
            axios.get('/api/user/' + id+"/edit")
                .then((response) => {
                    // console.log(response.data.user);
                    this.user_form = response.data.user;
                    this.loading = false;
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



    }
}
</script>

<style scoped>

</style>
