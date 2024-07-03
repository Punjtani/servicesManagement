<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <!-- ======== -->
        <div class="dashboard_content_area" v-if="!loading">

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Payroll</h1></div>
                <Navigation></Navigation>
            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg!==''"><strong>{{ successmsg }}</strong>
            </div>
            <div class="alert alert-danger" v-if="errormsg!==''"><strong>{{ errormsg }}</strong></div>
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================ -->
            <div class="table_area">
                <div class="table_area_head">
                    <div class="row align-items-center">
                        <div class="col-sm-12">
                            <h5 class="font_weight_400 font_family_Montserrat text_color_blue mg_bot_1_half_rem">
                                {{ employee.user.first_name }} {{ employee.user.middle_name }}
                                {{ employee.user.last_name }}
                                <small>Division - {{ employee.department.name }}</small></h5></div>
                    </div>

                    <div v-if="employee.department.name !=='Management' && employee.department.payroll_type !=='Salary'" class="row align-items-center row_spacing_5">
                        <div class="col-sm-6 col-md-6  col-lg-6">
                            <div class="row align-items-center row_spacing_5">

                                <div class="col-sm-6 text-sm-end">

                                    <div class="form-group">
                                        <!--<input type="date" class="form-control" v-model="startDate">-->
                                        <datepicker
                                            placeholder="Select date range"
                                            format="MM-DD-YYYY"
                                            input-class="form-control"
                                            v-model.trim="dateRange"
                                            :clearable="true"
                                            value-type="YYYY-MM-DD"
                                            range>
                                        </datepicker>

                                    </div>

                                </div>
                                <!--<div class="col-sm-3 text-sm-end">
                                    <div class="form-group">
                                        <input type="date" class="form-control" v-model="endDate">
                                    </div>
                                </div>-->
                                <div class="col-sm-6 text-sm-end">
                                    <div class="form-group">
                                        <multiselect
                                            deselectLabel=""
                                            v-model="service_sub_category"
                                            :show-labels="true"
                                            track-by="name"
                                            label="name"
                                            placeholder="Select Service"
                                            :select-label="''"
                                            :options="sub_categories"
                                            :searchable="true"
                                            @select="selectSubCategory"
                                        >
                                        <template #singleLabel="{ option }">
                                                    <div>
                                                        <span class="multiselect__single-label unit-size">{{ option.name }}</span>
                                                        <button v-if="service_sub_category"
                                                            class="multiselect__clear"
                                                            @mousedown="clearCurrentFilter('service')">
                                                            &#x2715;
                                                        </button>
                                                    </div>
                                                </template>
                                    </multiselect>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-sm-6 col-md-6  col-lg-6 text-sm-end">
                            <button type="button" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Add MANGEMENT EXTRAS</button>
                        </div>-->
                    </div>
                </div>
                <div class="no_more_tables table_to_be_used table_with_widths"  v-if="employee.department.name !=='Management' && employee.department.payroll_type !=='Salary'">
                    <table class="table text_center_for_md percentage-table-heading">
                        <thead class="thead_new">
                        <tr>
                            <th class="width_10_percent text_left_for_md">Unit #</th>
                            <th class="width_10_percent">Service</th>
                            <th class="width_10_percent">Service Date</th>
                            <!-- <th class="width_24_percent tb_width_1s">Service Details</th>
                            <th class="width_10_percent">Total Billed</th>
                            <th class="width_13_percent">Employee's Pay</th> -->
                            <th class="width_13_percent">Done</th>
                        </tr>
                        </thead>
                        <tbody class="tbody_new percentage-table" v-for="(item,index) in jobs"
                               v-if="employee.department.payroll_type ==='Percentage' && item.requested_job_service && item.requested_job_service.length > 0">
                        <tr aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo" class="colapsible_tr">

                            <td class="uniqueclass tb_width_2 text_left_for_md" data-title="Unit #"
                                data-bs-toggle="collapse" :data-bs-target="'#panelsStayOpen-collapseTwo'+ index">
                                <svg class="table_icon_chevron">
                                    <use xlink:href="#right-chevron"/>
                                </svg>
                                {{ item.property.title }} {{ item.apartment_number }} {{ item.apartment_type && item.apartment_type.type }} -
                                {{ item.apartment_status }}
                            </td>

                            <td>{{ item.requested_job_service && item.requested_job_service[0] && item.requested_job_service[0].service.category }}</td>

                            <td v-if="item.requested_job_service && item.requested_job_service[0]">{{ item.requested_job_service && item.requested_job_service[0] && item.requested_job_service[0].end_date | formatDate }}</td>
                            <td v-else="!item.requested_job_service">--</td>

<!--                            <td v-if="item.is_paid_payroll ==0 &&  item.requested_job_service[0].is_fixed_price == 0"-->
<!--                                colspan="3"></td>-->

