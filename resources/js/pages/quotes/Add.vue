<template>
    <div>
        <div v-if="loading">
            <Loader></Loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                <strong>{{ successmsg }}</strong>
            </div>
            <!-- <div id="errir-alert" class="alert alert-danger" v-if="errormsg!=='' && successmsg == ''">
                <strong>{{ errormsg }}</strong>
            </div> -->
            <div class="row row_spacing_5">
                    <div class="col-sm-6 col-8" v-if="!id"><h1 class="h4 heading_text">Create Quote</h1></div>
                    <div class="col-sm-6 col-8 d-md-flex mb-sm-2-custom" v-if="id">
                        <h1 class="h4 heading_text">Edit Quote # {{id}}</h1>


                        <b-button v-if="job.quote_status == 'approved'" variant="success custom_status_btn">Approved</b-button>
                        <b-button v-else-if="job.quote_status == 'awaiting_response'" variant="danger custom_status_btn">Awaiting Response</b-button>
                        <b-button  v-else-if="job.quote_status == 'draft'" variant="info custom_status_btn">Draft</b-button>
                        <b-button v-else-if="job.quote_status == 'changes_requested'" variant="warning custom_status_btn">Changes Requested</b-button>
                        <b-button v-else-if="job.quote_status == 'converted'" variant="success custom_status_btn">Converted</b-button>
                        <b-button v-else-if="job.quote_status == 'archived'" variant="warning custom_status_btn">Archived</b-button>

                    </div>
                    <Navigation></Navigation>
                <div class="inline_buttonss">
                    <div class="row">
                        <div class="col-sm-12 text-end">

                            <router-link v-if="id && job.quote_status != 'approved' && (job.created_by?.roles?.id == roleData.super_admin)  && job.quote_status != 'converted' && role == roleData.client" to="#" data-bs-toggle="modal" data-bs-target="#updateQuoteModal" @click.native="setQuoteStatus('approved')" class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">Approve</router-link>
                            <router-link v-if="(role == roleData.super_admin || role == roleData.admin) && id && showEmailAndOtherOptions" to="#" data-bs-toggle="modal" data-bs-target="#sendQuoteEmailModal" @click.native="setJobData()" class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">Send Email</router-link>
                            <a v-if="(role == roleData.super_admin || role == roleData.admin) && id && showEmailAndOtherOptions" href="#" id="dropdownMenuButton" class="no_action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <button  class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600 custom-action-btn">
                                    More actions
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="16" height="16" x="0" y="0" viewBox="0 0 32 32" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M29.604 10.528 17.531 23.356a2.102 2.102 0 0 1-3.062 0L2.396 10.528c-.907-.964-.224-2.546 1.1-2.546h25.008c1.324 0 2.007 1.582 1.1 2.546z" fill="#ffffff" data-original="#000000" opacity="1" class=""></path></g></svg>                                </button>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <router-link v-if="job.quote_status != 'converted'" to="to" data-bs-toggle="modal" data-bs-target="#updateQuoteModal" @click.native="setQuoteStatus('approved')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Approve </router-link>
                                <router-link v-if="job.quote_status != 'converted'" to="to" data-bs-toggle="modal" data-bs-target="#updateQuoteModal" @click.native="setQuoteStatus('awaiting_response')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Awaiting Response </router-link>
                                <router-link v-if="job.quote_status != 'converted'" to="to" data-bs-toggle="modal" data-bs-target="#updateQuoteModal" @click.native="setQuoteStatus('changes_requested')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Changes Requested </router-link>
                                <router-link v-if="job.quote_status != 'converted'" to="to" data-bs-toggle="modal" data-bs-target="#updateQuoteModal" @click.native="setQuoteStatus('converted')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Convert to Job </router-link>
                                <router-link v-if="job.quote_status != 'converted'" to="to" data-bs-toggle="modal" data-bs-target="#updateQuoteModal" @click.native="setQuoteStatus('archived')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Archived </router-link>
                                <router-link  to="to" data-bs-toggle="modal" data-bs-target="#viewQuotePdf" @click.native="quotePdf('preview')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Preview </router-link>
                                <router-link  to="to" @click.native="quotePdf('download')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Download PDF </router-link>
