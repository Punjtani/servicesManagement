<template>
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Crew</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="resetForm()"></button>
</div>
<form autocomplete="off" @submit.prevent="crew_submit" method="post">
            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="form_box">
                            <div class="form-group">
                                <label>Crew Name</label>
                                <input type="text" v-model.trim = "$v.crew_form.name.$model" class="form-control" :class="{'is-invalid':submit && $v.crew_form.name.$error, 'is-valid':!$v.crew_form.name.$invalid}" placeholder="Crew Name">
                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.crew_form.name.required">Crew is Required</span>
                                    <span v-if="!$v.crew_form.name.minLength">Crew length must be 2 Characters</span>
                                    <span v-if="!$v.crew_form.name.maxLength">Crew length must not be greater then 20 Characters</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Select Division</label>
                                <select v-model.trim="$v.crew_form.department_id.$model" @change = "getEmployee($event)"
                                        class="form-select" :class="{'is-invalid':submit && $v.crew_form.department_id.$error, 'is-valid':!$v.crew_form.department_id.$invalid}"  aria-label="Department Id">
                                    <option v-for="(item1, index1) in departments"
                                            :key="index1" :value="item1.id">
                                        {{ item1.name }}
                                    </option>
                                </select>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.crew_form.department_id.required">Division is Required</span>
                                </div>
                            </div>

                            <div  class="no_more_tables table_to_be_used mg_top_1rem" v-if="crew_form.department_id"> 
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th class="text-center">Percentage</th>
                                            <th class="text-center">Lead</th>
                                            <th class="text_end_only_desktop">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, index) in employee_list">
                                        <td data-title="Member">
                                            <select v-model.trim="item.employee_id" class="form-select"   aria-label="Department Id" @change = "updateEmployeeList()"
                                            :class="{'is-invalid':submit && item.employee_id== '', 'is-valid':item.employee_id !== ''}">
                                                <option v-for="(item1, index1) in employees" :key="index1" :value="item1.id" :disabled = "selectedEmployees.includes(item1.id)">
                                                    {{ item1.user.first_name }} {{ item1.user.middle_name }} {{ item1.user.last_name }}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback" v-if="submit">
                                                <span v-if="item.employee_id == ''">Employee is Required</span>
                                            </div>
                                        </td>
                                        <td  data-title="Percentage" class="text-center">
                                            <input type="number" v-model.trim="item.percentage" class="form-control text-center form_control_small" placeholder="Percentage"
                                            :class="{'is-invalid':submit && Number(item.percentage)=== 0, 'is-valid':Number(item.percentage) !== 0}">
                                            <div class="invalid-feedback" v-if="submit">
                                                <span v-if="Number(item.percentage) === 0">Percentage should be greater than 0</span>
                                            </div>
                                        </td>
                                        <td data-title="Lead" class="text-center">
                                            <span >
                                            <input type="checkbox" v-model.trim="item.lead" data-ng-model="example.check" v-bind:true-value="1"  v-bind:false-value="0" />
                                            </span>
                                        </td>
                                        <td data-title="Action" class="text-end">
                                            <svg class="cross__button text_end_only_desktop text-md-end" @click="removeMember(item, index)"><use xlink:href="#cancelcross" /></svg>
                                            <!--<button type="button" class="cross__button text_end_only_desktop text-md-end btn-close btncclose "  ></button>-->
                                        </td>
                                    </tr>
                                    
                                    </tbody>
                                </table>
                                <div class="inline_buttonss">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="custom_checkbox">
                                            <label>
                                                <input type="checkbox" name="status" :checked="crew_form.crew_activated?'checked':''" v-model="crew_form.crew_activated" value="1" >
                                                <span class="box">Active</span>
                                            </label>
                                            </div>                                            
                                        </div>
                                        <div class="col-sm-6 text-end">
                                            <button type="button" class="btn btn_orange border_radius_5 font_14 text_uppercase font_weight_600" @click="addMember()">
                                                Add New Member
                                            </button>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="success-alert1" class="alert alert-success" style="margin-top: 10px;" v-if="successmsg!==''">
                                <strong>{{ successmsg }}</strong>
                            </div>
                            <div id="errir-alert" class="alert alert-danger" v-if="errormsg!=='' && successmsg == ''">
                                <strong>{{ errormsg }}</strong>
                            </div>
                            <div id="errir-alert" class="alert alert-danger" v-if="submit && leadExist == false && leadErrormsg!='' && successmsg == ''">
                                <strong>{{ leadErrormsg }}</strong>
                            </div>
                            <div id="errir-alert" class="alert alert-danger" v-if="submit && leadExist == true && leadErrormsg!='' && successmsg == ''">
                                <strong>{{ leadErrormsg }}</strong>
                            </div>
