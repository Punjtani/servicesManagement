<template>
    <div>
       <div class="dashboard_content_area">
           <h1 class="h4 heading_text">Employee Time Sheet</h1>
           <div class="row mg_top_bot_40_on_desktop row_spacing_2_on_mobiles">
               <div class="col-3 col-sm-3 col-md-3">
                    <a href="" class="btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">Today</a>
               </div>
                <div class="col-3 col-sm-3 col-md-3">
                     <a href="" class="btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">Yesterday</a>
               </div>
                <div class="col-3 col-sm-3 col-md-3">
                     <a href="" class="btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">This Week</a>
               </div>
                <div class="col-3 col-sm-3 col-md-3">
                     <a href="" class="btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">This Month</a>
               </div>
           </div>
             <!-- ================ -->
            <div class="table_area">
                <div  class="table_to_be_used mg_top_1rem table_not_responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="60%">DATE</th>
                                <th  width="40%" class="text-end">TIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>7/15/2021</td>
                                <td class="text-end">5:00</td>
                            </tr>
                            <tr>
                                <td>7/15/2021</td>
                                <td class="text-end">5:00</td>
                            </tr>
                            <tr>
                                <td>7/15/2021</td>
                                <td class="text-end">5:00</td>
                            </tr>
                            <tr>
                                <td>7/15/2021</td>
                                <td class="text-end">5:00</td>
                            </tr>
                            <tr>
                                <td>7/15/2021</td>
                                <td class="text-end">5:00</td>
                            </tr>
                            <tr>
                                <td>7/15/2021</td>
                                <td class="text-end">5:00</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="blue_tr">
                                <td width="60%">Total</td>
                                <td width="40%" class="text-end">34.50</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
    
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
