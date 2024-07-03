<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Edit Paint Grade</h4>
 <button type="button" class="btn-close" v-on:click="closeAndResetForm" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="paint_grade_update" method="post">
            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                            </div>

                        <div class="form_box">
                            <div class="form-group">
                                <label>Paint Grade <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.paint_grade_form.name.$model" class="form-control" :class="{'is-invalid': submit && $v.paint_grade_form.name.$error, 'is-valid':!$v.paint_grade_form.name.$invalid}" placeholder="Paint Grade">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.paint_grade_form.name.required">Paint Grade is Required</span>
                                    <span v-if="!$v.paint_grade_form.name.minLength">Paint Grade length must be 2 Characters</span>
                                    <span v-if="!$v.paint_grade_form.name.maxLength">Paint Grade length must not be greater then 20 Characters</span>
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
    props: ['paintGradeId'],
    data() {
        return {
            id:"",
            paint_grade_form : {
                name: "",
            },
            submit : false,
            errmsg:"",
        }
    },
    validations: {
        paint_grade_form : {
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
        'paintGradeId'(val) {
            // console.log('changed')

            this.id = val;
            if(val)
           {
            this.getRecord(val);
           }
        }
    },
    methods: {
        getRecord(id) {
            axios.get('/api/paint-grade/' + id+"/edit")
                .then((response) => {
                    // console.log(response)
                    this.paint_grade_form.name = response.data.paint_grade.name;
                });
        },

        closeAndResetForm(){
            $("#PaintGradeEdit").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        },
        paint_grade_update() {
            this.submit = true
            this.$v.paint_grade_form.$touch();
            if (this.$v.paint_grade_form.$anyError) {
                return;
            }
            axios.put('/api/paint-grade/'+this.id, this.paint_grade_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    this.$v.paint_grade_form.$reset();
                    this.paint_grade_form = {
                        name: "",
                    },
                    this.submit = false
                    this.$forceUpdate();
                    $("#PaintGradeEdit").modal('hide');
                    this.$emit('closeEvent',"Paint Grade  has been updated successfully")
                })
                .catch(err => {
                    this.$v.paint_grade_form.$reset();
                    this.paint_grade_form = {
                        name: "",
                    },
                    this.submit = false
                    this.$forceUpdate();
                    this.has_error = true;
                })

        }
    }
}
</script>

<style scoped>

</style>