<hr>
                           
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
import { required, minLength,maxLength, between } from 'vuelidate/lib/validators'
export default {
    props : ['crewId'],
    data() {
        return {
            departments : [],
            employees: [],
            selectedEmployees: [],
            successmsg: "",
            errormsg: "",
            leadErrormsg:"",
            leadExist: false,
            id :"",
            employee_list: [],
            crew_form : {
                name: "",
                department_id: "",
                crew_activated: "",
            },
            submit:false
        }
    },
    validations: {
        crew_form : {
            name: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(20),
            },
            department_id: {
                required,
            },

        }
    },
    mounted() {
        this.addMember();
        this.addMember();
        this.getDepartments();
    },

    watch: {
        'crewId'(val) {
            this.id = val;
            this.getRecord(val);
        }
    },
    methods: {
        getRecord(id) {
            if(id !== null && id !== ""){
            axios.get('/api/crew/' + id+"/edit")
                .then((response) => {
                    this.crew_form = response.data.crew;
                    //get respected department employees
                    if(this.crew_form ){
                        var department = this.departments.find(dept=>(dept.id == this.crew_form.department_id))
                        this.employees = department.employee;
                        //get crew member
                        this.getMemberList();
                    }
                });
            }
        },
        crew_submit() {
            this.submit=true;
            this.leadExist=false;
            this.$v.crew_form.$touch();
            if (this.$v.crew_form.$anyError) {
                return;
            }
            //check out list

            for (let data of this.employee_list){
                if(data.lead === 1){
                    if(this.leadExist == true){
                        this.leadErrormsg = "Please select only one lead for crew";
                        setTimeout(() => {
                            this.leadErrormsg = "";
                        }, 3000);
                        return;
                    }else{
                        this.leadExist = true;
                    }
                }
                
                if(data.employee_id === "" || Number(data.percentage) === 0){
                    return;
                }
            }
                
            // console.log("reach here");
            // to check if no lead exist
            var sub = new Promise((resolve, reject) => {
                if(this.leadExist == false){
                    this.leadErrormsg = "Please select a lead for crew";
                    setTimeout(() => {
                        this.leadErrormsg = "";
                    }, 3000);
                    return;
                }else{
                    resolve();
                }
            });
                sub.then(()=>{
                    this.crew_form.employee_list = this.employee_list;
                    if(this.id){
                        axios.put('/api/crew/'+this.id, this.crew_form)
                            .then(response => {
                                $("#employee-crew-members-edit-modal").modal('hide');
                                this.$emit('closeEvent',"Crew has been updated successfully")
                                this.resetForm();
                            })
                            .catch(err => {
                                this.has_error = true;
                            })
                    }else {

                        axios.post('/api/crew', this.crew_form)
                            .then(response => {
                                $("#employee-crew-members-add-modal").modal('hide');
                                this.$emit('closeEvent', "Crew  has been added successfully")
                                this.resetForm();
                            })
                            .catch(err => {
                                this.has_error = true;
                            })
                    }
                });

        },
        getDepartments(){
            axios.get('/api/crew/create')
                .then((response) => {
                    this.departments = response.data.departments
                });
        },
        getEmployee(depart){
            var department = this.departments.find(dept=>(dept.id == depart.target.value))
            this.employees = department.employee;
        },
        addMember(){
            var member = {employee_id:"", lead:0, percentage:0}
            this.employee_list.push(member);
        },
        removeMember(item, index){
            if(this.employee_list.length> 2){
                this.employee_list.splice(index, 1);
                let index1 = this.selectedEmployees.findIndex(data => (data == item.employee_id));
                this.selectedEmployees.splice(index1, 1);
            }else{
                this.errormsg = "At least two members are required to make a Crew";
                setTimeout(() => {
                    this.errormsg = "";
                }, 3000);
            }
        },
        updateEmployeeList(){
            this.selectedEmployees = [];
            this.employee_list.forEach(data=>{
                if(data.employee_id !== ""){
                    this.selectedEmployees.push(data.employee_id);
                }
            })            
        },
        getMemberList(){
            this.employee_list = [];
            this.crew_form.employe.forEach(data =>{
                let item = {id:data.pivot.id, employee_id : data.pivot.employee_id, percentage: data.pivot.percentage, lead: data.pivot.is_lead};
                this.employee_list.push({...item});
            });
            this.updateEmployeeList();
        },
        resetForm(){
            this.$v.crew_form.$reset();
            this.crew_form = {
                name: "",
                department_id: "",
                employee_list:[],
            }
            this.leadExist = false;
            this.submit=false
            this.errormsg = "";
            this.successmsg = "";
            this.leadErrormsg = "";
            this.selectedEmployees = [];
            this.addMember();
            this.addMember();
            this.$forceUpdate();
            this.$emit('closeEvent');

        }
    }
}
</script>

<style scoped>

</style>

