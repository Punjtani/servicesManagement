<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="dashboard_content_area" v-if="!loading">
            <div class="row align-items-center mb-4" >
                <div class="col-sm-11 col-9"><h1 class="h4 mb-0">Job Details</h1></div>
                <div class="col-md-1 d-flex justify-content-end">
                    <p @click="$router.go(-1)" class="h3 mb-2 cursor-pointer" ><b-icon icon="arrow-left-circle-fill"></b-icon></p>
                </div>

            </div>
            <div class="details_shorts">
                <div class="row">

                    <div class="col">
                        <div class="details_shorts_inner">
                            <h6 class="font_14 text_color_white font_weight_700 font_family_Montserrat">Community<small class="text_color_white font_weight_500 font_family_Montserrat">{{property.title}}</small></h6>
                        </div>
                    </div>
                    <div class="col">
                        <div class="details_shorts_inner">
                            <h6 class="font_14 text_color_white font_weight_700 font_family_Montserrat">Apartment Size<small class="text_color_white font_weight_500 font_family_Montserrat">{{apartment_type?.type}}</small></h6>
                        </div>
                    </div>
                    <div class="col">
                        <div class="details_shorts_inner">
                            <h6 class="font_14 text_color_white font_weight_700 font_family_Montserrat">Unit #<small class="text_color_white font_weight_500 font_family_Montserrat">{{job.apartment_number}}</small></h6>
                        </div>
                    </div>
                    <div class="col">
                        <div class="details_shorts_inner">
                            <h6 class="font_14 text_color_white font_weight_700 font_family_Montserrat">Apartment Status <small class="text_color_white font_weight_500 font_family_Montserrat">{{job.apartment_status}}</small></h6>
                        </div>
                    </div>
                     <div class="col" v-if="is_PO">
                        <div class="details_shorts_inner">
                            <h6 class="font_14 text_color_white font_weight_700 font_family_Montserrat">PO # <small class="text_color_white font_weight_500 font_family_Montserrat">{{job.po_number}}</small></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="details_shorts_inner">
                        <h6 class="font_20 text_color_orange font_weight_600 font_family_Montserrat mg_bot_20_on_desktop">Job Cancellation Notes</h6>
                        <textarea disabled class="form-control" placeholder="Job Notes" v-model="job.cancel_notes"></textarea>
                    </div>
                </div>
            </div>
            <div class="row mg_bot_30" v-if="job.service_ready_check_list.length > 0">
                <div class="col-sm-12">
                    <div class="heading_paragrph_box mg_bot_20">
                        <h6 class="font_20 text_color_orange font_weight_600 font_family_Montserrat mg_bot_20_on_desktop">Service Ready Checklist</h6>
                        <div v-for="(item, index) in job.service_ready_check_list" :key="index">
                            <p class="mg_0 font_weight_500">{{item.name}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="job.requested_job_service.length > 0" class="no_more_tables table_to_be_used table_with_widths more_tables_with_less_pd">
                <h6 class="font_20 text_color_orange font_weight_600 font_family_Montserrat mg_bot_20_on_desktop">Services</h6>
                <table class="table">
                    <thead>
                            <tr>
                                <th>Service</th>
                                <th >Requested For</th>
                                <th class="text-md-center_no">Requested For</th>
                                <th class="text-md-center_no" >Scheduled Date</th>
                                <th class="text-md-center_no">Scheduled Time</th>
                                <th class="text-md-center_no">Assigned To</th>

                            </tr>
                    </thead>
                    <tbody v-if="item.service" v-for="(item, index) in job.requested_job_service" :key="item.service_id" class="tbody_new">
                        <tr class="colapsible_tr colapsible_trtd" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                            <td class="uniqueclass" data-title="Service(s)" data-bs-toggle="collapse" :data-bs-target="'#panelsStayOpen-collapseThree' + index"><svg class="table_icon_chevron"><use xlink:href="#right-chevron" /></svg><strong>{{item.service.category}}</strong></td>
                            <td  data-title = "Requested Date"><strong>{{item.requested_date | formatDate}}</strong></td>
                            <td class="text-md-center_no" data-title = "Requested For">
                                <strong v-if="item.requested_for">{{item.requested_for | formatDate}}</strong>
                                <strong v-else>--</strong>
                            </td>
                            <td class="text-md-center_no" data-title = "Scheduled Date">
                                <strong v-if="item.scheduled_date">{{item.scheduled_date | formatDate}}</strong>
                                <strong v-else>--</strong>
                            </td>
                            <td class="text-md-center_no" data-title = "Scheduled Time" v-if="item.anytime == null">--</td>
                            <td class="text-md-center_no" data-title = "Scheduled Time" v-else-if="item.anytime == 1 && item.scheduled_time">
                                <strong>{{item.anytime == 1 ? "Any Time":item.scheduled_time | formatTime}}</strong>

                            </td>
                            <td class="text-md-center_no" data-title = "Scheduled Time" v-else>
                                <strong>--</strong>

                            </td>
                            <td class="text-md-center_no" data-title = "Assigned To">
                                <strong v-if="item.assigned_name">{{item.assigned_name}}</strong>
                                <strong v-else>--</strong>
                            </td>
                        </tr>

                        <tr class="colapsible_tr colapsible_inner_tr" >
                            <td class="hiddenRow" colspan="6">

                                    <table  :id="'panelsStayOpen-collapseThree'+index" class="table table_hidden_block accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                        <tbody>
                                            <tr class="tr_full_in_all">
                                                <td class="td_full_in_all width_one_box">
                                                    <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_16">{{item.service.category}} Services</h6>
                                                </td>
                                                <td class="width_four_box text_md_right">
                                                    <a v-if="(item.end_date)" href="#" class="table_btn_link font_12 font_weight_700 text_uppercase text_md_right" data-bs-toggle="modal" data-bs-target="#ViewNotes"
                                                        @click="viewNotesMethod(item.requested_service_id)"><span >Employee View</span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr class="tr_full_in_all">
                                                <td :colspan=" 6" class="hiddenRow">
                                                    <div class="hidden_tr_div"   v-for="(item1, index1) in item.requested_job_sub_service" v-bind:style=" item1.is_invoice == 0 ? 'background-color: antiquewhite' : 'background-color: #f1f5f8' " v-bind:key="item1.id" >
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <td  data-title="Sub Service(s)">{{item1.service_sub_category? item1.service_sub_category.name : item.service.category}} <b v-if="item1.is_invoice == 0">(Not Billed)</b></td>
                                                                    <td data-title="Base Price" class="width_fifteen text-center">{{item1.base_price | toCurrency}}</td>

                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="tr_full_in_all">
                                                <td class="td_full_in_all"  :colspan="6">
                                                    <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_16">Job Notes</h6>
                                                </td>
                                            </tr>
                                                <tr class="tr_full_in_all">
                                                <td class="td_full_in_all" :colspan="6">
                                                    <textarea v-model = "item.description"  class="taxarea_job form-control" ></textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                            </td>
                        </tr>
                        <!-- ===================== -->



                        <!-- ===================== -->
                        <!-- ================= -->
                        <!-- ===================== -->
                    </tbody>
                        <tfoot>
                        <!-- ===================== -->
                        <!-- ======Blue TR=============== -->
                        <tr class="display_on_desktop blue_tr">
                            <td colspan="7">
                                <table class="table mg_0">
                                    <tbody>
                                        <tr>
                                            <td class="width_ninty text_md_right" >Total:</td>
                                            <td class="width_fifteen" data-title="Total" >{{this.totalamount | toCurrency}}</td>
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
    </div>
</template>
<script>
import Loader from "../LoaderSpinner";
    export default {
        components: {
            'Loader': Loader,
        },
        data() {
            return {
                id: this.$route.params.id,
                job: '',
                apartment_type: '',
                property:'',
                is_PO: false,
                loading: true,
                totalamount: 0,
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
        mounted() {
            // console.log('Dashboard Component mounted.')
            this.jobCreate();
        },
        methods: {
            getRecord(id) {
            axios.get('/api/job/' + id + "/edit")
                .then((response) => {
                    this.job = response.data.job;
                    this.apartment_type = (this.job.apartment_type_id) ? this.appartmentTypes.find(apartment => (apartment.id == this.job.apartment_type_id)) : null;
                    this.property = this.properties.find(property => (property.id == this.job.property_id));
                    this.is_PO = this.property.client.is_PO_required ? true : false;

                    // total price
                    this.job.requested_job_service.forEach(item =>{
                        if(item.service){
                            item.requested_job_sub_service.forEach(data =>{
                                this.totalamount = this.totalamount + data.base_price;
                            })
                        }
                    })
                    this.loading = false;
                });
            },
            jobCreate() {
                axios.get('/api/job/create')
                    .then((response) => {
                        this.serviceReadyChecklist = response.data.serviceReadyChecklist;
                        this.clients = response.data.clients;
                        this.appartmentTypes = response.data.appartmentTypes;
                        this.properties = response.data.properties;
                        this.priceIncrement = response.data.priceIncrement
                        if(this.id){
                            this.getRecord(this.id);
                        }
                    });
            },
        }
    }
</script>
