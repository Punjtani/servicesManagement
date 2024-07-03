<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>
            <form autocomplete="off" @submit.prevent="refinish_specs_submit" method="post">
                <div class="modal-bodys">
                    <div class="form_box">
                        <div class="row">
                        <div class="col-sm-6 col-8">
                            <h1 class="h4 heading_text">Refinish Specs</h1>
                        </div>
                            <Navigation></Navigation>
                        </div>
                        <div class="row">
                            <!-- <div class="col-sm-6"><h5 class="h4 heading_text">Refinish Specs</h5></div> -->
                            <div class="col-sm-12 text-end">
                                <router-link v-bind:to="'/property-refinish-specs-view/' + refinish_spec_form.property_id" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Client Refinish Specs View</router-link>
                                <!-- <router-link v-bind:to="'/client-property/' + client_id" class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Back</router-link> -->
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="no_more_tables table_to_be_used mg_top_half_rem add_paint_specs_table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Primer Color</th>
                                            <th>Multispect Color</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-title="Color">
                                                <div class="form-group">
                                                    <select :disabled="role==roleData.employee" v-model.trim="refinish_spec_form.refinish_color_id"
                                                            class="form-select">
                                                        <option v-for="(item1, index1) in refinish_values.refinish_colors"
                                                                :key="index1" :value="item1.id">
                                                            {{ item1.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td data-title="Multispect Color">
                                                <div class="form-group">
                                                    <select :disabled="role==roleData.employee" v-model.trim="refinish_spec_form.multispect_color_id"
                                                            class="form-select">
                                                        <option v-for="(item1, index1) in refinish_values.multispect_colors"
                                                                :key="index1" :value="item1.id">
                                                            {{ item1.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </td>


                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
                                <a @click="$router.back()"><button type="cancel" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600"> Cancel</button></a>
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
import roleData from '../../../data.js';
import Navigation from "../../../components/Navigation";
export default {
    name: "RefinishSpecs",
    components: {
        'Loader': Loader,
        'Navigation': Navigation
    },
    data() {
        return {
            successmsg: "",
            loading: false,
            already_record: false,
            refinish_values: [],
            multispect_values: [],
            refinish_spec_form_temp: "",
            refinish_providers: ['Ress', 'Client'],
            refinish_spec_form: {
                property_id: this.$route.params.id,
                refinish_provider: "Client",

                refinish_color_id: '',
                multispect_color_id: '',

            },
            client_id:'',
            roleData:roleData,
            role: "",
        }
    },

    mounted() {

        this.loading = true;
        this.getRecord(this.refinish_spec_form.property_id);
        this.role = localStorage.getItem("role");
        // this.getpaintValues();
        // this.loading = false;

    },
    methods: {
        refinish_specs_submit() {
            this.loading=true;
            // console.log(this.refinish_spec_form);
                axios.post('/api/client-refinish-spec', this.refinish_spec_form)
                    .then(response => {
                        this.loading=false;
                        this.successmsg = "Refinish Specs has been updated";
                        setTimeout(() => {
                            this.successmsg = "";
                            // this.$router.back();
                        }, 3000);

                    })
                    .catch(err => {
                        this.has_error = true;
                    })

        },
        getRecord(id) {
            axios.get('/api/client-refinish-spec/' + id + "/edit")
                .then((response) => {
                    this.refinish_spec_form_temp = response.data.ClientRefinishSpec;
                    // if(this.refinish_spec_form_temp.length >0){
                    //     this.client_id = this.refinish_spec_form_temp[0].property.client_id;
                    // }
                    this.client_id = response.data.property.client.id;
                    this.getRefinishValues();
                    // this.loading = false;
                });
        },
        getRefinishValues() {
            axios.get('/api/client-refinish-spec')
            .then((response) => {
                this.refinish_values = response.data.refinishValues;
                this.loading = false;
                var matchedFound = false
                this.refinish_spec_form_temp.forEach((value1, index1) => {
                        matchedFound=true;
                        this.refinish_spec_form.refinish_color_id = value1.paint_color_id;
                        this.refinish_spec_form.multispect_color_id = value1.multispect_color_id;

                });

                if (!matchedFound) {
                    // console.log('Record Not Found, Adding auto values')
                    this.refinish_spec_form.refinish_color_id = this.refinish_values.refinish_colors[0].id;
                    this.refinish_spec_form.multispect_color_id = this.refinish_values.multispect_colors[0].id;

                }
            });
        },
    }
}
</script>

<style scoped>

</style>
