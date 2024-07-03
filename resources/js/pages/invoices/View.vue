<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''"> <strong>{{ successmsg }}</strong>
            </div>
            <div id="errir-alert" class="alert alert-danger" v-if="errormsg !== '' && successmsg == ''">
                <strong>{{ errormsg }}</strong>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="removeOneServiceInvoicePop" tabindex="-1"
                aria-labelledby="removeOneServiceInvoicePop" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal_content_inner">
                            <h5 class="text-center font_weight_600">Are you sure you want to remove this service?</h5>
                            <div class="text-center">
                                <button @click="removeOneService(true)"  class="btn btn_blue"> Yes </button>
                                <button @click="removeOneService(false)" class="btn btn_blue"> No </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="mb-sm-2-custom col-12 col-sm-9 col-md-9 col-lg-9 d-md-flex flex-wrap">
                    <h1 class="h4 heading_text mr-4">Invoice #{{ invoice.id }}</h1>
                    <b-button v-if="invoice.is_paid == 1"
                        variant="success custom_status_btn mb-sm-2-custom ">Paid</b-button>
                    <b-button v-else-if="invoice.is_paid == 2"
                        variant="secondary custom_status_btn mb-sm-2-custom btn-secondary">Partial Paid</b-button>
                    <b-button v-else-if="invoice.is_paid == 0 && invoice.is_cancelled == 0"
                        variant="danger custom_status_btn mb-sm-2-custom ">Unpaid</b-button>
                    <b-button v-else-if="invoice.is_cancelled == 1"
                        variant="danger custom_status_btn mb-sm-2-custom ">Cancelled</b-button>
                    <b-button v-if="invoice.is_due_passed == 1" variant="danger custom_status_btn mb-sm-2-custom ">Past
                        Due</b-button>
                    <b-button v-if="invoice.is_qb_synced == 1" variant="success custom_status_btn mb-sm-2-custom ">QB
                        Synced</b-button>
                    <b-button v-if="invoice.is_qb_synced == 0" variant="warning custom_status_btn mb-sm-2-custom ">Not
                        Synced</b-button>
                </div>
                <div class="mb-sm-2-custom col-sm-3 col-12 text-end  d-flex justify-content-end">

                    <p @click="$router.go(-1)" class="h3 mb-2 cursor-pointer history_back_btn" v-bind:class="{ 'disable-backward': !canGoBack }"><b-icon
                            icon="arrow-left-circle-fill"></b-icon></p>
                    <p @click="$router.go(1)" class="h3 mb-2 cursor-pointer history_farward_btn" v-bind:class="{ 'disable-forward': !canGoForward }"><b-icon
                            icon="arrow-right-circle-fill"></b-icon></p>
                </div>
            </div>


            <div class="row row_spacing_5">

                <div class="col-sm-3 col-md-3 col-lg-3 text-end">
                    <button v-if="((role == roleData.admin) || (role == roleData.super_admin))" @click="getInvoiceData()"
                        class="btn_big_medium btn btn_orange border_radius_5 font_14  font_weight_600 btn-block mg_bot_5"
                        data-bs-toggle="modal" data-bs-target="#sendInvoice">Send Invoice</button>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 text-end">
                    <button class="btn_big_medium btn btn_orange border_radius_5 font_14 font_weight_600 btn-block mg_bot_5"
                        @click="downloadInvoice">Download Invoice</button>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 text-end">
                    <button class="btn_big_medium btn btn_orange border_radius_5 font_14 font_weight_600 btn-block mg_bot_5"
                        @click="viewInvoice" data-bs-toggle="modal" data-bs-target="#viewInvoice">View Invoice</button>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3 text-end">
                    <button type="button"
                        class="btn_big_medium btn btn_orange border_radius_5 font_14 font_weight_600 btn-block mg_bot_5"
                        data-bs-toggle="modal" data-bs-target="#CancelInvoicePopup">Cancel Invoice</button>
                </div>
            </div>
            <form autocomplete="off" @submit.prevent="edit_invoice_submit" method="post">
                <div v-if="invoice">
                    <div class="dashboard_content_area">
                        <!--                        <span class="text-success">{{txtSync}}</span>-->
                        <div class="details_shorts invoice_page">
                            <div class="row">
                                <div class="col">
                                    <div class="details_shorts_inner row align-items-center mb-sm-2-custom">
                                        <div class="col-md-4 px-0">
                                            <h6 class="font_14  font_weight_700 font_family_Montserrat mb-md-0 ">Issued Date
                                            </h6>
                                        </div>
                                        <div class="col-md-7">
                                            <!--											<input type="date"  v-on:change="changeInvoiceDate($event)" :disabled="role == roleData.client" v-model.trim="invoiceIssueDate" class=" mt-md-0 form-control form_control_un form_control_un_dates" placeholder="Issued Date" required>-->
                                            <datepicker :disabled="role == roleData.client" placeholder="Issued Date"
                                                format="MM-DD-YYYY" input-class="form-control"
                                                v-model.trim="invoiceIssueDate" :clearable="false" value-type="YYYY-MM-DD"
                                                v-on:change="changeInvoiceDate(invoiceIssueDate)">
                                            </datepicker>
                                        </div>
                                    </div>
                                    <div class="details_shorts_inner row align-items-center mb-sm-2-custom">
                                        <div class="col-md-4 px-0">
                                            <h6 class="font_14  font_weight_700 font_family_Montserrat mb-md-0 ">Due Date
                                            </h6>
                                        </div>
                                        <div class="col-md-7 mt-1">
                                            <datepicker :disabled="role == roleData.client" placeholder="Due Date"
                                                format="MM-DD-YYYY" input-class="form-control"
                                                v-model.trim="invoice.due_date" :clearable="false" value-type="YYYY-MM-DD"
                                                v-on:change="edit_invoice_submit()">
                                            </datepicker>
                                        </div>
                                    </div>
                                    <div class="details_shorts_inner row align-items-center mb-sm-2-custom">
                                        <div class="col-md-4 px-0">
                                            <h6 class="font_14  font_weight_700 font_family_Montserrat mb-md-0 ">PO # </h6>
                                        </div>
                                        <div class="col-md-7 mt-1">
                                            <input type="text" v-model.trim="invoiceForm.invoiceDetails.job.po_number"
                                                class="form-control" />
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="details_shorts_inner">
                                        <div class="row align-items-center ">
                                            <div class="col-md-4 col-lg-2 col-sm-5 px-0">
                                                <h6 class="font_14  font_weight_700 font_family_Montserrat mb-md-0">Ordered
                                                    By</h6>
                                            </div>
                                            <div class="col-md-8 col-lg-10  col-sm-7 px-0">
                                                <small class=" font_weight_500 font_family_Montserrat">
                                                    {{ invoice.job.property.title }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mt-1	">
                                            <div class="col-md-4 col-lg-2 col-sm-5 px-0">
                                                <h6 class="font_14  font_weight_700 font_family_Montserrat mb-md-0">Job #</h6>
                                            </div>
                                            <div class="col-md-8 col-lg-10  col-sm-7 px-0">
                                                <small class=" font_weight_500 font_family_Montserrat">{{
                                                    invoice.job.id
                                                }}</small>
                                            </div>
                                        </div>
                                        <div v-if="invoice.job.apartment_number" class="row align-items-center mt-1	">
                                            <div class="col-md-4 col-lg-2 col-sm-5 px-0">
                                                <h6 class="font_14  font_weight_700 font_family_Montserrat mb-md-0">Unit #
                                                </h6>
                                            </div>
                                            <div class="col-md-8 col-lg-10  col-sm-7 px-0">
                                                <small
                                                    class=" font_weight_500 font_family_Montserrat">{{ invoice.job.apartment_number }}</small>
                                            </div>
                                        </div>
                                        <!--										<div class="row align-items-center mt-1	" >-->
                                        <!--											<div class="col-md-4 col-lg-2 col-sm-5 px-0">-->
                                        <!--												<h6 class="font_14  font_weight_700 font_family_Montserrat mb-md-0">Invoice</h6>-->
                                        <!--											</div>-->
                                        <!--											<div class="col-md-8 col-lg-10   col-sm-7 px-0">-->
                                        <!--												<small class=" font_weight_500 font_family_Montserrat">{{ invoice.id }}-->
                                        <!--												</small>-->
                                        <!--											</div>-->
                                        <!--										</div>-->



                                    </div>




                                </div>
                                <div class="col">
                                    <div class="heading_paragrph_box mg_bot_20">
                                        <h6 class="font_20  font_weight_700 font_family_Montserrat mb-2">
                                            Billing Address</h6>
                                        <!--<p class="mg_0 font_weight_500"><strong>{{ invoice.job.property.title }}</strong></p>-->
                                        <p class="mg_0 font_weight_500">{{ invoice.job.property.billing_address }}</p>
                                        <p class="mg_0 font_weight_500">{{ invoice.job.property.billing_address_2 }}</p>
                                        <p class="mg_0 font_weight_500">{{ invoice.job.property.billing_city }}
                                            {{ invoice.job.property.billing_state }},
                                            {{ invoice.job.property.billing_zipcode }}
                                        </p>
                                    </div>
                                    <div class="heading_paragrph_box mg_bot_20">
                                        <h6 class="font_20  font_weight_700 font_family_Montserrat mb-2">
                                            Service Address
                                        </h6>
                                        <p class="mg_0 font_weight_500">{{ invoice.job.property.street1 }}</p>
                                        <p class="mg_0 font_weight_500">{{ invoice.job.property.street2 }}</p>
                                        <p class="mg_0 font_weight_500">{{ invoice.job.property.city }}
                                            {{ invoice.job.property.state }}, {{ invoice.job.property.zipcode }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- top block end here -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="heading_paragrph_box mg_bot_20">
                                    <h4 class="font_20s  font_weight_600 font_family_Montserrat mg_bot_20_on_desktop">
                                        Services Provided</h4>
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================================================================= -->
                        <!-- ============================================================================================================= -->
                        <!-- ============================================================================================================= -->
                        <!-- ============================================================================================================= -->
                        <!-- ============================================================================================================= -->
                        <div class="table_area">
                            <div
                                class="no_more_tables table_to_be_used mg_top_14 table_with_short_spacing invoice_table_container ">
                                <table class="table draggable invoices_table"  ref="invoiceTable">
                                    <thead>
                                        <tr>
                                            <th class=" text_uppercase_only_on_desktop">Product/Service</th>
                                            <th class=" text_uppercase_only_on_desktop">Date/Service Note</th>
                                            <th class=" text-left text_uppercase_only_on_desktop">QTY.</th>
                                            <th class=" text-left text_uppercase_only_on_desktop">Unit Price</th>
                                            <th class=" text-left text_uppercase_only_on_desktop">Total</th>

                                        </tr>
                                    </thead>
                                    <tbody v-if="item.service && item.requested_job_sub_service && item.requested_job_sub_service.length > 0 && item.requested_job_sub_service.some(e =>  e.is_invoice == 1)" class="tbody draggable-inv" @mouseup="clickup()"
                                        :class="{ 'single-no_old': item.requested_job_sub_service.length == 1 }"
                                        v-for="(item, index0) in invoiceForm.invoiceDetails.job.job_services">
                                        <tr>
                                            <td colspan="5">
                                                <h5 class="parent_service">{{ item.service.category }} <span
                                                        v-if="!item.service.is_taxable && invoice.job.property.tax_type && (invoice.job.apartment_status == 'vacant')">NON-TAXABLE</span>
                                                </h5>
                                                <div class="parent_tr_data">
                                                    <div class="row align-items-center mx-0">
                                                        <div class="col-sm-12 col-md-12 col-lg-3 px-0">
                                                            <!--														<input type="date" :disabled="role == roleData.client" v-model.trim="item.scheduled_date" class="form-control" placeholder="Transation Date">-->
                                                            <datepicker :id="'ScheduleDate' + item.id"
                                                                :disabled="role == roleData.client"
                                                                placeholder="Scheduled Date" format="MM-DD-YYYY"
                                                                input-class="form-control"

                                                                v-model.trim="item.scheduled_date" :clearable="false"
                                                                value-type="YYYY-MM-DD">
                                                            </datepicker>
                                                            <b-tooltip :target="'ScheduleDate' + item.id"
                                                                variant="info"><strong class="custom-toltip">Scheduled
                                                                    Date</strong></b-tooltip>

                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-3 px-1">
                                                            <datepicker :id="'completionDate' + item.id"
                                                                        :disabled="role == roleData.client"
                                                                        placeholder="completion Date" format="MM-DD-YYYY"
                                                                        input-class="form-control"

                                                                        v-model.trim="item.end_date" :clearable="false"
                                                                        value-type="YYYY-MM-DD">
                                                            </datepicker>
<!--                                                            <span class="completion-status" :id="'completionDate' + item.id"-->
<!--                                                                v-if="item.end_date">Completed :-->
<!--                                                                {{ item.end_date | formatDate }}</span>-->
<!--                                                            <span class="completion-status" :id="'Notcompleted' + item.id"-->
<!--                                                                v-else>Not Completed</span>-->
                                                            <b-tooltip :target="'completionDate' + item.id"
                                                                variant="info"><strong class="custom-toltip">Completion
                                                                    Date</strong></b-tooltip>
<!--                                                            <b-tooltip :target="'Notcompleted' + item.id"-->
<!--                                                                variant="info"><strong class="custom-toltip">Completion-->
<!--                                                                    Date</strong></b-tooltip>-->


                                                        </div>
                                                        <div class="col-sm-12 col-md-12 col-lg-6 px-1 error-v-center">
                                                            <p class="text-danger error-v-center " v-if="item.errorMsgForDate"> {{ item.errorMsgForDate }}</p>
                                                        </div>



                                                        <div class="col-sm-12 col-md-12 col-lg-3 px-0"> <a href="#"
                                                                class="table_btn_links  font_12 font_weight_700 text_uppercase"
                                                                data-bs-toggle="modal" data-bs-target="#ViewNotes"
                                                                @click="viewNotesMethod(item)">
                                                                <span
                                                                    v-if="(role == roleData.super_admin || role == roleData.admin) && (item.notes_data && (item.notes_data.notes != null || item.notes_data.service_job_progres_attatchment.length > 0 || item.notes_data.service_job_progress_after_attatchment.length > 0))">View
                                                                    Pictures &amp; Report</span>
                                                                <span
                                                                    v-if="(role == roleData.client) && (item.notes_data.service_job_progres_attatchment.length > 0 || item.notes_data.service_job_progress_after_attatchment.length > 0)">View
                                                                    Pictures</span></a> </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <sortable class="sortable-inv_old drag-sortable" v-if="item1.is_invoice == 1"
                                            v-for="(item1, index1) in item.requested_job_sub_service" :key="item1.id"
                                            :index="index1" v-bind:key="item1.id" v-model="dragData"
                                            drag-direction="vertical" replace-direction="vertical"
                                            @sortend="sortend($event, item.requested_job_sub_service, item1.id)">

                                            <tr :class="getClass(item.requested_job_sub_service, index1)" id="ele_"
                                                @mousedown="click(item1.id)" @mouseup="clickup(item1.id)">
                                                <td data-title="Description">
                                                    <b class="">{{ item1.sub_service.name }}</b>
                                                </td>
                                                <td data-title="Description Details">
                                                    <textarea rows="1" :disabled="role == roleData.client"
                                                        v-model.trim="item1.description" placeholder="Add details..."
                                                        class="taxarea_job form-control"></textarea>
                                                </td>
                                                <td data-title="Quantity" class="text_right_all width_40">
                                                    <input type="number" :disabled="role == roleData.client"
                                                        v-on:change="changeQuantity(item1.quantity, index0, index1)"
                                                        v-model.trim="item1.quantity" class="form-control"
                                                        placeholder="Qty">
                                                </td>
                                                <td data-title="Unit Price" class="text_right_all">
                                                    <div class="form-group prefix_align_center">
                                                        <div class="dollar-div">
                                                            <span class="fieldAffix-item">$</span>
                                                            <input type="number" step="any"
                                                                :disabled="role == roleData.client"
                                                                v-on:change="changeBasePrice(item1.base_price, index0, index1)"
                                                                v-model.trim="item1.base_price" class="form-control"
                                                                placeholder="Price">
                                                        </div>
                                                    </div>
                                                </td>

                                                <td data-title="Total" class="text_right_all sub_total_td">
                                                    <div class="form-group prefix_align_cente">
                                                        <div class="dollar-div">
                                                            <span class="fieldAffix-item">$</span>
                                                            <span class="fake_form_control "> {{ item1.total_price }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <button type="button" :disabled="role == roleData.client"
                                                        @click="removeItem(index0, index1)"
                                                        class="cross__button text_end_only_desktop text-md-end btn-close btncclose "></button>
                                                </td>
                                            </tr>
                                        </sortable>
                                    </tbody>
                                    <!-- ===============================separator============================================================================== -->
                                    <!-- ===============================separator============================================================================== -->


                                    <!-- body for line items-->
                                    <div class="row add_item_main_row"
                                        v-if="(role == roleData.super_admin || role == roleData.admin) && invoiceForm.lineItems.length > 0">
                                        <h6
                                            class="font_20 text_color_orange font_weight_600 font_family_Montserrat mg_bot_20_on_desktop">
                                            Line Items</h6>
                                    </div>
                                    <tbody class="tbody add_item">
                                        <div class="drag-sortable">
                                            <tr v-for="(item, index) in invoiceForm.lineItems" class="parent_service_tr_div"
                                                v-bind:key="item.id">
                                                <td data-title="" class="add-invoice view-wrap">

                                                    <multiselect deselectLabel="" :closeOnSelect="true"
                                                        :clearOnSelect="true" :multiple="false"
                                                        v-model.trim="item.subService" placeholder="Select Service"
                                                        :select-label="''" :options="options" :searchable="true"
                                                        track-by="name" label="name" @select="selctedService($event, index)"
                                                        :class="{ 'is-invalid': submit & item.subService === '', 'is-valid': !item.subService === '' }">
                                                    </multiselect>
                                                    <div class="invalid-feedback" v-if="submit"> <span
                                                            v-if="item.subService == ''">Service is Required</span> </div>

                                                </td>
                                                <td data-title="Description Details">
                                                    <textarea rows="1" v-model.trim="item.description"
                                                        placeholder="Add details..."
                                                        class="taxarea_job form-control"></textarea>
                                                </td>
                                                <td data-title="Quantity" class="text_right_all width_40 add-invoice-number">
                                                    <input type="number"
                                                        v-on:change="changeLineItemQuantity(item.quantity, index)"
                                                        v-model.trim="item.quantity" class="form-control" placeholder="Qty"
                                                        :class="{ 'is-invalid': submit & Number(item.quantity) < 1, 'is-valid': Number(item.quantity) >= 1 }">
                                                    <div class="invalid-feedback" v-if="submit"> <span
                                                            v-if="Number(item.quantity) < 1">Quantity is Required</span>
                                                    </div>
                                                </td>
                                                <td data-title="Unit Price" class="text_right_all add-invoice-number">

                                                    <div class="form-group prefix_align_center">
                                                        <div class="dollar-div">
                                                            <span class="fieldAffix-item">$</span>
                                                            <input type="number" step="any"
                                                            v-on:change="changeLineItemBasePrice(item.base_price, index)"
                                                            v-model.trim="item.base_price" class="form-control"
                                                            placeholder="Price"
                                                            :class="{ 'is-invalid': submit & Number(item.base_price) <= 0, 'is-valid': Number(item.base_price) > 0 }">
                                                        </div>
                                                        <div class="invalid-feedback" v-if="submit"> <span
                                                                v-if="Number(item.base_price) <= 0">Price is Required</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td data-title="Total" class="text_right_all total-invice-add new-add-line">
                                                    <div class="dollar-div">
                                                            <span class="fieldAffix-item">$</span>
                                                    <span class="fake_form_control">{{ item.total_price }} </span></div>
                                                   <button type="button" @click="removeLineItem(item, index)"
                                                        class="cross__button text_end_only_desktop text-md-end btn-close btncclose "></button>
                                                </td>
                                            </tr>
                                        </div>
                                    </tbody>
                                    <!-- add line item button-->

                                    <div v-if="totalTax" class=" invoice_sub_total">
                                        <div class="row">
                                            <div class="col-md-6 col-8 left">
                                                <h6> Invoice Subtotal</h6>
                                            </div>
                                            <div class="col-md-6 col-4 right">
                                                <h6>
                                                    {{ totalInvoice | toCurrency }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="totalTax" class=" invoice_sub_total">
                                        <div class="row">
                                            <div class="col-md-6 col-8 left">
                                                <h6 v-if="invoice.job.property.tax_type">
                                                    {{ invoice.job.property.tax_type.name }}
                                                    ({{ invoice.job.property.tax_type.rate }}%)
                                                </h6>
                                            </div>
                                            <div class="col-md-6 col-4 right">
                                                <h6>
                                                    {{ totalTax | toCurrency }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    <tfoot class="bg_blue">
                                        <tr class="bg_blue tfoot_bold_text tfoot_text_white">
                                            <td colspan="4" class="display_on_desktop">Invoice Total:</td>
                                            <td colspan="3" data-title="Invoice Balance" class="text-right"> <span>&nbsp;
                                                    &nbsp;</span> {{ totalInvoice + totalTax | toCurrency }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div v-if="(role == roleData.admin) || (role == roleData.super_admin)"
                                    class="inline_buttonss text-end before_payment_buttons">
                                    <div v-if="((role == roleData.admin) || (role == roleData.super_admin)) "
                                        class="left_buttons" style="text-align: left">
                                        <div class="form-group mb-0">
                                            <button type="button" @click="getPaymentInformation"
                                                v-if="payableAmount > 0"
                                                class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600"
                                                data-bs-toggle="modal" data-bs-target="#paymentAdd">Collect Payment</button>
                                            <a v-if="invoice.is_qb_synced == 0" :href="'/qb/sync/invoice/' + id" @click="showLoading"
                                                class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Sync
                                                with QB</a>

                                        </div>
                                        <span class="text-danger" v-if="invoice.is_qb_synced == 0"><strong>Note:</strong> The invoice has not synced with <strong>Quick Books</strong> yet.</span>

                                    </div>
                                    <button type="button"
                                        class="btn btn_orange border_radius_5 font_14 text_uppercase font_weight_600 mg_top_14"
                                        @click="addItem">Add Line Item</button>
                                    <button type="submit"
                                        class="btn btn_medium btn_orange mg_top_14 border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
                                    <button type="submit" @click="saveAndClose"
                                        class="btn btn_medium btn_orange mg_top_14 border_radius_5 text-uppercase font_14 font_weight_600">Save
                                        &amp; Close</button>
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================================================================= -->
                        <!-- ============================================================================================================= -->
                        <!-- ============================================================================================================= -->
                        <!-- ============================================================================================================= -->
                        <!-- ============================================================================================================= -->
                        <hr>

                        <div class="table_area" v-if="(role == roleData.admin) || (role == roleData.super_admin)">
                            <div
                                class="no_more_tables table_to_be_used mg_top_14 table_with_short_spacing table_with_white_bg">
                                <div class="row" v-if="(role == roleData.admin) || (role == roleData.super_admin)">
                                    <div class="col-sm-12">
                                        <div class="heading_paragrph_box mg_bot_20">
                                            <h2
                                                class=" text_color_orange  font_weight_600 font_family_Montserrat mg_bot_20_on_desktop">
                                                Payment History</h2>
                                        </div>
                                    </div>
                                </div>
                                <table class="table payments_table">

                                        <tr>
                                            <th class="width_30_percent">Payment Description</th>
                                            <th class="width_20_percent">Method</th>
                                            <th class="width_10_percent">Check No</th>
                                            <th class="width_10_percent">Confirmation No</th>
                                            <th class="width_20_percent">Date</th>
                                            <th class="width_10_percent">Total</th>
                                            <th
                                                class="width_10_percent">Action</th>
                                        </tr>


                                        <tr v-for="item in invoice.transactions" :key="item.id">
                                            <td data-title="Payment Description">{{ item.notes ? item.notes : '--' }} </td>
                                            <td data-title="Payment Description">{{ item.method }} </td>
                                            <td data-title="Check No">{{ item.check_no ? item.check_no : '--' }}</td>
                                            <td data-title="Confirmation No">
                                                {{ item.confirmation_no ? item.confirmation_no : '--' }}
                                            </td>
                                            <td data-title="Payment Date">{{ item.transaction_date | formatDate }}</td>
                                            <td data-title="Total">{{ item.amount | toCurrency }}</td>
                                            <td data-title="edit">
                                                <a @click="setTransactionData(item)" data-bs-toggle="modal"
                                                    data-bs-target="#editTransactionModal"
                                                    class="font_weight_700 table_btn_link">Edit</a>

                                            </td>
                                        </tr>


                                        <tr v-if="creditAmount > 0"
                                            class="bg_blue tfoot_bold_text tr_border_bottom tfoot_text_white">
                                            <td class="display_on_desktop  " data-title="Invoice Total" colspan="6">Client's
                                                Credit</td>
                                            <td data-title="Client's Credit">{{ creditAmount | toCurrency }}</td>
                                        </tr>
                                        <tr class="bg_blue tfoot_bold_text tr_border_bottom tfoot_text_white">
                                            <td class="display_on_desktop  " data-title="Invoice Total" colspan="6">Total
                                                Paid</td>
                                            <td data-title="Total Paid">{{ totalPaid | toCurrency }}</td>
                                        </tr>
                                        <tr v-if="payableAmount > 0"
                                            class="bg_blue tfoot_bold_text tr_border_bottom tfoot_text_white">
                                            <td class="display_on_desktop  " data-title="Invoice Total" colspan="6">Amount
                                                Due</td>
                                            <td data-title="Amount Due">${{ payableAmount }}</td>
                                        </tr>

                                </table>
                            </div>
                        </div>

                        <!--to add payments-->
                        <div class="modal fade custom_base_model custom_base_model_small" id="paymentAdd" tabindex="-1"
                            aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true" data-bs-backdrop="static">
                            <AddPayment :invoiceAmount="totalInvoice" :invoiceId="id" :payableAmount="payableAmount"
                                :clientId="clientId" :creditAmount="creditAmount" @closeEvent="forceRerender"></AddPayment>
                        </div>
                        <!--to view notes of employers-->
                        <div class="modal fade custom_base_model custom_base_model_small" id="ViewNotes" tabindex="-1"
                            aria-labelledby="viewNotes" aria-hidden="true" data-bs-backdrop="static">
                            <ViewNotes @closeEvent="notesForceRenderer" :notesData="notesData"></ViewNotes>
                        </div>
                        <!--to view pdf-->
                        <div class="modal fade custom_base_model custom_base_model_large" id="viewInvoice" tabindex="-1"
                            aria-labelledby="lineItem" aria-hidden="true" data-bs-backdrop="static">
                            <viewPdf :url="url" @closeEvent="ClosePdf"></viewPdf>
                        </div>
                        <!--edit transation modal-->
                        <div class="modal fade custom_base_model custom_base_model_small" id="editTransactionModal"
                            tabindex="-1" aria-labelledby="apaintFinishingAddAddLabel" aria-hidden="true"
                            data-bs-backdrop="static">
                            <EditInvoiceTransaction :transationData="transationData" :invoiceAmount="totalInvoice"
                                :invoiceId="id" :payableAmount="payableAmount" :clientId="clientId"
                                :creditAmount="creditAmount" @closeEvent="forceRerender"></EditInvoiceTransaction>
                        </div>
                        <div class="modal fade custom_base_model custom_base_model_large" id="sendInvoice" tabindex="-1"
                            aria-labelledby="sendInvoice" aria-hidden="true" data-bs-backdrop="static">
                            <ConfirmSendInvoice @closeEvent="forceRerender" :invoice="invoiceData" :clientId="clientId">
                            </ConfirmSendInvoice>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal fade custom_base_model custom_base_model_small" id="CancelInvoicePopup" tabindex="-1"
                aria-labelledby="CancelInvoicePopup" aria-hidden="true" data-bs-backdrop="static">
                <CancelInvoicePopup @closeEvent="cancelInvoice"></CancelInvoicePopup>
            </div>
        </div>
    </div>
</template>
<script>
import jQuery from 'jquery';


import axios from "axios";
import loader from "../LoaderSpinner";
import AddPayment from "./AddPayment";
import roleData from '../../data.js';
import ViewNotes from "./ViewNotes";
import Multiselect from 'vue-multiselect';
import Sortable from 'vue-drag-sortable';
import { required } from 'vuelidate/lib/validators';
import ConfirmSendInvoice from './confirmSendInvoice';
import viewPdf from './viewPdf';
import moment from 'moment';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import CancelInvoicePopup from './cancelInvoicePopup';
import EditInvoiceTransaction from "./EditInvoiceTransaction";
import Navigation from "../../components/Navigation";
import { BButton, BTooltip } from 'bootstrap-vue'

export default {
    name: "viewInvoice",
    components: {
        'loader': loader,
        'AddPayment': AddPayment,
        Multiselect,
        'ViewNotes': ViewNotes,
        'ConfirmSendInvoice': ConfirmSendInvoice,
        'viewPdf': viewPdf,
        'Sortable': Sortable,
        'Datepicker': Datepicker,
        'CancelInvoicePopup': CancelInvoicePopup,
        'EditInvoiceTransaction': EditInvoiceTransaction,
        'Navigation': Navigation,
        'b-button': BButton,
        'b-tooltip': BTooltip,
    },
    data() {
        return {
            itemTemp:undefined,
            index1Temp: undefined,
            removeLine:false,
            momentFormat: {
                //[optional] Date to String
                stringify: (date) => {
                    return date ? moment(date).format('LL') : ''
                },
                //[optional]  String to Date
                parse: (value) => {
                    return value ? moment(value, 'LL').toDate() : null
                },
                //[optional] getWeekNumber
                getWeek: (date) => {
                    return // a number
                }
            },
            id: this.$route.params.id,
            successmsg: "",
            errormsg: "",
            loading: true,
            errorMsgForDate: '',
            invoice: "",
            txtSync: "",
            totalInvoice: 0,
            totalPaid: 0,
            invoiceView: false,
            submit: false,
            services: [],
            parentServices: [],
            invoiceForm: {
                invoiceId: this.$route.params.id,
                invoiceDetails: [],
                lineItems: []
            },
            dragData: {},
            role: "",
            roleData: roleData,
            notesData: "",
            notes_data: "",
            options: [],
            requested_job_services: [],
            invoiceIssueDate: "",
            closeFlag: false,
            invoiceData: "",
            url: "",
            totalTax: 0,
            payableAmount: 0,
            clientId: "",
            creditAmount: 0,
            transationData: {},
        }
    },
    mounted() {
        this.getRecord(this.id);
        this.updateInvoicePdf();
        this.role = localStorage.getItem("role");
        this.checkSync()
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
    methods: {
        setTransactionData(data) {
            this.transationData = data
        },
        roundTo(str) {
            if (typeof str !== "number") {
                return str;
            }
            var formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            });
            return formatter.format(str);

            // return Math.round((n + Number.EPSILON) * 100) / 100;
        },
        customFormate(e) {
            jQuery("tbody.tbody.draggable-inv").addClass('no');
            jQuery("[key='" + e + "']").closest('tbody').removeClass('no');
            // console.log("down");
        },
        click(e) {
            jQuery("tbody.tbody.draggable-inv").addClass('no');
            jQuery("[key='" + e + "']").closest('tbody').removeClass('no');
            // console.log("down");
        },
        clickup(e) {
            jQuery("tbody.tbody.draggable-inv").removeClass('no');
            // jQuery("[key='"+e+"']").closest('tbody').removeClass('no');
            // console.log("up");
        },
        getRecord(id) {
            axios.get('/api/invoice/' + id).then((response) => {
                this.invoice = response.data.invoice;
                // this.invoice = this.sortFunc(this.invoice);
                // this.clientId = this.invoice.job.property.client.id;
                this.clientId = this.invoice.job.property.id;
                this.totalInvoice = response.data.totalInvoice;
                this.totalPaid = response.data.totalPaid;
                this.invoiceForm.invoiceDetails = this.invoice;
                // this.invoiceForm.lineItems = this.invoice.invoice_line_item;
                var issueDateUnformatted = this.invoice.created_at;
                this.invoiceIssueDate = moment(issueDateUnformatted).format('YYYY-MM-DD');
                this.invoice.due_date = moment(this.invoice.due_date).format('YYYY-MM-DD');
                //for line item
                this.invoiceForm.invoiceDetails.job.job_services.forEach(data => {
                    if (data.scheduled_date) {
                        // var filter_date_unformatted = new Date(data.scheduled_date);
                        data.scheduled_date = moment(data.scheduled_date).format('YYYY-MM-DD');
                    }
                    if (data.end_date) {
                        // var filter_date_unformatted = new Date(data.end_date);
                        data.end_date = moment(data.end_date).format('YYYY-MM-DD');
                    }

                    data.requested_job_sub_service.forEach(subservives => {
                        if (subservives.base_price) {
                            subservives.base_price = (Math.round(subservives.base_price * 100) / 100).toFixed(2);
                            subservives.total_price = (Math.round(subservives.total_price * 100) / 100).toFixed(2);

                        }

                    });
                    this.getNotesAttachments(data);
                });
                this.getPropertyServices()
                this.totalTax = response.data.totalTax;
                this.updateInvoiceTotal(this.totalInvoice);
                this.getPaymentInformation();
                setTimeout(()=>{
                if(!(this.payableAmount>0))
                {
                    this.getPaymentInformation();
                }
                },2000)
            });
        },
        selctedService(event, index) {
            this.invoiceForm.lineItems[index].base_price = Number(event.price);
            this.invoiceForm.lineItems[index].total_price = Number(event.price);
            // to remove from options
            let index1 = this.services.findIndex(item => item.id == event.id);
            if (index1) {
                this.services.splice(index1, 1);
                this.multiSelectOption();
            }
            this.updateTotal();
            this.getTaxInformation();
        },
        getPropertyServices() {
            let appendUrl = "";
            try {
                if(this.invoice.job.apartment_type_id){
                    appendUrl = "/" + this.invoice.job.apartment_type_id;
                }
            }catch(e){}

            axios.get('/api/property-services/' + this.invoice.job.property_id + appendUrl ).then((response) => {
                this.services = response.data.services;
                this.parentServices = response.data.parentServices;
                this.selectedServices();
            });
        },
        selectedServices() {
            this.invoice.job.job_services.forEach((data) => {
                data.requested_job_sub_service.forEach((item) => {
                    let service = this.services.find(subService => (subService.service_type_id == data.service_id && subService.sub_service_id == item.sub_service_id));
                    if (service) {
                        this.services.splice(this.services.indexOf(service), 1);
                    }
                })
            });
            this.multiSelectOption();
        },
        multiSelectOption() {
            this.services.sort((a, b) => a.id - b.id);
            this.options = [];
            this.parentServices.forEach((value) => {
                // this.options.push(value);
                if (this.services.length > 0) {
                    this.services.some(item => {
                        if (value.service_type_id == item.service_type_id) {
                            item.name = item.service_sub_category.name;
                            this.options.push(item);
                        }
                    })
                }
            });
            this.loading = false;
        },
        sortFunc: function (array) {
            let data = (array.job.job_services).slice().sort(function (a, b) {
                return (a.end_date < b.end_date) ? 1 : -1;
            });
            array.job.job_services = data;
            return array;
        },
        forceRerender(msg) {
            this.successmsg = "";
            this.loading = true;
            this.getRecord(this.id);
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },
        notesForceRenderer() {
            this.notesData = null;
        },
        saveAndClose() {
            this.closeFlag = true;
        },
        scrollToTable(toRef) {
            try {
                this.$refs[toRef].scrollIntoView({ behavior: "smooth" })
            } catch(e){}
        },
        edit_invoice_submit() {
            this.submit = true;
            this.loading = true;
            for (let data of this.invoiceForm.lineItems) {
                if (data.subService == '' || Number(data.base_price) < 0 || Number(data.quantity) < 1) {
                    this.loading = false;
                    this.scrollToTable("invoiceTable");
                    return;
                }
            }
            this.invoiceForm.invoiceDetails.job.lineItems = this.invoiceForm.lineItems;
            this.invoiceForm.invoiceDetails.job.invoice_due_date = this.invoice.due_date;
            let endDateBeforeStartDate = false;

            this.invoiceForm.invoiceDetails.job.job_services.forEach( (item, indx) => {
             this.invoiceForm.invoiceDetails.job.job_services[indx]['errorMsgForDate'] = "";
                if (this.invoiceForm.invoiceDetails.job.job_services[indx].requested_job_sub_service.length < 1)
                {
                    return
                }
                if (item.scheduled_date && item.end_date) {
                        const startDate = new Date(item.scheduled_date);
                        const endDate = new Date(item.end_date);
                        const isBefor = moment(endDate).isBefore(startDate);
                        if(isBefor) {
                              this.invoiceForm.invoiceDetails.job.job_services[indx]['errorMsgForDate'] = 'The scheduled date must be prior to the completion date';
                        }
                        endDateBeforeStartDate = endDateBeforeStartDate || isBefor;
                        if (endDateBeforeStartDate) return;
                }


            });
            if(!endDateBeforeStartDate){
    this.invoiceForm.invoiceDetails.job.job_services.forEach(function (item) {

                // if (item.scheduled_date && item.end_date) {
                //         const startDate = new Date(item.scheduled_date);
                //         const endDate = new Date(item.end_date);
                //         endDateBeforeStartDate = moment(endDate).isBefore(startDate)
                //         if (endDateBeforeStartDate) return;
                // }
                if (item.scheduled_date) {
                    var filter_date_unformatted = new Date(item.scheduled_date);
                    item.scheduled_date = moment(filter_date_unformatted).format('YYYY-MM-DD');
                }
                if (item.end_date) {
                    var filter_date_unformatted = new Date(item.end_date);
                    item.end_date = moment(filter_date_unformatted).format('YYYY-MM-DD');
                }

            });
            }


            if(endDateBeforeStartDate) {
                this.scrollToTable("invoiceTable");
                // this.errorMsgForDate = 'The scheduled date must be prior to the completion date';
                this.loading = false;
                // this.submit=false;
                return;
            } else {
                this.errorMsgForDate = '';
            }
            // // console.log("service", this.invoiceForm.invoiceDetails.job.job_services);
            axios.put('/api/edit-invoice/' + this.id, this.invoiceForm.invoiceDetails.job).then(response => {
                this.loading = false;
                if (this.closeFlag) {
                    this.$router.push('/invoices');
                } else {
                    this.invoiceForm.invoiceDetails.job.job_services.forEach(data => {
                        if (data.scheduled_date) {
                            var filter_date_unformatted = new Date(data.scheduled_date);
                            data.scheduled_date = moment(filter_date_unformatted).format('YYYY-MM-DD');
                        }
                        if (data.end_date) {
                            var filter_date_unformatted = new Date(data.end_date);
                            data.end_date = moment(filter_date_unformatted).format('YYYY-MM-DD');
                        }

                        this.getNotesAttachments(data);
                    });
                    this.successmsg = "Invoice has been updated";
                    this.invoiceForm.lineItems = [];
                    this.submit = false;
                    this.getRecord(this.id);
                }
                setTimeout(() => {
                    this.successmsg = "";
                }, 3000);
            }).catch(err => {
                this.has_error = true;
            })
        },
        showLoading() {
            this.loading = true
        },
        viewNotesMethod(item) {
            this.notesData = item.notes_data;
        },
        getNotesAttachments(item) {
            axios.get('/api/employee/get-notes-attachment/' + item.id).then((response) => {
                item.notes_data = response.data.progress;
            });
        },
        checkSync() {
            var success = this.$route.query.success
            if (success == 1) {
                this.txtSync = "Synced";
            } else if (success == 0) {
                this.txtSync = "Unable to Sync";
            }
        },
        downloadInvoice() {
            axios.get('/api/download-invoice/' + this.id, {
                responseType: 'blob'
            }).then((response) => {
                if (this.invoiceView) {
                    var url = URL.createObjectURL(new Blob([response.data], {
                        type: 'application/pdf'
                    }));
                    this.url = url;
                    // window.open(url, '_blank');
                    this.invoiceView = false;
                    url = '';
                } else {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'invoice.pdf');
                    document.body.appendChild(fileLink);
                    fileLink.click()
                    fileLink.parentNode.removeChild(fileLink);
                }
            });
        },
        getInvoiceData() {
            this.invoiceData = this.invoice;
            this.invoiceData.total = this.totalInvoice + this.totalTax;
        },
        viewInvoice() {
            this.invoiceView = true;
            this.downloadInvoice();
        },
        addItem() {
            var item = {
                subService: "",
                description: "",
                base_price: 0,
                quantity: 1,
                total_price: 0
            };
            this.invoiceForm.lineItems.push(item);
        },
        removeLineItem(item, index, showPopup=true) {
            this.removeLine = true;
            if(showPopup)
            {
                this.itemTemp=item;
                this.index1Temp = index;
                $("#removeOneServiceInvoicePop").modal('show');

                return;
            }
            this.invoiceForm.lineItems.splice(index, 1);
            this.services.push(item.subService);
            this.multiSelectOption();
            this.updateTotal();
            this.getTaxInformation();
        },
        removeOneService(proceed) {
            if(proceed)
            {
                if(this.removeLine)
                {
                    this.removeLineItem(this.itemTemp, this.index1Temp, false);
                } else {
                    this.removeItem(this.itemTemp, this.index1Temp, false);
                }

            }
            $("#removeOneServiceInvoicePop").modal('hide');
            this.itemTemp=undefined;
            this.index1Temp =undefined;
        },

        removeItem(index, index1, showPopup=true) {
            this.removeLine = false;
            if(showPopup)
            {
                this.itemTemp=index;
                   this.index1Temp =index1;
                $("#removeOneServiceInvoicePop").modal('show');

                return;
            }
            this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].is_invoice = 0;
            if (this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service.every(e =>  e.is_invoice == 0)) {
                this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service = [];
            }
            this.updateTotal();
            this.getTaxInformation();
        },
        changeQuantity(val, index, index1) {
            this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].total_price = this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].base_price * val;
            this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].total_price = (Math.round(this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].total_price * 100) / 100).toFixed(2)
            this.updateTotal();
            this.getTaxInformation();
        },
        changeBasePrice(val, index, index1) {
            this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].total_price = this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].quantity * val;

            this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].total_price = (Math.round(this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].total_price * 100) / 100).toFixed(2)

            this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].base_price = (Math.round(this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].base_price * 100) / 100).toFixed(2)
            this.updateTotal()
            this.getTaxInformation();
        },
        changeLineItemQuantity(val, index) {
            this.invoiceForm.lineItems[index].total_price = this.invoiceForm.lineItems[index].base_price * val;
            this.invoiceForm.lineItems[index].total_price = (Math.round(this.invoiceForm.lineItems[index].total_price * 100) / 100).toFixed(2);
            this.updateTotal();
            this.getTaxInformation();
        },
        changeLineItemBasePrice(val, index, index1) {
            this.invoiceForm.lineItems[index].total_price = this.invoiceForm.lineItems[index].quantity * val;

            this.invoiceForm.lineItems[index].total_price = (Math.round(this.invoiceForm.lineItems[index].total_price * 100) / 100).toFixed(2);
            this.invoiceForm.lineItems[index].base_price = (Math.round(this.invoiceForm.lineItems[index].base_price * 100) / 100).toFixed(2);
            this.updateTotal()
            this.getTaxInformation();
        },
        updateTotal() {
            let sum = 0;
            this.invoiceForm.invoiceDetails.job.job_services.forEach(item => {
                var i = item.requested_job_sub_service.length;
                while (i--) {
                    if (item.requested_job_sub_service[i].is_invoice == 1) {
                        sum = sum + Math.round(item.requested_job_sub_service[i].total_price);
                    }
                }
            })
            var i = this.invoiceForm.lineItems.length;
            while (i--) {
                sum = sum + Number(this.invoiceForm.lineItems[i].total_price);
            }
            this.totalInvoice = sum;

        },
        // changeOrderDown(item, index, index0) {
        // // save clicked item in temporary variable
        // let temp = item
        // // move the following item to the clicked element
        // Vue.set(this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service, index, this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service [index + 1])
        // if( this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service[index].order != 0){
        //     this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service[index].order = this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service[index].order-1
        // }
        // // move clicked item to destination
        // temp.order = temp.order+1
        // Vue.set(this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service, index+1, temp);
        // },
        // changeOrderUp(item, index, index0) {
        //     // save clicked item in temporary variable
        //     let temp = item
        //     // move the following item to the clicked element
        //     Vue.set(this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service, index, this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service [index - 1])
        //     this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service[index].order=this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service[index].order+1;
        //     // move clicked item to destination
        //     if(temp.order != 0){
        //         temp.order = temp.order-1
        //     }
        //     Vue.set(this.invoiceForm.invoiceDetails.job.job_services[index0].requested_job_sub_service, index-1,temp);
        // },
        sortend(e, list, id) {
            const {
                oldIndex, newIndex
            } = e
            this.rearrange(list, oldIndex, newIndex)
            jQuery("tbody.tbody.draggable-inv").removeClass('no');
            // console.log("sort_end");
        },
        rearrange(array, oldIndex, newIndex) {
            if (oldIndex > newIndex) {
                array.splice(newIndex, 0, array[oldIndex])
                array.splice(oldIndex + 1, 1)
            } else {
                array.splice(newIndex + 1, 0, array[oldIndex])
                array.splice(oldIndex, 1)
            }
            jQuery("tbody.tbody.draggable-inv").removeClass('no');
        },
        cancelInvoice(val) {
            if (val == 1) {
                this.loading = true;
                axios.put('/api/cancel-invoice/' + this.id).then((response) => {
                    this.loading = false;
                    if (response.data.error) {
                        this.errormsg = response.data.error;
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    } else {
                        this.successmsg = response.data.message;
                        setTimeout(() => {
                            this.successmsg = "";
                            this.$router.push('/cancelled-invoices');
                        }, 1000);
                    }
                });
            }
        },
        changeInvoiceDate(value) {
            var filter_date_unformatted = new Date(value);
            let filter_date = moment(filter_date_unformatted).format('YYYY-MM-DD');
            var date = filter_date;
            axios.put('/api/change-invoice-date/' + this.id, {
                'date': date
            }).then(response => {
                this.loading = false;
                this.successmsg = "Invoice date has been updated";
                this.edit_invoice_submit();
                setTimeout(() => {
                    this.successmsg = "";
                }, 3000);
            }).catch(err => {
                this.has_error = true;
            })
        },
        ClosePdf() {
            // console.log('ok');
            this.url = '';
        },
        getClass(item, index) {

            if (item.length > 1 && index == 0) {
                return 'first';
            } else if (item.length == 1 && index == 0) {
                return 'first last';
            } else if (index == item.length - 1) {
                return 'last';
            }
        },
        getTaxInformation() {
            if (this.invoice.job.apartment_status == 'vacant') {
                var taxPercentage = this.invoice.job.property.tax_type ? this.invoice.job.property.tax_type.rate : 0;
                var totalServicePrice = 0;
                if (taxPercentage) {
                    this.invoiceForm.invoiceDetails.job.job_services.forEach(item => {
                        if (item.service && item.service.is_taxable == 1) {
                            item.requested_job_sub_service.forEach(item => {
                                totalServicePrice += Math.round(item.total_price);
                            })
                        }
                    })
                    this.totalTax = (taxPercentage / 100) * totalServicePrice;
                    this.totalTax = parseFloat(this.totalTax.toFixed(2));
                    // this.totalTax = Math.round(this.totalTax);
                    // // console.log("this.totalTax ",this.totalTax )
                }
            }
        },
        updateInvoicePdf() {
            axios.put('/api/update-invoice-pdf/' + this.id).then(response => {
            }).catch(err => {
                this.has_error = true;
            })
        },
        updateInvoiceTotal(invoiceTotal) {
            const totalPayable = invoiceTotal + this.totalTax;
            axios.put('/api/update-invoice-total/' + this.id, { 'invoiceTotal': totalPayable }).then(response => {
            }).catch(err => {
                this.has_error = true;
            })
        },
        getPaymentInformation() {
            axios.get('/api/payment-info/' + this.id + '/client/' + this.clientId).then((response) => {
                this.payableAmount = (Math.round(response.data.payableAmount * 100) / 100).toFixed(2);
                this.creditAmount = response.data.creditAmount;
            });
        }
    },
}


// Asad aali
// jQuery(document).ready(function(){
	// // console.log("main ready hun");
	// jQuery(".sortable-inv_old tr").on("click",function(){
	// 	jQuery("tbody.tbody.draggable-inv").addClass('no');
	// 	jQuery(".sortable-inv_old").closest('tbody').removeClass('no');
	// 	// console.log("sss");
	// });

// });
</script>
<style scoped></style>
