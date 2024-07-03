<template>
    <div class="modal-dialog modal-dialog-centered ">
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="modal-content" v-if="jobData">
            <div class="modal-header">
 <h4 class="modal-title"> Email quote #<span class="text_uppercase">{{jobData.id}}</span> TO <span class="text_uppercase"> {{ propertyName}}</span></h4>
 <button type="button" class="btn-close"  data-dismiss="modal" @click="close()"  aria-label="Close"></button>
</div>
<form ref="sendForm" id="sendForm" autocomplete="off" @submit.prevent="sendQuoteEmail" method="post">

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
                                        <textarea type="text" class="form-control" v-html="$v.sendForm.body.$model" placeholder="Write details ..."
                                        :class="{'is-invalid':$v.sendForm.body.$error}">
                                        </textarea>
                                        <div class="invalid-feedback" v-if="!$v.sendForm.body.required">
                                            <span>Email Details is Required</span>
                                        </div>
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
                                            <button type="button" class="btn btn_blue border_radius_5 font_14 text_uppercase font_weight_600" @click = "sendQuoteEmail()" aria-label="Close">Send</button>
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
    props: ['jobData','totalamount'],
    components: {
        'Loader': Loader,
        'Multiselect': Multiselect,
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
            propertyName:"",
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
        'jobData'(){

            if (this.jobData.property){
                let managerName ="";
                let managerEmail =[];
                            if (this.jobData.property.client.user){
                               let firstName = this.jobData.property.client.user.first_name ? this.jobData.property.client.user.first_name : "";
                               let lastName =   this.jobData.property.client.user.last_name ? this.jobData.property.client.user.last_name : "";
                                managerName =  firstName +' '+ lastName
                                managerEmail = {
                                    email:this.jobData.property.client.user.email,
                                    first_name:firstName,
                                    last_name:lastName,
                                }
                            }
                this.name = managerName;
                this.sendForm.email.push(managerEmail);
            }
            this.sendForm.body = "Hello "+ this.name +",\n\nThank you for asking us to quote on your project, Please find a detailed copy of our quote attached to this email.\n\nThe quote total is $" + this.totalamount + ".00 as of " + new Date().toLocaleDateString("en-US")  +".\n\n If you have any questions or concerns regarding this quote, please don't hesitate to get in touch with us at info@reservicesystems.com.\n\nSincerely,\nReal Estate Service Systems LLC"
            this.sendForm.subject = " Quote from Real Estate Service Systems LLC - " +  new Date().toLocaleDateString("en-US");
            this.propertyName = this.jobData.property.title + ' (' + this.jobData.property.client.user.first_name + ')'
            // this.sendForm.email = this.invoice.email
        },
        'totalamount'(val){
            this.totalamount = val
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
        sendQuoteEmail(){
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
            this.sendForm.body = this.sendForm.body.replace('\n','<br>')
            axios.post('/api/send-quote-email/' + this.jobData.id, this.sendForm).then((response) => {
                this.loading = false;
                this.$emit('closeEvent', "Email has sent successfully");
                $("#sendQuoteEmailModal").modal('hide');
            });
        },
        close(){
            $("#sendQuoteEmailModal").modal('hide');
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
