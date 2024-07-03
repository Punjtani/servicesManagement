<template>
 <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Paint Method</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>

</div>
<form autocomplete="off" @submit.prevent="paint_method_submit" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                       
                        <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                            </div>

                        <div class="form_box">
                            <div class="form-group">
                                <label>Paint Method <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.paint_method_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.paint_method_form.name.$error, 'is-valid':!$v.paint_method_form.name.$invalid}" placeholder="Paint Method">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.paint_method_form.name.required">Paint Color is Required</span>
                                    <span v-if="!$v.paint_method_form.name.minLength">Paint Color length must be 2 Characters</span>
                                    <span v-if="!$v.paint_method_form.name.maxLength">Paint Color length must not be greater then 20 Characters</span>
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
            paint_method_form : {
                name: "",
            },
            submit : false,
            errmsg: "",
        }
    },
    validations: {
        paint_method_form : {
            name: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(20),
            },

        }
    },
    mounted() {
    },
    methods: {
        paint_method_submit() {
            this.submit= true
            this.$v.paint_method_form.$touch();
            if (this.$v.paint_method_form.$anyError) {
                return;
            }

            axios.post('/api/paint-method', this.paint_method_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    this.$v.paint_method_form.$reset();
                    this.paint_method_form = {
                        name: "",
                    }
                    this.$forceUpdate();
                    $("#PaintMethodAdd").modal('hide');
                    this.$emit('closeEvent',"Paint Method  has been added successfully")
                })
                .catch(err => {
                    this.$v.paint_method_form.$reset();
                    this.paint_method_form = {
                        name: "",
                    }
                    this.$forceUpdate();
                    this.has_error = true;
                })

        },
        resetForm(){
            this.$v.paint_method_form.$reset();
            this.paint_method_form = {
                name: "",
            },
            this.submit = false
            this.$forceUpdate();
        }
    }
}
</script>

<style scoped>

</style>
