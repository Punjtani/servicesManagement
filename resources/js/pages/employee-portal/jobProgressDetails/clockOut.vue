
<template>
    <div class="modal-dialog">

        <div class="modal-dialog timer_box_outer" id="">
            <div class="modal-content">
                <div class="modal-header">
 <h4 class="modal-title"> {{ timeNow | formatTime }}</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button></div>

                <div class="modal_content_inner">

                    <div class="modal-body timer_box">
                        <p class="timer_counter_desc text-center">Clock In time
                            <br> {{ start_time }}
                        </p>

                    </div>
                </div>
                <div class="modal-footer">
                <div class="inline_buttons">
                            <div class="row">
                                <div class="col-12 col-sm-12 mg_top_20">
                                    <button @click="clockOut" type="submit"
                                            class="btn dark_red_btn border_radius_5 font_14 text_uppercase font_weight_600">
                                        Clock Out
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
import moment from 'moment';
export default {
    props : ['progressId','startTime', 'timeNow'],
    data() {
        return {

            id : null,
            start_time:"",
            time:"",
            progress_id:null,
            clockForm : {
                time:"",
            },
        }
    },


    watch: {
        'progressId'(val){
            this.progress_id = val;
        },
        'startTime'(val){
            this.start_time = val;
        },
        'timeNow'(val){
            this.time = val;
        }
    },

    methods: {
        clockOut() {
            // console.log(this.id);
            var today = new Date();
            this.clockForm.time = today.toISOString();
            this.clockForm.time = moment(this.clockForm.time).format("MM-DD-YYYY H:m")
            axios.post('/api/employee/requested-service-clock-out/' + this.progress_id, this.clockForm)
                .then((response) => {
                    this.resetForm();
                });
        },
        resetForm(){
            this.id = null;
            this.time = "";
            this.jobProgress = "";
            this.$forceUpdate();
            this.$emit('closeEvent',"Clock out modal has been closed")
            $("#modalClockOut").modal('hide');
        }
    }
}
</script>
