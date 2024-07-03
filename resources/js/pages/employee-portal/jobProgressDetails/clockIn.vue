<template>
    <div class="modal-dialog">

        <div class="modal-dialog timer_box_outer">
            <div class="modal-content">
                <div class="modal-header">
 <h4 class="modal-title"> {{ time | formatTime }}</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button></div>

                <div class="modal_content_inner">

                    <div class="modal-body timer_box">
                        <div class="inline_buttons">
                            <div class="row">
                                <div class="col-12 col-sm-12">
                                    <button @click="clockIn" type="submit"
                                            class="btn dark_green_btn border_radius_5 font_14 text_uppercase font_weight_600">
                                        Clock In
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import moment from 'moment';
export default {
    props : ['requestId', 'timeNow'],
    data() {
        return {

            id : null,
            time:"",
            clockForm : {
                time:"",
            },
        }
    },


    watch: {
        'requestId'(val){
            this.id = val;
        },
        'timeNow'(val){
            this.time = val;
        }
    },

    methods: {
        clockIn() {
            // console.log(this.id);
            var today = new Date();
            this.clockForm.time = today.toISOString();
            this.clockForm.time = moment(this.clockForm.time).format("MM-DD-YYYY H:m")
            axios.post('/api/employee/requested-service-clock-in/' + this.id, this.clockForm)
                .then((response) => {
                    this.resetForm();
                });
        },
        resetForm(){
            this.id = null;
            this.time = "";
            this.$forceUpdate();
            this.$emit('closeEvent',"Clock in modal has been closed")
            $("#modalClock").modal('hide');
        }
    }
}
</script>
