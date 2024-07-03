<template>
    <div class="modal-dialog">
        <button type="button" class="btn-close" data-bs-dismiss="modal" @click="resetForm()" aria-label="Close"></button>
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Add Extra Charges</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" @click="resetForm()" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="extra_submit" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <h4 class="">Add Extra Charges</h4>
                        <div class="col-sm-12">
                                <small v-if="job">Unit # - {{job.property.title}} {{job.apartment_number}} {{job.apartment_type.type}}</small>
					        </div>
                        <div class="form_box">



                            <div class="form-group">
                                <label>Amount</label>
                                    <input type="number" v-model.trim="$v.extra_form.amount.$model"   class="form-control"
                                           :class="{'is-invalid':submit && $v.extra_form.amount.$error, 'is-valid':!$v.extra_form.amount.$invalid}"
                                           placeholder="Add Amount">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.extra_form.amount.required">Required</span>
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
                                        <button type="button" data-bs-dismiss="modal" @click="resetForm()"
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
import { required, minLength,maxLength, between, requiredIf  } from 'vuelidate/lib/validators';
import Datepicker from 'vuejs-datepicker';
import roleData from '../../data.js';
export default {
    name: "Assign",
    components: {
        'Datepicker': Datepicker,
    },
    props : ['employee','job'],
    data() {
        return {
            extra_form : {
                employee_id: "",
                job_id: "",
                amount: "",

            },

            submit: false
        }
    },
    validations: {
        extra_form : {
            amount: {
                required,
            },
        }
    },
    mounted() {
        // console.log(this.job);
        this.resetForm();
    },

    watch: {
        'job'(val) {
            this.getRecord();
        }
    },

    methods: {
        getRecord() {

            axios.get('/api/management_extra/'+this.job.id+'/'+this.employee.id)
                .then((response) => {
                    let data = response.data.management_extra;
                    if(data && data[0].amount != null){
                        this.extra_form.amount=(Math.round( data[0].amount * 100) / 100).toFixed(2);
                    }
                });
        },

        extra_submit() {

            this.submit = true
            this.loading=true;
            this.$v.extra_form.$touch();
            if (this.$v.extra_form.$anyError) {
                // console.log("false");
                this.loading=false;
                return;
            }
            this.extra_form.employee_id = this.employee.id;
            this.extra_form.job_id = this.job.id;
            axios.put('/api/management_extra', this.extra_form)
                .then(response => {
                    this.resetForm();
                    this.$emit('closeEvent',"Extra Charges have been added");
                })
                .catch(err => {
                    this.loading=false;
                    this.has_error = true;
                })
            $("#AddExtra").modal('hide');
        },

        resetForm(){
            this.$v.extra_form.$reset();
            this.extra_form = {
                employee_id: "",
                job_id: "",
                amount:"",
            }
            this.submit=false
            this.$emit('closeEvent','');
            this.$forceUpdate();
        }
    }
}
</script>
