<template>
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Job Report</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
</div>

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="form_box">
                            <div class="row ">
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
                            </div>
                            
                            <div class="row align-items-end">
                                <div class="col-sm-12">
                                    <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                                        <strong>{{ successmsg }}</strong>
                                    </div>

                                    <div id="error-alert" class="alert alert-danger" v-if="errormsg!==''">
                                        <strong>{{ errormsg }}</strong>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
            <div class="inline_buttons">
                                <div class="row">
                                    <div class="col-6 col-sm-6">
                                        <button @click = "submitReport()" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600">
                                            Save and Continue
                                        </button>
                                    </div>
                                    <div  class="col-6 col-sm-6">
                                        <button :disabled='disableValue||completed' @click="jobCompleted()"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Job Complete
                                        </button>
                                    </div>
                                    <!--<div class="col-3 col-sm-3">
                                        <button type="button" @click="resetForm()"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Cancel
                                        </button>
                                    </div>-->
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
import Loader from "../../LoaderSpinner";
import UploadFileComponent from "../../../components/UploadFileComponent";

export default {
    
    components : {
        'Multiselect': Multiselect,
        'Loader': Loader,
        UploadFileComponent : UploadFileComponent
    },
    props : ['requestId'],
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
                modifiedBefore:"",
                modifiedAfter:"",
            },
            
        }
            
    },
    watch: {
        'requestId'(val){
            this.id = val;
            this.getRecord();
        }
    },
    mounted() {
        this.loading = false;
    },
    methods : {
        jobCompleted(){
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
                    }, 3000);
                }


            });
        },
        getRecord(){
            axios.get('/api/employee/get-notes-attachment/' + this.id)
            .then((response) => {
                this.reportForm.notes = response.data.progress.notes
                this.completed = response.data.completed
                response.data.progress.service_job_progres_attatchment.forEach((file) => {
                    this.Existingfiles.push(file.file_name);
                });
                response.data.progress.service_job_progress_after_attatchment.forEach((file) => {
                    this.AfterExistingfiles.push(file.file_name);
                });

                // console.log(this.completed);
                this.jobProgress();
            })
        },
        jobProgress(){
            axios.get('/api/employee/job-progress/' + this.id)
            .then((response) => {
                this.disableValue = response.data.disabled;
                // console.log('ok',this.disableValue);
            })
        },
        fileUploaded(filenames){
            this.reportForm.modifiedBefore = "modified";
            this.reportForm.uploadedImagesPath = filenames;
        },
        AfterfileUploaded(filenames){
            this.reportForm.modifiedAfter = "modified";
            this.reportForm.afterUploadedImagesPath = filenames;
        },
        submitReport(){
            // console.log(this.reportForm);
            axios.put('/api/employee/progress_report/' + this.id, this.reportForm)
            .then((response) => {
                this.successmsg = "Job Information have been saved";
                setTimeout(() => {
                    this.successmsg = "";
                    this.resetForm();
                }, 5000);
            })
        },
        resetForm(){   
            this.id = null;
            this.$refs.uploadFile.mergedFiles=null;
            this.$refs.afterUploadFile.mergedFiles=null;
            this.Existingfiles=null;
            this.AfterExistingfiles=null;
            this.$forceUpdate();
            this.$emit('closeEvent',"Report modal has been closed")
            $("#report").modal('hide');
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

</style>