<!--                            <td v-if=" item.requested_job_service[0].is_fixed_price == 1 || item.is_paid_payroll ==1"-->
<!--                                colspan="2"></td>-->

<!--                            <td v-if="item.is_paid_payroll ==1">{{ item.paid_payroll_price | toCurrency }}</td>-->

<!--                            <td v-if="item.is_paid_payroll ==0 &&  item.requested_job_service[0].is_fixed_price == 1">-->
<!--                                {{ item.requested_job_service && item.requested_job_service[0] && item.requested_job_service[0].payroll_price | toCurrency }} (Project Pay)-->
<!--                            </td>-->

                            <td data-title="Done">
<!--                                <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"-->
<!--                                   data-bs-toggle="modal" data-bs-target="#AddExtra" @click="AssignInfo(item)">-->
<!--                                    <span>Extras</span>-->
<!--                                </a>-->
                                <span class="seprator">|</span>
                                <input type="checkbox" data-ng-model="example.check" @change="payroll_paid(item)"
                                       v-bind:true-value="1" v-bind:false-value="0"
                                       v-model.trim="item.is_paid_payroll"/>&nbsp;<span class="paid">Paid</span>
                            </td>

                        </tr>
                        <tr class="colapsible_inner_tr">
                            <td colspan="7" class="hiddenRow hiddenRow_less">
                                <div :id="'panelsStayOpen-collapseTwo'+ index" class="accordion-collapse collapse"
                                     aria-labelledby="panelsStayOpen-headingTwo">
                                    <table class="table tablex payrol-border">
                                        <thead class="thead_new allhide">
                                        <!-- <tr class="allhide">
                                            <th class="width_10_percent allhide">Unit #</th>
                                            <th class="width_10_percent allhide">Service</th>
                                            <th class="width_10_percent allhide">Service Date</th>
                                            <th class="tb_width_1s allhide width_27_percent">Service Details</th>
                                            <th class="width_10_percent allhide">Total Billed</th>
                                            <th class="width_13_percent allhide">Employee's</th>
                                            <th class="width_10_percent allhide">Done</th>
                                        </tr> -->


                                        <tr>
                                            <!-- <th class="width_10_percent text_left_for_md">Unit #</th>
                                            <th class="width_10_percent">Service</th>
                                            <th class="width_10_percent">Service Date</th> -->
                                            <th class="width_24_percent tb_width_1s">Service Details</th>
                                            <th class="width_10_percent">QTY x Unit Price</th>
                                            <th class="width_10_percent">Total Billed</th>
                                            <th class="width_13_percent">Employee's Pay <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"
                                                                                           data-bs-toggle="modal" data-bs-target="#AddExtra" @click="AssignInfo(item)">
                                                <span>Extras</span>
                                            </a></th>
                                            <!-- <th class="width_13_percent">Done</th> -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item1,index1) in item.requested_job_service[0].requested_job_sub_service"
                                            v-if="item1.is_invoice == 1">
                                            <!-- <td data-title="Unit #" class="tb_width_2"></td>
                                            <td data-title="Service"></td>
                                            <td data-title="Service Date"></td> -->
                                            <td data-title="Job Services" class="left-align">
                                                {{ item1.sub_service.name }}
                                            </td>
                                            <td data-title="Qty x Unit Price">{{ item1.quantity}} x {{ item1.base_price | toCurrency}}</td>
                                            <td data-title="Total">{{ item1.total_price | toCurrency }}</td>
                                            <td data-title="Employee's pay"
                                                v-if="item.requested_job_service[0].is_fixed_price == 0 && item.requested_job_service[0].assigned_to_type === 'individual'">
                                                ${{ (item1.total_price * (employee.salary_value / 100)).toLocaleString() }}
                                                ({{ employee.salary_value }}%)
                                            </td>
                                            <td v-if="item.requested_job_service[0].is_fixed_price == 1 && item.requested_job_service[0].assigned_to_type === 'individual'" class="mobile-hide-dots">--</td>
                                            <td data-title="Employee's pay"
                                                v-if="item.requested_job_service[0].is_fixed_price == 0 && item.requested_job_service[0].assigned_to_type === 'crew'">
                                                ${{ (item1.total_price * (item.crew_employee / 100)).toLocaleString() }}
                                                ({{ item.crew_employee }}%)


                                            </td>
                                            <td v-if="item.requested_job_service[0].is_fixed_price == 1 && item.requested_job_service[0].assigned_to_type === 'crew'" class="mobile-hide-dots">--</td>

                                            <!--                                                    <td  v-if="item.is_paid_payroll ==0 &&  item.requested_job_service[0].is_fixed_price == 1"></td>-->
                                            <!--                                                    <td></td>-->
                                        </tr>
                                        <tr v-if="item.management_extra_and_payroll.length>0 && item.management_extra_and_payroll[0].amount !== null && item.management_extra_and_payroll[0].amount !== 0">
                                            <!-- <td data-title="Unit #" class="tb_width_2"></td>
                                            <td data-title="Service"></td> -->
                                            <td data-title="Management Extras" class="left-align mobile-hide">
                                                Management Extras

                                            </td>
                                            <td data-title="Management Extras" class="left-align desktop-hide">

                                                <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"
                                                                                           data-bs-toggle="modal" data-bs-target="#AddExtra" @click="AssignInfo(item)">
                                                <span>Add Extras</span>
                                            </a>
                                            </td>
                                            <td class="mobile-hide-dots">--</td>
                                            <td>{{ item.management_extra_and_payroll[0].amount | toCurrency }}</td>
                                            <td>{{ item.employee_extras | toCurrency}} (Extra)</td>

                                        </tr>
                                        <tr>
                                            <!-- <td data-title="Unit #" class="tb_width_2"></td>
                                            <td data-title="Service"></td> -->
                                            <td data-title="Sub Services Total" class="left-align mobile-hide">
                                                <strong>Sub Services Total</strong>
                                            </td>
                                            <td data-title="Sub Services Total" class="left-align desktop-hide pt-0">

                                            </td>
                                            <td class="mobile-hide-dots">--</td>
                                            <td><strong>{{item.total | toCurrency}}</strong><span class="desktop-hide">(Sub Services Total)</span></td>
                                            <td><strong v-if="item.requested_job_service[0].is_fixed_price == 0">{{item.payroll  | toCurrency}}</strong><span v-if="item.requested_job_service[0].is_fixed_price == 0" class="desktop-hide">(Payroll Total)</span></td>
                                            <td v-if="item.requested_job_service[0].is_fixed_price == 1">
                                                <strong>  {{ item.requested_job_service && item.requested_job_service[0] && item.requested_job_service[0].payroll_price + item.employee_extras| toCurrency }} (Project Pay)</strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>

                        </tbody>
                        <tbody class="tbody_new percentage-table" v-for="(item,index) in jobs"
                               v-if="employee.department.payroll_type ==='Fixed'">
                        <tr aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo" class="colapsible_tr">
                            <td class="uniqueclass tb_width_2 text_left_for_md" data-title="Unit #" data-bs-toggle="collapse"
                                :data-bs-target="'#panelsStayOpen-collapseTwo'+ index">
                                <svg class="table_icon_chevron">
                                    <use xlink:href="#right-chevron"/>
                                </svg>
                                {{ item.property.title }} {{ item.apartment_number }} {{ item.apartment_type && item.apartment_type.type }} -
                                {{ item.apartment_status }}
                            </td>
                            <td data-title="Service">{{ item.requested_job_service && item.requested_job_service[0] && item.requested_job_service[0].service.category }}</td>
                            <td data-title="Service Date" v-if="item.requested_job_service && item.requested_job_service[0]">{{ item.requested_job_service && item.requested_job_service[0] && item.requested_job_service[0].end_date | formatDate}}</td>
                            <td data-title="Service Date" v-else="!item.requested_job_service">--</td>
