<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Notes</h4>
                <button type="button" class="btn-close" @click="resetForm()"  aria-label="Close"></button>
            </div>
            <form autocomplete="off" method="get">
            <div class="modal_content_inner">
              
                    <div class="modal-body">
                        
                        <div class="form_box" v-if="(before_attachments.length > 0||after_attachments.length > 0 || notes) ">
                            <div v-if="notes && (role == roleData.super_admin || role == roleData.admin)" class="form-group" >
                                <label>Notes </label>
                                <textarea disabled class="form-control" placeholder="Job Notes" v-model="notes"></textarea>
                            </div>
                            <div v-if="before_attachments.length > 0" class="row align-items-center no-gutters attachments_box">
                                <label>Before Attachments</label>
                                <br>
                                <div class="col-6 col-md-3 cols_5" v-for="(item1, index1) in before_attachments">                            
                                        <div class="img__box">
                                            <img @click="onImageClick(index1,before_attachments, 'Before Attachments')" :src="'/'+item1.file_name">
                                        </div>                               
                                </div>                                
                            </div>
                            <div v-if="after_attachments.length > 0" class="row align-items-center no-gutters attachments_box">
                                <label>After Attachments</label>
                                <br>
                                <div class="col-6 col-md-3 cols_5" v-for="(item1, index1) in after_attachments">                            
                                        <div class="img__box">
                                            <img @click="onImageClick(index1,after_attachments,'After Attachments')" :src="'/'+item1.file_name">
                                        </div>                               
                                </div>                                
                            </div>
                            <br>
                        </div>
                        <div v-if="(before_attachments.length <= 0) && (after_attachments.length <= 0) && (notes == null)" class="alert alert-danger">
                            <strong>No Notes or Attachments Available!</strong>
                        </div>
                    </div>
                    <div class="modal fade custom_base_model custom_base_model_large" id="imagePopup" tabindex="-1" aria-labelledby="imagePopup" aria-hidden="true" data-bs-backdrop="static">
                        <ImagePopup @closeEvent="clearPopupImagePath"  :currentIndex="currentIndex" :images= "images" :popupImagePath="popupImagePath" :headingText="heading_text"></ImagePopup>
                    </div>
                </div>
                </form>
           
        </div>
    </div>
</template>

<script>
import axios from "axios";
import roleData from '../../data.js';
import ImagePopup from "../../components/EnlargeImage";
export default {
    components: {
        'ImagePopup': ImagePopup,
    },
    props : ['notesId'],
    data() {
        return {
            notes: "",
            before_attachments: [],
            after_attachments:[],
            notes_data:[],
            id : null,
            roleData:roleData,
            role: "",
            popupImagePath :"",
            currentIndex:"",
            images:"",
            heading_text:"",
        }
    },

    mounted() {
        this.role = localStorage.getItem("role");
    },

    watch: {
        'notesId'(val) {
            this.id = val;
            this.getNotesAttachments();
        }
    },

    methods: {
        getNotesAttachments() {
            axios.get('/api/employee/get-notes-attachment/' + this.id)
                .then((response) => {
                    this.notes_data = response.data.progress;
                    if(this.notes_data){
                        this.notes = this.notes_data.notes;
                        this.before_attachments = this.notes_data.service_job_progres_attatchment;
                        this.after_attachments = this.notes_data.service_job_progress_after_attatchment;
                    }
                });
        },
        resetForm(){
            this.notes_data = [];
            this.notes = "";
            this.before_attachments = [];
            this.after_attachments = [];
            this.id = null;
            this.$forceUpdate();
            this.$emit('closeEvent',"Notes modal has been closed")
            $("#ViewNotes").modal('hide');
        },
        onImageClick(index,images,text){
            this.currentIndex = index;
            this.images = images;
            this.popupImagePath = this.images[this.currentIndex].file_name;
            this.heading_text = text;
            $("#imagePopup").modal('show');
            $("#imagePopup").focus();
        },
        clearPopupImagePath(){
            this.popupImagePath = "";
        }
    }
}
</script>
