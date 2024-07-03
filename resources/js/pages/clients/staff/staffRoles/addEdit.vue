<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Role</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" @submit.prevent="staffRoleSubmit" method="post">
            <div class="modal_content_inner">
                
                    <div class="modal-body">
                       
                        <div class="form_box">
                            <div class="form-group">
                                <label>Role Name <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.staff_role_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.staff_role_form.name.$error, 'is-valid':!$v.staff_role_form.name.$invalid}" placeholder="Role Name">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.staff_role_form.name.required">Role is Required</span>
                                    <span v-if="!$v.staff_role_form.name.minLength">Role length must be 2 Characters</span>
                                    <span v-if="!$v.staff_role_form.name.maxLength">Role length must not be greater then 20 Characters</span>
                                </div>
                            </div>
                            <!--<div class="form-group">
                                <label>Role Type <span class="required-staric">*</span></label>
                                <multiselect deselectLabel="" v-model.trim="staff_role_form.role_type"  :select-label="''" :options="role_type_options" :searchable="true" :allow-empty="false"
                                    :class="{'is-invalid':$v.staff_role_form.role_type.$error, 'is-valid':!$v.staff_role_form.role_type.$invalid}">
                                </multiselect>
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.staff_role_form.role_type.required">Role Type is Required</span>
                                </div>
                            </div>-->                           
                            
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
                            </div></div>
                </form>
            
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { required, minLength,maxLength, between } from 'vuelidate/lib/validators'
import Multiselect from 'vue-multiselect';
export default {
    name: "AddOrEdit",
    components : {
        'Multiselect': Multiselect
    },
    props : ['roleId'],
    data() {
        return {
            staff_role_form : {
                name: "",
                // role_type:""
            },
            submit:false,
            // role_type_options: ["Client", "Property"],
        }
    },
    validations: {
        staff_role_form : {
            name: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(20),
            },
            role_type:{
                // required
            }

        }
    },
    mounted() {
    },
    watch: {
        'roleId'(val) {
            this.id = val;
            this.getRecord(val);
        }
    },

    methods: {
        getRecord(id) {
            axios.get('/api/staff-roles/' + id+"/edit")
                .then((response) => {
                    this.staff_role_form = response.data.staff_role;
                    // if(this.staff_role_form.is_property_level){
                    //     this.staff_role_form.role_type = 'Property';
                    // }else{
                    //     this.staff_role_form.role_type = 'Client';
                    // }
                });
        },
        staffRoleSubmit() {
            this.submit=true
            this.$v.staff_role_form.$touch();
            if (this.$v.staff_role_form.$anyError) {
                return;
            }
            // if(this.staff_role_form.role_type == 'Client'){
            //     this.staff_role_form.is_property_level = 0;
            // }else{
            //     this.staff_role_form.is_property_level = 1;
            // }

            if(this.roleId){
                axios.put('/api/staff-roles/'+this.id, this.staff_role_form)
                    .then(response => {
                        $("#RoleEdit").modal('hide');
                        this.$emit('closeEvent',"Role has been updated successfully")
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }else{
                axios.post('/api/staff-roles', this.staff_role_form)
                    .then(response => {
                        $("#RoleAdd").modal('hide');
                        this.$emit('closeEvent', "Role  has been added successfully")
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }
            this.$v.staff_role_form.$reset();
            this.staff_role_form = {
                name: "",
                // role_type:"",
            }
            this.submit=false
            this.$forceUpdate();
        }
    }
}
</script>

<style scoped>

</style>