<!--                            <td v-if="item.is_paid_payroll ==0 && item.requested_job_service[0].is_fixed_price == 0"-->
<!--                                colspan="3"></td>-->
<!--                            <td v-if="item.is_paid_payroll ==1 || item.requested_job_service[0].is_fixed_price == 1"-->
<!--                                colspan="2"></td>-->
<!--                            <td v-if="item.is_paid_payroll ==1" colspan="1">${{ item.paid_payroll_price }}</td>-->
<!--                            <td v-if="item.is_paid_payroll ==0 && item.requested_job_service[0].is_fixed_price == 1"-->
<!--                                colspan="3">${{ item.requested_job_service && item.requested_job_service[0] && item.requested_job_service[0].payroll_price }} (Project Pay)-->
<!--                            </td>-->
                            <td data-title="Done">
<!--                                <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"-->
<!--                                   data-bs-toggle="modal" data-bs-target="#AddExtra" @click="AssignInfo(item)">-->
<!--                                    <span>Extras</span>-->
<!--                                </a>-->
<!--                                <span class="seprator">|</span>-->
                                <input type="checkbox" data-ng-model="example.check" @change="payroll_paid(item)"
                                       v-bind:true-value="1" v-bind:false-value="0"
                                       v-model.trim="item.is_paid_payroll"/>&nbsp;<span class="paid">Paid</span>
                            </td>
                        </tr>
                        <tr class="colapsible_inner_tr">
                            <td colspan="7" class="hiddenRow hiddenRow_less">
                                <div :id="'panelsStayOpen-collapseTwo'+ index" class="accordion-collapse collapse"
                                     aria-labelledby="panelsStayOpen-headingTwo">
                                    <table class="table tablex payrol-border">
                                        <thead class="thead_new allhide">
                                        <tr class="allhide">
                                            <!-- <th class="allhide">Unit #</th>
                                            <th class="allhide">Service</th>
                                            <th class="allhide">Service Date</th> -->
                                            <th class="width_24_percent tb_width_1s">Service Details</th>
                                            <th class="width_10_percent">QTY x Unit Price</th>
                                            <th class="width_10_percent">Total Billed</th>
                                            <th class="width_13_percent">Employee's pay
                                                <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"
                                                     data-bs-toggle="modal" data-bs-target="#AddExtra" @click="AssignInfo(item)">
                                                <span>Extras</span>
                                            </a></th>

