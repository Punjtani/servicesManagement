<template>
   <div>
    <div v-if="loading">
            <loader></loader>
        </div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Complete job</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" @click="resetForm" aria-label="Close"></button>
            </div>
            <div class="modal_content_inner">
                <div class="modal-body">
                    <div class="form_box">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea  placeholder="Job Notes" class="form-control" v-model="reportForm.notes"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 width_50">
                                <div class="form-group">
                                    <label>Before Attachments</label>
                                    <UploadFileComponent  api="/api/file-upload" ref="uploadFile"  :accept="acceptImageTypes" :files="Existingfiles" :multiple="true" @uploaded="fileUploaded" @invalid="invalidFileType"></UploadFileComponent>
                                </div>
                            </div>
                            <div class="col-sm-12 width_50">
                                <div class="form-group">
                                    <label>After Attachments</label>
                                    <UploadFileComponent  api="/api/file-upload" ref="afterUploadFile"  :accept="acceptImageTypes" :files="AfterExistingfiles" :multiple="true" @uploaded="AfterfileUploaded" @invalid="invalidFileType"></UploadFileComponent>
                                </div>
                            </div>
                            <div class="form-group" v-if="showBothDates">
                                <label>Job Start time </label>
                                <datepicker
                                    type="datetime"
                                    placeholder = "Select Job Start Time"
                                    v-model.trim="reportForm.start_time"
                                    :clearable="false"
                                    :disabled-date="(date) => date <= new Date(disable_date_from)"
                                    input-class="form-control"
                                    format="MM-DD-YYYY hh:mm A"
                                    >
                                </datepicker>


                            </div>
                            <div class="form-group" v-if="showBothDates || jobStarted">
                                <label>Job End time </label>

                                <datepicker
                                    type="datetime"
                                    placeholder = "Select Job End Time"
                                    v-model.trim="reportForm.end_time"
                                    :clearable="false"
                                    :disabled-date="(date) => date <= new Date(disable_date_from)"
                                    input-class="form-control"

                                    format="MM-DD-YYYY hh:mm A"
                                    >
                                </datepicker>


                            </div>
                        </div>
                    </div>
                </div>

                </div>
                <div class="modal-footer">
                     <div class="inline_buttons">
                        <div class="row align-items-end ">
                            <div class="col-sm-12">
                                <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                                    <strong>{{ successmsg }}</strong>
                                </div>

                                <div id="error-alert" class="alert alert-danger" v-if="errormsg!==''">
                                    <strong>{{ errormsg }}</strong>
                                </div>
                                <div  class="alert alert-info" v-if="statusMsg!==''">
                                    <strong>{{ statusMsg }}</strong>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-6 col-sm-6">
                                    <button @click = "submitReport()" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600">
                                        Save Report
                                    </button>
                                </div>
                                <div  class="col-6 col-sm-6">
                                    <button v-if="readyTocomplete" :disabled='completed' @click="jobCompleted()"
                                            class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                        Complete
                                    </button>
                                </div>

                            </div>
                        </div>
                     </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from "axios";
