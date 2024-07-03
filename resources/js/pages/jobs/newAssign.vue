<template>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" v-if="role == roleData.admin || role == roleData.super_admin">Assign Job</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" @click="resetForm" aria-label="Close"></button>
            </div>
            <form autocomplete="off" @submit.prevent="assign_submit" method="post">

            <div class="modal_content_inner">

                    <div class="modal-body">
                        <h4 class="" v-if="role == roleData.client">Requested Date</h4>
                        <div class="form_box">

                            <div class="form-group" v-if="role == roleData.admin || role == roleData.super_admin">
                                <div class="row">
                                    <div class="col-6 col-sm-6">
                                        <label>Select Assignee</label>
                                    </div>
                                    <div class="col-6 col-sm-6 text-end"
                                        v-if="(assign_form.assigned_to_id) && (role == roleData.admin || role == roleData.super_admin)">
                                        <a class="table_btn_link font_12 font_weight_700 text_uppercase"
                                            @click="unassignEmployee()">
                                            Unassign
                                        </a>
                                    </div>
                                </div>
                                <select v-model.trim="$v.assign_form.assigned_to_type.$model" placeholder="Select Assignee"
                                    class="form-select"
                                    :class="{ 'is-invalid': submit && $v.assign_form.assigned_to_type.$error, 'is-valid': !$v.assign_form.assigned_to_type.$invalid }"
                                    aria-label="Department Id">
                                    <option value="crew">Crew</option>
                                    <option value="individual">Individual</option>
                                </select>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.assigned_to_type.required">Assignee is Required</span>
                                </div>
                            </div>


                            <div class="form-group"
                                v-if="assign_form.assigned_to_type == 'crew' && (role == roleData.admin || role == roleData.super_admin)">
                                <label>Select Crew</label>
                                <select v-model.trim="$v.assign_form.assigned_to_id.$model" class="form-select"
                                    :class="{ 'is-invalid': submit && $v.assign_form.assigned_to_id.$error, 'is-valid': !$v.assign_form.assigned_to_id.$invalid }"
                                    aria-label="Department Id">
                                    <option v-for="(item1, index1) in crews" :key="index1" :value="item1.id">
                                        {{ item1.name }}
                                    </option>
                                    <option disabled v-if="crews && crews.length <= 0" disabled>No Crew Available</option>
                                </select>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.assigned_to_id.required">Crew is Required</span>
                                </div>
                            </div>


                            <div class="form-group"
                                v-if="assign_form.assigned_to_type == 'individual' && (role == roleData.admin || role == roleData.super_admin)">
                                <label>Select Employee</label>
                                <select v-model.trim="$v.assign_form.assigned_to_id.$model" class="form-select"
                                    :class="{ 'is-invalid': submit && $v.assign_form.assigned_to_id.$error, 'is-valid': !$v.assign_form.assigned_to_id.$invalid }"
                                    aria-label="Department Id">
                                    <option v-for="(item1, index1) in employees" :key="index1" :value="item1.id">
                                        {{ item1.user.first_name }}
                                        {{ item1.user.middle_name }}
                                        {{ item1.user.last_name }}
                                    </option>
                                    <option disabled v-if="employees && employees.length <= 0">No Employee Available
                                    </option>
                                </select>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.assigned_to_id.required">Employee is Required</span>
                                </div>
                            </div>
                            <div class="form-group" v-if="role == roleData.client">
                                <label>Requested For </label>

                                <!--<datepicker
                                    placeholder = "Select date"
                                    format="MM/dd/yyyy"
                                    input-class="form-control"
                                    v-model.trim="$v.assign_form.requested_for.$model"
                                    :class="{'is-invalid':submit && $v.assign_form.requested_for.$error, 'is-valid':!$v.assign_form.requested_for.$invalid}">
                                </datepicker>-->
                                <datepicker placeholder="Select date" format="MM-DD-YYYY"
                                    v-model.trim="$v.assign_form.requested_for.$model" :clearable="false"
                                    value-type="YYYY-MM-DD"
                                    :input-class="{ 'is-invalid': $v.assign_form.requested_for.$error, 'is-valid': !$v.assign_form.requested_for.$invalid, 'form-control': true }">
                                </datepicker>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.requested_for.required">Date is Required</span>
                                </div>
                            </div>

                            <div class="form-group" v-if="role != roleData.client">
                                <label>Schedule Date </label>

                                <!--<datepicker
                                    placeholder = "Select date"
                                    format="MM/dd/yyyy"
                                    input-class="form-control"
                                    v-model.trim="$v.assign_form.scheduled_date.$model"
                                    :class="{'is-invalid':submit && $v.assign_form.scheduled_date.$error, 'is-valid':!$v.assign_form.scheduled_date.$invalid}">
                                </datepicker>-->
                                <datepicker placeholder="Select date" format="MM-DD-YYYY"
                                    v-model.trim="$v.assign_form.scheduled_date.$model" :clearable="false"
                                    value-type="YYYY-MM-DD"
                                    :input-class="{ 'is-invalid': $v.assign_form.scheduled_date.$error, 'is-valid': !$v.assign_form.scheduled_date.$invalid, 'form-control': true }">
                                </datepicker>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.scheduled_date.required">Date is Required</span>
                                </div>
                            </div>

                            <div class="form-group" v-if="assign_form.scheduled_date && role != roleData.client">
                                <label>Schedule Time </label>

                                <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" v-model.trim="assign_form.anytime" v-bind:true-value="1"
                                            v-bind:false-value="0">
                                        <span class="box">Anytime</span>
                                    </label>
                                </div>
                                <!-- <vue-timepicker v-if="assign_form.anytime == null || assign_form.anytime == 0" format="hh:mm a"  v-model="$v.assign_form.scheduled_time.$model"></vue-timepicker> -->
                                <input type="time" v-if="assign_form.anytime == null || assign_form.anytime == 0"
                                    input-class="form-control" v-model.trim="$v.assign_form.scheduled_time.$model"
                                    class="form-select"
                                    :class="{ 'is-invalid': submit && $v.assign_form.scheduled_time.$error, 'is-valid': !$v.assign_form.scheduled_time.$invalid }">

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.assign_form.scheduled_time.required">Time is Required</span>
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
                                        <button type="button" @click="resetForm('no-refresh')" data-bs-dismiss="modal"
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
import { required, minLength, maxLength, between, requiredIf } from 'vuelidate/lib/validators';
// import Datepicker from 'vuejs-datepicker';
import roleData from '../../data.js';
import Datepicker from 'vue2-datepicker';
// Main JS (in UMD format)

