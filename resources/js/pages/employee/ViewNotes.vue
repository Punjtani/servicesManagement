<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Notes</h4>
 <button type="button" class="btn-close" @click="resetForm()"  aria-label="Close"></button>
</div>

            <div class="modal_content_inner">
                <form autocomplete="off" method="get">
                    <div class="modal-body">
                        <div class="form_box" v-if="(attachments.length > 0 || notes) ">
                            <div v-if="notes" class="form-group" >
                                <label>Notes </label>
                                <textarea disabled class="form-control" placeholder="Job Notes" v-model="notes"></textarea>
                            </div>
                            <div v-if="attachments.length > 0" class="row align-items-center no-gutters attachments_box">
                                <label>Attachments</label>
                                <br>
                                <div class="col-6 col-md-3 cols_5" v-for="(item1, index1) in attachments">                            
                                        <div class="img__box">
                                            <img :src="'/'+item1.file_name">
                                        </div>                               
                                </div>                                
                            </div>
                            <br>
                        </div>
                        <div v-if="(attachments.length <= 0) && (notes == null)" class="alert alert-danger">
                            <strong>No Notes or Attachments Available!</strong>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    components: {
    },
    props : ['notesId'],
    data() {
        return {
            notes: "",
            attachments: [],
            notes_data:[],
            id : null,
        }
    },

    mounted() {
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
                    this.notes_data = response.data.notes;
                    if(this.notes_data){
                        this.notes = this.notes_data.notes;
                        this.attachments = this.notes_data.service_job_progres_attatchment;
                    }
                });
        },
        resetForm(){
            this.notes_data = [];
            this.notes = "";
            this.attachments = [];
            this.id = null;
            this.$forceUpdate();
            this.$emit('closeEvent',"Notes modal has been closed")
            $("#ViewNotes").modal('hide');
        }
    }
}
</script>
