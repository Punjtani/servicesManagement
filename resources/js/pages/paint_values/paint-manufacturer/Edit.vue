<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Edit Paint Manufacturer</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="paint_manufacturer_update" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                            </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Paint Manufacturer <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.paint_manufacturer_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.paint_manufacturer_form.name.$error, 'is-valid':!$v.paint_manufacturer_form.name.$invalid}" placeholder="Paint Manufacturer">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.paint_manufacturer_form.name.required">Paint Manufacturer is Required</span>
                                    <span v-if="!$v.paint_manufacturer_form.name.minLength">Paint Manufacturer length must be 2 Characters</span>
                                    <span v-if="!$v.paint_manufacturer_form.name.maxLength">Paint Manufacturer length must not be greater then 20 Characters</span>
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
                                        <button type="button" data-bs-dismiss="modal"
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
    props: ['paintManufacturerId'],
    data() {
        return {
            id:"",
            paint_manufacturer_form : {
                name: "",
            },
            submit : false,
            errmsg:"",
        }
    },
    validations: {
        paint_manufacturer_form : {
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
        'paintManufacturerId'(val) {
            this.id = val;
            if(val)
           {
            this.getRecord(val);
           }
        }
    },
    methods: {
        getRecord(id) {
            axios.get('/api/paint-manufacturer/' + id+"/edit")
                .then((response) => {
                    // console.log(response)
                    this.paint_manufacturer_form.name = response.data.paint_manufacturer.name;
                });
        },

        closeAndResetForm(){
            $("#PaintManufacturerEdit").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        },
        paint_manufacturer_update() {
            this.submit = true
            this.$v.paint_manufacturer_form.$touch();
            if (this.$v.paint_manufacturer_form.$anyError) {
                return;
            }
            axios.put('/api/paint-manufacturer/'+this.id, this.paint_manufacturer_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    this.$v.paint_manufacturer_form.$reset();
                    this.paint_manufacturer_form = {
                        name: "",
                    },
                    this.submit=false
                    this.$forceUpdate();
                    $("#PaintManufacturerEdit").modal('hide');
                    this.$emit('closeEvent',"Paint Manufacturer  has been updated successfully")
                })
                .catch(err => {
                    this.$v.paint_manufacturer_form.$reset();
                    this.paint_manufacturer_form = {
                        name: "",
                    },
                    this.submit=false
                    this.$forceUpdate();
                    this.has_error = true;
                })


        }
    }
}
</script>

<style scoped>

</style>
