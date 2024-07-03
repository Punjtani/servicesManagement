<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Edit Primer Color</h4>
 <button type="button" class="btn-close"  v-on:click="resetFormAndCloseModal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="refinish_color_update" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        
                        <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                        </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Primer Color <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.refinish_color_form.name.$model" class="form-control" :class="{'is-invalid': submit && $v.refinish_color_form.name.$error, 'is-valid':!$v.refinish_color_form.name.$invalid}" placeholder="Primer Color">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.refinish_color_form.name.required">Paint Color is Required</span>
                                    <span v-if="!$v.refinish_color_form.name.minLength">Paint Color length must be 2 Characters</span>
                                    <span v-if="!$v.refinish_color_form.name.maxLength">Paint Color length must not be greater then 20 Characters</span>
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
                                        <button type="button"  v-on:click="resetFormAndCloseModal"
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
    props: ['refinishColorId'],
    data() {
        return {
            id:"",
            refinish_color_form : {
                name: "",
            },
            submit: false,
            errmsg:""
        }
    },
    validations: {
        refinish_color_form : {
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
        'refinishColorId'(val) {
            if(val){
                this.id = val;
                this.getRecord(val);
            }
        }
    },
    methods: {
        getRecord(id) {
            axios.get('/api/refinish-color/' + id+"/edit")
                .then((response) => {
                    this.refinish_color_form.name = response.data.refinish_color.name;
                });
        },

        resetFormAndCloseModal() {
            this.resetForm();
            $("#RefinishColorEdit").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        },
        resetForm(){
            this.refinish_color_form = {
                        name: "",
                    }
        },

        refinish_color_update() {
            this.submit = true;
            this.$v.refinish_color_form.$touch();
            if (this.$v.refinish_color_form.$anyError) {
                return;
            }


            axios.put('/api/refinish-color/'+this.id, this.refinish_color_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    this.$v.refinish_color_form.$reset();
                    this.refinish_color_form = {
                        name: "",
                    }
                    this.$forceUpdate();
                    $("#RefinishColorEdit").modal('hide');
                    this.$emit('closeEvent',"Refinish color  has been updated successfully")
                })
                .catch(err => {
                    this.has_error = true;
                })


            this.submit=false;

        }
    }
}
</script>

<style scoped>

</style>
