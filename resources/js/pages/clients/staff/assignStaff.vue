<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Assign Staff to {{Property.title}}</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="staff_submit" method="post">

            <div class="modal_content_inner">
                    <div class="modal-bodys">
                        
                        <div class="form_box">
                            <div class="form-group">
                                <label>Select Staff</label>
                                <select name="staff" v-model.trim = "$v.staff_form.staff.$model" :class="{'is-invalid':submit && $v.staff_form.staff.$error, 'is-valid':!$v.staff_form.staff.$invalid}" id="staff" class="form-select">
                                    <option  v-for="staff in staffList" :key="staff.id" :value="staff.id">{{staff.user.first_name}} {{staff.user.middle_name}} {{staff.user.last_name}}</option>
                                </select>
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.staff_form.staff.required">Staff is Required</span>
                                </div>
                            </div>
                            <div class="inline_buttons">
                                <div class="row">
                                    <div class="col-6 col-sm-6">
                                        <button type="submit" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600">
                                            Save
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-6 text-end">
                                        <button type="button" v-on:click="resetForm" data-bs-dismiss="modal" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600" aria-label="Close">Cancel</button> 
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <div class="inline_buttonss">
                                    <div class="col-sm-12 text-end">
                                        <button @click="addStaff()" type="button" data-bs-toggle="modal" data-bs-target="#AddOrEdit"
                                            class="btn_big_medium btn btn_medium btn_orange mg_top_5 border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">
                                            Add New
                                        </button>
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
export default {
    name: "view_manager",
    props: ['Property'],
    data() {
        return {
            staff_form : {
                staff: "",
            },
            staffList:[],
            propertyId:"",
            submit: false,
        }
    },
    validations: {
        staff_form : {
            staff: {
                required,
            },
        }
    },
    watch: {
        'Property'(val) {
            this.getRecord(val.client_id);
            
        }
    },
    methods: {
        staff_submit() {
            this.submit = true;
            this.$v.staff_form.$touch();
            if (this.$v.staff_form.$anyError) {
                return;
            }
            axios.put('/api/staff/assign/'+this.Property.id, this.staff_form)
                .then(response => {
                    this.$emit('closeEvent',"Staff has been assign successfully")
                    this.resetForm();
                })
                .catch(err => {
                    this.has_error = true;
                })
            $("#ManagerView").modal('hide');
        },
        addStaff(){
            this.$emit('addStaff')
            this.resetForm();
        },
        getRecord(id) {
            this.loading = true;
            axios.get('/api/staff/'+id)
                .then((response) => {
                    this.staffList = response.data.staff;
                });
        },
        resetForm(){
            this.$v.staff_form.$reset();
            this.staff_form.staff = "";
            this.submit=false;
            this.$forceUpdate();
            // console.log('thh', this.propertyId);
        }
    }
}
</script>
