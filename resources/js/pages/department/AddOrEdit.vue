<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Division</h4>
 <button type="button" class="btn-close"  v-on:click="resetFormAndCloseModal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="department_submit" method="post">
            <div class="modal_content_inner">
                    <div class="modal-body">
                        <h4 class="">Division</h4>
                        <div class="alert alert-danger" v-if="deletemsg!==''">
                            <strong>{{ deletemsg }}</strong>
                        </div>
                        <div class="form_box">
                            <div class="form-group">
                                <label>Division <span class="required-staric">*</span></label>
                                <input type="text" v-model.trim = "$v.depatment_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.depatment_form.name.$error, 'is-valid':!$v.depatment_form.name.$invalid}" placeholder="Division Name">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.depatment_form.name.required">Division is Required</span>
                                    <span v-if="!$v.depatment_form.name.minLength">Division length must be 2 Characters</span>
                                    <span v-if="!$v.depatment_form.name.maxLength">Division length must not be greater then 20 Characters</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Payroll Type <span class="required-staric">*</span></label>
                                <multiselect deselectLabel="" v-model.trim="depatment_form.payroll_type"  :select-label="''" :options="payroll_type_options" :searchable="true" :allow-empty="false"
                                    :class="{'is-invalid':$v.depatment_form.payroll_type.$error, 'is-valid':!$v.depatment_form.payroll_type.$invalid}">
                                </multiselect>
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.depatment_form.payroll_type.required">Payroll Type is Required</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Select Service </label>
                                <multiselect
                                              deselectLabel=""
                                              v-model.trim="depatment_form.service"
                                              placeholder="Select Service"
                                              :select-label="''"
                                              :options="servicesList"
                                              :searchable="true"
                                              track-by="category"
                                              label="category"
                                              :allow-empty="false">

                                </multiselect>
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
                                        <button type="button"  v-on:click="resetFormAndCloseModal"
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
import Multiselect from 'vue-multiselect';
export default {
    name: "AddOrEdit",
    components : {
        'Multiselect': Multiselect
    },
    props : ['deptId'],
    data() {
        return {
            depatment_form : {
                name: "",
                payroll_type:"",
                service:{},
            },
            submit:false,
            payroll_type_options: ["Fixed", "Hourly", "Percentage","Salary"],
            servicesList: [],
            deletemsg: "",
        }
    },
    validations: {
        depatment_form : {
            name: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(20),
            },
            payroll_type:{
                required
            }

        }
    },
    mounted() {
        this.getServicsList();
    },

    watch: {
        'deptId'(val) {
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
            axios.get('/api/department/' + id+"/edit")
                .then((response) => {
                    this.depatment_form = response.data.department;
                    // if (this.depatment_form.deparment_service){
                    //     this.depatment_form.service = this.servicesList.find(service => (service.id == 1));
                    // this.depatment_form.service =  this.servicesList.find(data=>(data.id==1))


                    // }

                });
        },
        getServicsList() {
            axios.get('/api/service-type')
                .then((response) => {
                    this.servicesList = response.data.service_types;
                });
        },
        resetFormAndCloseModal() {
            this.resetForm();
            $("#DeptEdit").modal('hide');
            $("#DeptAdd").modal('hide');
            this.$emit('closeEvent',"no-refresh")
        },
        resetForm(){
            this.$v.depatment_form.$reset();
                        this.depatment_form = {
                            name: "",
                            service:{},
                        }
        },
        department_submit() {
            this.submit=true
            this.$v.depatment_form.$touch();
            if (this.$v.depatment_form.$anyError) {
                return;
            }

            if (this.depatment_form.service){
                this.depatment_form.service_id = this.depatment_form.service.id
            }
            else {
                this.depatment_form.service_id = null

            }
            if(this.deptId){
                axios.put('/api/department/'+this.id, this.depatment_form)
                    .then(response => {
                        this.submit=false
                        if(response.data.message){
                        this.deletemsg = response.data.message;
                        setTimeout(() => {
                            this.deletemsg = "";
                        }, 3000);
                    }
                    else
                    {
                        this.submit=false
                        this.$v.depatment_form.$reset();
                        this.depatment_form = {
                            name: "",
                            service:{},
                        }
                        this.$forceUpdate();

                        $("#DeptEdit").modal('hide');
                        this.$emit('closeEvent',"Division has been updated successfully")
                    }
                    })
                    .catch(err => {
                        this.submit=false
                        this.has_error = true;
                    })
            }else {
                this.submit=false
                axios.post('/api/department', this.depatment_form)
                    .then(response => {
                        this.submit=false
                        if(response.data.message){
                        this.deletemsg = response.data.message;
                        setTimeout(() => {
                            this.deletemsg = "";
                        }, 3000);
                    }
                       else
                       {
                        this.$v.depatment_form.$reset();
                        this.depatment_form = {
                            name: "",
                            service:{},
                        }
                         this.submit=false
                        this.$forceUpdate();
                        $("#DeptAdd").modal('hide');
                        this.$emit('closeEvent', "Division  has been added successfully")
                       }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }




            this.submit=false


        }
    },

    created() {
        // this.getServicsList();
    }
}
</script>

<style scoped>

</style>