<!--                                            <th class="allhide">Done</th>-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item1,index1) in item.requested_job_service[0].requested_job_sub_service"
                                            v-if="item1.is_invoice == 1">
                                            <!-- <td data-title="Unit #" class="tb_width_2"></td>
                                            <td data-title="Service" ></td>
                                            <td data-title="Service Date" ></td> -->
                                            <td data-title="Job Services" class="left-align">
                                                {{ item1.sub_service.name }}
                                            </td>
                                            <td data-title="Qty x Unit Price">{{ item1.quantity}} x {{ item1.base_price | toCurrency}}</td>
                                            <td data-title="Total">{{ item1.total_price | toCurrency}}</td>
                                            <td data-title="Employee's pay"
                                                v-if="item.requested_job_service[0].is_fixed_price == 0 && item.requested_job_service[0].assigned_to_type === 'individual'">
                                                {{ item1.payroll_price | toCurrency}} (fixed)
                                            </td>
                                            <td data-title="Employee's pay"
                                                v-if="item.requested_job_service[0].is_fixed_price == 1 && item.requested_job_service[0].assigned_to_type === 'individual'">
                                               --
                                            </td>
                                            <td data-title="Employee's pay"
                                                v-if="item.requested_job_service[0].is_fixed_price == 0 && item.requested_job_service[0].assigned_to_type === 'crew'">
                                                {{ item1.payroll_price | toCurrency}} (fixed)
                                            </td>
                                            <td data-title="Employee's pay"
                                                v-if="item.requested_job_service[0].is_fixed_price == 1 && item.requested_job_service[0].assigned_to_type === 'crew'">
                                                --
                                            </td>
                                            <td v-if="item.is_paid_payroll ==0 && item.requested_job_service[0].is_fixed_price == 1"></td>
                                            <td></td>
                                        </tr>
                                        <tr v-if="item.management_extra_and_payroll.length>0 && item.management_extra_and_payroll[0].amount !== null && item.management_extra_and_payroll[0].amount !== 0">
                                            <!-- <td data-title="Unit #" class="tb_width_2"></td>
                                            <td data-title="Service" ></td>
                                            <td data-title="Service Date" ></td> -->
                                            <td data-title="Extras" class="left-align mobile-hide">
                                                Management Extras
                                            </td>
                                            <td data-title="Extras" class="left-align desktop-hide">
                                                <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"
                                                     data-bs-toggle="modal" data-bs-target="#AddExtra" @click="AssignInfo(item)">
                                                <span>Add Extras</span>
                                            </a>
                                            </td>
                                            <td></td>
                                            <td class="mobile-hide-dots">--</td>
                                            <td>{{ item.management_extra_and_payroll[0].amount | toCurrency}}</td>
                                            <td>{{ item.employee_extras | toCurrency}} (Extra)</td>
                                        </tr>
                                        <tr>
                                            <!-- <td data-title="Unit #" class="tb_width_2"></td>
                                            <td data-title="Service"></td> -->
                                            <td data-title="Sub Services Total" class="left-align mobile-hide">
                                                <strong>Sub Services Total</strong>
                                            </td>
                                            <td data-title="Sub Services Total" class="left-align desktop-hide pt-0">

                                            </td>
                                            <td class="mobile-hide-dots">--</td>
                                            <td><strong>{{item.total | toCurrency}}</strong><span class="desktop-hide">(Sub Services Total)</span></td>
                                            <td><strong v-if="item.requested_job_service[0].is_fixed_price == 0">{{item.payroll  | toCurrency}}</strong><span v-if="item.requested_job_service[0].is_fixed_price == 0" class="desktop-hide">(Payroll Total)</span></td>
                                            <td v-if="item.requested_job_service[0].is_fixed_price == 1">
                                                <strong>  {{ item.requested_job_service && item.requested_job_service[0] && item.requested_job_service[0].payroll_price + item.employee_extras| toCurrency }} (Project Pay)</strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot class="footer-row">
                        <!-- ===================== -->
                        <!-- ======Blue TR=============== -->
                        <tr class="display_on_desktop blue_tr">

                            <td class="left-align">Total</td>
                            <!-- <td class="width_10_percent"></td>
                           <td class="width_10_percent"></td>
                           <td class="width_24_percent tb_width_1s"></td>  -->
                           <td class=""></td>
                            <td class="">{{ totalammount | toCurrency }}</td>
                            <td class="">{{ totalPyroll | toCurrency }}</td>


                        </tr>
                        <!-- ===================== -->
                        <tr class="display_on_mobile blue_tr">
                            <td class="tb_width_1s"  data-title="Total Sub Servics" colspan="5">{{ totalammount | toCurrency }}</td>
                            <td class="tb_width_1s" data-title="Total Payroll" colspan="5">
                                {{ totalPyroll | toCurrency }}
                            </td>
                        </tr>
                        <!-- ======Blue TR=============== -->
                        <!-- ===================== -->
                        </tfoot>

                    </table>
                    <div v-if="!loading && jobs.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <div class="modal fade custom_base_model custom_base_model_small" id="AddExtra" tabindex="-1"
                         aria-labelledby="serviceTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                        <AddExtra @closeEvent="extraRerender" :employee="employee" :job="job"></AddExtra>
                    </div>
                </div>
                <div class="no_more_tables table_to_be_used table_with_widths" v-if="employee.department.name =='Management' && employee.salary_type =='Salary'">

                                    <table class="table tablex payrol-border management-table">
                                        <thead class="thead_new allhide">
                                        <tr>

                                            <th class="width_24_percent tb_width_1s">Salary Term</th>
                                            <th class="width_10_percent" v-if="employee.salary_term == 'Yearly'">No of Years</th>
                                            <th class="width_10_percent" v-if="employee.salary_term == 'Monthly'"># of Months</th>
                                            <th class="width_10_percent" v-if="employee.salary_term == 'Weekly'">No of Weeks</th>
                                            <th class="width_13_percent">Weekly Salary</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>

                                            <td data-title="Salary Term" class="left-align">
                                                {{ employee.salary_term }}
                                            </td>
                                            <td data-title="No of Years" v-if="employee.salary_term == 'Yearly'">{{ employee.salary_period }}</td>
                                            <td data-title="# of Months" v-if="employee.salary_term == 'Monthly'">{{ employee.salary_period }}</td>
                                            <td data-title="No of Weeks" v-if="employee.salary_term == 'Weekly'">{{ employee.salary_period }}</td>
                                            <td data-title="Weekly Salary">${{ calculatedSalary }}</td>

                                        </tr>

                                        </tbody>
                                    </table>


                </div>
                <div class="no_more_tables table_to_be_used table_with_widths" v-if="!(employee.department.name =='Management' && employee.salary_type =='Salary') && !(employee.department.name !=='Management' && employee.salary_type !=='Salary')">

                                    <table class="table tablex payrol-border management-table">
                                        <thead class="thead_new allhide">
                                        <tr>

                                            <th class="width_24_percent tb_width_1s">Salary Term</th>
                                            <th class="width_10_percent" v-if="employee.salary_term == 'Yearly'">No of Years</th>
                                            <th class="width_10_percent" v-if="employee.salary_term == 'Monthly'"># of Months</th>
                                            <th class="width_10_percent" v-if="employee.salary_term == 'Weekly'">No of Weeks</th>
                                            <th class="width_13_percent">Weekly Salary</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>



                                            <td style="color: red" colspan="3" class="text-center" data-title="Weekly Salary">Payroll Type must be "Salary" for this Division  </td>

                                        </tr>

                                        </tbody>
                                    </table>


                </div>
                <!-- ====================== -->
                <!-- ====================== -->
                <!-- ================== -->
            </div>
            <!-- =============== -->
            <!-- =============== -->
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
        </div>

    </div>