import {required, minLength, maxLength, email, url, decimal} from 'vuelidate/lib/validators';
import Multiselect from 'vue-multiselect';
import Loader from "../LoaderSpinner";
import UploadFileComponent from "../../components/UploadFileComponent";
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from "moment";
export default {

    components : {
        'Multiselect': Multiselect,
        'Loader': Loader,
        UploadFileComponent : UploadFileComponent,
        'Datepicker': Datepicker,

    },
    props : ['requestId','assignedToId','completEmployeeData','jobProgressData'],
    data() {
        return {
            id:null,
            loading : true,
            successmsg: "",
            errormsg: "",
            completed:false,
            acceptImageTypes: ['image/png','image/jpeg'],
            Existingfiles : [],
            AfterExistingfiles : [],
            disableValue:true,
            reportForm : {
                notes: "",
                uploadedImagesPath: [],
                afterUploadedImagesPath: [],
                start_time:"",
                end_time:"",
                removedFiles: {
                    "before": [],
                    "after": []
                }
            },
            showBothDates:false,
            jobStarted:false,
            statusMsg:"",
            readyTocomplete:false,
            assigneeId:false,
           disable_date_from:"",

        }

    },
    watch: {
        'requestId'(val){
            this.id = val;
        },
        'assignedToId'(val){
            this.assigneeId = val;
        },

        'completEmployeeData'(val){
            var setDate = new Date(val.progress.date_disable_from);
            this.disable_date_from = setDate
            // // console.log(" this.shcedule_date", setDate)
            this.reportForm.notes = val.progress.notes
            this.completed = val.completed
            val.progress.service_job_progres_attatchment.forEach((file) => {
                this.Existingfiles.push(file.file_name);
            });
            val.progress.service_job_progress_after_attatchment.forEach((file) => {
                this.AfterExistingfiles.push(file.file_name);
            });

        },
        'jobProgressData'(val){
            this.completed = val.completed;
            if (val.status == "not_started"){
                this.statusMsg="Employee has not started the job yet";
                this.showBothDates=true
                this.jobStarted=false
                this.disableValue = true
            }
            else if (val.status == "in_progress"){
                this.statusMsg="Job in progress";
                this.showBothDates=false
                this.jobStarted=true
                this.disableValue = true

            }
            else if (val.status == "ready_to_complete"){
                this.statusMsg="";
                this.showBothDates=false
                this.jobStarted=false
                this.disableValue = false
                this.readyTocomplete = true

            }

        }
    },
    mounted() {
        this.loading = false;

    },
    methods : {
        getRecord(){
            axios.get('/api/employee/get-notes-attachment/' + this.id)
                .then((response) => {
                    this.reportForm.notes = response.data.progress.notes
                    // console.log(this.reportForm.notes);
                    // console.log(response);
                    // alert(this.reportForm.notes);
                    this.completed = response.data.completed
                    this.Existingfiles = [];
                    this.AfterExistingfiles = [];
                    response.data.progress.service_job_progres_attatchment.forEach((file) => {
                        this.Existingfiles.push(file.file_name);
                    });
                    response.data.progress.service_job_progress_after_attatchment.forEach((file) => {
                        this.AfterExistingfiles.push(file.file_name);
                    });
                    this.jobProgress();
                })
        },
        jobProgress(){
            axios.get('/api/employee/employee-job-progress/' + this.id +'/'+ this.assigneeId)
                .then((response) => {
                    this.completed = response.data.completed;
                    if (response.data.status == "not_started"){
                        this.statusMsg="Employee has not started the job yet";
                        this.showBothDates=true
                        this.jobStarted=false
                        this.disableValue = true
                    }
                    else if (response.data.status == "in_progress"){
                        this.statusMsg="Job in progress";
                        this.showBothDates=false
                        this.jobStarted=true
                        this.disableValue = true

                    }
                    else if (response.data.status == "ready_to_complete"){
                        this.statusMsg="";
                        this.showBothDates=false
                        this.jobStarted=false
                        this.disableValue = false
                        this.readyTocomplete = true

                    }
                })
        },
        jobCompleted(event){
            this.loading = true;
            axios.put('/api/employee/job-completed/' + this.id, this.reportForm)
                .then((response) => {

                    if(response.data.timerRunning){
                        this.errormsg = "Please turn off the timer first"
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    }
                    else if(response.data.noProgress){
                        this.errormsg = "Job can not mark complete without any progress"
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    }else{
                        this.successmsg = "Job has been marked completed";
                        setTimeout(() => {
                            this.successmsg = "";
                            this.resetForm();
                            window.location.reload();
                        }, 1000);
                        this.loading = false;
                    }

                });

        },

        fileUploaded(filenames, removedFiles){
            this.reportForm.removedFiles.before = removedFiles;
            this.reportForm.uploadedImagesPath = filenames;
        },
        AfterfileUploaded(filenames, removedFiles){
            this.reportForm.removedFiles.after = removedFiles;
            this.reportForm.afterUploadedImagesPath = filenames;
        },
        submitReport(){
            this.reportForm.start_time = this.reportForm.start_time ? moment(this.reportForm.start_time).format("YYYY-MM-DD H:m") : "";
            this.reportForm.end_time = this.reportForm.end_time ? moment(this.reportForm.end_time).format("YYYY-MM-DD H:m") : "";
            axios.put('/api/employee/employee-progress_report/' + this.id, this.reportForm)
                .then((response) => {
                    if(response.data.error){
                        this.errormsg = response.data.error
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    }
                  else
                  {
                    this.successmsg = "Job Information have been saved";
                    setTimeout(() => {
                        this.successmsg = "";
                    }, 5000);
                    this.getRecord();
                  }

                })
        },
        resetForm(){
            this.Existingfiles = []
            this.AfterExistingfiles = []
            this.id = null;
            this.$refs.uploadFile.mergedFiles=null;
            this.$refs.afterUploadFile.mergedFiles=null;
            this.$forceUpdate();
            this.$emit('closeEvent',"Report modal has been closed")
            $("#completeEmployeeJob").modal('hide');
        },
        invalidFileType(msg){
            this.errormsg=msg;
            setTimeout(() => {
                this.errormsg = "";
            }, 3000);
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
