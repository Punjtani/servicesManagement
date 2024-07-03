<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title">Edit Paint Finishing</h4>
 <button type="button" class="btn-close" v-on:click="closeAndResetForm"  aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="paint_finishing_update" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                            </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Paint Finishing <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.paint_finishing_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.paint_finishing_form.name.$error, 'is-valid':!$v.paint_finishing_form.name.$invalid}" placeholder="Paint Finishing">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.paint_finishing_form.name.required">Paint Finishing is Required</span>
                                    <span v-if="!$v.paint_finishing_form.name.minLength">Paint Finishing length must be 2 Characters</span>
                                    <span v-if="!$v.paint_finishing_form.name.maxLength">Paint Finishing length must not be greater then 20 Characters</span>
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
    props: ['paintFinishingId'],
    data() {
        return {
            id:"",
            paint_finishing_form : {
                name: "",
            },
            submit : false,
            errmsg:""
        }
    },
    validations: {
        paint_finishing_form : {
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
        'paintFinishingId'(val) {
            this.id = val;
            if(val)
           {
            this.getRecord(val);
           }
        }
    },
    methods: {
        getRecord(id) {
            axios.get('/api/paint-finishing/' + id+"/edit")
                .then((response) => {
                    this.paint_finishing_form.name = response.data.paint_finishing.name;
                });
        },

        closeAndResetForm(){
            $("#PaintFinishingEdit").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        },
        paint_finishing_update() {
            this.submit = true
            this.$v.paint_finishing_form.$touch();
            if (this.$v.paint_finishing_form.$anyError) {
                return;
            }
            axios.put('/api/paint-finishing/'+this.id, this.paint_finishing_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    this.$v.paint_finishing_form.$reset();
                    this.paint_finishing_form = {
                        name: "",
                    }
                    this.submit=false
                    this.$forceUpdate();
                    $("#PaintFinishingEdit").modal('hide');
                    this.$emit('closeEvent',"Paint Finishing  has been updated successfully")
                })
                .catch(err => {
                    this.$v.paint_finishing_form.$reset();
                    this.paint_finishing_form = {
                        name: "",
                    }
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
