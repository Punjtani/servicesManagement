<template>
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header" v-if="id">
                <h4 class="modal-title">View / Copy Quote # {{id}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" @click="formReset()" aria-label="Close"></button>
            </div>
            <div class="modal_content_inner">

                <div class="modal-body">
                    <div class="table_area">
                        <div class="row row_spacing_5">
                            
<!--                            <div class="col-sm-6 col-12 d-flex" v-if="id">-->
<!--                                <b-button v-if="job.quote_status == 'approved'" variant="success custom_status_btn">Approved</b-button>-->
<!--                                <b-button v-else-if="job.quote_status == 'awaiting_response'" variant="danger custom_status_btn">Awaiting Response</b-button>-->
<!--                                <b-button  v-else-if="job.quote_status == 'draft'" variant="info custom_status_btn">Draft</b-button>-->
<!--                                <b-button v-else-if="job.quote_status == 'changes_requested'" variant="warning custom_status_btn">Changes Requested</b-button>-->
<!--                                <b-button v-else-if="job.quote_status == 'converted'" variant="success custom_status_btn">Converted</b-button>-->
<!--                                <b-button v-else-if="job.quote_status == 'archived'" variant="warning custom_status_btn">Archived</b-button>-->

<!--                            </div>-->

                        </div>
                            <div class="table_area">
                                <div class="table_area_head pd_top_24">
                                    <div class="row row_spacing_5">
                                        <div class="col-sm-3">
                                            <label>Community</label>
                                            <div class="form-group">
                                                <span>{{quote_form.property_id && quote_form.property_id.title ? quote_form.property_id.title  : "--" }}</span>
                                                <div class="invalid-feedback">
                                                    <span v-if="!$v.quote_form.property_id.required">Field is Required</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Unit Size</label>
                                            <div class="form-group">
                                                <span>{{quote_form.apartment_type_id && quote_form.apartment_type_id.type  ? quote_form.apartment_type_id.type : "--"}}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Unit #</label>
                                            <div class="form-group">
                                               <span>{{quote_form.apartment_number ? quote_form.apartment_number : "--"}}</span>

                                            </div>
                                        </div>
                                        <div class="col-sm-3" v-if="is_PO">
                                            <label>PO #</label>
                                            <div class="form-group">
                                               <span>{{quote_form.po_number}}</span>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row align-items-center row_spacing_5 mg_bot_30" v-if="role==roleData.client">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Service Ready Checklist</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div >
                                            <div class="custom_checkbox" v-for="(item, index) in serviceReadyChecklist" :key="index" :value="item.id">
                                                <label>
                                                    <input disabled type="checkbox" :value="item.id" v-model.trim="$v.quote_form.job_service_ready_checklist.$model" />
                                                    <span class="box">{{ item.name }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="quote_form.requested_job_services.length > 0" class="no_more_tables table_to_be_used table_with_widths more_tables_with_less_pd colapsible_tr_padding">
                                    <table class="table">
                                        <tbody v-for="(item, index) in jobServices" :key="item.service_type_id" class="tbody_new">
                                        <tr class="colapsible_tr colapsible_trtd" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                            <td class="uniqueclass" data-title="Service(s)" data-bs-toggle="collapse" :data-bs-target="'#panelsStayOpen-collapseThree' + index"><svg class="table_icon_chevron"><use xlink:href="#right-chevron" /></svg><strong>{{item.service.category}}</strong></td>

                                        </tr>
                                        <tr class="colapsible_tr colapsible_inner_tr">
                                            <td class="hiddenRow" colspan="9">
                                                <table  :id="'panelsStayOpen-collapseThree'+index" class="add_assign_job_tbl table table_hidden_block accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                                    <tbody>

                                                    <tr class="tr_full_in_all">
                                                        <td :colspan=" 6" class="hiddenRow">
                                                            <div class="hidden_tr_div"   v-for="(item1, index1) in jobServicesDetails[item.service.category]" v-bind:style=" item1.is_invoice == 0 ? 'background-color: antiquewhite' : 'background-color: #f1f5f8' " v-bind:key="item1.id" >
                                                                <table class="table edit-job-div">
                                                                    <tbody>

                                                                    <tr>
                                                                        <td data-title="Sub Service" class="width_100">
                                                                            <h2>Service</h2>
                                                                            <div class="item-heading">
                                                                            {{item1.service_sub_category? item1.service_sub_category.name : item1.service.category}}
                                                                        </div>
                                                                         </td>
                                                                        <td  data-title="Description" class="width_200">
                                                                            <h2>Description</h2>
                                                                            <textarea v-if="item1.description" class="taxarea_job form-control" placeholder="Description">{{item1.description}}</textarea>
                                                                        <textarea v-else class="taxarea_job form-control">  ---</textarea>
                                                                        </td>

                                                                        <td data-title="Quantity" class="text_right_all width_40">
                                                                            <h2>QTY</h2>
                                                                           <div class="form-control number-div"> {{item1.quantity}}</div>
                                                                        </td>
                                                                        <td data-title="Unit Price" class="text_right_all width_40">
                                                                            <h2>Unit Price</h2>
                                                                            <div class="dollar-div">
                                                                <span class="fieldAffix-item">$</span>
                                                                            <div class="form-control number-div">  {{item1.price}}</div></div>
                                                                        </td>
                                                                        <td data-title="Total" class="text_right_all width_40">
                                                                            <h2>Total</h2>
                                                                            <div class="dollar-div">
                                                                <span class="fieldAffix-item">$</span>
                                                                            <div class="form-control number-div">
                                                                            {{item1.total_price.toFixed(2)}}</div>
                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr v-if="item.description" class="tr_full_in_all">
                                                        <td class="td_full_in_all"  :colspan="7">
                                                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_16">Notes</h6>
                                                        </td>
                                                    </tr>
                                                    <tr class="tr_full_in_all">
                                                        <td v-if="item.description" class="td_full_in_all" :colspan="7">
                                                            {{item.description}}
                                                        </td>
                                                    </tr>
                                                    <tr v-if="item.Existingfiles.length > 0" class="tr_full_in_all">
                                                        <td class="td_full_in_all"  :colspan="7">
                                                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_16">Attachments</h6>
                                                        </td>
                                                    </tr>
                                                    <tr class="tr_full_in_all">
                                                        <td class="td_full_in_all" :colspan="7">
                                                            <div class="row align-items-center no-gutters attachments_box">
                                                                <div class="col-6 col-md-3 cols_5" v-for="(item1, index1) in item.Existingfiles">
                                                                    <div class="img__box">
                                                                        <img :src="'/'+item1">
                                                                    </div>
                                                                </div>
                                                            </div>
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
                            <div id="success-alert1" class="alert alert-success" style="margin-top: 10px;" v-if="successmsg!==''">
                                <strong>{{ successmsg }}</strong>
                            </div>
                            <div id="errir-alert" class="alert alert-danger" v-if="errormsg!=='' && successmsg == ''">
                                <strong>{{ errormsg }}</strong>
                            </div>
                           

                    </div>
                </div>

            </div>
            <div class="modal-footer">
            <div class="inline_buttonss">
                                <div class="row" v-if="id">
                                    <div class="col-sm-12 text-end">
                                        <button type="button" @click="copyQuote()" class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Copy
                                        </button>
                                        <button type="button" @click="formReset()" class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Cancel
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {required, minLength, maxLength, email, requiredIf, url, decimal} from 'vuelidate/lib/validators'
import Loader from "../LoaderSpinner";

import Multiselect from 'vue-multiselect';
import roleData from '../../data.js';

export default {
    props: ['quoteId'],
    components: {
        'Loader': Loader,
        Multiselect,

    },
    data() {
        return {
            acceptImageTypes: ['image/png','image/jpeg'],
            roleData:roleData,
            todayDate: "",
            year: "",
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
            id:null,
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
                type:"quote"
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
                required,
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
                required,
            },
        }
    },


    watch: {
        'quoteId'(val) {
           this.id = val
            if(this.id)
            {
                this.getRecord()
            }
        },
        '$route.params.id'(val) {
            if(!val){
                this.formReset();
            }
        },
        'quote_form.property_id'(val) {
            if (this.quote_form.apartment_type_id) {
                if(this.quote_form.apartment_type_id && this.quote_form.apartment_type_id.id){
                    this.getPropertyServices();
                }
            }
        },
        'quote_form.apartment_type_id'(val) {
            if (this.quote_form.property_id) {
                if(this.quote_form.property_id && this.quote_form.property_id.id){
                    this.getPropertyServices();
                }
            }
        },
    },
    computed: {
        resultQuery(){
            if(this.searchQuery){
                return  this.options.filter((item)=>{
                    if(item.service_sub_category){
                        return item.service_sub_category.name.toLowerCase().startsWith(this.searchQuery.toLowerCase());
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
    },
    methods: {

        showSection(){
            this.searchIcon = true;
        },
        sortByName: function (list) {
            return _.orderBy(list, 'service_type_id', 'asc');
        },

        copyQuote() {
                axios.post('/api/copyquote/' + this.id)
                    .then(response => {
                        this.loading=false;
                        if (response.data.error) {
                            this.errormsg = response.data.error;
                            setTimeout(() => {
                                this.errormsg = "";
                            }, 3000);
                        } else {
                            this.successmsg = "Quote has been Copied, Redirecting to new quote ...";
                            this.$emit('updateCount');
                            this.errormsg="";
                            if(this.closeFlag){
                                this.$router.push('/all-quotes');
                            }else{
                                setTimeout(() => {
                                    $("#ViewOrCopyQuoteModal").modal('hide');
                                    this.successmsg = "";
                                    this.$router.push('/edit-quote/'+response.data.job_id);

                                }, 3000);
                            }

                        }
                    })
                    .catch(err => {
                        this.has_error = true;
                    })

        },
        getRecord() {
            this.loading=true;
            axios.get('/api/quote/' + this.id + "/edit")
                .then((response) => {
                    if (response.data.status == 'not_found'){
                        this.errormsg = "Quote Not Found";
                        this.loading=false;

                        setTimeout(() => {
                            this.errormsg = "";
                            this.$router.push('/all-quotes')
                        }, 3000);
                        return;
                    }
                    this.job = response.data.job;
                    this.quote_form.apartment_type_id = this.appartmentTypes.find(apartment => (this.job && apartment.id == this.job.apartment_type_id));
                    this.quote_form.property_id = this.properties.find(property => (this.job && property.id == this.job.property_id));
                    this.is_PO = this.quote_form.property_id && this.quote_form.property_id.client.is_PO_required ? true : false;
                    this.quote_form.apartment_number = this.job.apartment_number;
                    this.quote_form.apartment_status = this.job.apartment_status;
                    this.quote_form.po_number = this.job.po_number;

                    this.job.service_ready_check_list.forEach((data) => {
                        this.quote_form.job_service_ready_checklist.push(data.id);
                    });

                    this.loading = false;
                });
        },

        jobCreate() {
            axios.get('/api/job/create')
                .then((response) => {
                    this.serviceReadyChecklist = response.data.serviceReadyChecklist;
                    this.clients = response.data.clients;
                    this.appartmentTypes = response.data.appartmentTypes;
                    this.properties = this.sortFunc(response.data.properties);
                    this.priceIncrement = response.data.priceIncrement;
                    if(this.id){
                        this.getRecord();
                    }
                    this.getIncrementPercentage();

                });
        },
        sortFunc: function (array){
            return array.slice().sort(function(a, b){
                return (a && b && a.title.toLowerCase() > b.title.toLowerCase()) ? 1 : -1;
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
        getPropertyServices() {
            let appendURL = "";
            try {
                appendURL = "/" + this.quote_form.apartment_type_id.id;
            } catch(e){}
            axios.get('/api/property-services/' + this.quote_form.property_id.id + appendURL)
                .then((response) => {
                    this.services = response.data.services;
                    this.parentServices = response.data.parentServices;

                    if(this.services.length==0){
                        this.errormsg = "Pricing Details for this property is required to continue";
                    }else{
                        if(this.id){
                            this.requestedJobServices();
                        }
                        this.errormsg = "";
                    }

                    this.services.forEach((value, index) => {
                        value.requested_date = this.todayDate
                    });
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
                        service.payroll_price = data.payroll_price;
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
                                    subService.price=item.base_price.toFixed(2);
                                    subService.requested_sub_service_id = item.id;
                                    subService.is_invoice = item.is_invoice;
                                    subService.quantity = item.quantity;
                                    subService.description = item.description ? item.description : "";
                                    (this.jobServicesDetails[serviceValue.service.category]).push({...subService})
                                    this.quote_form.requested_job_services.push(subService);
                                }
                            })
                        });

                    }
                })

            });
            this.reCalculate()
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

        formReset(){
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
            this.job="";
            this.id = undefined;
            this.submit= false

            $("#ViewOrCopyQuoteModal").modal('hide');
            this.$emit('closeEvent',"Modal has been closed")

        },
        selectProperty(property){
            this.is_PO = property.client.is_PO_required ? true : false;
            this.appartmentTypes = property.appartment_types;
            let appartment = this.appartmentTypes.find(appartment => (this.quote_form.apartment_type_id && appartment.id == this.quote_form.apartment_type_id.id))
            if(!appartment){
                this.quote_form.apartment_type_id = "";
            }

        },
        searchOptions(){
            // jquery for select option deropdown
            var items = document.getElementById('items');
            items.classList.add('visible');
            items.style.display = "block";
        },
        closeOptions(){
            items.classList.remove('visible');
            items.style.display = "none";
            if(this.searchQuery){
                this.searchQuery='';
            }
            this.searchIcon = false;

        },
        forceRerender() {
            this.assignParentCategoryId = '';
            this.selectedService="";
            this.quote_submit()
        },
        setJobData() {
            // this.loading=true;
            this.emailJobData = this.job

        },
        saveAndClose(){
            this.closeFlag = true;
        },

    },

}

</script>

<style scoped>
input[type='time']::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    width: 95%;
}
</style>
