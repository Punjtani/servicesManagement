<template>
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title"> {{this.serviceName}} Notes</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="resetForm()"></button>
</div>
<form autocomplete="off" @submit.prevent="price_submit" method="post">
            <div class="modal_content_inner">

                    <div class="modal-body">
                        <div class="form_box" >
                            <div  class="no_more_tables table_to_be_used mg_top_1rem">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <!--<th>Appartment</th>
                                            <th class="text-center">Price Details</th>-->
                                            <th class="text_end_only_desktop">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <tr v-if="notes_list && notes_list.length" v-for="(item, index) in notes_list" :key="index" :value="item.id" >
                                        <td  data-title="Description" class="text-center">
                                            <input type="text" v-model.trim="item.description" class="form-control input_padding_right form_control_small" placeholder="Add details"
                                            :class="{'is-invalid':submit && item.description== '', 'is-valid':item.description !== ''}">
                                            <!-- <div class="invalid-feedback" v-if="submit">
                                                <span   v-if="item.description == ''">Description is required</span>
                                            </div> -->
                                        </td>
                                        <!--<td data-title="Apartment">
                                             <multiselect
                                                deselectLabel=""
                                                v-model.trim="item.apartment_type_id"
                                                :show-labels="false"
                                                track-by="type"
                                                label="type"
                                                placeholder="Select Apartment"
                                                :select-label="''"
                                                :options="apartments"
                                                :searchable="true"
                                                value = "id"
                                            ></multiselect>
                                        </td>
                                        <td  data-title="Price Details" class="text-center">
                                            <input type="text" v-model.trim="item.price" class="form-control text-center form_control_small"
                                            placeholder="Add Price details" :class="{'is-invalid':submit && item.price== '', 'is-valid':item.price !== ''}"
                                            >
                                            <div class="invalid-feedback" v-if="submit">
                                                <span v-if="item.price == ''">Price details are required</span>
                                            </div>
                                        </td>-->


                                        <td data-title="Action" class="text-end">
                                            <svg class="cross__button text_end_only_desktop text-md-end" style="margin-top:-4px" @click="openEvent(item, index)"><use xlink:href="#cancelcross" /></svg>
                                            <!--<button type="button" class="cross__button text_end_only_desktop text-md-end btn-close btncclose "  ></button>-->
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                                <div class="inline_buttonss">
                                    <div class="row mb-3">

                                        <div class="col-sm-12 text-end">
                                            <button type="button" style="height:auto;min-height:0;" class="btn btn_orange border_radius_5 font_14 text_uppercase font_weight_600" @click="addMember()">
                                                Add More Notes
                                            </button>

                                        </div>
                                    </div>
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
                <div class="inline_buttons">
                                <div class="row">
                                    <div class="col-6 col-sm-6">
                                        <button type="submit" @click="resetForm()"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Save
                                        </button>
                                    </div>
                                    <div class="col-6 col-sm-6">
                                        <button type="button" data-bs-dismiss="modal" @click="resetForm()"
                                                class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">
                                            Cancel
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
import { required, minLength,maxLength, between } from 'vuelidate/lib/validators';
import Multiselect from 'vue-multiselect';
export default {
    props : ['property','serviceId','notes','serviceName','confirmDelete'],
    components:{
        Multiselect
    },
    data() {
        return {
            // apartments : [],

            successmsg: "",
            errormsg: "",
            id :"",
            notes_list: [],
            price_form : {
                notes_list:[]
            },
            submit:false,
            deleteItem:"",
            deleteIndex:"",
        }
    },
    watch: {
        'property'(val) {
            // console.log(val);
            this.id = val;
            this.getRecord(val);
        },
        'notes'(val) {
            if(val){
                this.updateNotes();
            }
        },
        'confirmDelete'(val) {
            if(val == true){
                this.removeMember(this.deleteItem,this.deleteIndex);
            }
        },
    },
    mounted() {
    },
    methods: {
        getRecord(id) {
            // console.log('val', id);
            axios.get('/api/get-property-price-notes/'+ id)
                .then((response) => {
                    // console.log
                    if (response.data.clientPriceNotes)
                    {
                        this.notes_list = response.data.clientPriceNotes;
                    }

                    // this.apartments =  response.data.appartmentTypes;
                    if(this.notes_list && this.notes_list.length<=0){
                        this.addMember();
                    }
                    // this.notes_list.forEach((data, index)=>{
                    //     if(data.apartment_type_id != null){
                    //         let value =  this.apartments.find(apartment => (apartment.id == data.apartment_type_id));
                    //         if(value){
                    //         data.apartment_type_id = value;
                    //         }else{
                    //             this.notes_list.splice(index, 1);

                    //         }
                    //     }
                    // })


                });
        },
        updateNotes(){

            this.notes_list = this.notes;
            // console.log(this.notes_list);
            if(this.notes_list && this.notes_list.length<=0){
                this.addMember();
            }
        },
        price_submit() {
            $("#priceNotesModel").modal('hide');
            this.submit=true;
            // for (let data of this.notes_list){
            //     if(data.description === ""){
            //         return;
            //     }
                // if(data.apartment_type_id){
                //     data.apartment_type_id = data.apartment_type_id.id;
                // }

            // }
            this.price_form.notes_list = this.notes_list;
            this.price_form.serviceId = this.serviceId;

            // console.log(this.price_form, 'okish');
            axios.post('/api/get-property-price-notes/'+ this.id, this.price_form)
            .then(response => {

                window.scrollTo(0, middle);
                this.$emit('closeEvent',"Price Notes has been added successfully")
                this.resetForm();
            })
            .catch(err => {
                this.has_error = true;
            })
        },
        addMember(){
            // var member = {description:"",apartment_type_id:"", price:""}
            if (!this.notes_list) {
                this.notes_list = [];
            }
             var member = {description:""}
            this.notes_list.push(member);
        },
        removeMember(item, index){
            if(this.notes_list.length){
                this.notes_list.splice(index, 1);
            }else{
                this.errormsg = "At least one entry is required to submit the form";
                setTimeout(() => {
                    this.errormsg = "";
                }, 3000);
            }
            this.$emit('deletecloseEvent');
        },
        resetForm(){
            this.submit=false;
            this.errormsg = "";
            this.successmsg = "";
            // this.getRecord();
            this.$forceUpdate();
            this.$emit('closeEvent');

        },
        openEvent(item,index){
            this.deleteIndex = item;
            this.deleteIndex = index;
            this.$emit('openEvent','');
        },
    }
}
</script>


