<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Kindly change your password to enter</h4>
 </div>
 <form autocomplete="off" @submit.prevent="submit_form" method="post">
            <div class="modal_content_inner">
<!--                <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>-->
               
                    <div class="modal-body">
                        <div class="form_box">
                            <div class="form-group">
                                <label>New Password <span class="required-staric">*</span></label>
                                <input  name="confirm_password" type="password" min="6" v-model.trim="$v.password_form.new_password.$model"
                                       class="form-control"
                                       :class="{'is-invalid':($v.password_form.new_password.$error ), 'is-valid':(!$v.password_form.new_password.$invalid)}"
                                       placeholder="New Password">
                                <div class="invalid-feedback">
                                    <span
                                        v-if="!$v.password_form.new_password.required">Password is Required</span>
                                    <span v-if="!$v.password_form.new_password.minLength">Password length must be greater than 6 characters </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password <span class="required-staric">*</span></label>
                                <input  name="confirm_password" type="password" min="6" v-model.trim="$v.password_form.confirm_password.$model"
                                       class="form-control"
                                       :class="{'is-invalid':($v.password_form.confirm_password.$error ), 'is-valid':(!$v.password_form.confirm_password.$invalid)}"
                                       placeholder="Confirm Password">
                                <div class="invalid-feedback">
                                    <span v-if="!$v.password_form.confirm_password.required">Password is Required</span><br>
                                        <span v-if="!$v.password_form.confirm_password.sameAsPassword">Password mismatch </span>
                                </div>
                            </div>

                            <div id="errir-alert" class="alert alert-danger" v-if="errormsg!==''">
                                <strong>{{ errormsg }}</strong>
                            </div>
                            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                                <strong>{{ successmsg }}</strong>
                            </div>
                          
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <div class="inline_buttons">
                                <div class="row">
                                    <div class="col-12 col-sm-12">
                                        <button type="submit"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Update
                                        </button>
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
import {required, minLength,sameAs } from 'vuelidate/lib/validators'


export default {
    name: "AddPayment",

    data() {
        return {
            successmsg: "",
            errormsg: "",
            password_form: {
                new_password:"",
                confirm_password: "",
            },
        }
    },
    validations(){
        return {
            password_form: {

                new_password: {
                required,
                    minLength: minLength(6)
            },
                confirm_password: {
                    required,
                    sameAsPassword: sameAs('new_password')
                },


        }
        }

    },


    methods: {
        submit_form() {
            this.loading = true;
            this.$v.password_form.$touch();
            if (this.$v.password_form.$anyError) {
                this.loading = false;
                return;
            }
            axios.post('/api/auth/update-password', this.password_form)
                .then(response => {
                    if (response.data.status == 'success'){
                        this.loading = false;
                        this.successmsg = response.data.message;
                        this.$store.commit('isPasswordUpdated',1);
                        localStorage.setItem('password_status',1);


                        setTimeout(() => {
                            this.successmsg = "";
                            $("#updatePasswordModal").modal('hide');
                          this.resetForm();
                        }, 3000);

                    }
                    else {
                        this.errormsg = response.data.message;
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);

                    }


                    // this.resetForm();
                })
                .catch(err => {
                    this.loading = false;
                    this.has_error = true;
                    this.resetForm();
                })
            // this.password_form = {}
        },
        resetForm(){
            this.$v.password_form.$reset();
            const yourDate = new Date();
            // this.password_form = {
            //     transaction_method: "",
            //     confirm_password: this.payableconfirm_password,
            //     notes: "",
            //     transaction_date: yourDate.toISOString().split('T')[0],
            // }
            this.$forceUpdate();
        },

    },


}
</script>

<style scoped>

</style>
