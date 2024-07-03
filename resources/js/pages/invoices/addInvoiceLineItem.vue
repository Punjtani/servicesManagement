// <template>
//     <div class="modal-dialog">
//         <div class="modal-content">
//             <div class="modal_content_inner">
//                 <button type="button" class="btn-close" data-bs-dismiss="modal" @click="resetForm" aria-label="Close"></button>
//                 <div v-if="loading">
//                     <Loader></Loader>
//                 </div>
//                 <form v-if="!loading" autocomplete="off" @submit.prevent="saveSelected" method="post">
//                     <div class="modal-body">
//                         <h4 class="">Line Item</h4>
//                         <div class="form_box"> 
//                             <div class="form-group">
                            
//                                 <!-- ====================== -->
//                             <div id="list1"  class="dropdown-check-list dropdown_check_list" tabindex="100">
//                                 <span id="anchors" class="anchor" @click="showSection">
//                                     <span v-show="!searchIcon" class="anchor_icons anchor_icons_arrow">
//                                         <svg class="anchoricon"><use xlink:href="#arrow_down" /></svg>
//                                     </span>
//                                     <!--<span v-show="searchIcon" class="anchor_icons anchor_icons_cross" @click="closeOptions()">
//                                         <svg class="anchoricon"><use xlink:href="#cancelcross" /></svg>
//                                     </span>-->
//                                     <input class="newInputClass form-control"  @click="searchOptions()" placeholder="Select Services" v-model="searchQuery" type="text" />
//                                 </span>

//                                 <div id="items" class="items">
//                                     <ul id="itemss" v-show="options.length > 0" class="itemss" v-for="(item, index) in resultQuery" v-bind:key="item.id">
//                                         <li v-if="!item.service_sub_category">
//                                             <strong>{{ item.service.category }}</strong>
//                                         </li>
//                                         <li>
//                                             <ul id="itemss" class="itemss">
//                                                 <li v-if="item.service_sub_category">
//                                                     <div class="custom_checkbox">
//                                                         <label>
//                                                             <input
//                                                                 type="checkbox"
//                                                                 data-ng-model="example.check"
//                                                                 :value="item"
//                                                                 v-model.trim="requested_job_services"
//                                                                 @change = "closeOptions()"
                                                        
//                                                             />
//                                                             <span class="box">{{ item.service_sub_category.name }}</span>
//                                                         </label>
//                                                     </div>
//                                                 </li>
//                                             </ul>
//                                         </li>
//                                     </ul>
//                                 </div>
//                             </div>
//                             <!-- ====================== -->

//                                 <div class="invalid-feedback" v-if="submit">
//                                     <span >Division is Required</span>
//                                 </div>
//                             </div>
                           
//                             <div id="success-alert1" class="alert alert-success" style="margin-top: 10px;" v-if="successmsg!==''">
//                                 <strong>{{ successmsg }}</strong>
//                             </div>
//                             <div id="errir-alert" class="alert alert-danger" v-if="errormsg!=='' && successmsg == ''">
//                                 <strong>{{ errormsg }}</strong>
//                             </div>

//                             <div class="inline_buttons">
//                                 <div class="row ">
//                                     <div class="col-6 col-sm-6 ">
//                                         <button type="submit"class="btn btn_blue mg_top_14 border_radius_5 font_14 text_uppercase font_weight_600">Save</button>
//                                     </div>
//                                     <div class="col-6 col-sm-6 ">
//                                         <button type="button" data-bs-dismiss="modal" @click="resetForm" class="btn btn_blue mg_top_14 border_radius_5 font_14 text_uppercase font_weight_600">Cancel</button>
//                                     </div>
//                                 </div>
//                             </div>
//                         </div>
//                     </div>
//                 </form>
//             </div>
//         </div>
//     </div>
// </template>

// <script>
// import axios from "axios";
// import Loader from "../LoaderSpinner";
// import { required, minLength,maxLength, between } from 'vuelidate/lib/validators'
// export default {
//     props : ['options'],
//     components: {
//         'Loader': Loader,
//     },
//     data() {
//         return {
//             searchQuery:"",
//             searchIcon: false,
//             successmsg: "",
//             errormsg: "",
//             loading: false,
//             submit:false,
//             requested_job_services:[]
//         }
//     },
//     validations: {
//     },

//     mounted(){
        
//     },
//     computed: {
//         resultQuery(){
//             if(this.searchQuery){
//                 return  this.options.filter((item)=>{
//                     if(item.service_sub_category){
//                         return this.searchQuery.toLowerCase().split(' ').every(v => item.service_sub_category.name.toLowerCase().includes(v))
//                     }
//                     if(item.service){
//                         return this.searchQuery.toLowerCase().split(' ').every(v => item.service.category.toLowerCase().includes(v))
//                     }
//                 })
//             }else{
//                 return this.options;
//             }
//         },   
//     },
//     methods: {
//         saveSelected(){
//             // console.log(this.requested_job_services);
//         },
//         showSection(){
//             this.searchIcon = true;
//         },
        
//         searchOptions(){
//             // jquery for select option deropdown
//             var items = document.getElementById('items');
//             items.classList.add('visible');
//             items.style.display = "block";
//         },
//         closeOptions(){
//             items.classList.remove('visible');
//             items.style.display = "none";
//             this.searchIcon = false;
//             this.searchQuery = '';

//         },
//         resetForm(){
//             this.requested_job_services = [];
//             this.$forceUpdate();
//             this.$emit('closeEvent',"Notes modal has been closed")
//             $("#lineItem").modal('hide');
//         }
//     }
// }
// </script>

// <style scoped>

// </style>

