<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Edit Role</h4>
 <button type="button" class="btn-close" v-on:click="resetFormAndCloseModal"  aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="role_submit" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="!!errmsg">
                                    <strong>{{ errmsg }}</strong>
                        </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Name <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim="$v.role_form.name.$model"
                                       class="form-control"
                                       :class="{'is-invalid': submit && $v.role_form.name.$error, 'is-valid':!$v.role_form.name.$invalid}"
                                       placeholder="Role name..">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.role_form.name.required">Role Name is Required</span>
                                </div>
                            </div>
                            <!--staff module revamp - commented-->
                            <!--<div class="form-group">
                                    <label>Role type <span class="required-staric">*</span></label>
                                    <select name="role_type" v-model.trim = "$v.role_form.role_type.$model" :class="{'is-invalid':submit && $v.role_form.role_type.$error, 'is-valid':!$v.role_form.role_type.$invalid}" id="role_type" class="form-select">
                                        <option value="Admin">Admin</option>
                                        <option value="Client">Client</option>
                                    </select>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.role_form.role_type.required">Role Type is Required</span>
                                    </div>
                            </div>-->
                          
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="inline_buttons">
                                <div class="row">
                                    <div class="col-6 col-sm-6 mg_bot_5">
                                        <button type="submit"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Save
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-6 mg_bot_5">
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
import {required, minLength, maxLength, between} from 'vuelidate/lib/validators'

export default {
    props: ['roleId'],
    name: "Edit",
    data() {
        return {
            errmsg:"",
            role_form: {
                name: "",
                // role_type: ""
            },
            id: "",
            submit: false
        }
    },
    validations: {
        role_form: {
            name: {
                required,
            },
            // role_type: {
            //     required,
            // },
        }
    },
    watch: {
        'roleId'(val) {
            this.id = val;
            if(this.id){
                this.getRecord(val);
            }
        }
    },
    methods: {
        role_submit() {
            this.submit = true;
            this.$v.role_form.$touch();
            if (this.$v.role_form.$anyError) {
                return;
            }
            axios.put('/api/role/'+this.id, this.role_form)
                .then(response => {
                    this.errmsg = response.data.error;
                    setTimeout(()=> {
                        this.errmsg = "";
                    },3000);
                    if(this.errmsg)
                    {
                        return;
                    }
                    $("#roleEdit").modal('hide');
                    this.$emit('closeEvent',"Role has been updated successfully")

                })
                .catch(err => {
                    $("#roleEdit").modal('hide');
                    this.has_error = true;
                })
        },
        getRecord(id) {
            this.loading = true;
            axios.get('/api/role/' + id+'/edit')
                .then((response) => {
                    // console.log(response);
                    this.role_form.name = response.data.role.name;
                    // this.role_form.role_type = response.data.role.role_type;
                });
        },
        resetFormAndCloseModal() {
            this.resetForm();
            $("#roleEdit").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        },
        resetForm(){

            this.$v.role_form.$reset();
            this.role_form.name = "";
            this.submit=false;
            this.$forceUpdate();
        }
    }
}
</script>

<style scoped>

</style>
