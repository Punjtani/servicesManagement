<template>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
 <h4 class="modal-title"> Role</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="role_submit" method="post">

                <div class="modal_content_inner">
                        <div class="modal-body">
                            <div class="form_box">
                                <div class="form-group">
                                    <label>Name <span class="required-staric">*</span></label>
                                    <multiselect deselectLabel="" v-model.trim="role_form.name" track-by="name" label="name" :select-label="''" :options="roles" :searchable="true" :allow-empty="false"
                                    :class="{'is-invalid':$v.role_form.name.$error, 'is-valid':!$v.role_form.name.$invalid}"></multiselect>
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.role_form.name.required">Role Name is Required</span>
                                    </div>
                            
                            </div>
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
import { required, minLength,maxLength, between } from 'vuelidate/lib/validators';
import Multiselect from 'vue-multiselect';
import roleData from '../../data.js';
export default {
    name: "Add",
    components : {
        'Multiselect': Multiselect
    },
    data() {
        return {
            roleData: roleData,
            roles: [],
            role_form : {
                name: ""
            },
            submit: false
        }
    },
    validations: {
        role_form : {
            name: {
                required,
            },
        }
    },
    mounted() {
        this.getInitialValues();
    },
    methods: {
        role_submit() {
            this.submit = true;
            this.$v.role_form.$touch();
            if (this.$v.role_form.$anyError) {
                return;
            }
            // console.log(this.role_form.name.id);
           if(this.role_form.name.id == this.roleData.admin){
                this.$router.push('/add-user')
            }
            else if(this.role_form.name.id == this.roleData.client){
                this.$router.push('/add-client')
            }else if(this.role_form.name.id == this.roleData.employee){
                this.$router.push('/add-employee')
            }
            $("#roleAdd").modal('hide');
        },
         getInitialValues(){
            axios.get('/api/user/create')
                .then((response) => {
                    this.roles = response.data.roles;
                    const index = this.roles.findIndex(obj => obj.id === this.roleData.super_admin);
                    this.roles.splice(index,1);
                });
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
