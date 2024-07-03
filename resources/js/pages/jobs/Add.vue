<template>
    <div>
        <div v-if="loading">
            <Loader></Loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''">
                <strong>{{ successmsg }}</strong>
            </div>
            <!-- <div id="errir-alert" class="alert alert-danger" v-if="errormsg !== '' && successmsg == ''">
                <strong>{{ errormsg }}</strong>
            </div> -->
            <div class="row row_spacing_5">
                <div class="col-sm-6 col-8" v-if="!id">
                    <h1 class="h4 heading_text">Job Detail</h1>
                </div>
                <div class="col-sm-6 col-8 d-md-flex mb-sm-2-custom edit-complete-wrap" v-if="id">
                    <div class="job_id_div_wrap">
                        <h1 class="h4 heading_text">Job # {{ id }}</h1>
                        <h1 class="h4 heading_text" v-if="job.invoice">   Invoice # {{ job.invoice.id}}</h1>
                    </div>

                    <b-button v-if="job.job_status == 'completed'"
                        variant="success custom_status_btn">Completed</b-button>
                    <b-button v-else-if="job.job_cancel == 1" variant="danger custom_status_btn">Cancelled</b-button>
                    <b-button v-else-if="job.job_status == 'not scheduled'"
                        variant="warning custom_status_btn">Unscheduled</b-button>
                    <b-button v-else-if="job.job_status == 'scheduled'"
                        variant="warning custom_status_btn">Scheduled</b-button>
                    <b-button v-else-if="job.job_status == 'partial scheduled'"
                        variant="secondary custom_status_btn btn-secondary">Partial Scheduled</b-button>
                    <b-button v-else-if="job.job_status == 'requested'"
                        variant="warning custom_status_btn">Requested</b-button>

                    <div class="m-lg-2 d-lg-flex justify-content-center align-items-center"
                        v-if="role != roleData.client && job.job_status == 'completed' && !job.invoice_no">
                        <label>
                            <input type="checkbox" :checked="job.is_billed == 2"
                                v-on:click="isReadyTobill(job.id, $event.target.checked)" />
                            <span class="box">Ready To Bill</span>
                        </label>
                    </div>


                </div>
                <Navigation></Navigation>


                <div class="col-md-12 col-lg-12 text-end">

                    <div class="row row_spacing_5 justify-content-end">

                        <div class="col-md-3"
                            v-if="!job.invoice && id && (role == roleData.super_admin || role == roleData.admin) && job.job_status == 'completed' && job.is_billed == 2">
                            <button
                                class="btn_big_medium btn btn_orange border_radius_5 font_14 text_uppercase font_weight_600 btn-block mg_bbot"
                                @click="generateInvoice">Generate Invoice</button>
                        </div>

                        <div class="col-md-3"
                            v-if="id && (role == roleData.super_admin || role == roleData.admin) && job.job_status != 'completed' && job.job_status == 'scheduled'">
                            <button
                                class="btn_big_medium btn btn_orange border_radius_5 font_14 text_uppercase font_weight_600 btn-block mg_bbot"
                                data-bs-toggle="modal" data-bs-target="#UnscheduleAllServicesModel">Unschedule Job</button>
                        </div>
                        <div class="col-md-3" v-if="id && job.job_status !== 'completed'">
                            <button
                                class="btn_big_medium btn btn_orange border_radius_5 font_14 text_uppercase font_weight_600 btn-block mg_bbot"
                                data-bs-toggle="modal" data-bs-target="#CancelJobPopup"
                                @click="deleteRequest(id, 'Job', 'job')">Cancel Job</button>
                        </div>
                    </div>
                </div>
            </div>
            <form autocomplete="off" @submit.prevent="job_submit" method="post">
                <div class="table_area edit-job-section">
                    <div class="table_area_head pd_top_24 pd_bot_30">
                        <div class="row row_spacing_5">
                            <div class="col-sm-5">
                                <label>Select Community</label>
                                <div class="form-group">
                                    <multiselect deselectLabel="" v-model.trim="job_form.property_id" :show-labels="false"
                                        track-by="title" label="title" placeholder="Select Community" :select-label="''"
                                        :options="properties" :searchable="true"
                                        :class="{ 'is-invalid': $v.job_form.property_id.$error, 'is-valid': !$v.job_form.property_id.$invalid }"
                                        @select="selectProperty" ref="community_multiselect" :selectedLabel="''"
                                        :allow-empty="true">
                                        <template #singleLabel="{ option }">
                                            <div>
                                                <span class="multiselect__single-label edit-community">{{ option.title }}</span>
                                                <button v-if="job_form.property_id" class="multiselect__clear"
                                                    @mousedown="clearCurrentFilter('community')">
                                                    &#x2715;
                                                </button>
                                            </div>
                                        </template>
                                    </multiselect>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.job_form.property_id.required">Field is Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label>Unit Size</label>
                                <div class="form-group">
                                    <multiselect deselectLabel="" v-model.trim="job_form.apartment_type_id"
                                        @input="onAppointmentTypeChange"
                                        :show-labels="false" track-by="type" label="type" placeholder="Unit Size"
                                        :select-label="''" :options="appartmentTypes" :searchable="true"
                                        :class="{ 'is-invalid': $v.job_form.apartment_type_id.$error, 'is-valid': !$v.job_form.apartment_type_id.$invalid }"
                                        ref="size_multiselect" :selectedLabel="''" :allow-empty="true">
                                        <template #singleLabel="{ option }">
                                            <div>
                                                <span class="multiselect__single-label unit-edit">{{ option.type }}</span>
                                                <button v-if="job_form.apartment_type_id" class="multiselect__clear"
                                                    @mousedown="clearCurrentFilter('size')">
                                                    &#x2715;
                                                </button>
                                            </div>
                                        </template>
                                    </multiselect>
                                    <div class="invalid-feedback">
                                        <span v-if="!$v.job_form.apartment_type_id.required">Field is Required</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <label>Unit #</label>
                                <div class="form-group">
                                    <input type="text" v-model.trim="$v.job_form.apartment_number.$model"
                                        class="form-control" placeholder="Unit #"
                                        :class="{ 'is-invalid': $v.job_form.apartment_number.$error, 'is-valid': !$v.job_form.apartment_number.$invalid }" />
                                    <div class="invalid-feedback" >
                                        <span v-if="!$v.job_form.apartment_number.maxLength">Field length must not be
                                            greater then 20 Characters</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2" v-if="is_PO">
                                <label>PO #</label>
                                <div class="form-group">
                                    <input type="text" v-model.trim="$v.job_form.po_number.$model" class="form-control"
                                        :class="{ 'is-invalid': submit && $v.job_form.po_number.$error, 'is-valid': !$v.job_form.po_number.$invalid }"
                                        placeholder="PO #" />
                                    <div class="invalid-feedback" v-if="submit">
                                        <span v-if="!$v.job_form.po_number.required">Field is Required</span>
                                        <span v-if="!$v.job_form.po_number.minLength">Field length must be 2
                                            Characters</span>
                                        <span v-if="!$v.job_form.po_number.maxLength">Field length must not be greater then
                                            20 Characters</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center row_spacing_5 mg_bot_18">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Apartment
                                    Status</h6>
                            </div>
                            <div class="form-group">
                                <div class="inline_radio radio_in_six">
                                    <div class="custom_radio">
                                        <label>
                                            <input value="vacant" type="radio" checked
                                                v-model.trim="$v.job_form.apartment_status.$model" />
                                            <span class="box"></span>Vacant
                                        </label>
                                    </div>
                                    <div class="custom_radio">
                                        <label>
                                            <input value="occupied" type="radio"
                                                v-model.trim="$v.job_form.apartment_status.$model" />
                                            <span class="box"></span>Occupied
                                        </label>
                                    </div>
                                </div>

                                <div class="invalid-feedback" v-if="submit">
                                    <span v-if="!$v.job_form.apartment_status.required">Field is Required</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center row_spacing_5 mg_bot_30 " v-if="role == roleData.client">
                        <div class="col-md-12">
                            <div class="form-group">
                                <h6
                                    class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20 servies-heading">
                                    Service Ready Checklist</h6>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="services-checklist">
                                <div class="custom_checkbox" v-for="(item, index) in serviceReadyChecklist" :key="index"
                                    :value="item.id">
                                    <label>
                                        <input type="checkbox" :value="item.id"
                                            v-model.trim="$v.job_form.job_service_ready_checklist.$model" />
                                        <span class="box">{{ item.name }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span v-if="submit && $v.job_form.job_service_ready_checklist.$anyError" style="color: #e3342f;">Atleast
                        one Record is Required</span>
                    <div class="row align-items-center row_spacing_5 mg_bot_30">
                        <div class="col-md-12">
                            <div id="success-alert2" class="alert alert-success" v-if="servcMsg !== ''">
                                    <strong>{{ servcMsg }}</strong>
                                </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" v-if="services.length > 0">
                                <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Choose
                                    Services
                                </h6>
                            </div>


                            <!-- ====================== -->
                            <div id="list1" v-show="services.length > 0" ref="customDropdown"  class="dropdown-check-list dropdown_check_list"
                                tabindex="100">
                                <span id="anchors" class="anchor" @click="showSection">
                                    <span v-if="!searchIcon" class="anchor_icons anchor_icons_arrow">
                                        <svg class="anchoricon" @click="openOptions()">
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

                                <div id="items" class="items">
                                    <ul id="itemss" class="itemss" v-for="(item, index) in resultQuery"
                                        v-bind:key="index">
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
                                                            <span @click="updateServiceList(item,'no-created-at')" class="box">{{ item.service_sub_category.name }}</span>
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
                    <span v-if="submit && $v.job_form.requested_job_services.$anyError" style="color: #e3342f;">Atleast one
                        Service is Required</span>

                    <div v-if="jobServices.length > 0 && !loading"
                        class="no_more_tables table_to_be_used table_with_widths more_tables_with_less_pd colapsible_tr_padding">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-md-left">Service</th>
                                    <th class="text-md-left">Created On</th>
                                    <th class="text-md-left">Requested For</th>
                                    <th class="text-md-left">Scheduled Date</th>
                                    <th class="text-md-left">Scheduled Time</th>
                                    <th class="text-md-left">Schedule last Updated</th>
                                    <th class="text-md-left">Completion Date</th>
                                    <th class="text-md-left">Assigned To</th>
                                    <th class="text_end_only_desktop">Action</th>
                                </tr>
                            </thead>
                            <tbody v-for="(item, index) in jobServices" :key="index" class="tbody_new">
                                <tr class="colapsible_tr colapsible_trtd" aria-expanded="false"
                                    aria-controls="panelsStayOpen-collapseThree">
                                    <td class="uniqueclass first-serve" data-title="Service(s)" data-bs-toggle="collapse" :data-bs-target="'#panelsStayOpen-collapseThree' + index">
                                        <svg class="table_icon_chevron">
                                            <use xlink:href="#right-chevron" />
                                        </svg>

                                        <strong>{{ item.service.category }}</strong>
                                    </td>
                                    <td data-title="Created On">

                                        <span
                                            v-if="item.created_at"><strong>{{ item.created_at | formatFullDate }}</strong></span>
                                        <span v-else>--</span>
                                    </td>
                                    <td class="text-md-left" data-title="Requested For">
                                        <span
                                            v-if="item.requested_for"><strong>{{ item.requested_for | formatDate }}</strong></span>
                                        <span v-else>--</span>
                                    </td>
                                    <td class="text-md-left" data-title="Scheduled Date">
                                        <span
                                            v-if="item.scheduled_date"><strong>{{ item.scheduled_date | formatDate }}</strong></span>
                                        <span v-else>--</span>
                                    </td>
                                    <td class="text-md-left" data-title="Scheduled Time"
                                        v-if="(item.anytime == 0 || item.anytime == null) && (item.scheduled_time == null || item.scheduled_time == '')">
                                        --</td>
                                    <td class="text-md-left" data-title="Scheduled Time"
                                        v-else-if="item.anytime == 0 && (item.scheduled_time != null || item.scheduled_time != '')">
                                       <strong> {{ item.scheduled_time | formatTime }}</strong>
                                    </td>
                                    <td class="text-md-left" data-title="Scheduled Time" v-else-if="item.anytime == 1">
                                        <strong>Any Time</strong>
                                    </td>
                                    <td class="text-md-left mobile-attribute" data-title="Scheduled Last Updated">
                                        <span
                                            v-if="item.schedule_update_at"><strong>{{ item.schedule_update_at | formatDateTime }}</strong></span>
                                        <span v-else>--</span>
                                    </td>
                                    <td class="text-md-left" data-title="Completion date">
                                        <span v-if="item.end_date"><strong>{{ item.end_date | formatDate }}</strong></span>
                                        <span v-else>--</span>
                                    </td>
                                    <td class="text-md-left" data-title="Assigned To">
                                        <strong>
                                            <span v-if="item.assigned_name">
                                                <span class="assineeText">{{ item.assigned_name }}</span>
                                                <span class="asigneeCrossBtn"
                                                    v-if="(role == roleData.super_admin || role == roleData.admin) && !item.end_date">
                                                    <svg @click="unassignEmployee(item)"
                                                        class="cross__button text_end_only_desktop text-md-end">
                                                        <use xlink:href="#cancelcross" />
                                                    </svg>
                                                </span>
                                            </span>
                                            <span v-if="!item.assigned_name">--</span>
                                        </strong>
                                    </td>
                                    <td data-title="Action" class="text_end_only_desktop">
                                        <div class="edit_action edit_job_actions">
                                            <a href="#" id="dropdownMenuButton" class="no_action" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" viewBox="0 0 16 16"
                                                    class="bi bi-three-dots-vertical no_action">
                                                    <path
                                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <router-link v-if="(!item.scheduled_date && (role == roleData.client))"
                                                    to="#" data-bs-toggle="modal" data-bs-target="#AssignJob"
                                                    @click.native="assignParentId(item)"
                                                    class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Request
                                                    Date</router-link>
                                                <router-link
                                                    v-if="(!item.assigned_to_id && (role == roleData.super_admin || role == roleData.admin) && id)"
                                                    to="#" data-bs-toggle="modal" data-bs-target="#AssignJob"
                                                    @click.native="assignParentId(item)"
                                                    class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Assign
                                                    & Schedule</router-link>
                                                <!--                                                <router-link v-if="(item.assigned_to_id && (role == roleData.super_admin || role == roleData.admin) && id)"  to="#" data-bs-toggle="modal" data-bs-target="#AssignJob" @click.native="assignParentId(item)" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Re Assign & Schedule</router-link>-->
                                                <router-link
                                                    v-if="item.assigned_to_id && id && !item.end_date && (role == roleData.super_admin || role == roleData.admin)"
                                                    to="#" data-bs-toggle="modal" data-bs-target="#completeEmployeeJob"
                                                    @click.native="employeeJobcomplete(item)"
                                                    class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Complete</router-link>
                                                <router-link v-if="item.assigned_to_id && id && item.end_date" to="#"
                                                    data-bs-toggle="modal" data-bs-target="#completeEmployeeJob"
                                                    @click.native="employeeJobcomplete(item)"
                                                    class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Attachments</router-link>
                                                <router-link
                                                    v-if="(item.assigned_to_id || (role == roleData.client && item.scheduled_date)) && !item.end_date"
                                                    to="#" data-bs-toggle="modal" data-bs-target="#AssignJob"
                                                    @click.native="assignParentId(item)"
                                                    class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Update</router-link>
                                                <router-link
                                                    v-if="((item.scheduled_date || item.assigned_to_id) && (role == roleData.super_admin || role == roleData.admin)) && !item.end_date"
                                                    to="#" data-bs-toggle="modal" data-bs-target="#UnscheduleServiceModel"
                                                    @click.native="unscheduleRequest(item)"
                                                    class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Unschedule</router-link>
                                                <router-link v-if="!item.end_date" to="#" data-bs-toggle="modal"
                                                    data-bs-target="#RemoveServicePopup"
                                                    @click.native="removeAllServicesPrompt(item, index)"
                                                    class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item">Cancel</router-link>
                                            </div>
                                        </div>
                                        <!--                                        <a v-if="(!item.scheduled_date && (role == roleData.client))" class="table_btn_link font_12 font_weight_700 text_uppercase" href="#" data-bs-toggle="modal" data-bs-target="#AssignJob"-->
                                        <!--                                        @click="assignParentId(item)">Request Date</a>-->
                                        <!--                                        <a v-if="(!item.assigned_to_id && (role == roleData.super_admin || role == roleData.admin) && id)" class="table_btn_link font_12 font_weight_700 text_uppercase" href="#" data-bs-toggle="modal" data-bs-target="#AssignJob"-->
                                        <!--                                        @click="assignParentId(item)">Assign & Schedule</a>-->
                                        <!--                                        <a class="table_btn_link font_12 font_weight_700 text_uppercase"  href="#" data-bs-toggle="modal" data-bs-target="#completeEmployeeJob"-->
                                        <!--                                           @click="employeeJobcomplete(item)" v-if="item.assigned_to_id && id && !item.end_date">Complete</a>-->
                                        <!--                                        <a class="table_btn_link font_12 font_weight_700 text_uppercase"  href="#" data-bs-toggle="modal" data-bs-target="#completeEmployeeJob"-->
                                        <!--                                           @click="employeeJobcomplete(item)" v-if="item.assigned_to_id && id && item.end_date">Attachments</a>-->
                                        <!--                                        <a class="table_btn_link font_12 font_weight_700 text_uppercase" v-if="(item.assigned_to_id || (role == roleData.client && item.scheduled_date)) && !item.end_date" href="#" data-bs-toggle="modal" data-bs-target="#AssignJob"-->
                                        <!--                                        @click="assignParentId(item)">Update</a>-->
                                        <!--                                        <a class="table_btn_link font_12 font_weight_700 text_uppercase" v-if="((item.scheduled_date || item.assigned_to_id) && (role == roleData.super_admin || role == roleData.admin)) && !item.end_date" href="#" data-bs-toggle="modal" data-bs-target="#UnscheduleServiceModel"-->
                                        <!--                                        @click="unscheduleRequest(item)">Unschedule</a>-->
                                        <!--                                        <a class="table_btn_link font_12 font_weight_700 text_uppercase" data-bs-toggle="modal" data-bs-target="#RemoveServicePopup" href="#" @click="removeAllServicesPrompt(item,index)" v-if="!item.end_date">Cancel</a>-->

                                    </td>
                                </tr>




                                <tr class="colapsible_tr colapsible_inner_tr">
                                    <td class="hiddenRow" colspan="9">

                                        <table :id="'panelsStayOpen-collapseThree' + index"
                                            class="add_assign_job_tbl table table_hidden_block accordion-collapse collapse"
                                            aria-labelledby="panelsStayOpen-headingThree">

                                            <tbody>
                                                <tr class="tr_full_in_all">
                                                    <td class="td_full_in_all width_one_box">
                                                        <h6
                                                            class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_16">
                                                            {{ item.service.category }} Services
                                                        </h6>
                                                    </td>
                                                    <!-- <td class="text-md-center width_two_box"  v-if="role != roleData.client">
                                                            <div class="table_checkbox">
                                                                <label>
                                                                    <input type="checkbox" data-ng-model="example.check" v-bind:true-value="1"  v-bind:false-value="0" v-model.trim = "item.is_fixed_price" />
                                                                    <span class="box">Project Pay</span>
                                                                </label>
                                                            </div>
                                                        </td> -->
                                                    <td class="text-md-center width_three_box mobile-box">
                                                        <div class="project-pay-wrap">
                                                            <div class="table_checkbox" v-if="role != roleData.client">
                                                                <label>
                                                                    <input type="checkbox" data-ng-model="example.check"
                                                                        v-bind:true-value="1" v-bind:false-value="0"
                                                                        v-model.trim="item.is_fixed_price" />
                                                                    <span class="box">Project Pay</span>
                                                                </label>
                                                            </div>
                                                            <div class="form-group ppyroll__Price edit-job-div"
                                                                v-if="item.is_fixed_price && item.is_fixed_price == 1">
                                                                <div class="dollar-div">
                                                                    <span class="fieldAffix-item">$</span>
                                                                    <input type="number" strp="any"
                                                                        class="form-control form-control-sm"
                                                                        v-model.trim="item.payroll_price"
                                                                        placeholder="Payroll Price" />
                                                                </div>
                                                            </div>
                                                            <a v-if="(id && item.end_date)" href="#"
                                                                class="table_btn_link font_12 font_weight_700 text_uppercase text_md_right"
                                                                data-bs-toggle="modal" data-bs-target="#ViewNotes"
                                                                @click="viewNotesMethod(item.requested_service_id)"><span>Attachments</span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <!-- <td class="width_four_box text_md_right">
                                                            <a v-if="(id && item.end_date)" href="#" class="table_btn_link font_12 font_weight_700 text_uppercase text_md_right" data-bs-toggle="modal" data-bs-target="#ViewNotes"
                                                                @click="viewNotesMethod(item.requested_service_id)"><span >Employee Report</span>
                                                            </a>
                                                        </td> -->
                                                </tr>

                                                <tr class="tr_full_in_all">
                                                    <td :colspan="6" class="hiddenRow">

                                                        <div class="hidden_tr_div"
                                                            v-for="(item1, index1) in jobServicesDetails[item.service.category]"
                                                            v-bind:style="item1.is_invoice == 0 ? 'background-color: antiquewhite' : 'background-color: #f1f5f8'">
                                                            <table class="table edit-job-div">
                                                                <tbody>
                                                                    <tr>

                                                                        <td data-title="Sub Service(s)" class="width_100">
                                                                            <h2>Service</h2>
                                                                            <div class="item-heading">
                                                                                {{ item1.service_sub_category ? item1.service_sub_category.name : item1.service.category }}
                                                                                <b v-if="item1.is_invoice == 0">(Not
                                                                                    Billed)</b>
                                                                            </div>
                                                                        </td>
                                                                        <td data-title="Description Details"
                                                                            class="width_200">
                                                                            <h2>Description</h2>
                                                                            <textarea v-model.trim="item1.description"
                                                                                class="taxarea_job form-control"
                                                                                placeholder="Description"></textarea>
                                                                        </td>


                                                                        <td data-title="Quantity"
                                                                            class="text_right_all width_40">
                                                                            <h2>QTY</h2>
                                                                            <!-- :disabled="role == roleData.client" -->
                                                                            <input type="number"
                                                                                v-on:change="reCalculate()"
                                                                                v-model.trim="item1.quantity"
                                                                                class="form-control" placeholder="Qty">
                                                                        </td>
                                                                        <td data-title="Unit Price"
                                                                            class="text_right_all width_70">
                                                                            <h2>Unit Price</h2>
                                                                            <div class="form-group prefix_align_center">
                                                                                <div class="dollar-div">
                                                                                    <span class="fieldAffix-item">$</span>
                                                                                    <input
                                                                                        :disabled="role == roleData.client"
                                                                                        type="number" step="any"
                                                                                        v-model.trim="item1.price"
                                                                                        class="form-control number-div"
                                                                                        placeholder="Price"
                                                                                        v-on:change="editPrice()">
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td data-title="Total"
                                                                            class="text_right_all width_70">
                                                                            <h2>Total</h2>
                                                                            <div class="form-group">
                                                                                <div class="dollar-div">
                                                                                    <span class="fieldAffix-item">$</span>
                                                                                    <input disabled
                                                                                        v-model.trim="item1.total_price.toFixed(2)"
                                                                                        class="form-control number-div"
                                                                                        placeholder="Total">
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td data-title="Delete"
                                                                            class="width_18 text-md-end">
                                                                            <button type="button"
                                                                                class="cross__button text_end_only_desktop text-md-end btn-close btncclose "
                                                                                @click="removeService(item1, index1)"></button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="tr_full_in_all">
                                                    <td class="td_full_in_all" :colspan="7">
                                                        <h6
                                                            class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_16">
                                                            Job Notes</h6>
                                                    </td>
                                                </tr>
                                                <tr class="tr_full_in_all">
                                                    <td class="td_full_in_all" :colspan="7">
                                                        <textarea v-model="item.description"
                                                            class="taxarea_job form-control"></textarea>
                                                    </td>
                                                </tr>
                                                <tr class="tr_full_in_all job-file-upload">
                                                    <td class="td_full_in_all" :colspan="7">
                                                        <UploadFileComponent ref="uploadFile" :serviceId="item.service.id"
                                                            :accept="acceptImageTypes" :files="item.Existingfiles"
                                                            :multiple="true" :placeholder="'button'"
                                                            @uploaded="fileUploaded" @invalid="invalidFileType"
                                                            api="/api/file-upload"></UploadFileComponent>
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
                                                    <td class="width_ninty text_md_right">Total:</td>
                                                    <td class="width_fifteen" data-title="Total">
                                                        {{ this.totalamount | toCurrency }}
                                                    </td>
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
<!--
                <div id="success-alert1" class="alert alert-success" style="margin-top: 10px;" v-if="successmsg !== ''">
                    <strong>{{ successmsg }}</strong>
                </div> -->
                <div id="errir-alert" class="alert alert-danger" v-if="errormsg !== '' && successmsg == ''">
                    <strong>{{ errormsg }}</strong>
                </div>

                <div class="inline_buttonss">
                    <div class="row">
                        <div class="col-sm-12 text-end">
                            <button type="submit"
                                class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">
                                Save
                            </button>
                            <button type="submit" @click="saveAndClose"
                                class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">
                                Save &amp; Close
                            </button>
                            <router-link to="/all-jobs"
                                class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">Cancel</router-link>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal fade custom_base_model custom_base_model_small" id="AssignJob" tabindex="-1"
                aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <Assign ref="assignInfo" @assign="assign_info" @closeEvent="forceRerender" :service="selectedService"
                    :parentServiceId="assignParentCategoryId" :jobId="id"></Assign>
            </div>
            <!--            Delete Model-->
            <!--<div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                 aria-labelledby="deleteModel" aria-hidden="true">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>-->
            <div class="modal fade custom_base_model custom_base_model_small" id="ViewNotes" tabindex="-1"
                aria-labelledby="viewNotes" aria-hidden="true" data-bs-backdrop="static">
                <ViewNotes @closeEvent="notesForceRenderer" :notesId="notesId"></ViewNotes>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="UnscheduleAllServicesModel" tabindex="-1"
                aria-labelledby="UnscheduleModel" aria-hidden="true" data-bs-backdrop="static">
                <UnscheduleModel @closeEvent="unscheduleAllServices"></UnscheduleModel>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="UnscheduleServiceModel" tabindex="-1"
                aria-labelledby="UnscheduleModel" aria-hidden="true" data-bs-backdrop="static">
                <UnscheduleModel @closeEvent="unscheduleAllServices" :serviceName="unscheduleParentServiceName">
                </UnscheduleModel>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="CancelJobPopup" tabindex="-1"
                aria-labelledby="UnscheduleModel" aria-hidden="true" data-bs-backdrop="static">
                <CancelJobPopup @closeEvent="deleteRecord" :jobId="id"></CancelJobPopup>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="RemoveServicePopup" tabindex="-1"
                aria-labelledby="RemoveServicePopup" aria-hidden="true" data-bs-backdrop="static">
                <RemoveServicePopup @closeEvent="removeAllServiceConfirm"></RemoveServicePopup>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="removeOneServicePop" tabindex="-1"
                aria-labelledby="removeOneServicePop" aria-hidden="true" data-bs-backdrop="static">
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

            <div class="modal fade custom_base_model" id="completeEmployeeJob" tabindex="-1"
                aria-labelledby="completeEmployeeJob" aria-hidden="true" data-bs-backdrop="static">
                <completeEmployeeJob @closeEvent="refreshData" :requestId="requestId" :assignedToId="assignedToId"
                    :completEmployeeData="completEmployeeData" :jobProgressData="jobProgressData"></completeEmployeeJob>
            </div>
            <div class="modal fade custom_base_model" id="serviceStatusModal" tabindex="-1"
                aria-labelledby="completeEmployeeJob" aria-hidden="true" data-bs-backdrop="static">
                <serviceStatusPopup @closeEvent="addNewitem" :serviceStatusData="serviceStatusData"></serviceStatusPopup>
            </div>
        </div>
    </div>
</template>


<script>
import axios from "axios";
import DeleteModel from "../deleteModel";
import UnscheduleModel from "./unscheduleService";
import { required, minLength, maxLength, email, requiredIf, url, decimal } from 'vuelidate/lib/validators'
import Loader from "../LoaderSpinner";
import Datepicker from 'vuejs-datepicker';
import Assign from "./newAssign";
import UploadFileComponent from "../../components/UploadFileComponent";
import Multiselect from 'vue-multiselect';
import roleData from '../../data.js';
import ViewNotes from "./ViewNotes";
import CancelJobPopup from "./cancelJobNotes";
import RemoveServicePopup from "./removeServicePopup";
import CurrencyInput from "./CurrencyInput";
import Navigation from "../../components/Navigation";
import completeEmployeeJob from "./completeEmployeeJob";
import serviceStatusPopup from "./serviceStatusPopup";
import moment from "moment";
export default {
    name: "Add",
    components: {
        'Loader': Loader,
        'Datepicker': Datepicker,
        'Assign': Assign,
        'DeleteModel': DeleteModel,
        UploadFileComponent: UploadFileComponent,
        Multiselect,
        'ViewNotes': ViewNotes,
        'UnscheduleModel': UnscheduleModel,
        'CancelJobPopup': CancelJobPopup,
        'RemoveServicePopup': RemoveServicePopup,
        'CurrencyInput': CurrencyInput,
        'Navigation': Navigation,
        'completeEmployeeJob': completeEmployeeJob,
        'serviceStatusPopup': serviceStatusPopup
    },
    data() {
        return {
            acceptImageTypes: ['image/png', 'image/jpeg'],
            roleData: roleData,
            todayDate: "",
            year: "",
            servcMsg: "",
            itemTemp:undefined,
            index1Temp: undefined,
            increment: null,
            searchQuery: "",
            currentComponent: 'clientEdit',
            services: [],
            parentServices: [],
            serviceReadyChecklist: [],
            service_ready_error: false,
            properties: [],
            clients: [],
            appartmentTypes: [],
            id: this.$route.params.id,
            successmsg: "",
            errormsg: "",
            loading: false,
            already_user: false,
            Existingfiles: [],
            job: [],
            jobServices: [],
            jobServicesDetails: [],
            anchorz: "",
            assignParentCategoryId: "",
            totalamount: 0,
            job_form: {
                property_id: "",
                apartment_type_id: "",
                apartment_number: "",
                apartment_status: "vacant",
                po_number: "",
                job_status: "",
                job_service_ready_checklist: [],
                requested_job_services: [],
            },
            value: [],
            options: [],
            submit: false,
            role: "",
            searchIcon: false,
            selectedService: "",
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            notesId: "",
            unscheduleParentServiceId: "",
            unscheduleParentServiceName: "",
            closeFlag: false,
            is_PO: false,
            removeServiceItem: "",
            removeServiceIndex: "",
            requestId: "",
            assignedToId: "",
            serviceStatusData: "",
            completEmployeeData: "",
            jobProgressData: "",
        }
    },
    validations: {
        job_form: {
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
                // required,
            },
            po_number: {
                // required,
                // required: requiredIf(function (job_form){
                //     return job_form.property_id.is_PO_required == 1;
                // }),
                // minLength: minLength(2),
                // maxLength: maxLength(20),
            },


            job_service_ready_checklist: {
                // required,
            },
             requested_job_services: {
            //     required,
             },
        }
    },

    watch: {
        '$route.params.id'(val) {
            if (!val) {
                this.formReset();
            }
        },
        'job_form.property_id'(val) {
            // if (this.job_form?.apartment_type_id) {
            //     if (this.job_form?.apartment_type_id?.id) {
                    if(val && val.id)
                    {
                        this.getPropertyServices();
                    }
            //     }
            // }
        },
        'job_form.apartment_type_id'(val) {
            if (this.job_form?.property_id) {
                if (this.job_form?.property_id?.id) {
                   // this.getPropertyServices();
                  // this.onAppointmentTypeChange();
                }
            }
        },
    },
    computed: {
        resultQuery() {
            if (this.searchQuery) {
                return this.options.filter((item) => {
                    if (item.service_sub_category) {
                        return item.service_sub_category.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                        // return item.service_sub_category.name.toLowerCase().startsWith(this.searchQuery.toLowerCase());
                        // return this.searchQuery.toLowerCase().split(' ').every(v => item.service_sub_category.name.toLowerCase() == v)
                    }
                    if (item.service) {
                        return item.service.category.toLowerCase().includes(this.searchQuery.toLowerCase());
                        // return item.service.category.toLowerCase().startsWith(this.searchQuery.toLowerCase());
                        // return this.searchQuery.toLowerCase().split(' ').every(v => item.service.category.toLowerCase().includes(v))
                    }
                })
            } else {
                return this.options;
            }
        },
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
        openOptions()
        {
            let items_ = document.getElementById('items');
            items_.classList.add('visible');
            items_.style.display = "block";
            this.searchIcon = true;
            this.showSection = false;
            this.showSection = true;
        },
        addListerners() {
            try {
            document.getElementById('job-add').addEventListener('click', (event) => {
            try{

                    this.onClickOutside(event);
                } catch(e){}
            });
        } catch(e){}
        try {
            document.getElementById('edit-job').addEventListener('click', (event) => {
            try{
                    this.onClickOutside(event);
                } catch(e){}
            });
        } catch(e){}
    },
        isReadyTobill(jobId, val) {
            // // console.log("checked",val);
            // var exist = role.permissions.includes(module);
            axios.post('/api/isreadytobill/' + jobId + '/' + val)
                .then((response) => {
                    if (response && response.data.msg) {
                        this.successmsg = response.data.msg;
                        this.getRecord(this.id);
                    }
                    else {
                        this.errormsg = "Failed to updated";
                    }
                    setTimeout(() => {
                        this.errormsg = "";
                        this.successmsg = "";
                    }, 3000);
                }).catch(err => {
                    // // console.log(err);
                });
        },
        onClickOutside(event) {
            const dropdownElement = this.$refs.customDropdown;
            if (!dropdownElement.contains(event.target)) {
                this.closeOptions();
            }
        },
        getServicePriceById : (data, s, f) => {
           const result = data.find(x => x.service_type_id === s.service_type_id && x.sub_service_id === s.sub_service_id );
           return (result && result.price ? result.price : 0).toFixed(2);
        },
        async onAppointmentTypeChange() {
            this.totalamount = 0;

                const response = await axios.get('/api/property-services/' + this.job_form?.property_id?.id + "/" + this.job_form?.apartment_type_id?.id );
                const services = response.data.services;
                this.loading = true;

                for(const ind in this.services) {
                    this.services[ind].price = this.getServicePriceById(services,this.services[ind])
                }
                await   this.multiSelectOption(true);
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
        clearCurrentFilter(name) {
            if (name == 'size') {
                this.job_form.apartment_type_id = "";

            } else if (name == 'community') {
                this.job_form.property_id = "";

            }
            this.$refs[name + '_multiselect'].toggle();
        },
        employeeJobcomplete(service) {
            this.requestId = service.requested_service_id;
            this.assignedToId = service.assigned_to_id;
            axios.get('/api/employee/get-notes-attachment/' + service.requested_service_id)
                .then((response) => {
                    this.completEmployeeData = response.data
                    this.jobProgress(service.requested_service_id, service.assigned_to_id)
                })

        },
        jobProgress(reqId, assignedTo) {
            axios.get('/api/employee/employee-job-progress/' + reqId + '/' + assignedTo)
                .then((response) => {
                    this.jobProgressData = response.data;
                })
        },
        showSection() {
            this.searchIcon = true;
        },
        sortByName: function (list) {
            return _.orderBy(list, 'service_type_id', 'asc');
        },
        addNewitem(val) {
            if (val == 0) {
                this.serviceStatusData = "";
                return
            }
            let exist = this.jobServices.some(data => data.service_type_id === this.serviceStatusData.service_type_id);

            if (exist) {

                this.totalamount += this.serviceStatusData.price;
                this.jobServicesDetails[this.serviceStatusData.service.category].push(this.serviceStatusData);
                // // console.log("this.jobServicesDetails",this.jobServicesDetails)

            }

        },
         updateServiceList: function (item, options="") {
            this.loading = true;
            if(options=="no-created-at")
            {
                item.created_at = "";
            }
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
            this.totalamount += item.price;
            this.jobServicesDetails[item.service.category] = [... this.jobServicesDetails[item.service.category],{...item}];
            this.servcMsg = 'Service has been added';
            setTimeout(() =>this.servcMsg = '', 3000);
            if (this.searchQuery) {
                this.searchQuery = '';
            }
           // this.closeOptions();
            this.reCalculate();
            this.loading = false;
        },
        job_submit() {
            this.submit = true;
            this.loading = true;
            this.$v.job_form.$touch();
            this.service_ready_error = false;

            if (this.$v.job_form.$anyError) {
                this.loading = false;
                return;
            }
            // let project_pay = [];
            let job_data = this.job_form;
            job_data.requested_job_services = [];
            // job_data.requested_job_sub_services = [];
            job_data.property_id = this.job_form.property_id.id;

            job_data.apartment_type_id = (this.job_form.apartment_type_id) ? this.job_form.apartment_type_id.id : null;
            this.jobServices.forEach(data => {
                data.requested_job_sub_services = [];
                const array = this.jobServicesDetails[data.service.category];
                array.forEach(item => {
                    data.requested_job_sub_services.push(item);
                });
                job_data.requested_job_services.push(data);
            })
            if (this.id) {
                axios.put('/api/job/' + this.id, job_data)
                    .then(response => {
                        this.loading = false;
                        if (response.data.error) {
                            this.errormsg = response.data.error;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {
                            this.successmsg = "Job has been updated";
                            this.$emit('updateCount');
                            this.errormsg = "";
                            if (this.closeFlag) {
                                var path = localStorage.getItem('lastRoute');
                                if(path == '/required-po')
                                this.$router.push('/required-po');
                                 else
                                this.$router.push('/all-jobs');
                            } else {
                                this.Existingfiles = [];
                                this.getRecord(this.id);
                            }
                            setTimeout(() => {
                                this.successmsg = "";
                            }, 2000);
                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            } else {

                axios.post('/api/job', job_data)
                    .then(response => {
                        this.loading = false;
                        if (response.data.error) {
                            this.errormsg = response.data.error;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {
                            this.successmsg = "Job has been added";
                            this.$emit('updateCount');
                            this.errormsg = "";
                            if (this.closeFlag) {
                                this.$router.push('/all-jobs');
                            } else {
                                this.$router.push('/edit-job/' + response.data.job_id);
                                this.id = response.data.job_id;
                                this.getRecord(response.data.job_id);
                            }
                            setTimeout(() => {
                                this.successmsg = "";
                                // this.$router.back()
                            }, 2000);
                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }
        },
        getRecord(id) {
            this.loading = true;
            axios.get('/api/job/' + id + "/edit")
                .then((response) => {
                    if (response){
                        this.job = response.data.job;
                    // console.log(this.job);
                    this.job_form.apartment_type_id = (this.job.apartment_type_id) ? this.appartmentTypes.find(apartment => (apartment.id == this.job.apartment_type_id)) : null;
                    this.job_form.property_id = (this.job.property_id) ? this.properties.find(property => (property.id == this.job.property_id)) : null;
                    this.is_PO = this.job_form?.property_id.is_PO_required ? true : false;
                    this.job_form.apartment_number = this.job.apartment_number;
                    this.job_form.apartment_status = this.job.apartment_status;
                    this.job_form.po_number = this.job.po_number;

                    this.job.service_ready_check_list.forEach((data) => {
                        this.job_form.job_service_ready_checklist.push(data.id);
                    });

                    }

                    // this.job.job_attatchment.forEach((file) => {
                    //     this.Existingfiles.push(file.filename);
                    // });
                    this.loading = false;
                });
        },

        jobCreate() {
            axios.get('/api/job/create')
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
                    if (this.id) {
                        this.getRecord(this.id);
                    }
                    this.getIncrementPercentage();

                });
        },
        sortFunc: function (array) {
            return array.slice().sort(function (a, b) {
                return (a.title.toLowerCase() > b.title.toLowerCase()) ? 1 : -1;
            });
        },
        getIncrementPercentage() {
            let data = this.priceIncrement.find(increment => (increment.year == this.year));
            if (data) {
                this.increment = data.percentage;
            }
        },
        customLabel(service) {
            return `${service.service_sub_category.name}`;
        },
        customLabelClient(client) {
            if (client.user) {
                return `${client.user.first_name ? client.user.first_name : ''} ${client.user.middle_name ? client.user.middle_name : ''} ${client.user.last_name ? client.user.last_name : ''}`;
            }
        },
        getPropertyServices() {
            // this.loading = true;
            let appendUrl = ''
            try {
                if (this.job_form?.apartment_type_id?.id)
                     appendUrl = '/' + this.job_form?.apartment_type_id?.id;
            }catch(e){}
            axios.get('/api/property-services/' + this.job_form?.property_id?.id)
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

                        // value.requested_date = this.todayDate
                    });

                    this.multiSelectOption();
                    this.loading = false;
                });
        },
        requestedJobServices() {
            let val = [];
            this.totalamount = 0;
            this.job_form.requested_job_services = [];
            this.jobServices = [];
            this.jobServicesDetails = [];
            this.job.requested_job_service.forEach((data) => {
                this.parentServices.some((service) => {
                    if (service.service_type_id == data.service_id) {
                        service.description = data.description;
                        service.Existingfiles = [];
                        if (data.job_attatchment) {
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
                        if (data.assigned_to_id !== null) {
                            service.assigned_to_id = data.assigned_to_id
                            service.assigned_to_type = data.assigned_to_type
                            if (service.assigned_to_type == "individual") {
                                // service.assigned_name= data.employee.user.first_name + " "+data.employee.user.middle_name +" "+data.employee.user.last_name;
                                service.assigned_name = `${data.employee.user?.first_name ? data.employee.user?.first_name : ''} ${data.employee.user.middle_name ? data.employee.user?.middle_name : ''} ${data.employee.user.last_name ? data.employee.user.last_name : ''}`
                            } else {
                                service.assigned_name = data.employee_crew.name;
                            }
                        }
                        //to get scheduled infor
                        if (data.scheduled_date !== null) {
                            service.scheduled_date = data.scheduled_date
                            service.scheduled_time = data.scheduled_time
                            service.anytime = data.anytime
                            service.start_date = data.start_date
                        }
                        if (data.schedule_update_at !== null) {
                            service.schedule_update_at = data.schedule_update_at

                        }
                        this.jobServices.push({ ...service, created_at: data.created_at });

                        let serviceValue = service;
                        this.jobServicesDetails[serviceValue.service.category] = [];
                        data.requested_job_sub_service.forEach(item => {
                            this.totalamount += Number(item.base_price);
                            this.services.some((subService) => {
                                if (subService.service_type_id == data.service_id && subService.sub_service_id == item.sub_service_id) {
                                    // subService.price = item.base_price.toFixed(2);
                                    subService.requested_sub_service_id = item.id;
                                    subService.is_invoice = item.is_invoice;
                                    subService.quantity = item.quantity;
                                    subService.description = item.description ? item.description : "";
                                    (this.jobServicesDetails[serviceValue.service.category]).push({ ...subService, price: item.base_price.toFixed(2)})
                                    this.job_form.requested_job_services.push(subService);
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
                                if (this.job_form?.apartment_type_id?.id)
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
        editPrice() {
            // this.totalamount=0;
            // this.jobServices.forEach(data =>{
            //     this.jobServicesDetails[data.service.category].forEach(item=>{
            //         this.totalamount += Number(item.price);
            //     })
            // })
            this.reCalculate()
        },
        reCalculate() {
            this.jobServices.forEach(data => {
                this.jobServicesDetails[data.service.category].forEach(item => {

                    item.total_price = Number(item.price * item.quantity);
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
        updateTotal() {
            this.totalamount = 0;
            this.jobServices.forEach(data => {
                this.jobServicesDetails[data.service.category].forEach(item => {
                    this.totalamount += Number(item.price * item.quantity);
                })
            })
        },
        fileUploaded(filenames, serviceId) {
            // this.job_form.filePaths = filenames;
            this.jobServices.forEach((rjs, index) => {
                if (rjs.service_type_id == serviceId) {
                    this.jobServices[index].filePaths = filenames;
                    this.jobServices[index].Existingfiles = filenames;
                }
            });
        },
        invalidFileType(msg) {
            this.errormsg = msg;
            setTimeout(() => {
                this.errormsg = "";
            }, 3000);
        },
        selectProperty(property) {
            this.is_PO = property.is_PO_required ? true : false;
            this.appartmentTypes = property.appartment_types;
            let appartment = this.appartmentTypes.find(appartment => (this.job_form && this.job_form.apartment_type_id  && appartment.id == this.job_form.apartment_type_id.id))
            if (!appartment) {
                this.job_form.apartment_type_id = "";
            }

        },
        formReset() {
            this.$v.job_form.$reset();
            this.job_form = {
                property_id: "",
                apartment_type_id: "",
                apartment_number: "",
                apartment_status: "vacant",
                po_number: "",
                job_status: "",
                job_service_ready_checklist: [],
                requested_job_services: [],
                jobServices: [],
                jobServicesDetails: [],
                filePaths: []
            }
            this.Existingfiles = [];
            this.$refs.uploadFile.mergedFiles = null;
            this.job = "";
            this.id = undefined;
            this.submit = false
            this.$forceUpdate();
        },
        searchOptions() {
            // jquery for select option deropdown
            var items = document.getElementById('items');
            items.classList.add('visible');
            items.style.display = "block";
        },
        closeOptions() {
            let items_ = document.getElementById('items');
            items_.classList.remove('visible');
            items_.style.display = "none";
            this.searchIcon = false;
            if (this.searchQuery) {
                this.searchQuery = '';
            }
            this.searchIcon = false;


        },
        removeAllService(item, index) {
            let data = this.job_form.requested_job_services.filter(data => (data.service_type_id === item.service_type_id));
            data.forEach(x => {
                let index = this.jobServices.findIndex(data => data.service_type_id === item.service_type_id);
                this.job_form.requested_job_services[index].description = '';
                this.job_form.requested_job_services.splice(index, 1);
            })
            this.jobServices.splice(index, 1);
            this.jobServicesDetails[item.service.category].forEach((val, index1) => {
                //deduct price from total amount
                this.totalamount = this.totalamount - val.price;
                this.totalamount = this.totalamount.toFixed(2);
                //remove from sub service array
                this.jobServicesDetails[item.service.category].splice(index1, 1);
            })
        },
        removeOneService(proceed) {
            if(proceed)
            {
                this.removeService(this.itemTemp, this.index1Temp, false);
            }
            $("#removeOneServicePop").modal('hide');
            this.itemTemp=undefined;
            this.index1Temp =undefined;
        },
        removeService(item, index1, showPopup=true) {
            if(showPopup)
            {
                this.itemTemp=item;
                   this.index1Temp =index1;
                $("#removeOneServicePop").modal('show');

                return;
            }
            this.loading = true;
           // let data = this.job_form.requested_job_services.filter(data => (data.service_type_id === item.service_type_id))

            //remove from main array
            let index = this.job_form.requested_job_services.findIndex(data => (data.id === item.id));
            try {
                this.job_form.requested_job_services[index].description = '';
            this.job_form.requested_job_services.splice(index, 1);
            } catch(e){}
            //deduct price from total amount
            // this.totalamount = this.totalamount - this.jobServicesDetails[item.service.category][index1].price

            //remove from sub service array
            this.jobServicesDetails[item.service.category].splice(index1, 1);

            if (this.jobServicesDetails[item.service.category].length < 1)
            {
                let index = this.jobServices.findIndex(data => data.service_type_id === item.service_type_id);
                if (index > -1) {
                    this.jobServices.splice(index, 1);
                }
            }
            this.loading = false;
            this.reCalculate()
        },

        assignParentId(service) {
            this.assignParentCategoryId = service.service_type_id;
            this.selectedService = service;
        },
        forceRerender(msg="") {
            this.assignParentCategoryId = '';
            this.selectedService = "";
            if(msg!="no-refresh"){
                this.job_submit()
            }

        },
        refreshData() {
            this.requestId = null
            this.assignedToId = null
            this.getRecord(this.id);
            // window.location.reload()
        },
        assign_info() {
            let assign_data = this.$refs.assignInfo.assign_form;
            // // console.log(assign_data, 'in the d');
            let index = this.jobServices.findIndex(item => (item.service_type_id == assign_data.parentCategory));
            if (index > -1) {
                this.jobServices[index]['assigned_to_id'] = assign_data.assigned_to_id
                this.jobServices[index]['assigned_to_type'] = assign_data.assigned_to_type
                this.jobServices[index]['requested_date'] = assign_data.requested_date
                this.jobServices[index]['requested_for'] = assign_data.requested_for
                this.jobServices[index]['scheduled_date'] = assign_data.scheduled_date
                this.jobServices[index]['scheduled_time'] = assign_data.scheduled_time
                this.jobServices[index]['anytime'] = assign_data.anytime
                //to get assignment of services
                if (assign_data.assigned_to_id !== null || assign_data.assigned_to_id !== '') {
                    this.jobServices[index]['assigned_name'] = assign_data.assigned_name;
                }
            }
            this.assignParentCategoryId = '';
            this.selectedService = "";
            this.loading = false;
        },
        generateInvoice() {
            // this.loading=true;
            axios.get('/api/job-invoice-generate/' + this.id).then((response) => {
                this.successmsg = "Invoice has been generated";
                this.errormsg = "";
                setTimeout(() => {
                    this.loading = true;
                    this.successmsg = "";
                    // this.getRecord(this.id)
                    this.$router.push('/view-invoice/' + response.data.invoice_id);
                }, 1000);
            });
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
                this.$router.push('/cancel-job-details/' + this.deleteId);
                this.loading = false;
            }, 1000);
        },
        viewNotesMethod(id) {
            this.notesId = id;
        },
        notesForceRenderer() {
            this.notesId = '';
        },
        unscheduleRequest(item) {
            this.unscheduleParentServiceId = item.service.id;
            this.unscheduleParentServiceName = item.service.category;
        },
        unscheduleAllServices(val) {
            if (val == 0) {
                this.unscheduleParentServiceId = "";
                this.unscheduleParentServiceName = "";
                return;
            }
            this.loading = true;
            if (this.unscheduleParentServiceId != "") {
                axios.put('/api/unschedule-job-service/' + this.id + '/' + this.unscheduleParentServiceId)
                    .then(response => {
                        this.loading = false;
                        if (response.data.error) {
                            this.errormsg = response.data.error;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {
                            this.successmsg = response.data.message;
                            this.jobServices.forEach(item => {
                                if (item.service_type_id == this.unscheduleParentServiceId) {
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
                                this.$router.go();
                            }, 1000);
                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })

            } else {
                axios.put('/api/unschedule-job-service/' + this.id)
                    .then(response => {
                        this.loading = false;
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
                                this.$router.go();
                            }, 2000);
                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })
            }

        },
        saveAndClose() {
            this.closeFlag = true;
        },
        unassignEmployee(item) {
            let index = this.jobServices.findIndex(data => (data.service_type_id == item.service.id));
            if (index > -1) {
                this.jobServices[index]['assigned_to_id'] = '';
                this.jobServices[index]['assigned_to_type'] = '';
                this.jobServices[index]['assigned_name'] = '';
                this.$forceUpdate();
            }
        },
        removeAllServicesPrompt(item, index) {
            this.removeServiceItem = item;
            this.removeServiceIndex = index;
        },
        removeAllServiceConfirm(val) {
            if (val == 0) {
                this.removeServiceItem = "";
                this.removeServiceIndex = "";
                return
            }
            this.removeAllService(this.removeServiceItem, this.removeServiceIndex);
        }
    },

}

</script>



<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