// Import the *.vue file (CSS included)
// The *.vue file with CSS included
// import VueTimepicker from 'vue2-timepicker/src/vue-timepicker.vue'
// NOTE: In some cases, it requires additional workarounds in the bundler's config
// Note the `/sfc` suffix here
export default {
    name: "Assign",
    components: {
        'Datepicker': Datepicker,
        // 'VueTimepicker':VueTimepicker
    },
    props: ['parentServiceId', 'jobId', 'service'],

    data() {
        return {
            employees: [],
            crews: [],
            role: "",
            roleData: roleData,
            assign_form: {
                assigned_to_id: "",
                assigned_to_type: "",
                scheduled_time: "",
                scheduled_date: "",
                requested_for: "",
                anytime: 0,
            },
            disabledDates: {
                to: new Date(Date.now() - 8640000)
            },
            submit: false
        }
    },
    validations() {
        if (this.role != this.roleData.client) {
            return {
                assign_form: {
                    assigned_to_id: {
                        required: requiredIf(function (assign_form) {
                            return assign_form.assigned_to_type;
                        }),

                    },
                    assigned_to_type: {
                        // required,
                        // required: requiredIf(function (assign_form){
                        //     return assign_form.assigned_to_id;
                        // }),
                    },
                    scheduled_time: {
                        required: requiredIf(function (assign_form) {
                            return assign_form.scheduled_date && assign_form.anytime == 0;
                        }),
                    },
                    scheduled_date: {
                        // required:requiredIf(function (assign_form){
                        //     return assign_form.scheduled_time;
                        // }),
                    },
                    requested_for: {
                        required: true
                    }

                }
            }

        } else {
            return {
                assign_form: {
                    assigned_to_id: {
                        required: false

                    },
                    assigned_to_type: {
                        required: false
                    },
                    scheduled_time: {
                        required: false
                    },
                    scheduled_date: {
                        required: false
                    },
                    requested_for: {
                        required: true
                    }

                }
            }

        }
    },
        mounted() {

            this.role = localStorage.getItem("role");
            if (this.role == this.roleData.client) {
            }
        },

        watch: {
            'parentServiceId'(val) {
                this.id = val;
                this.getRecord(val);
            },
            'service'(val) {
            },
            'assign_form.anytime'(val) {
                // // console.log("any time",val)
                if (val == 1) {
                    this.assign_form.scheduled_time = ""
                }
            },
        },

        methods: {
            getRecord(id) {
                axios.get('/api/job-assign/' + this.jobId + '/' + this.id)
                    .then((response) => {
                        this.crews = response.data.crews;
                        this.employees = this.sortFunc(response.data.employees);
                        this.assign_form.requested_date = "";
                        // this.employees.sort((a, b) => a.first_name.localeCompare(b.first_name));
                        if (response.data.assignDetail) {
                            this.assign_form.assigned_to_type = response.data.assignDetail.assigned_to_type;
                            this.assign_form.assigned_to_id = response.data.assignDetail.assigned_to_id;
                            this.assign_form.requested_date = response.data.assignDetail.requested_date;
                            this.assign_form.requested_for = response.data.assignDetail.requested_for;
                            this.assign_form.scheduled_time = response.data.assignDetail.scheduled_time;
                         //   this.assign_form.scheduled_time = this.$options.filters.formatTime(response.data.assignDetail.scheduled_time);

                            this.assign_form.scheduled_date = response.data.assignDetail.scheduled_date;
                            this.assign_form.anytime = response.data.assignDetail.anytime;
                        } else {
                            if (this.service) {
                                this.assign_form.requested_date = this.service.requested_date;

                                if (this.service.requested_for) {
                                    this.assign_form.requested_for = this.service.requested_for;
                                }
                                if (this.service.assigned_to_id) {
                                    this.assign_form.assigned_to_type = this.service.assigned_to_type;
                                    this.assign_form.assigned_to_id = this.service.assigned_to_id;
                                }
                                if (this.service.scheduled_date) {
                                    this.assign_form.scheduled_time = this.$options.filters.formatTime(this.service.scheduled_time);

                                    this.assign_form.scheduled_date = this.service.scheduled_date;
                                    this.assign_form.anytime = this.service.anytime;
                                }
                            }

                        }
                    });
            },
            sortFunc: function (array) {
                if (array && array.length > 0) {
                    return array.slice().sort(function (a, b) {
                        return (a.user?.first_name?.toLowerCase() > b.user?.first_name?.toLowerCase()) ? 1 : -1;
                    });
                } else {
                    return array;
                }
            },
            assign_submit() {

                this.submit = true
                this.loading = true;
                this.$v.assign_form.$touch();
                if (this.$v.assign_form.$anyError) {
                    this.loading = false;
                    return;
                }
                if (this.assign_form.assigned_to_id !== null && this.assign_form.assigned_to_id != '') {
                    if (this.assign_form.assigned_to_type == "individual") {
                        try {
                            let employee = this.employees.find(data => (data.id == this.assign_form.assigned_to_id))
                        // this.assign_form.assigned_name= employee.user.first_name + " "+employee.user.middle_name +" "+employee.user.last_name;
                            this.assign_form.assigned_name = `${employee.user.first_name} ${employee.user.middle_name ? employee.user.middle_name : ''} ${employee.user.last_name}`
                        } catch(e){}
                    } else {
                        try {
                            let crew = this.crews.find(data => (data.id == this.assign_form.assigned_to_id))
                            if (crew){
                                this.assign_form.assigned_name = crew.name;
                            }
                        } catch(e){}
                    }
                }
                this.assign_form.parentCategory = this.parentServiceId;
                this.$emit('assign', this.assign_form);

                $("#AssignJob").modal('hide');
                this.resetForm();
            },


            resetForm(msg="") {
                this.$v.assign_form.$reset();
                this.assign_form = {
                    assigned_to_id: "",
                    assigned_to_type: "",
                    scheduled_time: "",
                    scheduled_date: "",
                    requested_date: "",
                    requested_for: "",
                    anytime: "",
                }
                this.submit = false;
                this.$forceUpdate();
                this.$emit('closeEvent',msg);
            },
            unassignEmployee() {
                this.assign_form.assigned_to_type = "";
                this.assign_form.assigned_to_id = "";
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
