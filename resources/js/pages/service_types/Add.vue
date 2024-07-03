<template>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
 <h4 class="modal-title"> Service</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="service_types_submit" method="post">

                <div class="modal_content_inner">
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="deletemsg!==''">
                                <strong>{{ deletemsg }}</strong>
                            </div>
                            <div class="form_box">
                                <div class="form-group">
                                    <label>Service</label>
                                    <input type="text" v-model.trim = "$v.service_type_form.category.$model" class="form-control" :class="{'is-invalid': submit && $v.service_type_form.category.$error, 'is-valid':!$v.service_type_form.category.$invalid}" placeholder="Service Category">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.service_type_form.category.required">Service Category is Required</span>
                                        <span v-if="!$v.service_type_form.category.minLength">Service Category length must be 4 Characters</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Payroll Type</label>
                                    <select v-model.trim = "$v.service_type_form.payroll_type.$model" class="form-select" :class="{'is-invalid':submit && $v.service_type_form.payroll_type.$error, 'is-valid':!$v.service_type_form.payroll_type.$invalid}"  aria-label="Payroll Type">
                                        <option value="">Select Payroll Type</option>
                                        <option value="Percentage">Percentage Pay</option>
                                        <option value="Hourly">Hourly Pay</option>
                                        <option value="Monthly">Monthly Pay</option>
                                        <option value="Fixed">Fixed Pay</option>
                                    </select>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.service_type_form.payroll_type.required">Payroll Type is Required</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Color Shade</label>
                                    <input type="color" v-model.trim = "$v.service_type_form.color.$model" class="form-control" placeholder="Select Color Shade">
                                </div>

                                <div class="form-group">
                                    <input type="checkbox"  v-model.trim="$v.service_type_form.is_taxable.$model"  v-bind:true-value="1"  v-bind:false-value="0" >
                                    <label>Taxable</label>
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
                                            <button type="button" v-on:click="resetForm" data-bs-dismiss="modal"
                                                    class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                                Cancel
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
import { required, minLength,maxLength, between } from 'vuelidate/lib/validators'
export default {
    name: "Add",
    data() {
        return {
            service_type_form : {
                category: "",
                color: "",
                payroll_type: "",
                is_taxable: 0
            },
            deletemsg: "",
            submit: false
        }
    },
    validations: {
        service_type_form : {
            category: {
                required,
                minLength: minLength(4),
            },
            payroll_type: {
                required,
            },
            color: {},
            is_taxable:{}

        }
    },
    mounted() {
    },
    methods: {
        service_types_submit() {
            this.submit=true;
            this.$v.service_type_form.$touch();
            if (this.$v.service_type_form.$anyError) {
                return;
            }

            axios.post('/api/service-type', this.service_type_form)
                .then(response => {
                    if(response.data.message){
                        this.deletemsg = response.data.message;
                        setTimeout(() => {
                            this.deletemsg = "";
                        }, 3000);
                    }else{
                        $("#serviceTypesAdd").modal('hide');
                        this.$emit('closeEvent',"Service Type has been added successfully")
                        this.resetForm();
                    }
                })
                .catch(err => {
                    this.has_error = true;
                })
        },
        resetForm(){
            this.$v.service_type_form.$reset();
            this.submit = false;
            this.service_type_form = {
                category: "",
                // short_code: "",
                payroll_type: "",
                is_taxable: 0
            };
            this.$forceUpdate();
        }
    }
}
</script>

<style scoped>

</style>
