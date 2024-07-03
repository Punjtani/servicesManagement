<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''">
                <strong>{{ successmsg }}</strong>
            </div>
            <form autocomplete="off" @submit.prevent="paint_specs_submit" method="post">
                <div class="modal-bodys">
                    <div class="form_box">

                        <div class="row">
                            <div class="col-sm-6 col-8">
                                <h1 class="h4 heading_text">Paint Specs</h1>
                            </div>

                            <Navigation></Navigation>
                            <div class="col-sm-6 text-end">
                            </div>
                            <div class="col-sm-6 text-end">
                                <router-link v-bind:to="'/property-paint-specs-view/' + paint_spec_form.property_id"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Client
                                    Paint Specs View</router-link>
                            </div>

                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Paint Provider</label>
                                    <div class="form-group">
                                        <select v-model.trim="paint_spec_form.paint_provider" class="form-select">
                                            <option v-for="(item1, index1) in paint_providers" :key="index1" :value="item1">
                                                {{ item1 }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="no_more_tables table_to_be_used mg_top_half_rem add_paint_specs_table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Surface</th>
                                            <th>Manufacturer</th>
                                            <th>Color</th>
                                            <th>Finish</th>
                                            <th>Grade</th>
                                            <th>Coat</th>
                                            <th>Method</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in paint_values.paint_surfaces" :key="item.id">
                                            <td data-title="Surface">{{ item.name }}</td>
                                            <td data-title="Manufacturer">
                                                <div class="form-group">
                                                    <select v-model.trim="paint_spec_form.paint_manufacturer_id[index]"
                                                        class="form-select">
                                                        <option v-for="(item1, index1) in paint_values.paint_manufacturers"
                                                            :key="index1" :value="item1.id">
                                                            {{ item1.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td data-title="Color">
                                                <div class="form-group">
                                                    <select v-model.trim="paint_spec_form.paint_color_id[index]"
                                                        class="form-select">
                                                        <option v-for="(item1, index1) in paint_values.paint_colors"
                                                            :key="index1" :value="item1.id">
                                                            {{ item1.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td data-title="Finish">
                                                <div class="form-group">
                                                    <select v-model.trim="paint_spec_form.paint_finish_id[index]"
                                                        class="form-select">
                                                        <option v-for="(item1, index1) in paint_values.paint_finishes"
                                                            :key="index1" :value="item1.id">
                                                            {{ item1.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td data-title="Grade" class="min_max_80">
                                                <div class="form-group">
                                                    <select v-model.trim="paint_spec_form.paint_grade_id[index]"
                                                        class="form-select">
                                                        <option v-for="(item1, index1) in paint_values.paint_grades"
                                                            :key="index1" :value="item1.id">
                                                            {{ item1.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td data-title="Coat" class="min_max_80">
                                                <div class="form-group">
                                                    <input min="1" class="form-control" type="number"
                                                        v-model.trim="paint_spec_form.coats[index]">
                                                </div>
                                            </td>
                                            <td data-title="Method">
                                                <div class="form-group">
                                                    <input min="1" class="form-control" type="hidden"
                                                        v-model.trim="paint_spec_form.coats[index]">
                                                    <span v-if="item.paint_method">{{ item.paint_method.name }}</span>
                                                    <span v-else>--</span>
                                                    <!--                                                    <select  v-model.trim="paint_spec_form.paint_method_id[index]"-->
                                                    <!--                                                            class="form-select">-->
                                                    <!--                                                        <option v-for="(item1, index1) in paint_values.paint_methods"-->
                                                    <!--                                                                :key="index1" :value="item1.id">-->
                                                    <!--                                                            {{ item1.name }}-->
                                                    <!--                                                        </option>-->
                                                    <!--                                                    </select>-->

                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 text-end">
                                <button type="submit"
                                    class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
                                <a href="javascript::void(0)" @click="goBack()"><button type="button"
                                        class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">
                                        Cancel</button></a>
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
import { required, minLength, maxLength, email, url, decimal } from 'vuelidate/lib/validators'
import Loader from "../../LoaderSpinner";
import Navigation from "../../../components/Navigation";

export default {
    name: "PaintSpecs",
    components: {
        'Loader': Loader,
        'Navigation': Navigation,
    },
    data() {
        return {
            successmsg: "",
            loading: false,
            timerId: 0,
            paint_values: [],
            paint_spec_form_temp: "",
            paint_providers: ['Ress', 'Client'],
            paint_spec_form: {
                property_id: this.$route.params.id,
                paint_provider: "Client",
                paint_manufacturer_id: [],
                paint_surface_id: [],
                paint_color_id: [],
                paint_finish_id: [],
                paint_grade_id: [],
                coats: [],
                paint_method_id: [],
            },
            client_id: "",
        }
    },

    mounted() {

        this.loading = true;

        this.getRecord(this.paint_spec_form.property_id);
        // this.getpaintValues();
        // this.loading = false;

    },
    methods: {
        goBack() {
            if (this.timerId) {
                clearTimeout(this.timerId);
            }
            this.$router.back();
        },
        paint_specs_submit() {
            this.loading = true;
            // console.log(this.paint_spec_form);
            axios.post('/api/client-paint-spec', this.paint_spec_form)
                .then(response => {
                    this.loading = false;
                    this.successmsg = "Paint Specs has been updated";
                    if (this.timerId) {
                        clearTimeout(this.timerId);
                    }
                    this.timerId = setTimeout(() => {
                        this.successmsg = "";
                        this.$router.back();
                    }, 3000);

                })
                .catch(err => {
                    this.has_error = true;
                })

        },
        getRecord(id) {
            axios.get('/api/client-paint-spec/' + id + "/edit")
                .then((response) => {
                    // // console.log(response);
                    this.paint_spec_form_temp = response.data.clientPaintSpec;
                    // if(this.paint_spec_form_temp.length > 0){
                    //     this.client_id = this.paint_spec_form_temp[0].property.client_id;
                    // }
                    this.client_id = response.data.property.client.id;
                    this.getpaintValues();
                    // this.loading = false;
                });
        },
        getpaintValues() {
            axios.get('/api/client-paint-spec')
                .then((response) => {
                    this.paint_values = response.data.paintValues;

                    this.paint_values.paint_surfaces.forEach((value, index) => {
                        var matchedFound = false;
                        this.paint_spec_form_temp.forEach((value1, index1) => {
                            if (value.id == value1.paint_surface_id) {
                                // console.log('Record found')
                                matchedFound = true;
                                this.paint_spec_form.paint_surface_id[index] = value1.paint_surface_id;
                                this.paint_spec_form.paint_manufacturer_id[index] = value1.paint_manufacturer_id;
                                this.paint_spec_form.paint_finish_id[index] = value1.paint_finish_id;
                                this.paint_spec_form.paint_color_id[index] = value1.paint_color_id;
                                this.paint_spec_form.paint_grade_id[index] = value1.paint_grade_id;
                                // this.paint_spec_form.paint_method_id[index] = value1.paint_method_id;
                                this.paint_spec_form.paint_method_id[index] = value.paint_method ? value.paint_method.id : "";
                                this.paint_spec_form.coats[index] = value1.coats;
                                this.paint_spec_form.paint_provider = value1.paint_provider;
                            }
                        });

                        if (!matchedFound) {
                            // console.log('Record Not Found, Adding auto values')
                            this.paint_spec_form.paint_surface_id[index] = value.id;
                            this.paint_spec_form.paint_manufacturer_id[index] = this.paint_values.paint_manufacturers[0].id;
                            this.paint_spec_form.paint_finish_id[index] = this.paint_values.paint_finishes[0].id;
                            this.paint_spec_form.paint_color_id[index] = this.paint_values.paint_colors[0].id;

                            this.paint_spec_form.paint_grade_id[index] = this.paint_values.paint_grades[0].id;
                            this.paint_spec_form.paint_method_id[index] = this.paint_values.paint_methods[0].id;
                            this.paint_spec_form.coats[index] = 1;
                        }

                        if (!this.paint_spec_form.paint_surface_id[index]) {
                            this.paint_spec_form.paint_surface_id[index] = value.id;
                        }

                        if (!this.paint_spec_form.paint_manufacturer_id[index]) {
                            this.paint_spec_form.paint_manufacturer_id[index] = this.paint_values.paint_manufacturers[0].id;
                        }

                        if (!this.paint_spec_form.paint_finish_id[index]) {
                            this.paint_spec_form.paint_finish_id[index] = this.paint_values.paint_finishes[0].id;
                        }

                        if (!this.paint_spec_form.paint_color_id[index]) {
                            this.paint_spec_form.paint_color_id[index] = this.paint_values.paint_colors[0].id;
                        }

                        if (!this.paint_spec_form.paint_grade_id[index]) {
                            this.paint_spec_form.paint_grade_id[index] = this.paint_values.paint_grades[0].id;
                        }

                        if (!this.paint_spec_form.paint_method_id[index]) {
                            this.paint_spec_form.paint_method_id[index] = this.paint_values.paint_methods[0].id;
                        }

                        if (!this.paint_spec_form.coats[index]) {
                            this.paint_spec_form.coats[index] = 1;
                        }


                    });
                    this.loading = false;
                });
        },
    }
}
</script>

<style scoped></style>
