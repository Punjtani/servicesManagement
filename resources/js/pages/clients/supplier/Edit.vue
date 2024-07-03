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
                    <div class="modal-bodyx" >
                        <div class="form_box">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">Supplier</h5>
                            </div>

                            <div class="info_form mg_top_1rem">
                                <!-- ======== -->
                                <div class="row row_spacing_5">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">Address</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="text" class="form-control">
                                        </div>
                                        <div class="row align-items-center row_spacing_5 ">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="City">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-select" aria-label="State">
                                                        <option selected>State</option>
                                                        <option value="1">State</option>
                                                        <option value="2">State</option>
                                                        <option value="3">State</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Zip">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ======== -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">Notes</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.6125970553926!2d-73.98795218428675!3d40.74854924331511!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9aeb1c6b5%3A0x35b1cfbc89a6097f!2sEmpire%20State%20Building%2C%20New%20York%2C%20NY%2010001%2C%20USA!5e0!3m2!1sen!2s!4v1635666142859!5m2!1sen!2s" width="100%" height="260" style="border:1px solid #ccc;" allowfullscreen="" loading="lazy"></iframe>
                                        </div>
                                        <button type="button" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
                                        <router-link  to="/clients" type="button" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Cancel</router-link>
                                    </div>
                                </div>
                            </div>
<!-- ====Supplier details===old================== -->
                            <!-- <div class="row align-items-center">
                                <div class="col-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Supplier details *</label>
                                        <textarea type="text" v-model.trim="$v.supplier_form.details.$model"
                                                class="form-control"
                                                :class="{'is-invalid':$v.supplier_form.details.$error, 'is-valid':!$v.supplier_form.details.$invalid}"
                                                placeholder="Notes">  </textarea>
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.supplier_form.details.required">This Field is Required</span>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div class="inline_buttonss">
                                <div class="row">
                                    <div class="col-sm-12 text-sm-end">
                                        <button type="submit"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Confirm
                                        </button>
                                    </div>
                                </div>
                            </div>-->
                            <!-- ===Supplier details======old================ -->
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
            currentComponent : 'supplier',
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
