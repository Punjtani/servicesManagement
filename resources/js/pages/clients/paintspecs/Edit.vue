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

                <div class="table_area">
                    <div class="table_area_head">
                        <div class="row align-items-center row_spacing_5">
                            <div class="col-sm-6 col-md-6  col-lg-4">
                                    <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_bot_1_half_rem">Paint Specs</h5>
                            </div>
                            <div class="col-sm-6 col-md-6  col-lg-8 text-sm-end">
                                <button type="button" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Add New Specs</button>
                            </div>
                        </div>
                    </div>
                    <div class="no_more_tables table_to_be_used mg_top_half_rem">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Surface</th>
                                    <th>Color</th>
                                    <th>Finish</th>
                                    <th>Grade</th>
                                    <th>Coats</th>
                                    <th>Method</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-title="Surface">Wall</td>
                                    <td data-title="Color">Color</td>
                                    <td data-title="Finish">Finish</td>
                                    <td data-title="Grade">Grade</td>
                                    <td data-title="Coats">Coats</td>
                                    <td data-title="Method">Method</td>
                                    <td data-title="Action"><a href="#" class="font_weight_700">Edit</a></td>
                                </tr>
                                <tr>
                                    <td data-title="Surface">Wall</td>
                                    <td data-title="Color">Color</td>
                                    <td data-title="Finish">Finish</td>
                                    <td data-title="Grade">Grade</td>
                                    <td data-title="Coats">Coats</td>
                                    <td data-title="Method">Method</td>
                                    <td data-title="Action"><a href="#" class="font_weight_700">Edit</a></td>
                                </tr>
                                <tr>
                                    <td data-title="Surface">Wall</td>
                                    <td data-title="Color">Color</td>
                                    <td data-title="Finish">Finish</td>
                                    <td data-title="Grade">Grade</td>
                                    <td data-title="Coats">Coats</td>
                                    <td data-title="Method">Method</td>
                                    <td data-title="Action"><a href="#" class="font_weight_700">Edit</a></td>
                                </tr>
                                <tr>
                                    <td data-title="Surface">Wall</td>
                                    <td data-title="Color">Color</td>
                                    <td data-title="Finish">Finish</td>
                                    <td data-title="Grade">Grade</td>
                                    <td data-title="Coats">Coats</td>
                                    <td data-title="Method">Method</td>
                                    <td data-title="Action"><a href="#" class="font_weight_700">Edit</a></td>
                                </tr>
                                <tr>
                                    <td data-title="Surface">Wall</td>
                                    <td data-title="Color">Color</td>
                                    <td data-title="Finish">Finish</td>
                                    <td data-title="Grade">Grade</td>
                                    <td data-title="Coats">Coats</td>
                                    <td data-title="Method">Method</td>
                                    <td data-title="Action"><a href="#" class="font_weight_700">Edit</a></td>
                                </tr>
                                <tr>
                                    <td data-title="Surface">Wall</td>
                                    <td data-title="Color">Color</td>
                                    <td data-title="Finish">Finish</td>
                                    <td data-title="Grade">Grade</td>
                                    <td data-title="Coats">Coats</td>
                                    <td data-title="Method">Method</td>
                                    <td data-title="Action"><a href="#" class="font_weight_700">Edit</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

               
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
            currentComponent : 'paintspecs',
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
