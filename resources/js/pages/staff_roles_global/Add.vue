<template>
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
 <h4 class="modal-title"> Role</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
                <div v-if="loading">
                    <loader-spinner></loader-spinner>
                </div>
                <form autocomplete="off" @submit.prevent="role_submit" method="post">

                <div class="modal_content_inner">
                        <div class="modal-body">
                            <div class="form_box">
                                <div class="form-group">
                                    <label>Name <span class="required-staric">*</span></label>
                                    <input type="text" v-model.trim = "$v.role_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.role_form.name.$error, 'is-valid':!$v.role_form.name.$invalid}" placeholder="Role name..">
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.role_form.name.required">Role Name is Required</span>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <div v-if="IsPropertyLevelExist">
                                        <strong class="text-left font_weight_600">This Role already used by property level, Click to covert button to make it as global role.</strong><br>
                                        <strong class="text-left font_weight_600">Once it converted you will not be able to revert back as property level role.</strong>
                                        <!--                <p class="text-center font_weight_600">Please keep in mind, by canceling this service, all the sub services will be also removed from the list.</p>-->
                                        <div class="text-center">
                                            <button  class="btn btn-warning" @click="convertToGlobal()"> Convert </button>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="alert alert-danger" v-if="addRoleErrorMsg">
                                    <strong>{{ addRoleErrorMsg }}</strong>
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
import { required, minLength,maxLength, between } from 'vuelidate/lib/validators'
import LoaderSpinner from "../LoaderSpinner";
export default {
    name: "Add",
    data() {
        return {
            id:undefined,
            loading:false,
            IsPropertyLevelExist:false,
            addRoleErrorMsg:"",
            role_form : {
                name: "",
                // role_type: ""
            },
            submit: false
        }
    },
    validations: {
        role_form : {
            name: {
                required,
            },
            // role_type: {
            //     required,
            // },
        }
    },
    mounted() {
    },
    methods: {
        role_submit() {
            this.submit = true;
            this.$v.role_form.$touch();
            if (this.$v.role_form.$anyError) {
                return;
            }
            this.id = undefined;
            axios.post('/api/createstaffrole', this.role_form)
                .then(response => {
                    this.id = response.data.id;
                    this.loading = false
                    if (response.data.code == 403){
                        this.IsPropertyLevelExist = true
                    }else if (response.data.code == 200){
                        this.addRoleErrorMsg = response.data.error;

                        setTimeout(() => {
                            this.addRoleErrorMsg = "";
                        }, 3000);
                    }
                    else {

                        this.$emit('closeEvent',"Role has been added successfully")
                        this.resetForm();
                        $("#roleAdd").modal('hide');
                    }

                })
                .catch(err => {
                    this.has_error = true;
                })

        },
        async convertToGlobal() {
            // this.submit = true;
            // this.$v.role_form.$touch();
            // if (this.$v.role_form.$anyError) {
            //     return;
            // }

            this.loading = true
            try {
                const response = await axios.put('/api/toggleStaffRoleLevel/'+this.id+"?forceCreate=true");
                this.successmsg = response.data.message;
                this.loading = false


                setTimeout(() => {
                        this.successmsg = "";
                    }, 3000);

                    if (response.data.message){
                        this.$emit('closeEvent',"Role has been updated successfully")
                        $("#roleAdd").modal('hide');
                    }
            } catch(e){
                this.has_error = true;
                    this.loading = false
            }

        },
        resetForm(){
            this.IsPropertyLevelExist = false;
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
