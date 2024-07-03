<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tax Type</h4>
                <button type="button" class="btn-close" v-on:click="resetFormAndCloseModal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" @submit.prevent="tax_type_submit" method="post">
            <div class="modal_content_inner">
               
                    <div class="modal-body">
                        
                        <div class="alert alert-danger" v-if="!!errmsg">
                                <strong>{{ errmsg }}</strong>
                        </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Tax Name <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.tax_type_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.tax_type_form.name.$error, 'is-valid':!$v.tax_type_form.name.$invalid}" placeholder="Tax Name">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.tax_type_form.name.required">Tax Name is Required</span>
                                    <span v-if="!$v.tax_type_form.name.minLength">Tax Name length must be 2 Characters</span>
                                    <span v-if="!$v.tax_type_form.name.maxLength">Tax Name length must not be greater then 20 Characters</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Tax Rate (%) <span class="required-staric">*</span></label>
                                 <input type="number" step="any" v-model.trim = "$v.tax_type_form.rate.$model" class="form-control" :class="{'is-invalid':submit && $v.tax_type_form.rate.$error, 'is-valid':!$v.tax_type_form.rate.$invalid}" placeholder="Tax Rate">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.tax_type_form.rate.required">Rate is Required</span>
                                    <span v-if="!$v.tax_type_form.rate.between">Rate must be between {{$v.tax_type_form.rate.$params.between.min}} and {{$v.tax_type_form.rate.$params.between.max}}</span>
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
                                        <button type="button" v-on:click="resetFormAndCloseModal"
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
import Multiselect from 'vue-multiselect';
export default {
    name: "AddOrEdit",
    components : {
        'Multiselect': Multiselect
    },
    props : ['taxtTypeId'],
    data() {
        return {
            tax_type_form : {
                name: "",
                rate:""
            },
            submit:false,
            errmsg:""
            // rate_options: ["Fixed", "Hourly", "rate"],
        }
    },
    validations: {
        tax_type_form : {
            name: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(20),
            },
            rate:{
                required,
                between: between(0, 100),

            }

        }
    },
    mounted() {
    },

    watch: {
        'taxtTypeId'(val) {
            this.id = val;
          if(val)
          {
            this.getRecord(val);
          }
        }
    },

    methods: {
        getRecord(id) {
            axios.get('/api/tax-type/' + id+"/edit")
                .then((response) => {
                    this.tax_type_form = response.data.tax_type;
                });
        },
        resetFormAndCloseModal() {
            this.resetForm();
            $("#TaxTypeEdit").modal('hide');
            $("#TaxTypeAdd").modal('hide');
        },
        resetForm(){
            this.$v.tax_type_form.$reset();
                            this.tax_type_form = {
                                name: "",
                                rate:"",
                            }
            this.$emit('closeEvent',"no-refresh");
        },
        tax_type_submit() {

            this.$v.tax_type_form.$touch();
            if (this.$v.tax_type_form.$anyError) {
                this.submit=false
                return;
            }

            if(this.taxtTypeId){
                axios.put('/api/tax-type/'+this.id, this.tax_type_form)
                    .then(response => {
                            this.errmsg = response.data.error;
                            setTimeout(()=> {
                                this.errmsg = "";
                            },3000);
                            if(this.errmsg)
                            {
                                this.submit=false
                                return;
                            }
                            this.$v.tax_type_form.$reset();
                            this.tax_type_form = {
                                name: "",
                                rate:"",
                            }
                            this.submit=false
                            this.$forceUpdate();
                        $("#TaxTypeEdit").modal('hide');
                        this.$emit('closeEvent',"Tax Type has been updated successfully")
                    })
                    .catch(err => {
                            this.submit=false
                            this.$forceUpdate();
                        this.has_error = true;
                    })
            }else {
                axios.post('/api/tax-type', this.tax_type_form)
                    .then(response => {
                        this.errmsg = response.data.error;
                        setTimeout(()=> {
                            this.errmsg = "";
                        },3000);
                        if(this.errmsg)
                        {
                            this.submit=false
                            return;
                        }
                        this.$v.tax_type_form.$reset();
                        this.tax_type_form = {
                            name: "",
                            rate:"",
                        }
                        this.submit=false
                        this.$forceUpdate();
                        $("#TaxTypeAdd").modal('hide');
                        this.$emit('closeEvent', "Tax Type has been added successfully")
                    })
                    .catch(err => {
                        this.submit=false
                        this.$forceUpdate();
                        this.has_error = true;
                    })

            }
        }
    }
}
</script>

<style scoped>

</style>
