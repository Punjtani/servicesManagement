<template>
 <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> Edit Payment # {{transationData.id}}</h4>
 <button type="button" class="btn-close" v-on:click="resetForm" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form autocomplete="off" @submit.prevent="payment_submit" method="post">

            <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="form_box">


                            <div class="form-group">
                                <label>Payment Method <span class="required-staric">*</span></label>
                                <select @change="validateAmount" v-model.trim="$v.transaction_form.transaction_method.$model"
                                        class="form-select"
                                        :class="{'is-invalid':$v.transaction_form.transaction_method.$error, 'is-valid':!$v.transaction_form.transaction_method.$invalid}"
                                        aria-label="Department Id">
                                    <option value="">Select Method</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Check">Check</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option v-if="creditAmount > 0" value="Credit">Client's Credit</option>
                                    <option value="Money Order">Money Order</option>
                                    <option value="Other">Other</option>
                                </select>

                                <div class="invalid-feedback">
                                    <span v-if="!$v.transaction_form.transaction_method.required">Method is Required</span>
                                </div>
                            </div>
                            <div class="form-group" v-if="transaction_form.transaction_method == 'Check'">
                                <label>Check Number<span class="required-staric">*</span></label>
                                <input  name = "check_no" type="text" v-model.trim="$v.transaction_form.check_no.$model"
                                       class="form-control"
                                       :class="{'is-invalid':$v.transaction_form.check_no.$error, 'is-valid':!$v.transaction_form.check_no.$invalid}"
                                       placeholder="Check Number">
                                <!--<div class="invalid-feedback">
                                    <span v-if="!$v.transaction_form.check_no.required">Check Number is Required</span>
                                </div>-->
                                <div class="text-danger"><small v-if="!$v.transaction_form.check_no.required">Check Number is Required</small></div>
                            </div>
                            <div class="form-group" v-if="transaction_form.transaction_method == 'Bank Transfer'">
                                <label>Confirmation Number<span class="required-staric">*</span></label>
                                <input  name = "confirmation_no" type="text" v-model.trim="$v.transaction_form.confirmation_no.$model"
                                       class="form-control"
                                       :class="{'is-invalid':$v.transaction_form.confirmation_no.$error, 'is-valid':!$v.transaction_form.confirmation_no.$invalid}"
                                       placeholder="Confirmation Number">
                                <!--<div class="invalid-feedback">
                                    <span v-if="!$v.transaction_form.check_no.required">Check Number is Required</span>
                                </div>-->
                                <div class="text-danger"><small v-if="!$v.transaction_form.confirmation_no.required">Confirmation Number is Required</small></div>
                            </div>
                            <div class="form-group">
                                <label>Amount <span class="required-staric">*</span></label>
                                <input  @keyup="validateAmount" name = "amount" type="number" step="any" min="0" v-model.trim="$v.transaction_form.amount.$model"
                                       class="form-control"
                                       :class="{'is-invalid':($v.transaction_form.amount.$error || invalidAmount), 'is-valid':(!$v.transaction_form.amount.$invalid && !invalidAmount)}"
                                       placeholder="Amount">
                                <div class="invalid-feedback">
                                    <span
                                        v-if="!$v.transaction_form.amount.required">Amount is Required</span>
                                        <!--<span v-if="!$v.transaction_form.amount.maxValue">Amount is greater than invoice total amount</span>-->
                                </div>
                                <div class="text-danger"><small v-if="invalidAmount">Amount entered is greater than the client's credit amount</small></div>
                            </div>


                            <div class="form-group">
                                <label>Transaction Date <span class="required-staric">*</span></label>
                                <!--<input type="date" v-model.trim="$v.transaction_form.transaction_date.$model"
                                       class="form-control"
                                       :max="todayDate"
                                       :class="{'is-invalid':$v.transaction_form.transaction_date.$error, 'is-valid':!$v.transaction_form.transaction_date.$invalid}"
                                       placeholder="Transaction date">-->
                                <datepicker placeholder = "Transaction date" format="MM-DD-YYYY"
                                            v-model.trim="$v.transaction_form.transaction_date.$model" :clearable="false" valueType="MM-DD-YYYY"
                                            :input-class="{'is-invalid':$v.transaction_form.transaction_date.$error, 'is-valid':!$v.transaction_form.transaction_date.$invalid,'form-control':true}"
                                            >
                                </datepicker>
                                <div class="invalid-feedback">
                                    <span
                                        v-if="!$v.transaction_form.transaction_date.required">transaction_date is Required</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Notes <span class="required-staric">*</span></label>
                                <textarea class="form-control"
                                          v-model.trim="$v.transaction_form.notes.$model"
                                          :class="{'is-invalid':$v.transaction_form.notes.$error, 'is-valid':!$v.transaction_form.notes.$invalid}"></textarea>

                                <div class="invalid-feedback">
                                    <span v-if="!$v.transaction_form.notes.required">Notes is Required</span>
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <div class="inline_buttons">
                                <div class="row">
                                    <div class="col-12 col-sm-12">
                                        <button type="submit" v-if="this.invalidAmount"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600" disabled>
                                            Confirm
                                        </button>
                                        <button type="submit" v-else-if="!this.invalidAmount"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Confirm
                                        </button>
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
import {required, minLength, maxLength, between, maxValue,requiredIf} from 'vuelidate/lib/validators'
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from 'moment';
export default {
    name: "AddPayment",
    components: {
        'Datepicker': Datepicker,
    },
    props : ['transationData','invoiceId','invoiceAmount','payableAmount','clientId','creditAmount'],
    data() {
        return {
            todayDate: "",
            invoiceData: "",
            transaction_form: {
                transaction_id: "",
                invoice_id: "",
                transaction_method: "",
                amount: "",
                notes: "",
                transaction_date: "",
                check_no:"",
                confirmation_no:"",
            },
            invalidAmount:false,
        }
    },
    validations(){
        return {
            transaction_form: {
            transaction_method: {
                required,
            },
            amount: {
                required,
                // maxValue: maxValue(this.invoiceAmount)
            },
            notes: {
                // required,
            },
            transaction_date: {
                required,
            },
            invoice_id: {
                required
            },
            check_no: {
                required : requiredIf(function (transaction_form){
                    {
                        return transaction_form.transaction_method == 'Check'
                    }
                })
            },
            confirmation_no: {
                required : requiredIf(function (transaction_form){
                    {
                        return transaction_form.transaction_method == 'Bank Transfer'
                    }
                })
            },
        }
        }

    },
    mounted() {
        this.transaction_form.invoice_id = this.invoiceId;
        const yourDate = new Date();
        this.todayDate = yourDate.toISOString().split('T')[0];
        this.transaction_form.transaction_date = yourDate.toISOString().split('T')[0];
    },

    methods: {
        payment_submit() {
            this.loading = true;
            this.transaction_form.invoice_id = this.invoiceId;
            this.$v.transaction_form.$touch();
            if (this.$v.transaction_form.$anyError) {
                this.loading = false;
                return;
            }
            this.loading = true;
            this.transaction_form.client_id = this.clientId;
            axios.post('/api/update-transaction', this.transaction_form)
                .then(response => {
                    this.loading = false;
                    this.$emit('closeEvent', "Payment  has been updated successfully")
                    this.resetForm();
                })
                .catch(err => {
                    this.loading = false;
                    this.has_error = true;
                    this.resetForm();
                })
            this.transaction_form = {}
            $("#editTransactionModal").modal('hide');
        },
        resetForm(){
            this.$v.transaction_form.$reset();
            const yourDate = new Date();
            // this.transaction_form = {
            //     transaction_method: "",
            //     amount: this.payableAmount,
            //     notes: "",
            //     transaction_date: yourDate.toISOString().split('T')[0],
            // }
            this.$forceUpdate();
        },
        validateAmount(){

            if(this.transaction_form.transaction_method == "Credit"){
                if(this.transaction_form.amount > this.creditAmount){
                    this.invalidAmount = true;
                }else{
                    this.invalidAmount = false;
                }
            }else{
                this.invalidAmount = false;
            }
        }
    },
    watch: {
        transationData () {
            // console.log("transationData",this.transationData)
            this.transaction_form.transaction_id = this.transationData.id
            this.transaction_form.invoice_id = this.transationData.invoice_id
            this.transaction_form.transaction_date = moment(this.transationData.transaction_date).format('MM-DD-YYYY')
            this.transaction_form.transaction_method = this.transationData.method
            this.transaction_form.amount = this.transationData.amount.toFixed(2)
            this.transaction_form.confirmation_no = this.transationData.confirmation_no
            this.transaction_form.check_no = this.transationData.check_no
            this.transaction_form.notes = this.transationData.notes
        },
        'payableAmount'(val) {
            this.transaction_form.amount = val;

        }

    },

}
</script>

<style scoped>

</style>
