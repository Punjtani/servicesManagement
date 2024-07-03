<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Unit Type</h4>
 <button type="button" class="btn-close" v-on:click="closeAndResetForm"  aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="appartment_types_submit" method="post">
            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="deletemsg!==''">
                    <strong>{{ deletemsg }}</strong>
                    </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Unit Type<span class="required-staric">*</span></label>
                                <input type="text" v-model.trim="$v.appartment_type_form.appartment_type_name.$model"
                                       class="form-control"
                                       :class="{'is-invalid': submit && $v.appartment_type_form.appartment_type_name.$error, 'is-valid':!$v.appartment_type_form.appartment_type_name.$invalid}"
                                       placeholder="Unit Name">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.appartment_type_form.appartment_type_name.required">Unit Type Name is Required</span>
                                    <span v-if="!$v.appartment_type_form.appartment_type_name.minLength">Unit Type length must be 4 Characters</span>
                                    <span v-if="!$v.appartment_type_form.appartment_type_name.maxLength">Unit Type length must not be greater then 50 Characters</span>
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
                                        <button  type="button" v-on:click="closeAndResetForm"  class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
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
import {required, minLength, maxLength, between} from 'vuelidate/lib/validators'

export default {
    props: ['appartmentTypeId'],
    name: "Edit",
    data() {
        return {
            appartment_type_form: {
                appartment_type_name: "",
            },
            id: "",
            submit: false,
            deletemsg: "",
        }
    },
    validations: {
        appartment_type_form: {
            appartment_type_name: {
                required,
                minLength: minLength(4),
                maxLength: maxLength(50),
            },
        }
    },
    watch: {
        'appartmentTypeId'(val) {
            this.id = val;
            if(this.id){
                this.getRecord(val);
            }
        }
    },
    methods: {
        appartment_types_submit() {
            this.submit = true;
            this.$v.appartment_type_form.$touch();
            if (this.$v.appartment_type_form.$anyError) {
                return;
            }
            axios.put('/api/appartment-type/'+this.id, this.appartment_type_form)
                .then(response => {
                    if(response.data.message){
                        this.deletemsg = response.data.message;
                        setTimeout(() => {
                            this.deletemsg = "";
                        }, 3000);
                        if(this.deletemsg)
                        {
                            return;
                        }
                        this.resetForm();
                    }
                    else
                    {

                    $("#appartmentTypesEdit").modal('hide');
                    this.$emit('closeEvent',"Unit Type has been updated successfully")
                    }

                })
                .catch(err => {
                    this.has_error = true;
                })
        },
        getRecord(id) {
            this.loading = true;
            axios.get('/api/appartment-type/' + id+'/edit')
                .then((response) => {
                    // console.log(response);
                    this.appartment_type_form.appartment_type_name = response.data.appartment_type.type;
                });
        },
        resetForm(){
            this.$v.appartment_type_form.$reset();
            this.appartment_type_form.id="";
            this.appartment_type_form.appartment_type_name = "";
            this.submit=false;
            this.$forceUpdate();
        },
        closeAndResetForm(){
            this.resetForm();
            $("#appartmentTypesEdit").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        }
    }
}
</script>

<style scoped>

</style>
