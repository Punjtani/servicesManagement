<template>
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Billing</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="property_billing_update" method="post">

            <div class="modal_content_inner">
                    <!--<div class="form-group">                  
                        <div class="custom_checkbox">
                            <label>
                                <input type="checkbox"  v-model.trim="billing_form.po_number"  v-bind:true-value="1"  v-bind:false-value="0" >
                                <span class="box">PO Required</span>
                            </label>
                        </div>                        
                    </div>-->
                    <div class="form-group">
                        <div class="custom_checkbox">
                            <label>
                                <input type="checkbox"  v-model.trim="billing_form.separate_billing"  v-bind:true-value="1"  v-bind:false-value="0" >
                                <span class="box">Bill Separately for each service</span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Net Payment Terms</label>
                                <!--<input type="number" v-model.trim="billing_form.net_payment_terms"
                                        class="form-control "
                                        placeholder="Net Payment Terms">-->
                                <multiselect deselectLabel="" v-model.trim="billing_form.net_payment_terms" placeholder="Net payment terms" :select-label="''" :options="payment_term_options" :searchable="true" :allow-empty="false"> </multiselect>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Max Amount Per Invoice</label>
                                <input type="number" v-model.trim="billing_form.max_invoice_amount"
                                        class="form-control "
                                        placeholder="Max Invoice Amount $">
                            </div>
                        </div>
                    </div>
                  
                </div>
                <div class="modal-footer">
                <div class="inline_buttonss">
                        <div class="row">
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600">Save</button>
                                <button type="button" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600" @click = "closeModel()" aria-label="Close">Cancel</button>              
                            </div>
                        </div>
                    </div>
                </div>
                </form>
           
        </div>
    </div>
</template>


<script>
import Loader from "../../LoaderSpinner";
import {required, integer} from 'vuelidate/lib/validators'
import Multiselect from 'vue-multiselect';
export default {
    name: "ViewBilling",
    props: ['Property'],
    components: {
        'Loader': Loader,
        'Multiselect': Multiselect,
    },
    data() {
        return{
            id: "",
            payment_term_options: [30,60,90],
            billing_form : {
                // po_number:0,
                separate_billing:0,
                net_payment_terms:0,
                max_invoice_amount:0,
            }
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
        }
    },
   watch: {
        'Property'(val,old) {
            if(this.id!= val.id) {
                this.id = val.id;


                if (val) {

                    this.getRecord(val.id);
                }
            }
        }
    },
    methods:{
        property_billing_update() {
        // console.log("reached here")
        // // console.log(this.billing_form.po_number)
        axios.put('/api/property/' + this.Property.id + '/billing', this.billing_form)
            .then(response => {
                this.loading=true;
                this.$emit('closeEvent', "Property has been Updated successfully")
                this.closeModel();

            })
        },
        getRecord(id) {
            // console.log('getRecord');
            axios.get('/api/property/' + id + "/edit")
                .then((response) => {
                    // this.billing_form.po_number = response.data.property.is_PO_required;
                    this.billing_form.separate_billing = response.data.property.is_separate_billing;
                    this.billing_form.net_payment_terms = response.data.property.net_payment_term;
                    this.billing_form.max_invoice_amount = response.data.property.max_invoice_amount;
    
                   
                    // console.log(this.billing_form);
                });
        },
        closeModel(){
            this.billing_form = {
                // po_number: 0,
                separate_billing:0,
                net_payment_terms:0,
                max_invoice_amount:0,                
            }
            this.$forceUpdate();
            this.$emit('closeEventBilling', "");
            // console.log('model closed',this.property_form)
            $("#AttributeView").modal('hide');
        },
    }
}

</script>

<style scoped>

</style>
