<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Cancel Job</h4>
 <button type="button" class="btn-close" @click="resetForm()"  aria-label="Close"></button>
</div>
<form autocomplete="off"  @submit.prevent="submitNotes" method="put">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <h4 class=""></h4>
                        <div class="form_box">
                            <div class="form-group" >
                                <label>Reason for cancellation</label>
                                <textarea class="form-control" placeholder="Cancel Job Notes" v-model="$v.cancelNotes.$model" :class="{'is-invalid':$v.cancelNotes.$error, 'is-valid':!$v.cancelNotes.$invalid}"></textarea>
                                <div class="invalid-feedback"> <span v-if="!$v.cancelNotes.required">Required</span> </div>
                            </div>
                            <br>
                        </div>
                       
                    </div>
                </div>
                <div class="modal-footer">
                <div class="inline_buttons">
                                <div class="row">
                                    <div class="col-6 col-sm-6">
                                        <button type="submit" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600">
                                            Save
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-6">
                                        <button type="button" @click="resetForm()"
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
import roleData from '../../data.js';
import {required} from 'vuelidate/lib/validators';
export default {
    components: {
    },
    props : ['jobId'],
    data() {
        return {
            cancelNotes: "",
            id : null,
            roleData:roleData,
            role: "",
            submit : false,
            loading:false,
        }
    },
    validations: {            
        cancelNotes: {
            required,
        },
    },

    mounted() {
        this.role = localStorage.getItem("role");
    },

    watch: {
    },

    methods: {
        submitNotes() {      
            this.submit = true;
            this.loading=true;
            this.$v.cancelNotes.$touch();
            if (this.$v.cancelNotes.$anyError) {
                this.loading=false;
                return;
            }
            axios.put('/api/cancel-job-notes/' + this.jobId , {'cancelNotes' : this.cancelNotes })
                .then((response) => {
                    this.submit = false;
                    this.loading=false;
                    this.$emit('closeEvent', 1);
                    $("#CancelJobPopup").modal('hide');                    
                });
        },
        resetForm(){
            $("#CancelJobPopup").modal('hide');
        }
    }
}
</script>
