<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row">
            <div class="col-sm-6"><h1 class="h4 heading_text">Revenue Calculator</h1></div>                         
        </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <form autocomplete="off" @submit.prevent="calculate" method="post">
                <div class="modal-bodys" >
                    <div class="form_box form_box_history">
                    <div class="table_area_head">
                        <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">How much can your annual revenue increase with faster apartment turnovers?</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <!-- ======================= -->
                            <div class="row align-items-center row_spacing_5 ">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="number" class="form-control" placeholder="Est. Average Monthly Rent $">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="number" class="form-control" placeholder="Est. Average Turnover Rate $">
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center row_spacing_5 mg_top_5">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="number" class="form-control" placeholder="No. of Units">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-4">
                                            <button class="btn btn_medium btn_orange border_radius_0 text_uppercase_only_on_desktop font_14_only_on_desktop font_weight_600">
                                                Calculate
                                            </button>
                                        </div>
                                    </div>
                                </div>                                        
                            </div>
                            <!-- ======================= -->
                        </div>
                        <div class="col-md-8">
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
import {required, minLength, maxLength, email, url, decimal} from 'vuelidate/lib/validators'
import Loader from "../../LoaderSpinner";
import TopNavigation from "../../clients/TopNavigation";

export default {
    name: "Edit",
    components : {
        'Loader': Loader,
        'TopNavigation': TopNavigation,
    },
    data() {
        return {
            currentComponent : 'calculator',
            successmsg: "",
            loading: false,
        }
    },
    validations: {},
    mounted() {},
    methods: {
        calculate(){

        }
    }
}
</script>

<style scoped>

</style>
