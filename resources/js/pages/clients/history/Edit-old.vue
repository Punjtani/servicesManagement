<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
                <div class="row">
                <div class="col-sm-6"><h1 class="h4 heading_text">Clients</h1></div>                
                    <div class="col-sm-6 text-end">
                        <router-link  to="/clients" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Back</router-link>
                    </div>          
                </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="smp__tabs">
                <div v-if="id">
                    <TopNavigation :clientId="id" :currentComponent="currentComponent"></TopNavigation>
                </div>
                <form autocomplete="off" @submit.prevent="supplier_submit" method="post">
                    <div class="modal-bodys" >
                        <div class="form_box form_box_history">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">History</h5>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- ======================= -->
                                    <div class="row align-items-center row_spacing_5 ">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select class="form-select" aria-label="Percentage Pay">
                                                    <option selected>Complex</option>
                                                    <option value="1">Complex</option>
                                                    <option value="2">Complex</option>
                                                    <option value="3">Complex</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select class="form-select" aria-label="Percentage Pay">
                                                    <option selected>Service</option>
                                                    <option value="1">Service</option>
                                                    <option value="2">Service</option>
                                                    <option value="3">Service</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center row_spacing_5 ">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Unit No">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Job No">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center row_spacing_5 ">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="Invoice No">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" placeholder="PO">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center row_spacing_5 mg_top_5">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="mg_bot_12">Scheduled Date</label>
                                                <select class="form-select" aria-label="Percentage Pay">
                                                    <option selected>15/6/2021 to 15/6/2021</option>
                                                    <option value="1">15/6/2021 to 15/6/2021</option>
                                                    <option value="2">15/6/2021 to 15/6/2021</option>
                                                    <option value="3">15/6/2021 to 15/6/2021</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center row_spacing_5 mg_top_5">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="mg_bot_12">Completed</label>
                                                <select class="form-select" aria-label="Percentage Pay">
                                                    <option selected>15/6/2021 to 15/6/2021</option>
                                                    <option value="1">15/6/2021 to 15/6/2021</option>
                                                    <option value="2">15/6/2021 to 15/6/2021</option>
                                                    <option value="3">15/6/2021 to 15/6/2021</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ======================= -->
                                </div>
                                <div class="col-md-8">
                                    <!-- ======================= -->
                                    <div  class="no_more_tables table_to_be_used mg_top_half_rem history_table">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Job Details</th>
                                                    <th>Status</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td data-title="Job Details">Job # 1234 JA 919 Bed Room</td>
                                                    <td data-title="Status">Completed 15 Jul 2021</td>
                                                    <td data-title="Amount">$ 150</td>
                                                </tr>
                                                <tr>
                                                    <td data-title="Job Details">Job # 1234 JA 919 Bed Room</td>
                                                    <td data-title="Status">Completed 15 Jul 2021</td>
                                                    <td data-title="Amount">$ 150</td>
                                                </tr>
                                                <tr>
                                                    <td data-title="Job Details">Job # 1234 JA 919 Bed Room</td>
                                                    <td data-title="Status">Completed 15 Jul 2021</td>
                                                    <td data-title="Amount">$ 150</td>
                                                </tr>
                                                <tr>
                                                    <td data-title="Job Details">Job # 1234 JA 919 Bed Room</td>
                                                    <td data-title="Status">Completed 15 Jul 2021</td>
                                                    <td data-title="Amount">$ 150</td>
                                                </tr>
                                                <tr>
                                                    <td data-title="Job Details">Job # 1234 JA 919 Bed Room</td>
                                                    <td data-title="Status">Completed 15 Jul 2021</td>
                                                    <td data-title="Amount">$ 150</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- ======================= -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
            currentComponent : 'history',
            id:  this.$route.params.id ,
            successmsg: "",
            loading: false,

            supplier_form: {
                id : "",
                details: "",
                long: "",
                lat: "",
            }
        }
    },
    validations: {
        supplier_form: {
            details: {
                required,
                minLength: minLength(2),
            },

            long: "",
            lat: "",
            id: "",

        }
    },
    mounted() {
        if (this.id) {
            this.loading = true;
            this.getRecord(this.id);
        }
    },
    methods: {
        supplier_submit() {


            // console.log('Bilal here');

            this.$v.supplier_form.$touch();
            if (this.$v.supplier_form.$anyError) {
                return;
            }


                axios.put('/api/client-supplier/'+this.supplier_form.id, this.supplier_form)
                    .then(response => {

                        this.successmsg = "Supplier information has been updated";
                        setTimeout(() => {
                            this.successmsg = "";
                        }, 3000);


                    })
                    .catch(err => {
                        this.has_error = true;
                    })
        },
        getRecord(id) {
            axios.get('/api/client-supplier/' + id+"/edit")
                .then((response) => {
                    // console.log(response);
                    this.supplier_form = response.data.supplier;

                    // console.log(this.suplier_form);

                    this.loading = false;
                });
        },


    }
}
</script>

<style scoped>

</style>
