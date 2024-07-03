<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Edit Job Timesheet</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" @click="resetForm()" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="assign_submit" method="post">
            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="form_box">

                            <div class="form-group">
                                <label>Start Date <span class="required-staric">*</span></label>
                                <!--<datepicker
                                    placeholder = "Select date"
                                    format="MM/dd/yyyy"
                                    input-class="form-control"
                                    :disabledDates="disabledDates"
                                    v-model.trim="$v.assign_form.start_date.$model"
                                    :class="{'is-invalid':submit && $v.assign_form.start_date.$error, 'is-valid':!$v.assign_form.start_date.$invalid}">
                                </datepicker>-->
                                <datepicker
                                    placeholder = "Select date"
                                    format="MM-DD-YYYY"
                                    v-model.trim="$v.assign_form.start_date.$model"
                                    :clearable="false"
                                    :disabled-date="(date) => date <= new Date()"
                                    :disabled-calendar-changer="(date) => date <= new Date()"
                                    value-type="YYYY-MM-DD"
                                    :input-class="{'is-invalid':$v.assign_form.start_date.$error, 'is-valid':!$v.assign_form.start_date.$invalid,'form-control':true}"
                                    >
                                </datepicker>


                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.start_date.required">Start Date is Required</span>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label>Start Time <span class="required-staric">*</span></label>

                                <input type="time" input-class="form-control" v-model.trim="$v.assign_form.start_time.$model"
                                class="form-select" :class="{'is-invalid':submit && $v.assign_form.start_time.$error, 'is-valid':!$v.assign_form.start_time.$invalid}">

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.start_time.required">Time is Required</span>
                                </div>
                            </div>


                            <div class="form-group" >
                                <label>End Date<span class="required-staric">*</span></label>
                                <!--<datepicker
                                    placeholder = "Select date"
                                    format="MM/dd/yyyy"
                                    input-class="form-control"
                                    :disabledDates="disabledDates"
                                    v-model.trim="$v.assign_form.end_date.$model"
                                    :class="{'is-invalid':submit && $v.assign_form.end_date.$error, 'is-valid':!$v.assign_form.end_date.$invalid}">
                                </datepicker>-->
                                <datepicker
                                    placeholder = "Select date"
                                    format="MM-DD-YYYY"
                                    v-model.trim="$v.assign_form.end_date.$model"
                                    :clearable="false"
                                    :disabled-date="(date) => date <= new Date()"
                                    :disabled-calendar-changer="(date) => date <= new Date()"
                                    value-type="YYYY-MM-DD"
                                    :input-class="{'is-invalid':$v.assign_form.end_date.$error, 'is-valid':!$v.assign_form.end_date.$invalid,'form-control':true}"
                                    >
                                </datepicker>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.end_date.required">Start Date is Required</span>
                                </div>
                            </div>

                             <div class="form-group" >
                                <label>End Time <span class="required-staric">*</span></label>

                                <input type="time" input-class="form-control" v-model.trim="$v.assign_form.end_time.$model"
                                class="form-select" :class="{'is-invalid':submit && $v.assign_form.end_time.$error, 'is-valid':!$v.assign_form.end_time.$invalid}">

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.end_time.required">Time is Required</span>
                                </div>
                            </div>


                            <!--<div class="form-group" >
                                <label>Notes </label>
                                <textarea class="form-control" placeholder="Job Notes" v-model.trim="$v.assign_form.notes.$model"></textarea>
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
                                        <button type="button" data-bs-dismiss="modal" @click="resetForm()"
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
import { required, minLength,maxLength, between, requiredIf  } from 'vuelidate/lib/validators';
// import Datepicker from 'vuejs-datepicker';
import roleData from '../../data.js';
import moment from 'moment';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
export default {
    name: "Assign",
    components: {
        'Datepicker': Datepicker,
    },
    props : ['sheetId'],
    data() {
        return {

            timeRecords : "",
            role: "",
            roleData : roleData,
            assign_form : {
                start_date: "",
                end_date: "",
                start_time: "",
                end_time: "",
                // notes: "",
            },
            disabledDates: {
                to: new Date(Date.now() - 8640000)
            },
            submit: false
        }
    },
    validations: {
        assign_form : {
            start_date: {
                required: true

            },
            start_time: {
                required: true

            },
            end_date: {
                required: true
            },
            end_time: {
                required: true
            },
            notes:{

            }

        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
      //  this.getDepartments();
    },

    watch: {
        'sheetId'(val) {
            if(val){
                // console.log('bbbbbbbbb',val);
                this.id = val;
                this.getTimeSheets();
            }
        }
    },

    methods: {
        getTimeSheets() {
            axios.get('/api/employee/edit-time-sheet/' + this.id)
                .then((response) => {
                    this.timeRecords = response.data.timeRecord;
                    // console.log(this.timeRecords);

                    this.assign_form.start_date = this.timeRecords.start_time.split(' ')[0];
                    this.assign_form.start_time = this.timeRecords.start_time.split(' ')[1];
                    this.assign_form.end_date = this.timeRecords.end_time.split(' ')[0];
                    this.assign_form.end_time = this.timeRecords.end_time.split(' ')[1];
                    // this.assign_form.notes = this.timeRecords.notes;
                    this.loading=false;
                });
        },

        assign_submit() {
            this.submit = true
            this.loading=true;
            this.$v.assign_form.$touch();
            if (this.$v.assign_form.$anyError) {
                this.loading=false;
                return;
            }
            // console.log(this.assign_form);
            let start_date = "";
            let end_date = "";
            var start_date_unformatted = new Date(this.assign_form.start_date);
            var end_date_unformatted = new Date(this.assign_form.end_date);
            start_date= moment(start_date_unformatted).format('YYYY-MM-DD');
            end_date= moment(end_date_unformatted).format('YYYY-MM-DD');
            this.assign_form.start_date = start_date + ' '+this.assign_form.start_time;
            this.assign_form.end_date = end_date + ' '+this.assign_form.end_time;
                axios.put('/api/employee/update-time-sheet/' + this.id, this.assign_form)
                    .then(response => {
                        this.loading=false;
                        this.resetForm();

                        this.$emit('closeEvent',"Timesheet has been assigned");

                    })
                    .catch(err => {
                        this.loading=false;
                        this.has_error = true;
                    })
                    $("#EditTimeSheet").modal('hide');
        },

        resetForm(){
            this.$v.assign_form.$reset();
            this.assign_form = {
                start_time: "",
                end_time: "",
                start_date: "",
                end_date: "",
                // notes:"",
            }

            this.submit=false
            this.$forceUpdate();
            this.$emit('closeEvent',"");
        }
    }
}
</script>

<style scoped>
    input[type='time']::-webkit-calendar-picker-indicator {
        opacity: 0;
        position: absolute;
        width: 95%;
    }
</style>
