<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Edit Paint Color</h4>
 <button type="button" class="btn-close" v-on:click="closeAndResetForm"  aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="paint_color_update" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                            </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Paint Color <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.paint_color_form.name.$model" class="form-control" :class="{'is-invalid': submit && $v.paint_color_form.name.$error, 'is-valid':!$v.paint_color_form.name.$invalid}" placeholder="Paint Color">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.paint_color_form.name.required">Paint Color is Required</span>
                                    <span v-if="!$v.paint_color_form.name.minLength">Paint Color length must be 2 Characters</span>
                                    <span v-if="!$v.paint_color_form.name.maxLength">Paint Color length must not be greater then 20 Characters</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Hex Value <span class="required-staric">*</span></label>
                                <input type="color" v-model.trim = "$v.paint_color_form.hex_value.$model" class="form-control" :class="{'is-invalid':submit && $v.paint_color_form.hex_value.$error, 'is-valid':!$v.paint_color_form.hex_value.$invalid}" placeholder="Hex Value">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.paint_color_form.hex_value.required">Hex value is Required</span>
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
                                        <button type="button" v-on:click="closeAndResetForm"
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
    props: ['paintColorId'],
    data() {
        return {
            errmsg:"",
            id:"",
            paint_color_form : {
                name: "",
                hex_value: "",
            },
            submit: false,
        }
    },
    validations: {
        paint_color_form : {
            name: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(20),
            },
            hex_value: {
                required,
            },

        }
    },
    mounted() {
    },
    watch: {
        'paintColorId'(val) {

            // console.log('aaaaxxx');

            this.id = val;
           if(val)
           {
            this.getRecord(val);
           }
        }
    },
    methods: {
        getRecord(id) {
            axios.get('/api/paint-color/' + id+"/edit")
                .then((response) => {
                    this.paint_color_form.name = response.data.paint_color.name;
                    this.paint_color_form.hex_value = response.data.paint_color.hex_value;
                });
        },
        closeAndResetForm(){
            $("#PaintColorEdit").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        },

        paint_color_update() {
            this.submit = true;
            this.$v.paint_color_form.$touch();
            if (this.$v.paint_color_form.$anyError) {
                return;
            }


            axios.put('/api/paint-color/'+this.id, this.paint_color_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    this.$v.paint_color_form.$reset();
                    this.paint_color_form = {
                        name: "",
                        hex_value: "",
                    }
                    this.submit=false;
                    this.$forceUpdate();
                    $("#PaintColorEdit").modal('hide');
                    this.$emit('closeEvent',"Paint color  has been updated successfully")
                })
                .catch(err => {
                    this.$v.paint_color_form.$reset();
                    this.paint_color_form = {
                        name: "",
                        hex_value: "",
                    }
                    this.submit=false;
                    this.$forceUpdate();
                    this.has_error = true;
                })


        }
    }
}
</script>

<style scoped>

</style>
