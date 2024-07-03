<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Permission</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="permission_submit" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="form_box">
                            <div class="form-group">
                                <label>Permission Name</label>
                                <input type="text" v-model.trim = "$v.permission_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.permission_form.name.$error, 'is-valid':!$v.permission_form.name.$invalid}" placeholder="Permission Name">
                                <!--<div class="invalid-feedback">
                                    <span v-if="!$v.permission_form.name.required">Permission is Required</span>
                                    <span v-if="!$v.permission_form.name.minLength">Permission length must be 2 Characters</span>
                                    <span v-if="!$v.permission_form.name.maxLength">Permission length must not be greater then 20 Characters</span>
                                </div>-->
                            </div>
                            <div class="form-group">
                                <label>Permission Slug <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.permission_form.slug.$model" class="form-control" :class="{'is-invalid':submit && $v.permission_form.slug.$error, 'is-valid':!$v.permission_form.slug.$invalid}" placeholder="Permission Slug">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.permission_form.slug.required">Permission slug is Required</span>
                                    <span v-if="!$v.permission_form.slug.minLength">Permission slug length must be 2 Characters</span>
                                    <span v-if="!$v.permission_form.slug.maxLength">Permission slug length must not be greater then 20 Characters</span>
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
    name: "AddOrEdit",
    props : ['permId'],
    data() {
        return {
            permission_form : {
                slug: "",
                name:""
            },
            submit : false
        }
    },
    validations: {
        permission_form : {
            slug: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(20),
            },
            name: {
                // required,
            },


        }
    },
    mounted() {
    },

    watch: {
        'permId'(val) {

            // console.log('aaaaxxx');

            this.id = val;
            this.getRecord(val);
        }
    },

    methods: {
        getRecord(id) {
            axios.get('/api/permission/' + id+"/edit")
                .then((response) => {
                    this.permission_form = response.data.permission;
                });
        },
        permission_submit() {
            this.submit = true
            this.$v.permission_form.$touch();
            if (this.$v.permission_form.$anyError) {
                return;
            }

            if(this.permId){
                axios.put('/api/permission/'+this.id, this.permission_form)
                    .then(response => {
                        $("#PermEdit").modal('hide');
                        this.$emit('closeEvent',"Permission has been updated successfully")
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }else {

                axios.post('/api/permission', this.permission_form)
                    .then(response => {
                        $("#PermAdd").modal('hide');
                        this.$emit('closeEvent', "Permission  has been added successfully")
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }


            this.$v.permission_form.$reset();
            this.permission_form = {
                name: "",
                slug: "",
            },
            this.submit=false
            this.$forceUpdate();


        }
    }
}
</script>

<style scoped>

</style>
