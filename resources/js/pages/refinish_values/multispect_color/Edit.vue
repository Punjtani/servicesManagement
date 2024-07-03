<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Edit Multispect Color</h4>
 <button type="button" class="btn-close" v-on:click="resetFormAndCloseModal"  aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="multispect_color_update" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <h4 class=""></h4>
                        <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                            </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Multispect Color <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.multispect_color_form.name.$model" class="form-control" :class="{'is-invalid': submit && $v.multispect_color_form.name.$error, 'is-valid':!$v.multispect_color_form.name.$invalid}" placeholder="Multispect Color">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.multispect_color_form.name.required">Paint Color is Required</span>
                                    <span v-if="!$v.multispect_color_form.name.minLength">Paint Color length must be 2 Characters</span>
                                    <span v-if="!$v.multispect_color_form.name.maxLength">Paint Color length must not be greater then 20 Characters</span>
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
export default {
    name: "Edit",
    props: ['multispectColorId'],
    data() {
        return {
            id:"",
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
    watch: {
        'multispectColorId'(val) {
            if(val !== ''){
                this.id = val;
                this.getRecord(val);
            }
        }
    },
    methods: {
        getRecord(id) {
            axios.get('/api/multispect-color/' + id+"/edit")
                .then((response) => {
                    this.multispect_color_form.name = response.data.multispect_color.name;
                });
        },

        resetFormAndCloseModal() {
            this.resetForm();
            $("#MultispectColorEdit").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        },
        resetForm(){
            this.multispect_color_form = {
                name: "",
            }
        },
        multispect_color_update() {
            this.submit = true;
            this.$v.multispect_color_form.$touch();
            if (this.$v.multispect_color_form.$anyError) {
                return;
            }


            axios.put('/api/multispect-color/'+this.id, this.multispect_color_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    $("#MultispectColorEdit").modal('hide');
                    this.$emit('closeEvent',"Multispect color  has been updated successfully")
                })
                .catch(err => {
                    this.has_error = true;
                })

            this.$v.multispect_color_form.$reset();
            this.multispect_color_form = {
                name: "",
            }
            this.submit=false;
            this.$forceUpdate();
        }
    }
}
</script>

<style scoped>

</style>