<!--                                <router-link  to="#" data-bs-toggle="modal" data-bs-target="#updateQuoteModal" @click.native="quotePdf('print')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Print </router-link>-->

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <form autocomplete="off" @submit.prevent="quote_submit" method="post">
                <div class="table_area">
                    <div class="table_area_head pd_top_24 pd_bot_30">
                        <div class="row row_spacing_5">
                            <div class="col-sm-4">
                                <label>Select Community</label>
                                <div class="form-group">
                                    <multiselect
                                        deselectLabel=""
                                        v-model.trim="quote_form.property_id"
                                        :show-labels="false"
                                        track-by="title"
                                        label="title"
                                        placeholder="Select Community"
                                        :select-label="''"
                                        :options="properties"
                                        :searchable="true"
                                        :class="{'is-invalid':$v.quote_form.property_id.$error, 'is-valid':!$v.quote_form.property_id.$invalid}"
                                        @select="selectProperty"
                                        ref="community_multiselect" :selectedLabel="''">
                                        <template #singleLabel="{ option }">
                                            <div>
                                                <span class="multiselect__single-label edit-communit">{{ option.title }}</span>
                                                <button v-if="quote_form.property_id" class="multiselect__clear" @mousedown="clearCurrentFilter('community')">
                                                    &#x2715;
                                                </button>
                                            </div>
                                        </template>

                                    </multiselect>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.quote_form.property_id.required">Field is Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label>Unit Size</label>
                                <div class="form-group">
                                    <multiselect deselectLabel="" v-model.trim="quote_form.apartment_type_id"
                                        @input="onAppointmentTypeChange"
                                        :show-labels="false" track-by="type" label="type" placeholder="Unit Size"
                                        :select-label="''" :options="appartmentTypes" :searchable="true"
                                        :class="{ 'is-invalid': $v.quote_form.apartment_type_id.$error, 'is-valid': !$v.quote_form.apartment_type_id.$invalid }"
                                        ref="size_multiselect" :selectedLabel="''" :allow-empty="true">
                                        <template #singleLabel="{ option }">
                                            <div>
                                                <span class="multiselect__single-label unit-size">{{ option.type }}</span>
                                                <button v-if="quote_form.apartment_type_id" class="multiselect__clear"
                                                    @mousedown="clearCurrentFilter('size')">
                                                    &#x2715;
                                                </button>
                                            </div>
                                        </template>
                                    </multiselect>
                                    <!-- <multiselect
                                        deselectLabel=""
                                        v-model.trim="quote_form.apartment_type_id"
                                        :show-labels="false"
                                        track-by="type"
                                        label="type"
                                        placeholder="Unit Size"
                                        :select-label="''"
                                        :options="appartmentTypes"
                                        :searchable="true"

                                        :class="{'is-invalid':$v.quote_form.apartment_type_id.$error, 'is-valid':!$v.quote_form.apartment_type_id.$invalid}"
                                        ref="size_multiselect" :selectedLabel="''">
                                        <template #singleLabel="{ option }">
                                            <div>
                                                <span class="multiselect__single-label">{{ option.type }}</span>
                                                <button v-if="quote_form.apartment_type_id" class="multiselect__clear" @mousedown="clearCurrentFilter('size')">
                                                    &#x2715;
                                                </button>
                                            </div>
                                        </template>

                                    </multiselect> -->
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.quote_form.apartment_type_id.required">Field is Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label>Unit #</label>
                                <div class="form-group">
                                    <input
                                        type="text"
                                        v-model.trim="$v.quote_form.apartment_number.$model"
                                        class="form-control"
                                        placeholder="Unit #"
                                        :class="{'is-invalid':$v.quote_form.apartment_number.$error, 'is-valid':!$v.quote_form.apartment_number.$invalid}"

                                    />
                                    <div class="invalid-feedback" >
                                        <span v-if="!$v.quote_form.apartment_number.maxLength">Field length must not be greater then 20 Characters</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3" v-if="is_PO">
                                <label>PO #</label>
                                <div class="form-group">
                                    <input
                                        type="text"
                                        v-model.trim="$v.quote_form.po_number.$model"
                                        class="form-control"
                                        :class="{'is-invalid':submit && $v.quote_form.po_number.$error, 'is-valid':!$v.quote_form.po_number.$invalid}"
                                        placeholder="PO #"
                                    />
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.quote_form.po_number.required">Field is Required</span>
                                        <span v-if="!$v.quote_form.po_number.minLength">Field length must be 2 Characters</span>
                                        <span v-if="!$v.quote_form.po_number.maxLength">Field length must not be greater then 20 Characters</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center row_spacing_5 mg_bot_18">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Apartment Status</h6>
                            </div>
                            <div class="form-group">
                                <div class="inline_radio radio_in_six">
                                    <div class="custom_radio">
                                        <label>
                                            <input value="vacant" type="radio" checked v-model.trim="$v.quote_form.apartment_status.$model" />
                                            <span class="box"></span>Vacant
                                        </label>
                                    </div>
                                    <div class="custom_radio">
                                        <label>
                                            <input value="occupied" type="radio" v-model.trim="$v.quote_form.apartment_status.$model" />
                                            <span class="box"></span>Occupied
                                        </label>
                                    </div>
                                </div>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.quote_form.apartment_status.required">Field is Required</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="row align-items-center row_spacing_5 mg_bot_30" v-if="role==roleData.client || clientId">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Service Ready Checklist</h6>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div >
                                <div class="custom_checkbox" v-for="(item, index) in serviceReadyChecklist" :key="index" :value="item.id">
                                    <label>
                                        <input type="checkbox" :value="item.id" v-model.trim="$v.quote_form.job_service_ready_checklist.$model" />
                                        <span class="box">{{ item.name }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <span v-if="submit && $v.quote_form.job_service_ready_checklist.$anyError" style="color: #e3342f;">Atleast one Record is Required</span>
                    <div class="row align-items-center row_spacing_5 mg_bot_30">
                        <div class="col-md-12">
                            <div id="success-alert" class="alert alert-success" v-if="servcMsg !== ''">
                                    <strong>{{ servcMsg }}</strong>
                                </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" v-if="services.length > 0">
                                <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Choose Services</h6>
                            </div>

                            <!-- ====================== -->
                            <!-- <div id="list1" v-show="services.length > 0" class="dropdown-check-list dropdown_check_list" tabindex="100">
                                <span id="anchors" class="anchor" @click="showSection">
                                    <span v-if="!searchIcon" class="anchor_icons anchor_icons_arrow">
                                        <svg class="anchoricon"><use xlink:href="#arrow_down" /></svg>
                                    </span>
                                    <span v-if="searchIcon" class="anchor_icons anchor_icons_cross" @click="closeOptions()">
                                        <svg class="anchoricon"><use xlink:href="#cancelcross" /></svg>
                                    </span>
                                    <input class="newInputClass form-control" @click="searchOptions()" placeholder="Select Services" v-model="searchQuery" type="text" />
                                </span>

                                <div id="items" class="items">
                                    <ul id="itemss" class="itemss" v-for="(item, index) in resultQuery" v-bind:key="item.id">
                                        <li v-if="!item.service_sub_category">
                                            <strong>{{ item.service.category }}</strong>
                                        </li>
                                        <li>
                                            <ul id="itemss" class="itemss">
                                                <li v-if="item.service_sub_category">
                                                    <div class="custom_checkbox">
                                                        <label>
                                                            <input
                                                                type="checkbox"
                                                                data-ng-model="example.check"
                                                                :value="item"
                                                                v-model.trim="$v.quote_form.requested_job_services.$model"
                                                                @click="updateServiceList(item)"
                                                            />
                                                            <span class="box">{{ item.service_sub_category.name }}</span>
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div> -->


                            <div id="list1" v-show="services.length > 0 " ref="customDropdown"  class="dropdown-check-list dropdown_check_list"
                                tabindex="100">
                                <span id="anchors" class="anchor" @click="searchOptions()">
                                    <span v-if="!searchIcon" class="anchor_icons anchor_icons_arrow">
                                        <svg class="anchoricon">
                                            <use xlink:href="#arrow_down" />
                                        </svg>
                                    </span>
                                    <span v-if="searchIcon" class="anchor_icons anchor_icons_cross" @click="closeOptions()">
                                        <svg class="anchoricon">
                                            <use xlink:href="#cancelcross" />
                                        </svg>
                                    </span>
                                    <input class="newInputClass form-control" @click="searchOptions()"
                                        placeholder="Select Services" v-model="searchQuery" type="text" />
                                </span>

                                <div id="items" v-if="searchIcon" style="visibility: visible;" v-bind:class="{ 'd-none': !searchIcon, 'd-block': searchIcon,   }"    class="items">
                                    <ul id="itemss" class="itemss" v-for="(item, index) in resultQuery"
                                        v-bind:key="index+'a'">
                                        <li v-if="!item.service_sub_category">
                                            <strong>{{ item.service.category }}</strong>
                                        </li>
                                        <li>
                                            <ul id="itemss" class="itemss">
                                                <li v-if="item.service_sub_category">
                                                    <div class="custom_checkbox">
                                                        <label>
                                                            <!-- <input type="checkbox" data-ng-model="example.check"
                                                                :value="item"
                                                                style="visibility: hidden;"
                                                                v-model.trim="$v.job_form.requested_job_services.$model"
                                                                @click="updateServiceList(item)" /> -->
                                                            <span @click="updateServiceList(item)" class="box">{{ item.service_sub_category.name }}</span>
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- ====================== -->
                        </div>
                    </div>
                    <span v-if="submit && $v.quote_form.requested_job_services.$anyError" style="color: #e3342f;">Atleast one Service is Required</span>

                    <div v-if="jobServices.length > 0" class="no_more_tables table_to_be_used table_with_widths more_tables_with_less_pd colapsible_tr_padding">
                        <table class="table edit-job-div">
                            <thead>
                                  <tr>
                                        <th>Services</th>
                                        <th class="text_end_only_desktop">Action</th>
                                    </tr>
                            </thead>
                            <tbody v-for="(item, index) in jobServices" :key="index" class="tbody_new">
                                <tr class="colapsible_tr colapsible_trtd" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                    <td class="uniqueclass" data-title="Service(s)" data-bs-toggle="collapse" :data-bs-target="'#panelsStayOpen-collapseThree' + index"><svg class="table_icon_chevron"><use xlink:href="#right-chevron" /></svg><strong class="new-quote">{{item.service.category}}</strong></td>

                                    <td data-title="Action" class="text_end_only_desktop" >
                                        <a v-if="!item.end_date" class="table_btn_link font_12 font_weight_700 text_uppercase" data-bs-toggle="modal" data-bs-target="#RemoveServicePopup" href="#" @click="removeAllServicesPrompt(item,index)">Cancel</a>
                                    </td>
                                </tr>




                                <tr class="colapsible_tr colapsible_inner_tr" >
                                    <td class="hiddenRow" colspan="9">

                                            <table  :id="'panelsStayOpen-collapseThree'+index" class="add_assign_job_tbl table table_hidden_block accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">

                                                <tbody>
                                                    <tr class="tr_full_in_all">
                                                        <td class="td_full_in_all width_one_box">
                                                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_16">{{item.service.category}} Services</h6>
                                                        </td>
                                                        <td class="text-md-center_no width_two_box mobile-box">
                                                            <div class="project-pay-wrap">
                                                            <div class="table_checkbox"  v-if="role != roleData.client">
                                                                <label>
                                                                    <input type="checkbox" data-ng-model="example.check" v-bind:true-value="1"  v-bind:false-value="0" v-model.trim = "item.is_fixed_price" />
                                                                    <span class="box">Project Pay</span>
                                                                </label>
                                                            </div>
                                                            <div class="form-group ppyroll__Price" v-if="item.is_fixed_price && item.is_fixed_price == 1">
                                                                <div class="dollar-div">
                                                                <span class="fieldAffix-item">$</span>
                                                                <input type="number" strp = "any" class="form-control form-control-sm" v-model.trim = "item.payroll_price" placeholder="Payroll Price" />
                                                                    </div>
                                                            </div>
                                                            </div>
                                                        </td>
                                                        <!-- <td class="text-md-center_no width_three_box"  data-title="Payroll Price" v-if="item.is_fixed_price && item.is_fixed_price == 1"  >


                                                        </td> -->
                                                    </tr>
                                                    <tr class="tr_full_in_all">
                                                        <td :colspan=" 6" class="hiddenRow">
                                                            <div class="hidden_tr_div"   v-for="(item1, index1) in jobServicesDetails[item.service.category]" v-bind:style=" item1.is_invoice == 0 ? 'background-color: antiquewhite' : 'background-color: #f1f5f8' " v-bind:key="index1" >
                                                                <table class="table ">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td data-title="Sub Service(s)" class="width_100">
                                                                                <h2>Service</h2>
                                                                                <div class="item-heading">   {{item1.service_sub_category? item1.service_sub_category.name : item1.service.category}}
                                                                                </div>
                                                                            </td>
                                                                            <td data-title="Description" class="width_200">
                                                                                <h2>Description</h2>
                                                                                <textarea   v-model.trim="item1.description" class="taxarea_job form-control" placeholder="Description"></textarea>
                                                                            </td>
                                                                            <td data-title="Quantity" class="text_right_all width_40">
                                                                                <h2>QTY</h2>
                                                                                <input type="number" :disabled="role == roleData.client" v-on:change="reCalculate()" v-model.trim="item1.quantity" class="form-control" placeholder="Qty">
                                                                            </td>
                                                                            <td data-title="Unit Price" class="text_right_all width_70">
                                                                                <h2>Unit Price</h2>
                                                                                <div class="form-group prefix_align_center ">
                                                                                    <div class="dollar-div">
                                                                <span class="fieldAffix-item">$</span>
                                                                                <input :disabled="role == roleData.client" type="number" step="any" v-model.trim="item1.price" class="form-control" placeholder="Price" v-on:change="editPrice()" >
                                                                                </div>
                                                                                </div>
                                                                            </td>
                                                                            <td data-title="Total" class="text_right_all width_70">
                                                                                <h2>Total</h2>
                                                                                <div class="form-group ">
                                                                                    <div class="dollar-div">
                                                                <span class="fieldAffix-item">$</span>
                                                                                <input disabled   v-model.trim="item1.total_price.toFixed(2)" class="form-control" placeholder="Total" >
                                                                                </div>
                                                                                </div>
                                                                            </td>
                                                                            <td v-if="!item.end_date" data-title="Delete" class="width_18 text-md-end">
                                                                                 <button type="button" class="cross__button text_end_only_desktop text-md-end btn-close btncclose "  @click="removeService(item1, index1)"></button>
                                                                            </td>

                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="tr_full_in_all">
                                                        <td class="td_full_in_all"  :colspan="7">
                                                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_16">Job Notes</h6>
                                                        </td>
                                                    </tr>
                                                     <tr class="tr_full_in_all">
                                                        <td class="td_full_in_all" :colspan="7">
                                                            <textarea v-model = "item.description"  class="taxarea_job form-control" ></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr class="tr_full_in_all">
                                                        <td class="td_full_in_all" :colspan="7">
                                                            <UploadFileComponent ref="uploadFile" :serviceId="item.service.id" :accept="acceptImageTypes" :files="item.Existingfiles" :multiple="true" :placeholder="'button'" @uploaded="fileUploaded" @invalid="invalidFileType" api="/api/file-upload"></UploadFileComponent>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                    </td>
                                </tr>
                                <!-- ===================== -->
                                <!-- ================= -->
                                <!-- ===================== -->
                            </tbody>
                             <tfoot>
                                <!-- ===================== -->
                                <!-- ======Blue TR=============== -->
                                <tr class="display_on_desktop blue_tr">
                                    <td colspan="9">
                                        <table class="table mg_0">
                                            <tbody>
                                                <tr>
                                                    <td class="width_ninty text_md_right" >Total:</td>
                                                    <td class="width_fifteen text_md_right" data-title="Total" >{{this.totalamount | toCurrency}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="display_on_mobile blue_tr">
                                </tr>
                                    <!-- ======Blue TR=============== -->
                                    <!-- ===================== -->
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!--<UploadFileComponent ref="uploadFile" :accept="acceptImageTypes" :files="Existingfiles" :multiple="true" @uploaded="fileUploaded" @invalid="invalidFileType" api="/api/file-upload"></UploadFileComponent>-->

                <!-- <div id="success-alert1" class="alert alert-success" style="margin-top: 10px;" v-if="successmsg!==''">
                    <strong>{{ successmsg }}</strong>
                </div> -->
                <div id="errir-alert" class="alert alert-danger" v-if="errormsg!=='' && successmsg == ''">
                    <strong>{{ errormsg }}</strong>
                </div>


                <div class="inline_buttonss">
                    <div class="row">
<!--                        <strong>Save and...</strong>-->
<!--                        <div class="inline_radio radio_in_six mb-2">-->
<!--                            <div class="custom_radio">-->
<!--                            <label>-->
<!--                                <input value="send_email" type="radio"  v-model.trim="quote_form.quick_action" />-->
<!--                                <span class="box"></span>Send As Email-->
<!--                            </label>-->
<!--                        </div>-->
<!--                            <div class="custom_radio" v-if="job.quote_status != 'converted'">-->
<!--                            <label>-->
<!--                                <input value="convert_to_job" type="radio"  v-model.trim="quote_form.quick_action" />-->
<!--                                <span class="box"></span>Convert To Job-->
<!--                            </label>-->
<!--                        </div>-->
<!--                            <div class="custom_radio" v-if="job.quote_status != 'converted' && job.quote_status != 'awaiting_response'">-->
<!--                            <label>-->
<!--                                <input value="awaiting_response" type="radio"  v-model.trim="quote_form.quick_action" />-->
<!--                                <span class="box"></span>Mark As Awaiting Response-->
<!--                            </label>-->
<!--                        </div>-->
<!--                            <div class="custom_radio">-->
<!--                            <label>-->
<!--                                <input value="none" checked="checked" type="radio" v-model.trim="quote_form.quick_action" @click="resetAction()" />-->
<!--                                <span class="box"></span>No Action-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        </div>-->
                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">
                                Save
                            </button>
                            <button v-if="(role == roleData.client)" type="submit" @click="saveAndClose" class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">
                                Save &amp; Close
                            </button>
                            <a v-if="(role == roleData.super_admin || role == roleData.admin) && id && showEmailAndOtherOptions" href="#" id="dropdownMenuButton" class="no_action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <button  class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600 custom-action-btn">
                                    Save and
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="16" height="16" x="0" y="0" viewBox="0 0 32 32" xml:space="preserve"><g><path d="M29.604 10.528 17.531 23.356a2.102 2.102 0 0 1-3.062 0L2.396 10.528c-.907-.964-.224-2.546 1.1-2.546h25.008c1.324 0 2.007 1.582 1.1 2.546z" fill="#ffffff" data-original="#000000" opacity="1"></path></g></svg>                                </button>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <router-link :to="id ? id : ''"  @click.native="saveAnd('send_email')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Send Email </router-link>
                                <router-link v-if="job.quote_status != 'converted'" :to="id ? id : ''"  @click.native="saveAnd('convert_to_job')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Convert to Job </router-link>
                                <router-link v-if="job.quote_status != 'converted' && job.quote_status != 'awaiting_response'" :to="id ? id : ''"  @click.native="saveAnd('awaiting_response')" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Mark as Awaiting Response </router-link>

                            </div>
                            <router-link :to="redirectUrl" class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">Cancel</router-link>
                        </div>
                    </div>
                </div>
            </form>

            <div class="modal fade custom_base_model custom_base_model_small" id="ViewNotes" tabindex="-1"
                aria-labelledby="viewNotes" aria-hidden="true" data-bs-backdrop="static">
                <ViewNotes @closeEvent="notesForceRenderer" :notesId="notesId"></ViewNotes>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="CancelJobPopup" tabindex="-1"
                aria-labelledby="UnscheduleModel" aria-hidden="true" data-bs-backdrop="static">
                <CancelJobPopup @closeEvent="deleteRecord" :jobId="id"></CancelJobPopup>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="RemoveServicePopup" tabindex="-1"
                aria-labelledby="RemoveServicePopup" aria-hidden="true" data-bs-backdrop="static">
                <RemoveServicePopup @closeEvent="removeAllServiceConfirm"></RemoveServicePopup>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="removeOneServicePopQoute" tabindex="-1"
                aria-labelledby="removeOneServicePopQoute" aria-hidden="true" data-bs-backdrop="static">
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

            <div class="modal fade custom_base_model" id="serviceStatusModal" tabindex="-1"
                aria-labelledby="serviceStatusModal" aria-hidden="true" data-bs-backdrop="static">
                <serviceStatusPopup @closeEvent="addNewitem" :serviceStatusData="serviceStatusData"></serviceStatusPopup>
            </div>
            <div class="modal fade custom_base_model custom_base_model_large" id="sendQuoteEmailModal" tabindex="-1"
                aria-labelledby="sendQuoteEmailModal" aria-hidden="true" data-bs-backdrop="static">
                <sendQuoteEmail @closeEvent="refreshData" :jobData="emailJobData" :totalamount="totalamount"></sendQuoteEmail>
            </div>
            <div class="modal fade custom_base_model" id="updateQuoteModal" tabindex="-1"
                 aria-labelledby="updateQuoteModal" aria-hidden="true" data-bs-backdrop="static">
                <updateQuoteStatusPopup @closeEvent="updateQuoteStatus" :serviceStatusData="serviceStatusData"></updateQuoteStatusPopup>
            </div>
            <!--to view pdf-->
            <div  class="modal fade custom_base_model custom_base_model_large" id="viewQuotePdf" tabindex="-1" aria-labelledby="lineItem" aria-hidden="true" data-bs-backdrop="static">
                <viewPdf :url="url":previewContent="previewContent" @closeEvent="ClosePdf"></viewPdf>
            </div>
        </div>
    </div>
</template>


<script>
import axios from "axios";
import DeleteModel from "../deleteModel";

import {required, minLength, maxLength, email, requiredIf, url, decimal} from 'vuelidate/lib/validators'
import Loader from "../LoaderSpinner";
import Datepicker from 'vuejs-datepicker';

import UploadFileComponent from "../../components/UploadFileComponent";
import Multiselect from 'vue-multiselect';
import roleData from '../../data.js';
import ViewNotes from "./ViewNotes";
import CancelJobPopup from "./cancelJobNotes";
import RemoveServicePopup from "./removeServicePopup";
import Navigation from "../../components/Navigation";
import serviceStatusPopup from "./serviceStatusPopup";
import sendQuoteEmail from "./sendQuoteEmail";
import updateQuoteStatusPopup from "./updateQuoteStatusPopup";
import viewPdf from "./viewPdf";
export default {
    name: "Add",
    components: {
        'Loader': Loader,
        'Datepicker': Datepicker,
        'DeleteModel': DeleteModel,
        UploadFileComponent: UploadFileComponent,
        Multiselect,
        'ViewNotes': ViewNotes,
        'CancelJobPopup': CancelJobPopup,
        'RemoveServicePopup': RemoveServicePopup,
        'Navigation':Navigation,
        'serviceStatusPopup':serviceStatusPopup,
        'sendQuoteEmail':sendQuoteEmail,
        'updateQuoteStatusPopup':updateQuoteStatusPopup,
        'viewPdf':viewPdf,

    },

    data() {
        return {
            searchIcon: false,
            showEmailAndOtherOptions:false,
            clientId:null,
            acceptImageTypes: ['image/png','image/jpeg'],
            roleData:roleData,
            todayDate: "",
            year: "",
            servcMsg: "",
            increment:null,
            searchQuery:"",
            currentComponent: 'clientEdit',
            services: [],
            parentServices: [],
            serviceReadyChecklist: [],
            service_ready_error: false,
            properties: [],
            clients: [],
            appartmentTypes: [],
            id: (this.$route.params.id || '').toString(),
            successmsg: "",
            errormsg: "",
            loading: false,
            already_user: false,
            Existingfiles : [],
            job: [],
            jobServices:[],
            jobServicesDetails:[],
            anchorz : "",
            assignParentCategoryId:"",
            totalamount:0,
            quote_form: {
                property_id: "",
                apartment_type_id: "",
                apartment_number: "",
                apartment_status: "vacant",
                po_number: "",
                job_status: "",
                job_service_ready_checklist: [],
                requested_job_services: [],
                type:"quote",
                quick_action:"",
            },
            value: [],
            options: [],
            submit: false,
            role: "",

            selectedService: "",
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            notesId:"",
            unscheduleParentServiceId:"",
            unscheduleParentServiceName:"",
            closeFlag:false,
            is_PO:false,
            removeServiceItem:"",
            removeServiceIndex:"",
            requestId:"",
            assignedToId:"",
            serviceStatusData:"",
            completEmployeeData:"",
            jobProgressData:"",
            emailJobData:"",
            quoteStatus:"",
            url:'',
            previewContent:""

        }
    },
    validations: {
        quote_form: {
            property_id: {
                required,
            },
            apartment_type_id: {
                // required,
            },
            apartment_number: {
                maxLength: maxLength(20),
            },
            apartment_status: {
                required,
            },
            po_number: {
                // required,
                // required: requiredIf(function (quote_form){
                //     return quote_form.property_id.is_PO_required == 1;
                // }),
                // minLength: minLength(2),
                // maxLength: maxLength(20),
            },


            job_service_ready_checklist: {
                // required,
            },
            requested_job_services: {
              //  required,
            },
        }
    },


    watch: {
        '$route.params.id'(val) {
            if(!val){
                this.formReset();
            }
        },
        'quote_form.property_id'(val) {
            if(val && val.id)
                this.getPropertyServices();
        },
        'quote_form.apartment_type_id'(val) {
            // if (this.quote_form.property_id) {
            //     if(this.quote_form.property_id.id){
            //         this.getPropertyServices();
            //     }
            // }
        },
    },
    computed: {
        resultQuery(){
            if(this.searchQuery){
                return  this.options.filter((item)=>{
                    if(item.service_sub_category){
                        return item.service_sub_category.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        // return this.searchQuery.toLowerCase().split(' ').every(v => item.service_sub_category.name.toLowerCase() == v)
                    }
                    if(item.service){
                        return item.service.category.toLowerCase().startsWith(this.searchQuery.toLowerCase());
                        // return this.searchQuery.toLowerCase().split(' ').every(v => item.service.category.toLowerCase().includes(v))
                    }
                })
            }else{
                return this.options;
            }
        },
        redirectUrl() {
            return this.role==roleData.client ? `/client-quotes`+( this.clientId ? `/${this.clientId}` : '') :  "/all-quotes";
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        const yourDate = new Date()
        this.todayDate = yourDate.toISOString().split('T')[0];
        this.year = yourDate.getFullYear();
        this.jobCreate();
        if (this.id) {
            this.loading = true;
        }
        setTimeout( () =>{
            this.addListerners();
        },3000);
    },
    methods: {
        addListerners() {
            try {
            document.getElementById('create-quote').addEventListener('click', (event) => {
            try{
                    this.onClickOutside(event);
                } catch(e){}
            });
        } catch(e){}

        try {
            document.getElementById('edit-quote').addEventListener('click', (event) => {
            try{
                    this.onClickOutside(event);
                } catch(e){}
            });
        } catch(e){}
    },
        clearCurrentFilter(name){
            if (name=='community'){
                this.quote_form.property_id = "";
            }else if (name=='size'){
                this.quote_form.apartment_type_id = "";
            }
            this.$refs[name+'_multiselect'].toggle();

        },
        onClickOutside(event) {
            const dropdownElement = this.$refs.customDropdown;
            if (!dropdownElement.contains(event.target)) {
                this.closeOptions();
            }
        },
        ClosePdf(){
            this.url='';
        },
        employeeJobcomplete(service) {
            // // console.log("service",service)
            this.requestId = service.requested_service_id;
            this.assignedToId = service.assigned_to_id;
            axios.get('/api/employee/get-notes-attachment/' + service.requested_service_id)
                .then((response) => {
                    this.completEmployeeData = response.data
                    this.jobProgress(service.requested_service_id,service.assigned_to_id)
                })

        },
        jobProgress(reqId,assignedTo){
            axios.get('/api/employee/employee-job-progress/' + reqId +'/'+ assignedTo)
                .then((response) => {
                    this.jobProgressData = response.data;
                })
        },
        showSection(){
            this.searchIcon = true;
        },
        sortByName: function (list) {
            return _.orderBy(list, 'service_type_id', 'asc');
        },
        addNewitem(val){
            if (val == 0) {
                this.serviceStatusData = "";
                return
            }
            let exist = this.jobServices.some(data => data.service_type_id === this.serviceStatusData.service_type_id);
            // // console.log("exist",exist)
            if(exist){

                    this.totalamount += this.serviceStatusData.price;
                    this.jobServicesDetails[this.serviceStatusData.service.category].push(this.serviceStatusData);
                    // // console.log("this.jobServicesDetails",this.jobServicesDetails)

            }

        },
        getServicePriceById : (data, s, f) => {
           const result = data.find(x => x.service_type_id === s.service_type_id && x.sub_service_id === s.sub_service_id );
           return (result && result.price ? result.price : 0).toFixed(2);
        },
        async onAppointmentTypeChange() {
            this.totalamount = 0;

                const response = await axios.get('/api/property-services/' + this.quote_form?.property_id?.id + "/" + this.quote_form?.apartment_type_id?.id );
                const services = response.data.services;
                this.loading = true;
                await   this.multiSelectOption(true);
                for(const ind in this.services) {
                    this.services[ind].price = this.getServicePriceById(services,this.services[ind])
                }
                for(const i in this.jobServicesDetails)
                {
                    for(const j in this.jobServicesDetails[i]){
                        this.jobServicesDetails[i][j].price = this.getServicePriceById(services, this.jobServicesDetails[i][j] , true);
                        this.totalamount += (+this.jobServicesDetails[i][j].price);
                    }
                }


            this.reCalculate();
            this.loading = false;
        },
        // updateServiceList: function(item) {
        //     item.quantity=1
        //     if(this.jobServices.length > 0){
        //         item.Existingfiles = [];
        //         let exist = this.jobServices.some(data => data.service_type_id === item.service_type_id);
        //         if(exist){
        //                 let index = this.quote_form.requested_job_services.indexOf(item);
        //                 if(index > -1){
        //                     let index1 = this.jobServicesDetails[item.service.category].indexOf(item);
        //                     this.removeService(item, index1)
        //                 }else{

        //                     if (this.id){
        //                         let curentService = this.jobServices.filter(data => data.service_type_id === item.service_type_id);
        //                         // // console.log("curentService",curentService)
        //                         if (curentService[0].end_date){
        //                             this.serviceStatusData = item;
        //                             $("#serviceStatusModal").modal('show');
        //                         }
        //                         else {
        //                             this.totalamount += item.price;
        //                             this.jobServicesDetails[item.service.category].push(item);
        //                         }

        //                     }
        //                     else {
        //                         this.totalamount += item.price;
        //                         this.jobServicesDetails[item.service.category].push(item);
        //                     }

        //                 }

        //         }else{
        //             //to increment price
        //             // if(this.increment){
        //             //     item.price = Number(item.price) + (Number(item.price) *(this.increment/100))
        //             // }

        //             this.jobServicesDetails[item.service.category]=[];
        //             this.jobServicesDetails[item.service.category].push({...item});
        //             this.jobServices.push({...item})
        //             this.totalamount += item.price;
        //         }
        //     }
        //     else{

        //         //to increment price
        //         // if(this.increment){
        //         //     item.price = Number(item.price) + (Number(item.price) *(this.increment/100))
        //         // }
        //         item.Existingfiles = [];
        //         this.jobServices.push({...item})
        //         this.jobServicesDetails[item.service.category]=[];
        //         this.jobServicesDetails[item.service.category].push({...item});
        //         this.totalamount += item.price;
        //     }
        //     if(this.searchQuery){
        //         this.searchQuery='';
        //     }
        //     this.closeOptions();
        //     this.reCalculate();
        // },
        updateServiceList: function (item) {
            this.loading = true;

            item.quantity = 1
            item.Existingfiles = item.Existingfiles
            let exist = this.jobServices.filter(data => data.service_type_id === item.service_type_id);
            if (!exist || exist.length < 1){
                this.jobServices.push({ ...item })
                this.totalamount += item.price;
            }

            if (!this.jobServicesDetails[item.service.category])
            {
                this.jobServicesDetails[item.service.category]= [];
            }

            if (this.id){
                let curentService = this.jobServices.filter(data => data.service_type_id === item.service_type_id);
                // // console.log("curentService",curentService)
                if (curentService[0].end_date){
                    this.serviceStatusData = item;
                    $("#serviceStatusModal").modal('show');
                } else {
                    this.totalamount += item.price;
                    this.jobServicesDetails[item.service.category] = [... this.jobServicesDetails[item.service.category],{...item}];
                    this.servcMsg = 'Service has been added';
                    setTimeout(() =>this.servcMsg = '', 3000);
                }
            } else {
                this.totalamount += item.price;
                this.jobServicesDetails[item.service.category] = [... this.jobServicesDetails[item.service.category],{...item}];
                this.servcMsg = 'Service has been added';
                    setTimeout(() =>this.servcMsg = '', 3000);
            }
            if (this.searchQuery) {
                this.searchQuery = '';
            }
        // this.closeOptions();
            this.reCalculate();
            this.loading = false;
        },
        saveAnd(action) {
            // this.loading=true;
            this.quote_form.quick_action = action
            this.quote_submit();

        },
        quote_submit() {
            this.submit =true;
            this.loading=true;
            this.$v.quote_form.$touch();
            this.service_ready_error = false;

            if (this.$v.quote_form.$anyError) {
                this.loading=false;
                return;
            }
            // let project_pay = [];
            let job_data = this.quote_form;
            job_data.requested_job_services = [];
            // job_data.requested_job_sub_services = [];
            try {
                job_data.property_id = this.quote_form.property_id.id;

            } catch(e){ }
            try {
                job_data.apartment_type_id = this.quote_form.apartment_type_id.id;
            } catch(e){}
            this.jobServices.forEach(data =>{
                data.requested_job_sub_services = [];
                const array = this.jobServicesDetails[data.service.category];
                array.forEach(item =>{
                    data.requested_job_sub_services.push(item);
                });
                job_data.requested_job_services.push(data);
            })
            if(this.id){
                axios.put('/api/job/' + this.id, job_data)
                .then(response => {
                    this.loading=false;
                    if (response.data.error) {
                        this.errormsg = response.data.error;
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    } else {
                        this.successmsg = "Quote has been updated";
                        this.$emit('updateCount');
                        this.errormsg="";
                        if(this.closeFlag){
                            this.$router.go(-1)
                                // this.$router.push('/all-quotes');
                        }else{
                            this.Existingfiles = [];
                            this.getRecord(this.id);
                        }
                        setTimeout(() => {
                            this.successmsg = "";
                        }, 1000);
                    }
                })
                .catch(err => {
                    this.has_error = true;
                })
            }else{
                axios.post('/api/job', job_data)
                .then(response => {
                    this.loading=false;
                    if (response.data.error) {
                        this.errormsg = response.data.error;
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    } else {
                        this.successmsg = "Quote has been added";
                        this.$emit('updateCount');
                        this.errormsg="";
                        if(this.closeFlag){
                            //this.$router.push('/all-quotes');
                            this.$router.push(this.redirectUrl);
                        }else{
                            this.$router.push('/edit-quote/'+response.data.job_id);
                            this.id = (response.data.job_id).toString();
                            this.getRecord(this.id);
                            // this.getRecord(response.data.job_id);
                        }
                        setTimeout(() => {
                            this.successmsg = "";
                            // this.$router.back()
                        }, 1000);
                    }
                })
                .catch(err => {
                    this.has_error = true;
                })
            }
        },
        getRecord(id) {

            this.loading=true;
            axios.get('/api/quote/' + id + "/edit")
                .then((response) => {
                    if (response.data.status == 'not_found'){
                        this.errormsg = "Quote Not Found";
                        this.loading=false;

                        setTimeout(() => {
                            this.errormsg = "";
                            // this.$router.push('/all-quotes')
                            this.$router.push(this.redirectUrl);
                        }, 3000);
                        return;
                    }
                    this.job = response.data.job;
                    this.quote_form.apartment_type_id = this.appartmentTypes.find(apartment => (apartment.id == this.job.apartment_type_id));
                    this.quote_form.property_id = this.properties.find(property => (property.id == this.job.property_id));
                    this.is_PO = this.quote_form.property_id.is_PO_required ? true : false;
                    this.quote_form.apartment_number = this.job.apartment_number;
                    this.quote_form.apartment_status = this.job.apartment_status;
                    this.quote_form.po_number = this.job.po_number;
                    this.showEmailAndOtherOptions = this.job.requested_job_service && this.job.requested_job_service.length > 0;
                    this.job.service_ready_check_list.forEach((data) => {
                        this.quote_form.job_service_ready_checklist.push(data.id);
                    });
                    this.quote_form.quick_action = "none";
                    // this.job.job_attatchment.forEach((file) => {
                    //     this.Existingfiles.push(file.filename);
                    // });
                    this.loading = false;
                });
        },

        jobCreate() {
            if (this.$route.query.clientId){
                this.clientId = this.$route.query.clientId
            }
            axios.get('/api/job/create?clientId='+ this.clientId)
                .then((response) => {
                    this.serviceReadyChecklist = response.data.serviceReadyChecklist;
                    this.clients = response.data.clients;
                    this.appartmentTypes = response.data.appartmentTypes;

                    // $(response.data.properties).each(function(key, value) {
                    //     let client_name = "";
                    //     client_name = (response.data.properties[key].client != null)? " ("+response.data.properties[key].client.company+")" : "";
                    //     response.data.properties[key].title = response.data.properties[key].title+ client_name;
                    // });

                    this.properties = this.sortFunc(response.data.properties);
                    this.priceIncrement = response.data.priceIncrement;
                    if(this.id){
                        this.getRecord(this.id);
                    }
                    this.getIncrementPercentage();

                });
        },
        sortFunc: function (array){
            return array.slice().sort(function(a, b){
                return (a.title.toLowerCase() > b.title.toLowerCase()) ? 1 : -1;
            });
        },
       getIncrementPercentage(){
           let data = this.priceIncrement.find(increment => (increment.year == this.year));
            if(data){
                this.increment = data.percentage;
            }
       },
        customLabel(service){
            return `${service.service_sub_category.name}`;
        },
        customLabelClient(client){
            if(client.user){
                return `${client.user.first_name?client.user.first_name:''} ${client.user.middle_name?client.user.middle_name:''} ${client.user.last_name?client.user.last_name:''}`;
            }
        },
        // getPropertyServices() {
        //     axios.get('/api/property-services/' + this.quote_form.property_id.id + "/" + this.quote_form.apartment_type_id.id)
        //         .then((response) => {
        //             this.services = response.data.services;
        //             this.parentServices = response.data.parentServices;

        //             if(this.services.length==0){
        //                 this.errormsg = "Pricing Details for this property is required to continue";
        //             }else{
        //                 if(this.id){
        //                     this.requestedJobServices();
        //                 }
        //                 this.errormsg = "";
        //             }

        //             this.services.forEach((value, index) => {
        //                 value.requested_date = this.todayDate
        //             });
        //             this.multiSelectOption();
        //             this.loading = false;
        //         });
        // },
        getPropertyServices() {
            // this.loading = true;
            let appendUrl = ''
            try {
                if (this.quote_form?.apartment_type_id?.id)
                    appendUrl = '/' + this.quote_form?.apartment_type_id?.id;
            }catch(e){}
            axios.get('/api/property-services/' + this.quote_form?.property_id?.id)
                .then((response) => {
                    this.services = response.data.services;
                    this.parentServices = response.data.parentServices;

                    if (this.services.length == 0) {
                        this.errormsg = "Pricing Details for this property is required to continue";
                    } else {

                        if (this.id) {
                            this.requestedJobServices();
                        }
                        this.errormsg = "";
                    }

                    this.services.forEach((value, index) => {
                        try {
                            let exist = this.jobServices.some((data) =>  data.service_sub_category.id === value.service_sub_category.id)
                    if (exist && exist.length && this.id) {
                        this.updateServiceList(value);
                    }
                        } catch(e){}
                    //    if(true)
                    //       {


                    //       }
                    // if (this.id){
                    //     this.updateServiceList(value);
                    // }

                        value.requested_date = this.todayDate
                    });

                    this.multiSelectOption();
                    this.loading = false;
                });
        },
        requestedJobServices(){
            let val=[];
            this.totalamount = 0;
            this.quote_form.requested_job_services=[];
            this.jobServices = [];
            this.jobServicesDetails = [];
            this.job.requested_job_service.forEach((data)=>{
                this.parentServices.some((service) =>{
                    if(service.service_type_id == data.service_id){
                        service.description = data.description;
                        service.Existingfiles = [];
                        if(data.job_attatchment){
                            data.job_attatchment.forEach(file => {
                                service.Existingfiles.push(file.filename);
                            });
                        }
                        service.requested_service_id = data.id;
                        service.is_fixed_price = data.is_fixed_price;
                        service.payroll_price = (Math.round(data.payroll_price * 100) / 100).toFixed(2);
                        service.requested_date = data.requested_date;
                        service.requested_for = data.requested_for;
                        service.end_date = data.end_date;
                        service.isAlreadyAssigned = data.assigned_to_id
                        //to get assignment of services
                        if(data.assigned_to_id !== null){
                            service.assigned_to_id = data.assigned_to_id
                            service.assigned_to_type = data.assigned_to_type
                            if(service.assigned_to_type == "individual"){
                                // service.assigned_name= data.employee.user.first_name + " "+data.employee.user.middle_name +" "+data.employee.user.last_name;
                                service.assigned_name = `${data.employee.user.first_name} ${data.employee.user.middle_name?data.employee.user.middle_name:''} ${data.employee.user.last_name}`
                            }else{
                                service.assigned_name= data.employee_crew.name;
                            }
                        }
                        //to get scheduled infor
                        if(data.scheduled_date !== null){
                            service.scheduled_date = data.scheduled_date
                            service.scheduled_time = data.scheduled_time
                            service.anytime = data.anytime
                            service.start_date = data.start_date
                        }
                        if(data.schedule_update_at !== null){
                            service.schedule_update_at = data.schedule_update_at

                        }
                        this.jobServices.push({...service});

                        let serviceValue = service;
                        this.jobServicesDetails[serviceValue.service.category] = [];
                        data.requested_job_sub_service.forEach(item=>{
                            this.totalamount += Number(item.base_price);
                            this.services.some((subService)=>{
                                if(subService.service_type_id == data.service_id && subService.sub_service_id == item.sub_service_id){
                                  //  subService.price=item.base_price.toFixed(2);
                                    subService.requested_sub_service_id = item.id;
                                    subService.is_invoice = item.is_invoice;
                                    subService.quantity = item.quantity;
                                    subService.description = item.description ? item.description : "";
                                    (this.jobServicesDetails[serviceValue.service.category]).push({...subService,  price: item.base_price.toFixed(2)})
                                    this.quote_form.requested_job_services.push(subService);
                                }
                            })
                        });

                    }
                })

            });
            this.reCalculate()
        },
        multiSelectOption(setPrice=false) {
            this.options = [];

            this.services.sort((a, b) => {

                if (a.service_sub_category.name < b.service_sub_category.name) {
                    return -1;
                }
                if (a.service_sub_category.name > b.service_sub_category.name) {
                    return 1;
                }
                return 0;
            });

            this.parentServices.sort((a, b) => {
                if (a.service.category < b.service.category) {
                    return -1;
                }
                if (a.service.category > b.service.category) {
                    return 1;
                }
                return 0;
            });

            this.parentServices.forEach((value) => {
                this.options.push(value);
                if (this.services.length > 0) {
                    this.services.some(item => {
                        if (value.service_type_id == item.service_type_id) {
                            let condition = false;
                            try {
                                if (this.quote_form?.apartment_type_id?.id)
                                    condition = true;
                            }catch(e){}
                            if(!this.id && !setPrice && !condition) {
                                this.options.push({...item, price:0});
                            } else {this.options.push(item);}
                        }
                    })
                }
            });

        },

        reCalculate() {
            this.jobServices.forEach(data =>{
                this.jobServicesDetails[data.service.category].forEach(item=>{

                    item.total_price=Number(item.price*item.quantity);
                    item.price = (Math.round(item.price * 100) / 100).toFixed(2);

                    // var formatter = new Intl.NumberFormat('en-US', {
                    //     style: 'currency',
                    //     currency: 'USD'
                    // });
                    // let setVal = formatter.format(item.price);
                    // item.price = setVal.replace('$','');
                    // // console.log(item.price);
                })
            })
				// this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].total_price = this.invoiceForm.invoiceDetails.job.job_services[index].requested_job_sub_service[index1].base_price * val;
				this.updateTotal();
		},
        updateTotal(){
            this.totalamount=0;
            this.jobServices.forEach(data =>{
                this.jobServicesDetails[data.service.category].forEach(item=>{
                    this.totalamount += Number(item.price*item.quantity);
                })
            })
        },
        fileUploaded(filenames,serviceId){
            // this.quote_form.filePaths = filenames;
            this.jobServices.forEach((rjs,index) =>{
                if(rjs.service_type_id == serviceId){
                    this.jobServices[index].filePaths = filenames;
                    this.jobServices[index].Existingfiles = filenames;
                }
            });
        },
        invalidFileType(msg){
            this.errormsg=msg;
            setTimeout(() => {
                this.errormsg = "";
            }, 3000);
        },
        selectProperty(property){
            this.is_PO = property.is_PO_required ? true : false;
            this.appartmentTypes = property.appartment_types;
            let appartment = this.appartmentTypes.find(appartment => (this.quote_form.apartment_type_id && appartment.id == this.quote_form.apartment_type_id.id))
            if(!appartment){
                this.quote_form.apartment_type_id = "";
            }

        },
        formReset(){
            this.$v.quote_form.$reset();
            this.quote_form = {
                property_id: "",
                apartment_type_id: "",
                apartment_number: "",
                apartment_status: "vacant",
                po_number: "",
                job_status: "",
                job_service_ready_checklist: [],
                requested_job_services: [],
                jobServices:[],
                jobServicesDetails: [],
                filePaths: []
            }
            this.Existingfiles=[];
            this.$refs.uploadFile.mergedFiles=null;
            this.job="";
            this.id = undefined;
            this.submit= false
            this.$forceUpdate();
        },
        editPrice(){
            // this.totalamount=0;
            // this.jobServices.forEach(data =>{
            //     this.jobServicesDetails[data.service.category].forEach(item=>{
            //         this.totalamount += Number(item.price);
            //     })
            // })
            this.reCalculate()
        },
        async searchOptions(){
            // jquery for select option deropdown
           await this.showSection();
            // var items = document.getElementById('items');
            // items.classList.add('visible');
            // items.style.display = "block";
        },
        closeOptions(){

            this.searchIcon = false;
            // var items = document.getElementById('items');
            // items.removeAttribute("style");
            // items.style.display = "none";
            //items.classList.remove('visible');

            if(this.searchQuery){
                this.searchQuery='';
            }
           // this.searchIcon = false;


        },
        removeAllService(item, index){
            let data = this.quote_form.requested_job_services.filter(data => (data.service_type_id === item.service_type_id));
            data.forEach(x =>{
                let index = this.jobServices.findIndex(data => data.service_type_id === item.service_type_id);
                this.quote_form.requested_job_services[index].description = '';
                this.quote_form.requested_job_services.splice(index,1);
            })
            this.jobServices.splice(index, 1);
            this.jobServicesDetails[item.service.category].forEach((val, index1)=>{
            //deduct price from total amount
            this.totalamount = this.totalamount - val.price;
            this.totalamount = this.totalamount.toFixed(2);
            //remove from sub service array
            this.jobServicesDetails[item.service.category].splice(index1,1);
            })
        },
        removeOneService(proceed) {
            if(proceed)
            {
                this.removeService(this.itemTemp, this.index1Temp, false);
            }
            $("#removeOneServicePopQoute").modal('hide');
            this.itemTemp=undefined;
            this.index1Temp =undefined;
        },
        removeService(item, index1, showPopup=true) {
            if(showPopup)
            {
                this.itemTemp=item;
                   this.index1Temp =index1;
                $("#removeOneServicePopQoute").modal('show');

                return;
            }
            this.loading = true;
            let data = this.quote_form.requested_job_services.filter(data => (data.service_type_id === item.service_type_id))
            if(data.length == 1){
                let index = this.jobServices.findIndex(data => data.service_type_id === item.service_type_id);
                if(index > -1){
                    this.jobServices.splice(index, 1);
                }
            }
            //remove from main array
            let index = this.quote_form.requested_job_services.findIndex(data => (data.id === item.id));
            try {
                this.quote_form.requested_job_services[index].description = '';
            } catch(e){}
            this.quote_form.requested_job_services.splice(index,1);
            //deduct price from total amount
            // this.totalamount = this.totalamount - this.jobServicesDetails[item.service.category][index1].price

            //remove from sub service array
            this.jobServicesDetails[item.service.category].splice(index1,1);
            this.reCalculate()
            this.loading = false;
        },

        assignParentId(service) {
				this.assignParentCategoryId = service.service_type_id;
                this.selectedService = service;
		},
        forceRerender() {
				this.assignParentCategoryId = '';
                this.selectedService="";
                this.quote_submit()
		},
        refreshData() {
            this.requestId = null
            this.assignedToId= null
            this.getRecord(this.id);
            // window.location.reload()
		},
        assign_info(){
            let assign_data = this.$refs.assignInfo.assign_form;
            // // console.log(assign_data, 'in the d');
            let index = this.jobServices.findIndex(item=>(item.service_type_id == assign_data.parentCategory));
            if(index > -1){
                this.jobServices[index]['assigned_to_id'] = assign_data.assigned_to_id
                this.jobServices[index]['assigned_to_type'] = assign_data.assigned_to_type
                this.jobServices[index]['requested_date'] = assign_data.requested_date
                this.jobServices[index]['requested_for'] = assign_data.requested_for
                this.jobServices[index]['scheduled_date'] = assign_data.scheduled_date
                this.jobServices[index]['scheduled_time'] = assign_data.scheduled_time
                this.jobServices[index]['anytime'] = assign_data.anytime
                //to get assignment of services
                if(assign_data.assigned_to_id !== null || assign_data.assigned_to_id !== ''){
                    this.jobServices[index]['assigned_name'] = assign_data.assigned_name;
                }
            }
            this.assignParentCategoryId = '';
             this.selectedService="";
		    this.loading = false;
        },
        setJobData() {
            // this.loading=true;
            this.emailJobData = this.job

        },

        setQuoteStatus(status) {
            // this.loading=true;
            this.quoteStatus = status

        },
        quotePdf(type) {
            if(type=='preview'){
                axios.get('/api/quote-pdf/' +this.id+'/' +type, {
                    // responseType: 'blob'
                }).then((response) => {
                        // var url = URL.createObjectURL(new Blob([response.data],{type: 'application/pdf'}));
                        // this.url = url;
                    this.previewContent = response.data;

                });
            }
            else if (type=='download'){
                this.loading = true
                axios.get('/api/quote-pdf/' +this.id+'/' +type, {
                    responseType: 'blob'
                }).then((response) => {
                    this.loading = false
                    if (response){
                        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                        var fileLink = document.createElement('a');
                        fileLink.href = fileURL;
                        fileLink.setAttribute('download', 'quote.pdf');
                        document.body.appendChild(fileLink);
                        fileLink.click()
                        fileLink.parentNode.removeChild(fileLink);
                    }

                });
            }

        },
        updateQuoteStatus(val) {
            if (val == 0) {
                return ;
            }
            axios.post('/api/updatequotestatus/' + this.id + '/' + this.quoteStatus)
                .then((response) => {
                    if (response.data.status == "success"){
                        this.successmsg = "Status updated successfully!";
                        this.refreshData()
                    }
                });

            setTimeout(() => {
                this.successmsg = "";
            }, 3000);


        },
        deleteRequest(id, title, api) {
            this.deleteId = id;
            this.deleteTitle = title;
            this.deleteApi = api;
        },
        deleteRecord(val) {
            if (val == 0) {
                this.deleteId = "";
                this.deleteTitle = "";
                this.deleteApi = "";
                return
            }
            this.loading = true;
            axios.delete('/api/' + this.deleteApi + '/' + this.deleteId)
                .then((response) => {
                    this.deletemsg = response.data.msg;
                    this.$emit('updateCount');

                });
            setTimeout(() => {
                this.deletemsg = "";
                 this.$router.push('/cancel-job-details/'+this.deleteId);
                 this.loading = false;
            }, 1000);
        },
        viewNotesMethod(id){
            this.notesId = id;
        },
        notesForceRenderer(){
            this.notesId = '';
        },
        unscheduleRequest(item){
            this.unscheduleParentServiceId = item.service.id;
            this.unscheduleParentServiceName = item.service.category;
        },
        unscheduleAllServices(val){
            if(val == 0){
                this.unscheduleParentServiceId = "";
                this.unscheduleParentServiceName = "";
                return;
            }
            this.loading = true;
            if(this.unscheduleParentServiceId != ""){
                axios.put('/api/unschedule-job-service/'+ this.id + '/' + this.unscheduleParentServiceId)
                    .then(response => {
                    this.loading=false;
                    if (response.data.error) {
                        this.errormsg = response.data.error;
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    } else {
                        this.successmsg = response.data.message;
                        this.jobServices.forEach(item => {
                                if(item.service_type_id == this.unscheduleParentServiceId){
                                    item.assigned_to_id = null;
                                    item.assigned_to_type = "";
                                    item.scheduled_date = null;
                                    item.scheduled_time = "";
                                    item.assigned_name = "";
                                    item.anytime = 0;
                                    item.isAlreadyAssigned = 0;
                                }
                        });
                        setTimeout(() => {
                            this.successmsg = "";
                        }, 1000);
                    }
                })
                .catch(err => {
                    this.has_error = true;
                })

            }else{
                axios.put('/api/unschedule-job-service/'+ this.id)
                .then(response => {
                    this.loading=false;
                    if (response.data.error) {
                        this.errormsg = response.data.error;
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    } else {
                        this.successmsg = response.data.message;
                        this.jobServices.forEach(data => {
                            data.scheduled_date = "";
                            data.scheduled_time ? data.scheduled_time = null : '';
                            data.assigned_name = "";
                            data.anytime ? data.anytime = 0 : '';
                            data.isAlreadyAssigned = 0;
                            data.assigned_to_id = null;
                        });
                        this.Existingfiles = [];
                        this.getRecord(this.id);
                        setTimeout(() => {
                            this.successmsg = "";
                        }, 1000);
                    }
                })
                .catch(err => {
                    this.has_error = true;
                })
            }
        },
        resetAction(){
            this.quote_form.quick_action = "";
        },
        saveAndClose(){
            this.closeFlag = true;
        },
        unassignEmployee(item){
            let index = this.jobServices.findIndex(data=>(data.service_type_id == item.service.id));
            if(index > -1){
                this.jobServices[index]['assigned_to_id'] = '';
                this.jobServices[index]['assigned_to_type'] = '';
                this.jobServices[index]['assigned_name'] = '';
                this.$forceUpdate();
            }
        },
        removeAllServicesPrompt(item,index){
            this.removeServiceItem = item;
            this.removeServiceIndex = index;
        },
        removeAllServiceConfirm(val){
            if (val == 0) {
                this.removeServiceItem = "";
                this.removeServiceIndex = "";
                return
            }
            this.removeAllService(this.removeServiceItem,this.removeServiceIndex);
        }
    },

}

</script>



<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
