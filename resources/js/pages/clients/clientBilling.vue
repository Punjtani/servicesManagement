<template>
	<div>
		<div v-if="loading">
			<loader></loader>
		</div>
		<div v-if="!loading" class="dashboard_content_area">

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">{{clientName}}</h1></div>

                <div class="col-sm-6 col-4 text-end  d-flex justify-content-end">

                    <p @click="$router.go(-1)" class="h3 mb-2 cursor-pointer history_back_btn" v-bind:class="{ 'disable-backward': !canGoBack }" ><b-icon icon="arrow-left-circle-fill"></b-icon></p>
                    <p @click="$router.go(1)"class="h3 mb-2 cursor-pointer history_farward_btn" v-bind:class="{ 'disable-forward': !canGoForward }" ><b-icon icon="arrow-right-circle-fill"></b-icon></p>
                </div>
            </div>
			<div id="success-alert" class="alert alert-success" v-if="successmsg!==''"> <strong>{{ successmsg }}</strong> </div>
            <div id="errir-alert" class="alert alert-danger" v-if="errormsg!=='' && successmsg == ''">
                <strong>{{ errormsg }}</strong>
            </div>
			<div class="smp__tabs">
				<div v-if="clientId">
					<TopNavigation :clientId="clientId" :currentComponent="currentComponent"></TopNavigation>
				</div>
				<form autocomplete="off" @submit.prevent="client_billing_submit" method="post">
					<div class="modal-bodys">
						<!-- ============ -->
						<div class="info_form">
                            <div class="table_area_head">
                                <div class="row align-items-center">
                                    <div class="col-sm-6 col-8"><h5 class="font_weight_600 font_family_Montserrat text_color_orange  mg_bot_1rem_on_mobiles">Billing Information</h5></div>
                                    <div class="col-sm-6 col-4 text-end">
<!--                                        <router-link  to="/clients" class="btn_big_medium line_height_1_4 back_btn  btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                                    </div>
                                </div>
                                <p>*See individual properties for billing addresses</p>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Net Payment Terms</label>
                                        <multiselect deselectLabel="" v-model.trim="billing_form.net_payment_terms" placeholder="Net payment terms" :select-label="''" :options="payment_term_options" :searchable="true" :allow-empty="false"> </multiselect>
                                    </div>
                            </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Max Amount Per Invoice</label>
                                        <input type="number" v-model.trim="billing_form.max_invoice_amount"
                                                class="form-control "
                                                placeholder="Max Invoice Amount $">
                                    </div>
                                </div>
<!--                                <div class="col-sm-4">-->
<!--                                    <div class="form-group">-->
<!--                                        <label>Contact Email</label>-->
<!--                                            <input  type="email" v-model.trim="billing_form.contact_email" class="form-control"-->
<!--                                                   placeholder="Contact Email">-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                            <div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<div class="custom_checkbox">
                                            <label>
                                                <input type="checkbox"  v-model.trim="billing_form.separate_billing"  v-bind:true-value="1"  v-bind:false-value="0" >
                                                <span class="box">Bill Separately for each service</span>
                                            </label>
                                        </div>
									</div>
								</div>
                            </div>
<!--                            <div class="row">-->
<!--                                <div class="col-md-6">-->
<!--									<div class="form-group">-->
<!--										<div class="custom_checkbox">-->
<!--                                            <label>-->
<!--                                                <input type="checkbox"  v-model.trim="billing_form.is_po_required"  v-bind:true-value="1"  v-bind:false-value="0" >-->
<!--                                                <span class="box">PO Required</span>-->
<!--                                            </label>-->
<!--                                        </div>-->
<!--									</div>-->
<!--								</div>-->
<!--							</div>-->
							<div class="row">
								<div class="col-md-3">
									<button type="submit" class="btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
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

import Loader from "../LoaderSpinner";
import roleData from '../../data.js';
import axios from "axios";
import TopNavigation from "./TopNavigation";
import {required, integer,email} from 'vuelidate/lib/validators'
import Multiselect from 'vue-multiselect';
export default {

    components: {
        'roleData': roleData,
        'Loader': Loader,
        'TopNavigation': TopNavigation,
        'Multiselect': Multiselect,
    },
    data() {
        return {
            clientName:"",
            role: "",
			roleData: roleData,
            currentComponent : 'clientBilling',
            clientId:  this.$route.params.id ,
            successmsg: "",
            deletemsg: "",
            errormsg:"",
            has_error: false,
            loading: true,
            payment_term_options: [30,60,90],
            billing_form : {
                is_po_required:0,
                separate_billing:0,
                net_payment_terms:0,
                max_invoice_amount:0,
                contact_email:"",
            }
        }
    },
    computed: {
        canGoForward() {
           let canGoForw = false;
try {
    canGoForw =  window.navigation.canGoForward;
} catch (error) {
      if (window.history && typeof window.history.goForward === 'function') {         canGoForw = window.history.goForward();
    } else if (window.history && (window.history.length > 1 && window.history.length > (history.state && history.state.index || 0))) {
        canGoForw = true;
    } else {
        canGoForw = false;
    }
}
return canGoForw;
        },
        canGoBack() {
            let canGo = false;
try {
    canGo =  window.navigation.canGoBack;
} catch (error) {
    if (window.history && typeof window.history.goBack === 'function') {
        canGo =  true;
    } else if (window.history && window.history.length > 1) {
        canGo = true;
    } else {
        canGo = false;
    }
}
return canGo;
        }
    },
    valididations:{
        billing_form: {
            net_payment_terms: {
                integer
            },
            max_invoice_amount: {
                integer
            },
            contact_email: {
                required,
                email
            },
        }
    },
    mounted() {
        this.getRecord(this.clientId);

    },
    methods: {
        getRecord(id) {
            axios.get('/api/client/' + id + "/edit")
                .then((response) => {
                    this.loading = false;
                    this.billing_form.is_po_required = response.data.client.is_PO_required;
                    this.billing_form.separate_billing = response.data.client.is_separate_billing;
                    this.billing_form.net_payment_terms = response.data.client.net_payment_term;
                    this.billing_form.max_invoice_amount = (response.data?.client?.max_invoice_amount || 0).toFixed(2);
                    this.billing_form.contact_email = response.data.client.contact_email;
                    this.clientName = response.data.client.company;
                    // // console.log(this.billing_form);
                });
        },
        client_billing_submit() {
            this.loading = true;
            axios.put('/api/client-billing/' + this.clientId, this.billing_form)
                .then(response => {
                    this.loading = false;
                    if (response.data.code == 302){
                        this.errormsg = response.data.msg
                    }
                    else {
                        this.successmsg = response.data.msg

                    }

                    setTimeout(() => {
                        this.errormsg = "";
                        this.successmsg = "";
                    }, 3000);
                })
        },
    }
}


</script>
