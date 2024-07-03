<template>
    <div class="modal-dialog modal-dialog-centered ">
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title" v-if="invoice">  INVOICE #<span class="text_uppercase">{{invoice.id}}</span> TO <span class="text_uppercase">{{invoice.job.property.title}}</span></h4>
 <button type="button" class="btn-close"  data-dismiss="modal" @click="close()"  aria-label="Close"></button>

</div>
<form ref="sendForm" id="sendForm" autocomplete="off" @submit.prevent="sendInvoiceEmail" method="post">

            <div class="modal_content_inner"> 
                <div class="modal-body" >
                    
                    <div class="form_box">
                        <div class="row align-items-center">

                                    <div class="col-sm-12">
                                        <label class="text_color_blue">To:</label>
                                    </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                    <multiselect
                                    v-model.trim="$v.sendForm.email.$model"
                                    :show-labels="true"
                                    track-by="email"
                                    label="email"
                                    placeholder="To"
                                    :select-label="''"
                                    :options="allEmailList"
                                    :searchable="true"
                                    :multiple="true"
                                    @input="validateEmail"
                                    :taggable="true"
                                    @tag="addCustomEmail"
                                    tag-placeholder="Press enter to add custom email"
                                    >
                                    <template slot="option" slot-scope="props"><span>{{ props.option.first_name }} {{ props.option.last_name }} - {{props.option.email}} </span></template>
                                    </multiselect>

                                    <!--<div class="invalid-feedback">
                                        <span v-if="!$v.sendForm.email.required">Email Field is Required</span>
                                        <span v-if="!$v.sendForm.email.email">Email Field is not valid</span>
                                    </div>-->
                                    <div class="text-danger"><small v-if="emailError">{{emailError}}</small></div>
                                    <div class="text-danger"><small v-if="invalidEmail">Email is Required</small></div>
                                        <!--<h5 class="font_weight_600 font_family_Montserrat text_color_blue">To: <span class="color-black">{{invoice.email}}</span></h5>-->
                                    </div>
                                    <div class="form-group">
                                        <label class="text_color_blue">Subject:</label>
                                        <input type="text" v-model.trim="$v.sendForm.subject.$model"
                                               class="form-control "
                                               :class="{'is-invalid':$v.sendForm.subject.$error}"
                                               placeholder="Subject...">
                                        <div class="invalid-feedback">
                                            <span v-if="!$v.sendForm.subject.required">Subject Field is Required</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <!--<p v-if="invoice.job.property.manager"><b>Hello {{invoice.job.property.manager.first_name}} {{invoice.job.property.manager.middle_name}} {{invoice.job.property.manager.last_name}}</b></p>
                                        <p v-else><b>Hello {{invoice.job.property.client.user.first_name}} {{invoice.job.property.client.user.middle_name}} {{invoice.job.property.client.user.last_name}}</b></p>-->
                                        <textarea type="text" class="form-control" v-model.trim="$v.sendForm.body.$model" placeholder="Write details ..."
                                        :class="{'is-invalid':$v.sendForm.body.$error}">
                                        </textarea>
                                        <div class="invalid-feedback" v-if="!$v.sendForm.body.required">
                                            <span>Email Details is Required</span>
                                        </div>
                                        <!--<p>Thank you for your recent business with us. We have attached a detailed copy of Invoice #{{invoice.id}} to this email.</p>

                                        <p>The total amount due is ${{invoice.total}}.00, to be paid by {{invoice.due_date | formatDate}}.</p>

                                        <p>If you have any questions or concerns regarding this invoice, please don't hesitate to get in touch with us at info@reservicesystems.com.</p>

                                        <p>Sincerely,</p>

                                        <p>Real Estate Service Systems LLC</p>-->
                                    </div>
                                </div>
                              
                           
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
            <div class="inline_buttonss  text-end">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600" @click = "sendInvoiceEmail()" aria-label="Close">Send</button>
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
import Loader from "../LoaderSpinner";
import {required, email} from 'vuelidate/lib/validators';
import Multiselect from 'vue-multiselect';
export default {
    name: "Add",
    props: ['invoice','clientId'],
    components: {
        'Loader': Loader,
        'Multiselect': Multiselect,
    },
    mounted (){
        this.getStaffEmails();
    },
    data() {
        return {
           loading: false,
           name: "",
           sendForm: {
                body: "",
                subject:"",
                email:[],

           },
           client_id:"",
           clientEmail:[],
           staffEmails:[],
           allEmailList:[],
           invalidEmail:false,
            emailError:"",
        }
    },
    validations: {
        sendForm: {
            body: {
                required,
            },
            subject: {
                required,
            },
          email: {
            },

        }
    },
    watch: {
        'invoice'(){
            //select main email- get from property
            if (this.invoice.job.property){
                if (this.invoice.job.property && this.invoice.job.property.invoice_submission_email){
                    let emailContact =[];
                    emailContact = {
                        email:this.invoice.job.property.invoice_submission_email,
                        first_name: "",
                        last_name: "",
                    }
                    if (this.sendForm.email.filter(e => e.email === this.invoice.job.property.invoice_submission_email).length <= 0) {
                        this.sendForm.email.push(emailContact);
                    }
                }
            }
            //select email of property manager
            if (this.invoice.job.property.staff && this.invoice.job.property.staff.length > 0){
                let managerName ="";
                let managerEmail = {};
                this.invoice.job.property.staff.forEach(function( staff ) {
                    if (staff.staff_roles){
                        if(staff.staff_roles.name == 'Property Manager'){
                            if (staff.user){
                               let firstName = staff.user.first_name ? staff.user.first_name : "";
                               let lastName =   staff.user.last_name ? staff.user.last_name : "";
                                managerName =  firstName +' '+ lastName
                                managerEmail = {
                                    email:staff.user.email,
                                    first_name: firstName,
                                    last_name: lastName,
                                }
                            }

                        }else {
                            // console.log("property manager not found")
                        }
                    }
                });
                this.name = managerName;
                if (Object.keys(managerEmail).length > 0){
                    this.sendForm.email.push(managerEmail);
                }

                // select main email of client get from client
                if (this.invoice.job.property.client && this.invoice.job.property.client.contact_email){
                    let emailContact =[];
                    emailContact = {
                        email:this.invoice.job.property.client.contact_email,
                        first_name: "",
                        last_name: "",
                    }
                    if (this.sendForm.email.filter(e => e.email === this.invoice.job.property.client.contact_email).length <= 0) {
                        this.sendForm.email.push(emailContact);
                    }
                    // // console.log(' this.sendForm.email',  this.sendForm.email);
                }


            }
            // // console.log('here wo reach', this.invoice);
            // if(this.invoice.job.property.manager){
            //     this.name = this.invoice.job.property.manager.first_name +" " +(this.invoice.job.property.manager.middle_name ? this.invoice.job.property.manager.middle_name : "")+" "+this.invoice.job.property.manager.last_name
            // }else{
            //     this.name  = this.invoice.job.property.client.user.first_name+" "+(this.invoice.job.property.client.user.middle_name ? this.invoice.job.property.client.user.middle_name : "")+" "+this.invoice.job.property.client.user.last_name
            // }
            // let date =  this.invoice.due_date;
            // date = date.toLocaleDateString("en-US")
            this.sendForm.body = "Hello "+ this.name+",\n\nThank you for your recent business with us. We have attached a detailed copy of Invoice #" + this.invoice.id + " to this email.\nThe total amount due is $" + (Math.round(this.invoice.total * 100) / 100).toFixed(2) + ", to be paid by " + new Date(this.invoice.due_date).toLocaleDateString("en-US")  +".\n If you have any questions or concerns regarding this invoice, please don't hesitate to get in touch with us at info@reservicesystems.com.\n\nSincerely,\nReal Estate Service Systems LLC"

            this.sendForm.subject = " Invoice for " + this.invoice.job.property.title;
            // this.sendForm.email = this.invoice.email
            this.property_id = this.invoice.job.property.id;
            this.getStaffEmails();
        }
    },
    methods: {
        addCustomEmail(email){
           let check =  this.validateCustomEmail(email)
            if (check) {
                let customEmail = {
                    email:email,
                    first_name: check[1],
                    last_name: "",
                }
                if (!this.alreadyExist(email)){
                    this.sendForm.email.push(customEmail)
                    this.emailError= ""
                }else {
                    this.emailError = email + " is already in the selected list"
                }

            }else {
                this.emailError = "Invalid email address : " + email ;

            }
            // // console.log("this.sendForm.email",this.sendForm)


        },
        sendInvoiceEmail(){
            this.$v.sendForm.$touch();
            if(this.sendForm.email.length <= 0){
                this.invalidEmail = true;
                return;
            }
            if (this.$v.sendForm.$anyError) {
                // console.log(this.$v.sendForm.body.$anyError)
                return;
            }
            this.loading = true;
            axios.post('/api/send-invoice-email/' + this.invoice.id, this.sendForm).then((response) => {
                this.$emit('closeEvent', "Email has sent successfully");
                $("#sendInvoice").modal('hide');
            });
        },
        close(){
            $("#sendInvoice").modal('hide');
        },
        getStaffEmails(){
            axios.get('/api/staff-emails/' + this.clientId).then((response) => {
                this.staffEmails = response.data.staff;
                this.clientEmail = response.data.client;
                // this.sendForm.email = [...this.clientEmail];
                this.allEmailList = [...this.staffEmails, ...this.clientEmail];
            });
        },
        validateEmail(){
            this.emailError = ""
            if(this.sendForm.email.length <=0){
                this.invalidEmail = true;
            }else{
                this.invalidEmail = false;
            }
        },
        validateCustomEmail(email){

                return String(email)
                    .toLowerCase()
                    .match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
        },
         alreadyExist(email) {
    return this.sendForm.email.some(function(oldObj) {
        return oldObj.email === email;
    });
}


    }


}
</script>

<style scoped>

</style>
