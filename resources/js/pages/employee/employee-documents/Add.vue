<template>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">{{type}} Employee Document</h4>
                <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form autocomplete="off" @submit.prevent="documentSubmit" method="post">
                <div class="modal_content_inner">

                        <div class="modal-bodys">

                            <div class="form_box">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Document Name</label>
                                            <input type="text" v-model.trim="$v.document_form.title.$model" class="form-control"
                                                :class="{'is-invalid':submit && $v.document_form.title.$error, 'is-valid':!$v.document_form.title.$invalid}"
                                                placeholder="Document Name">

                                            <div class="invalid-feedback" v-if="submit">
                                                <span v-if="!$v.document_form.title.required">Required</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Document expiration date</label>
                                            <!--<input type="date" v-model.trim="$v.document_form.expiry.$model" class="form-control"
                                                :class="{'is-invalid':submit && $v.document_form.expiry.$error, 'is-valid':!$v.document_form.expiry.$invalid}"
                                                placeholder="Document Expiration Date">
                                            <div class="invalid-feedback" v-if="submit">
                                                <span v-if="!$v.document_form.expiry.required">Required</span>
                                            </div>-->
                                        <datepicker
                                            placeholder = "Expiration Date"
                                            format="MM-DD-YYYY"
                                            v-model.trim="$v.document_form.expiry.$model"
                                            :clearable="false"
                                            value-type="YYYY-MM-DD"
                                            :input-class="{'is-invalid':$v.document_form.expiry.$error, 'is-valid':!$v.document_form.expiry.$invalid,'form-control':true}"
                                            >
                                        </datepicker>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Document ID</label>
                                            <input type="text" v-model.trim="$v.document_form.id.$model" class="form-control"
                                                :class="{'is-invalid':submit && $v.document_form.id.$error, 'is-valid':!$v.document_form.id.$invalid}"
                                                placeholder="Document ID">
                                            <div class="invalid-feedback" v-if="submit">
                                                <span v-if="!$v.document_form.id.required">Required</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--<UploadFileComponent ref="uploadFile" :accept="acceptTypes" :files="Existingfiles" :multiple="false" @uploaded="fileUploaded" api="/api/file-upload"></UploadFileComponent>-->
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <div class="file_upload">
                                                <input class="form-control" :accept="acceptTypes" type="file" style="opacity: 0;" @change="upload"
                                                    multiple="false" name="uploaded_file" ref="uploaded_file"/>
                                                <img src="/images/image_placeholder_upload_File.png" alt="image_placeholder_upload_File W-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group" v-if="document_form.filePath != '' ">
                                        <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Attachments</h6>
                                    </div>
                                    <div class="col-6 col-md-3 cols_5" v-if="document_form.filePath != '' && getFileExtention(document_form.filePath) != 'pdf'">
                                        <div class="img__box">
                                            <img :src="'/'+document_form.filePath">
                                            <span class="del_icon" @click="deleteImage()">
                                            <svg class="close_sidebar_icon" style="color: white"><use xlink:href="#remove"/></svg></span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 cols_5" v-if="document_form.filePath != '' && getFileExtention(document_form.filePath) == 'pdf'">
                                        <div class="img__box">
                                            <img src="/images/pdf_placeholder.png" alt="pdf_placeholder_upload_File" height="150px" width="150px">
                                            <span class="del_icon" @click="deleteImage()">
                                            <svg class="close_sidebar_icon" style="color: white"><use xlink:href="#remove"/></svg></span>
                                        </div>
                                    </div>
                                </div>
                                <div id="error-alert" class="alert alert-danger" v-if="errormsg!==''">
                                    <strong>{{ errormsg }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn_medium btn_orange mg_top_14 border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
                    </div>
                    </form>

            </div>
        </div>
</template>

<script>
import axios from "axios";
import {required} from 'vuelidate/lib/validators'
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
export default {
    name: "Add",
    props:['documentId','type'],
    components : {
        'Datepicker': Datepicker,
    },
    data() {
        return {
            acceptTypes: ['image/png','image/jpeg','application/pdf'],
            has_error: false,
            loading: false,
            successmsg: "",
            errormsg:"",
            deletemsg:"",
            deleteId:"",
            id: this.$route.params.id,
            document_form:{
                title: "",
                expiry: "",
                id: "",
                filePath:"",
            },
            filenames:null,
            submit: false,
        }
    },
    validations: {
        document_form: {
            title: {
                required,
            },
            expiry: {
                // required,
            },
            id:{
                required,
            }
        }
    },
    watch: {
        'documentId'(val) {
            if (val) {
                this.getRecord();
            }
        }
    },
    mounted() {
    },
    methods: {
        documentSubmit(){
            this.submit=true;
            this.loading=true;
            this.$v.document_form.$touch();
            if (this.$v.document_form.$anyError) {
                this.loading=false;
                return;
            }
            if(this.document_form.filePath == ''){
                this.loading = false;
                this.$emit('closeEvent',"Please attach a doument",'error');
                $("#addDocument").modal('hide');
                return;
            }
            if(this.documentId){
                axios.post('/api/update-employee-document/'+this.documentId, this.document_form)
                .then(response => {
                    if(response.data.error){
                        this.$emit('closeEvent',response.data.error,'error');
                    }else{
                        this.$emit('closeEvent',"Document has been updated successfully");
                    }
                    this.resetForm();
                })
                .catch(err => {
                    this.has_error = true;
                })
            }
            else{
                axios.post('/api/employee-documents/'+this.id, this.document_form)
                    .then(response => {
                        if(response.data.error){
                            this.$emit('closeEvent',response.data.error,'error');
                        }else{
                            this.$emit('closeEvent',"Document has been added successfully");
                        }
                        this.resetForm();
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }
            $("#addDocument").modal('hide');

        },
        upload(){
            this.filenames = this.$refs.uploaded_file.files
            const filesArray = Array.from(this.filenames)
            var invalid = false;
            filesArray.forEach(data => {
                if(!this.acceptTypes.includes(data.type)){
                    invalid = true;
                }
            })
            if(invalid){
                this.errormsg = "File type is invalid";
                return;
            }
            let formData = new FormData();
            for (let i = 0; i < this.filenames.length; i++) {
                formData.append('files[]', this.filenames[i])
            }
            axios.post('/api/file-upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                this.fileUploaded(response.data.filenames)
            })
            .catch(err => {
                // console.log(err)
            })
        },
        fileUploaded(filenames){
            this.document_form.filePath = filenames[0];
        },
        resetForm(){
            this.$v.document_form.$reset();
            this.document_form = {
                title: "",
                expiry: "",
                id:"",
                filePath:"",
            };
            this.$refs.uploaded_file.files=null;
            this.$forceUpdate();
            this.$emit('popupCloseEvent');
        },
        deleteImage(){
            this.document_form.filePath = "";
            this.$refs.uploaded_file.files=null;
        },
        getFileExtention(filename){
            return  filename.substring(filename.lastIndexOf(".")+1);
        },
        getRecord(){
        axios.get('/api/edit-employee-document/'+this.documentId)
                .then((response) => {
                    // console.log(response.data);
                    this.document_form.title = response.data.document.document_title;
                    this.document_form.expiry = response.data.document.expiry_date;
                    this.document_form.id = response.data.document.document_number;
                    this.document_form.filePath = response.data.document.file_name;
                });
        }

    }
}
</script>
<style scoped>
.img__box {
    position: relative
}

.img__box:hover::after {
    content: "";
    position: absolute;
    right: 0;
    top: 0;
    left: 0;
    bottom: 0;
    background-color: #00000040;
}

.img__box .del_icon {
    position: absolute;
    top: 4px;
    right: 4px;
    padding: 0px;
    color: #fff;
    font-weight: bold;
    font-size: 14px;
    z-index: 222;
    cursor: pointer;
}

.img__box .del_icon .close_sidebar_icon {
    width: 20px;
    height: 20px;
    fill: darkred;
    z-index: 222;
}

.img__box:hover .del_icon .close_sidebar_icon {
    fill: darkred;
    width: 22px;
    height: 22px;
}
</style>
