<template>
        <div class="modal-dialog modal-dialog-centered ">

            <div class="modal-content">
                <div class="modal-header">
 <h4 class="modal-title"> Multispect Color</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="multispect_color_submit" method="post">

                <div class="modal_content_inner">
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                                </div>
                            <div class="form_box">
                                <div class="form-group">
                                    <label>Multispect Color <span class="required-staric">*</span></label>
                                    <input type="text" v-model.trim = "$v.multispect_color_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.multispect_color_form.name.$error, 'is-valid':!$v.multispect_color_form.name.$invalid}" placeholder="Multispect Color">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.multispect_color_form.name.required">Multispect Color is Required</span>
                                        <span v-if="!$v.multispect_color_form.name.minLength">Multispect Color length must be 2 Characters</span>
                                        <span v-if="!$v.multispect_color_form.name.maxLength">Multispect Color length must not be greater then 20 Characters</span>
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
            multispect_color_form : {
                name: "",
            },
            submit: false,
            errmsg:""
        }
    },
    validations: {
        multispect_color_form : {
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
        multispect_color_submit() {
            this.submit = true;
            this.$v.multispect_color_form.$touch();
            if (this.$v.multispect_color_form.$anyError) {
                return;
            }

            axios.post('/api/multispect-color', this.multispect_color_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    $("#MultispectColorAdd").modal('hide');
                    this.$emit('closeEvent',"Multispect color  has been added successfully")
                })
                .catch(err => {
                    this.has_error = true;
                })

            this.$v.multispect_color_form.$reset();
            this.multispect_color_form = {
                name: "",
            }
            this.$forceUpdate();
        },
        resetForm(){
            this.$v.multispect_color_form.$reset();
            this.multispect_color_form = {
                name: "",
            }
            this.submit = false;
            this.$forceUpdate();
        }
    }
}
</script>

<style scoped>

</style>
