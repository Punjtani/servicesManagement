<template>
    <div>
        <h1 class="h4 heading_text">Order Service</h1>
        <div class="table_area">
            <div class="table_area_head pd_top_24 pd_bot_30">
                <div class="row align-items-center row_spacing_5">
                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="row align-items-center row_spacing_5">
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="mg_bot_5">
                                    <select class="form-select max_width_270" aria-label="Percentage Pay">
                                        <option selected>Select Community</option>
                                        <option value="1">Select Community</option>
                                        <option value="2">Select Community</option>
                                        <option value="3">Select Community</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-6">
                                <div class="mg_bot_5">
                                    <select class="form-select max_width_270" aria-label="Percentage Pay">
                                        <option selected>Service Category</option>
                                        <option value="1">Service Category</option>
                                        <option value="2">Service Category</option>
                                        <option value="3">Service Category</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6  col-lg-6 text-sm-end">
                        <div class="row align-items-center row_spacing_5">
                            <div class="col-sm-4 text-sm-end">
                            </div>
                            <div class="col-sm-4 text-sm-end">
                                <div class="mg_bot_5">
                                    <input type="text" class="form-control" placeholder="Unit #">
                                </div>
                            </div>
                            <div class="col-sm-4 text-sm-end">
                                <div class="mg_bot_5">
                                    <input type="text" class="form-control" placeholder="PO">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="info_form">
                <!-- ======== -->
                <!-- ======== -->
                <div class="row align-items-center row_spacing_5 mg_bot_18">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Status </h6>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="inline_radio radio_in_six">
                                <!-- === -->
                                <div class="custom_radio ">
                                    <label>
                                        <input type="radio" name="Status">
                                        <span class="box"></span>Vacant
                                    </label>
                                </div>
                                <!-- === -->
                                <!-- === -->
                                <div class="custom_radio ">
                                    <label>
                                        <input type="radio" name="Status">
                                        <span class="box"></span>Occupied
                                    </label>
                                </div>
                                <!-- === -->
                                <!-- === -->
                            </div>
                            <!-- ====== -->
                        </div>
                    </div>
                </div>
                <!-- ======== -->
                <!-- ======== -->
                <div class="row align-items-center row_spacing_5 mg_bot_30">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Service Ready Checklist </h6>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="inline_checkbox checkbox_in_three">
                                <!-- === -->
                                <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" data-ng-model="example.check">
                                        <span class="box"></span>Have keys, Contractor can give easy access
                                    </label>
                                </div>
                                <!-- === -->
                                <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" data-ng-model="example.check">
                                        <span class="box"></span> Resident notified (if occupied)
                                    </label>
                                </div>
                                <!-- === -->
                                <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" data-ng-model="example.check">
                                        <span class="box"></span>Water in the apartment
                                    </label>
                                </div>
                                <!-- === -->
                                <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" data-ng-model="example.check">
                                        <span class="box"></span>Stove moved (If services require moving it)
                                    </label>
                                </div>
                                <!-- === -->
                                <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" data-ng-model="example.check">
                                        <span class="box"></span>Priors to turnovers repair completed
                                    </label>
                                </div>
                                <!-- === -->
                                <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" data-ng-model="example.check">
                                        <span class="box"></span>Power in the apartment
                                    </label>
                                </div>
                                <!-- === -->
                                <div class="custom_checkbox">
                                    <label>
                                        <input type="checkbox" data-ng-model="example.check">
                                        <span class="box"></span>No hazardous conditions present
                                    </label>
                                </div>
                                <!-- === -->
                            </div>
                            <!-- ====== -->
                        </div>
                    </div>
                </div>
                <!-- ======== -->
                <div class="row align-items-center row_spacing_5">
                    <div class="col-md-6 col-lg-3">
                        <div class="form-group">
                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange">I am requesting services for</h6>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="form-group">
                            <select class="form-select max_width_270" aria-label="Percentage Pay">
                                <option selected>Select Service Type</option>
                                <option value="1">Select Service Type</option>
                                <option value="2">Select Service Type</option>
                                <option value="3">Select Service Type</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-3">
                    </div>
                </div>
                <!-- ======= -->
                <div class="table_area">
                    <div class="no_more_tables table_to_be_used table_with_widths">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th class="tb_width_1">Service(s)</th>
                                    <th class="">Base Price ($)</th>
                                    <th class="">Requested Date</th>
                                    <th class="tb_width_4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr  aria-controls="panelsStayOpen-collapseTwo" class="colapsible_tr">
                                    <td class="tb_width_1 uniqueclass" data-title="Service(s)" aria-expanded="false"  data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo">
                                          <svg class="table_icon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg> 
                                        Paint</td>
                                    <td data-title="Base Price ($)">900</td>
                                    <td data-title="Requested Date">30/06/2021 </td>
                                    <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                </tr>
                                <tr class="colapsible_tr  colapsible_inner_tr">
                                    <td colspan="4" class="hiddenRow">
                                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                                            <table class="table">
                                                <tbody>
                                                    <tr >
                                                        <td class="tb_width_11" data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                 <!-- ===================== -->
                                <!-- ================= -->



                                <!-- ===================== -->
                                <tr data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" class="colapsible_tr">
                                    <td class="tb_width_1" data-title="Service(s)">  
                                <svg class="table_icon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg> Drywall</td>
                                    <td data-title="Base Price ($)">450</td>
                                    <td  data-title="Requested Date">30/06/2021 </td>
                                     <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                     
                                </tr>
                                
                                <tr class="colapsible_tr  colapsible_inner_tr">
                                    <td colspan="4" class="hiddenRow">
                                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                            <table class="table">
                                                <tbody>
                                                    <tr >
                                                        <td class="tb_width_11" data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                 <!-- ===================== -->
                                <tr  data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree" class="colapsible_tr">
                                    <td data-title="Service(s)">
                                          <svg class="table_icon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg> 
                                        Janitorial</td>
                                    <td data-title="Base Price ($)">600</td>
                                    <td data-title="Requested Date">30/06/2021</td>
                                    <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                </tr>
                                <tr class="colapsible_tr  colapsible_inner_tr">
                                    <td colspan="4" class="hiddenRow">
                                        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                            <table class="table">
                                                <tbody>
                                                    <tr >
                                                        <td class="tb_width_11" data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                 <!-- ===================== -->
                                 <!-- ===================== -->
                                <tr   data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour" class="colapsible_tr">
                                    <td data-title="Service(s)">
                                          <svg class="table_icon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg> 
                                        Refinishing	</td>
                                    <td data-title="Base Price ($)">300</td>
                                    <td data-title="Requested Date">30/06/2021</td>
                                    <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                </tr>
                                <tr class="colapsible_tr  colapsible_inner_tr">
                                    <td colspan="4" class="hiddenRow">
                                        <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
                                            <table class="table">
                                                <tbody>
                                                    <tr >
                                                        <td class="tb_width_11" data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr >
                                                        <td data-title="Service(s)">Pellentesque a nibh ac urna</td>
                                                        <td data-title="Base Price ($)">130</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                 <!-- ===================== -->
                                 <!-- ======Blue TR=============== -->
                                <tr class="display_on_desktop blue_tr">
                                    <td class="tb_width_1" colspan="1">Total Cost(s)</td>
                                    <td data-title="Total Cost(s)" colspan="3">2 250</td>
                                </tr>
                                 <!-- ===================== -->
                                  <tr class="display_on_mobile blue_tr">
                                    <td class="tb_width_1" data-title="Total Cost(s)" colspan="4">2 250</td>
                                </tr>
                                 <!-- ======Blue TR=============== -->
                                  <!-- ===================== -->
                            </tbody>
                           
                        </table>
                      
                  <!-- ======== -->
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h6 class="font_family_Montserrat font_weight_600 mg_0 text_color_orange font_20">Attachments</h6>
                        </div>
                    </div>
                    <div class="col-md-12 cols_in_5">
                        <div class="row align-items-center">
                            <div class="col-md-3 cols_5">
                                 <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                 <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                 <div class="form-group">
                                    <img src="images/image_placeholder.png" alt="image_placeholder">
                                </div>
                            </div>
                            <div class="col-md-3 cols_5">
                                 <div class="form-group">
                                     <div class="file_upload">
                                        <input class="form-control" type="file" style="opacity: 0;"  id="formFile">
                                        <img src="images/image_placeholder_upload_File.png" alt="image_placeholder_upload_File">
                                     </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ======= -->               
                           
                    </div>
                </div>
                <!-- ======== -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group inine_buttons">
                            <div class="inine_button_spacing">          
                                <button type="button" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Add More Apartments</button>
                            </div>
                            <div class="inine_button_spacing">   
                                <button type="button" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Place Order</button>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- =========== -->
</template>

<script>
    export default {
        mounted() {
            // console.log('Dashboard Component mounted.')
        }
    }
</script>
