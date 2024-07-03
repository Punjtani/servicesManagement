<template>
    <div>
       <div class="dashboard_content_area">
           <h1 class="h4 heading_text">Employee Notes</h1>
           <h3 class="h6 font_weight_500">Feb 26, Any Time</h3>
           <p>1 Bedroom Full Paint</p>
           <div class="row align-items-center">
               <div class="col-8 col-sm-10">
                   <p class="mg_bot_10">Sloth Apartments 19634 Rhinestone Dr German Town MD 20876</p>
               </div>
               <div class="col-4 col-sm-2 text-end">
                    <div class="contact_icons">
                        <div class="row text-end">
                            <div class="col-4 text-end">
                                <a href=""><svg class="contact_icon call__icon"><use xlink:href="#call_icon" /></svg></a>
                            </div>
                            <div class="col-4 text-end">
                                <a href=""><svg class="contact_icon chat__icon"><use xlink:href="#chat_icon" /></svg></a>
                            </div>
                                <div class="col-4 text-end">
                                <a href=""><svg class="contact_icon direction__icon"><use xlink:href="#direction_icon" /></svg></a>
                            </div>
                        </div>
                    </div>
               </div>
           </div>
            <div class="employee__notes_area">
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum 
                dolore eu fugiat nulla.</p>
                <p>Pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa 
                qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde 
                omnis iste natus error sit voluptatem</p>
            </div>
            <div class="employee__notes__before_after_pics">
                <div class="row align-items-center mg_bot_2rem">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Before Pics</h6>
                        </div>
                    </div>
                    <div class="col-md-12 cols_in_5">
                        <div class="row align-items-center">
                            <div class="col-md-3 cols_5">
                                    <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                    <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                    <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                    <div class="form-group">
                                        <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mg_bot_2rem">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">After Pics</h6>
                        </div>
                    </div>
                    <div class="col-md-12 cols_in_5">
                        <div class="row align-items-center">
                            <div class="col-md-3 cols_5">
                                    <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                    <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                    <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                    <div class="form-group">
                                        <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <a href="" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 btn__Block">Back to Job Details</a>
                </div>
            </div>
       </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            total_requests: 0,
            has_error: false,
            loading: true,
        }
    },
    mounted() {
        this.getDashboardValues();

    },
    methods: {
        getDashboardValues() {
            axios.get('/api/dashboard-stats')
                .then((response) => {
                    this.total_requests = response.data.reuquests_count;
                    this.loading = false;
                });
        }
    }

}
</script>