</template>

<script>
import axios from "axios";
import Loader from "../LoaderSpinner";
// import Datepicker from 'vuejs-datepicker';
import EmptySearch from "../EmptySearch";
import AddExtra from "./addExtra";
import Multiselect from 'vue-multiselect';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Navigation from "../../components/Navigation";

export default {
    name: "payrollpage",
    components: {
        'Loader': Loader,
        'Datepicker': Datepicker,
        'EmptySearch': EmptySearch,
        'AddExtra': AddExtra,
        'Navigation': Navigation,
        Multiselect,
    },

    data() {
        return {
            successmsg: "",
            errormsg: "",
            loading: true,
            employee: "",
            employee_crews: "",
            payroll_prices: "",
            requestedJobService: "",
            id: this.$route.params.id,
            startDate: "",
            endDate: "",
            totalammount: 0,
            totalPyroll: 0,
            job: "",
            sub_categories: [],
            service_sub_category: "",
            dateRange: "",
            calculatedSalary: 0.00,
            employeeExtra:0.00,
        }
    },


    mounted() {
        // const yourDate = new Date();
        // // console.log(yourDate);
        // this.endDate = yourDate.toISOString().split('T')[0];
        // this.startDate = new Date(yourDate.setDate(yourDate.getDate() - yourDate.getDay())).toISOString().split('T')[0];
        this.loading = true;
        var d = new Date();
        this.getSubServices();
        // new Date(d.setTime(d.getTime() - (d.getDay() ? d.getDay() : 7) * 24 * 60 * 60 * 1000)).toISOString().split('T')[0]
        this.endDate = new Date(d.setTime(d.getTime() - (d.getDay()))).toISOString().split('T')[0];
        this.startDate = new Date(d.setTime(d.getTime() - 6 * 24 * 60 * 60 * 1000)).toISOString().split('T')[0];
        // this.getRecord(this.id);
        this.dateRange = [this.startDate, this.endDate];
    },
    watch: {

        'startDate'(val) {
            this.loading = true;
            this.getRecord(this.id);
        },
        'endDate'(val) {
            this.loading = true;
            this.getRecord(this.id);
        },
        'service_sub_category'(val) {
            this.loading = true;
            this.getRecord(this.id);
        },
        'dateRange'(val) {
            this.loading = true;
            this.getRecord(this.id);
        },
    },
    methods: {

        getRecord(id) {
            axios.get('/api/payroll/' + this.id + '?start_date=' + this.startDate + '&end_date=' + this.endDate + '&service_sub_category=' + this.service_sub_category.id + '&dateRange=' + this.dateRange)
                .then((response) => {

                    this.jobs = response.data.jobs;
                    this.employee = response.data.employee;
                    this.employee_crews = response.data.employee_crew;
                    this.payroll_prices = response.data.payroll_prices;
                    if (this.jobs.length > 0) {
                        if (this.employee.department.payroll_type === 'Percentage') {
                            this.calculatePayrollByPercentage();
                        } else {
                            this.calculatePayrollByFixedPrice();
                        }
                    }

                    // // console.log("this.employee",this.employee.department.payroll_type)
                    if (this.employee.department.name =='Management' && this.employee.department.payroll_type =='Salary') {
                    if (this.employee.salary_type == "salary") {
                        if (this.employee.salary_term == "Monthly") {
                            // months × 4.348125
                            let monthlyWeeks = this.employee.salary_period * 4
                            this.calculatedSalary = (this.employee.salary_value / monthlyWeeks).toFixed(2);
                        } else if (this.employee.salary_term == "Weekly") {
                            this.calculatedSalary = (this.employee.salary_value /this.employee.salary_period).toFixed(2);
                        } else if (this.employee.salary_term == "Yearly") {
                            let Yealyweeks = this.employee.salary_period * 52
                            this.calculatedSalary = (this.employee.salary_value / Yealyweeks).toFixed(2);
                        }
                    }
                    }

                    this.loading = false;

                });
        },
        clearCurrentFilter(type)
        {
            if(type=="service")
            {
                this.service_sub_category= "";
            }
        },
        calculatePayrollByFixedPrice() {

            // to calculate total amount
            this.totalammount = 0
            this.totalPyroll = 0
            this.employeeExtra = 0
            this.jobs.forEach(job => {
                job.total = 0
                job.payroll = 0


                    job.is_paid_payroll = 0;
                    // job.project = job.job_service_details.find(item => (item.service_id == job.requested_job_service[0].service_id))
                    if (job.requested_job_service[0].is_fixed_price == 0) {
                        job.requested_job_service[0].requested_job_sub_service.forEach(data => {
                            //    job total
                            if (data.is_invoice == 1) {
                                job.total += data.total_price
                                let payroll = this.payroll_prices.find(item => (item.sub_service_id == data.sub_service_id))
                                //  to calculate payroll total
                                if (payroll) {
                                    //  to calculate payroll total
                                    if (job.requested_job_service[0].assigned_to_type === 'individual') {
                                        data.payroll_price = payroll.payroll_price
                                    } else if (job.requested_job_service[0].assigned_to_type === 'crew') {
                                        let crew = this.employee_crews.find(crew => (crew.crew_id == job.requested_job_service[0].assigned_to_id));
                                        data.payroll_price = (payroll.payroll_price * (crew.percentage / 100)).toLocaleString()
                                    }
                                    job.payroll += Number(data.payroll_price);
                                }
                            }

                        })

                        // if (job.management_extra_and_payroll.length > 0) {
                        //     job.payroll += (50 / 100) * job.management_extra_and_payroll[0].amount
                        //     job.employee_extras = (50 / 100) * job.management_extra_and_payroll[0].amount
                        //     job.total += job.management_extra_and_payroll[0].amount
                        // }
                    } else {
                        // // console.log("into ele")
                        job.requested_job_service[0].requested_job_sub_service.forEach(data => {
                            if (data.is_invoice == 1) {
                                job.total += data.total_price
                            }
                        })
                        job.payroll += Number(job.requested_job_service[0].payroll_price);

                    }

                if (job.management_extra_and_payroll.length > 0) {
                    job.payroll += (50 / 100) * job.management_extra_and_payroll[0].amount
                    job.employee_extras = (50 / 100) * job.management_extra_and_payroll[0].amount
                    job.total += job.management_extra_and_payroll[0].amount
                }
                if (job.management_extra_and_payroll.length > 0 && job.management_extra_and_payroll[0].is_paid_payroll == 1) {
                    job.is_paid_payroll = job.management_extra_and_payroll[0].is_paid_payroll;
                    // job.paid_payroll_price = job.management_extra_and_payroll[0].paid_payroll_price;
                }
                    this.totalPyroll += job.payroll;
                    this.totalammount += job.total;


            })

        },
        calculatePayrollByFixedPriceold() {
            // to calculate total amount
            this.totalammount = 0
            this.totalPyroll = 0
            this.jobs.forEach(job => {
                job.total = 0
                job.payroll = 0

                if (job.management_extra_and_payroll.length > 0 && job.management_extra_and_payroll[0].is_paid_payroll == 1) {
                    job.is_paid_payroll = job.management_extra_and_payroll[0].is_paid_payroll;
                    job.paid_payroll_price = job.management_extra_and_payroll[0].paid_payroll_price;
                } else {
                    job.is_paid_payroll = 0;
                    // job.project = job.job_service_details.find(item => (item.service_id == job.requested_job_service[0].service_id))
                    if (job.requested_job_service[0].is_fixed_price == 0) {
                        job.requested_job_service[0].requested_job_sub_service.forEach(data => {
                            //    job total
                            if (data.is_invoice == 1) {
                                job.total += data.total_price
                                let payroll = this.payroll_prices.find(item => (item.sub_service_id == data.sub_service_id))
                                //  to calculate payroll total
                                if (payroll) {
                                    //  to calculate payroll total

                                    if (job.requested_job_service[0].assigned_to_type === 'individual') {
                                        data.payroll_price = payroll.payroll_price
                                    } else if (job.requested_job_service[0].assigned_to_type === 'crew') {

                                        let crew = this.employee_crews.find(crew => (crew.crew_id == job.requested_job_service[0].assigned_to_id));
                                        data.payroll_price = (payroll.payroll_price * (crew.percentage / 100)).toLocaleString()
                                    }


                                    job.payroll += Number(data.payroll_price);

                                }
                            }

                        })
                        if (job.management_extra_and_payroll.length > 0) {
                            job.payroll += job.management_extra_and_payroll[0].amount
                        }

                    } else {
                        job.requested_job_service[0].requested_job_sub_service.forEach(data => {
                            if (data.is_invoice == 1) {
                                job.total += data.total_price
                            }
                        })
                        job.payroll += Number(job.requested_job_service[0].payroll_price);
                        if (job.management_extra_and_payroll.length > 0) {
                            job.payroll += job.management_extra_and_payroll[0].amount
                        }
                    }
                    this.totalPyroll += job.payroll;
                    this.totalammount += job.total;
                }
            })

        },
        calculatePayrollByPercentageold() {
            // to calculate total amount
            this.totalammount = 0
            this.totalPyroll = 0
            this.jobs.forEach(job => {
                job.total = 0
                job.payroll = 0
                if (job.management_extra_and_payroll.length > 0 && job.management_extra_and_payroll[0].is_paid_payroll == 1) {
                    job.is_paid_payroll = job.management_extra_and_payroll[0].is_paid_payroll;
                    job.paid_payroll_price = job.management_extra_and_payroll[0].paid_payroll_price;
                } else {
                    job.is_paid_payroll = 0;

                    // let project = job.job_service_details.find(item => (item.service_id == job.requested_job_service[0].service_id))
                    // job.project = project;
                    if (job.requested_job_service && job.requested_job_service.length > 0) {
                        if (job.requested_job_service[0] && job.requested_job_service[0].is_fixed_price == 0) {
                            job.requested_job_service[0].requested_job_sub_service.forEach(data => {
                                if (data.is_invoice == 1) {
                                    //    job total
                                    job.total += data.total_price

                                    //  to calculate payroll total
                                    if (job.requested_job_service[0].assigned_to_type === 'individual') {
                                        job.payroll += (data.total_price * (this.employee.salary_value / 100))
                                    } else if (job.requested_job_service[0].assigned_to_type === 'crew') {
                                        let crew = this.employee_crews.find(crew => (crew.crew_id == job.requested_job_service[0].assigned_to_id));
                                        job.crew_employee = crew.percentage;
                                        job.payroll += (data.total_price * (crew.percentage / 100))
                                    }
                                }
                            })
                            if (job.management_extra_and_payroll.length > 0) {
                                job.payroll += job.management_extra_and_payroll[0].amount
                            }
                        } else {
                            job.requested_job_service[0].requested_job_sub_service.forEach(data => {
                                //    job total
                                if (data.is_invoice == 1) {
                                    job.total += data.total_price
                                }
                            })
                            job.payroll += job.requested_job_service[0].payroll_price;
                            if (job.management_extra_and_payroll.length > 0) {
                                job.payroll += job.management_extra_and_payroll[0].amount
                            }
                        }
                    }
                    this.totalPyroll += job.payroll;
                    this.totalammount += job.total;
                }
            })

        },
        calculatePayrollByPercentage() {
            // to calculate total amount
            this.totalammount = 0
            this.totalPyroll = 0
            this.jobs.forEach(job => {
                job.total = 0
                job.payroll = 0
                // if (job.management_extra_and_payroll.length > 0 && job.management_extra_and_payroll[0].is_paid_payroll == 1) {
                //     job.is_paid_payroll = job.management_extra_and_payroll[0].is_paid_payroll;
                //     job.paid_payroll_price = job.management_extra_and_payroll[0].paid_payroll_price;
                // } else {
                    job.is_paid_payroll = 0;

                    // let project = job.job_service_details.find(item => (item.service_id == job.requested_job_service[0].service_id))
                    // job.project = project;
                    if (job.requested_job_service && job.requested_job_service.length > 0) {
                        if (job.requested_job_service[0] && job.requested_job_service[0].is_fixed_price == 0) {
                            job.requested_job_service[0].requested_job_sub_service.forEach(data => {
                                if (data.is_invoice == 1) {
                                    //    job total
                                    job.total += data.total_price

                                    //  to calculate payroll total
                                    if (job.requested_job_service[0].assigned_to_type === 'individual') {
                                        job.payroll += (data.total_price * (this.employee.salary_value / 100))
                                    } else if (job.requested_job_service[0].assigned_to_type === 'crew') {
                                        let crew = this.employee_crews.find(crew => (crew.crew_id == job.requested_job_service[0].assigned_to_id));
                                        job.crew_employee = crew.percentage;
                                        job.payroll += (data.total_price * (crew.percentage / 100))
                                    }
                                }
                            })

                        } else {

                            job.requested_job_service[0].requested_job_sub_service.forEach(data => {
                                //    job total
                                if (data.is_invoice == 1) {
                                    job.total += data.total_price
                                }
                            })
                            job.payroll += job.requested_job_service[0].payroll_price;
                        }
                        if (job.management_extra_and_payroll.length > 0) {
                            job.payroll += (50 / 100) * job.management_extra_and_payroll[0].amount
                            job.employee_extras = (50 / 100) * job.management_extra_and_payroll[0].amount
                            job.total += job.management_extra_and_payroll[0].amount
                        }
                    }
                if (job.management_extra_and_payroll.length > 0 && job.management_extra_and_payroll[0].is_paid_payroll == 1) {
                    job.is_paid_payroll = job.management_extra_and_payroll[0].is_paid_payroll;
                    // job.paid_payroll_price = job.management_extra_and_payroll[0].paid_payroll_price;
                }
                    this.totalPyroll += job.payroll;
                    this.totalammount += job.total;

            })

        },
        payroll_paid(job) {
            // console.log(job.is_paid_payroll);
            this.loading = true;

            let data = {
                'job_id': job.id,
                'employee_id': this.employee.id,
                'is_paid_payroll': job.is_paid_payroll,
                'paid_payroll_price': job.payroll

            }
            axios.put('/api/paid_payroll', data)
                .then(response => {
                    this.forceRerender();
                })
                .catch(err => {
                    this.loading = false;
                    this.has_error = true;
                })

        },
        AssignInfo(job) {
            this.job = job;
        },
        forceRerender(msg) {
            this.successmsg = "";

            this.getRecord(this.id);
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },
        extraRerender(msg) {
            this.successmsg = "";
            this.job = "";
            this.getRecord(this.id);
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },
        getSubServices() {
            axios.get('/api/service-subcategory/' + this.id)
                .then((response) => {
                    this.sub_categories = response.data.sub_categories;
                });
        },
        selectSubCategory(sub_category) {
            this.service_sub_category = sub_category.id;
        }
    },


}
</script>

<style scoped>

</style>
