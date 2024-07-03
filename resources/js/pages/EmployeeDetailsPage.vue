<template>
    <div>
        <!-- ======== -->
        <div class="dashboard_content_area">
            <h1 class="h4 heading_text">Employees</h1>
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
            <div class="smp__tabs">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general_info_tab" data-bs-toggle="tab" data-bs-target="#general_info" type="button" role="tab" aria-controls="general_info" aria-selected="true">General Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="history_tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payroll_tab" data-bs-toggle="tab" data-bs-target="#payroll" type="button" role="tab" aria-controls="payroll" aria-selected="false">Payroll</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents_tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">Documents</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="general_info" role="tabpanel" aria-labelledby=" general_info_tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_bot_2rem mg_top_half_rem">General Info</h5>
                            </div>
                            <div class="info_form">
                                <!-- ======== -->
                                <div class="row align-items-center row_spacing_5 ">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">Employe Name</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Middle Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Last Name">
                                        </div>
                                    </div>
                                </div>
                                <!-- ======== -->
                                <!-- ======== -->
                                <div class="row row_spacing_5">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">Division</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <!-- ====== -->
                                        <div class="form-group">
                                            <div class="inline_radio radio_in_five">
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Division">
                                                        <span class="box"></span>Paint
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Division">
                                                        <span class="box"></span>Carpet Cleaning
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Division">
                                                        <span class="box"></span>Drywall
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Division">
                                                        <span class="box"></span>Flooring
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Division">
                                                        <span class="box"></span>Punch-out
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Division">
                                                        <span class="box"></span>Janitorial
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Division">
                                                        <span class="box"></span>Refinishing
                                                    </label>
                                                </div>
                                                <!-- === -->
                                            </div>
                                            <!-- ====== -->
                                        </div>
                                    </div>
                                </div>
                                <!-- ======== -->
                                <!-- ======== -->
                                <div class="row align-items-center row_spacing_5">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">Contract Dates</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="First Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Middle Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                                <!-- ======== -->
                                <!-- ======== -->
                                <div class="row align-items-center row_spacing_5">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">Salared </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="inline_radio radio_in_five">
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Salared">
                                                        <span class="box"></span>Per Hour
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Salared">
                                                        <span class="box"></span>Percentage
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="Salared">
                                                        <span class="box"></span>Fixed Price
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
                                <div class="row align-items-center row_spacing_5">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">T Shirt Size</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <div class="inline_radio radio_in_five">
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>XXXS
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>XS
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>XS
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>S
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>M
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>L
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>XL
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>XXL
                                                    </label>
                                                </div>
                                                <!-- === -->
                                                <!-- === -->
                                                <div class="custom_radio ">
                                                    <label>
                                                        <input type="radio" name="t_Shirt_Size">
                                                        <span class="box"></span>XXXL
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">Address</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <!-- ======== -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <h6 class="font_family_Montserrat font_weight_600 mg_0">Notes</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <textarea class="form-control"></textarea>
                                        </div>
                                        <button type="button" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history_tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_bot_1_half_rem">History</h5>
                                <div class="row align-items-center row_spacing_5">
                                    <div class="col-sm-6 col-md-6  col-lg-8">
                                        <div class="mg_bot_5">
                                            <select class="form-select max_width_270" aria-label="Percentage Pay">
                                                <option selected>Select Community</option>
                                                <option value="1">Select Community</option>
                                                <option value="2">Select Community</option>
                                                <option value="3">Select Community</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6  col-lg-4 text-sm-end">
                                        <div class="row align-items-center row_spacing_5">
                                            <div class="col-sm-6 text-sm-end">
                                                <div class="mg_bot_5">
                                                    <select class="form-select max_width_170" aria-label="Percentage Pay">
                                                        <option selected>Start Date</option>
                                                        <option value="1">Start Date</option>
                                                        <option value="2">Start Date</option>
                                                        <option value="3">Start Date</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 text-sm-end">
                                                <div class="mg_bot_5">
                                                    <select class="form-select max_width_170" aria-label="Percentage Pay">
                                                        <option selected>End Date</option>
                                                        <option value="1">End Date</option>
                                                        <option value="2">End Date</option>
                                                        <option value="3">End Date</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div  class="no_more_tables table_to_be_used mg_top_0rem">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Community</th>
                                            <th>Unit #</th>
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th>Earning</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-title="Community">Aenean laoreet</td>
                                            <td data-title="Unit #">369-X</td>
                                            <td data-title="Date">11/03/2021</td>
                                            <td data-title="Category">Carpet Cleaning</td>
                                            <td data-title="Earning">1234</td>
                                            <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Community">Nam eget erat sagittis</td>
                                            <td data-title="Unit #">522-W</td>
                                            <td data-title="Date">06/04/2021</td>
                                            <td data-title="Category">Drywall</td>
                                            <td data-title="Earning">456</td>
                                            <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Community">Vivamus fringilla</td>
                                            <td data-title="Unit #">115-S</td>
                                            <td data-title="Date">22/06/2021</td>
                                            <td data-title="Category">Paint</td>
                                            <td data-title="Earning">789</td>
                                            <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Community">Mauris a lacus vulputate</td>
                                            <td data-title="Unit #">456-C</td>
                                            <td data-title="Date">18/07/2021</td>
                                            <td data-title="Category">Flooring</td>
                                            <td data-title="Earning">567</td>
                                            <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                    <div class="tab-pane fade" id="payroll" role="tabpanel" aria-labelledby="payroll_tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_bot_1_half_rem">Payroll</h5>
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <h5 class="font_weight_400 font_family_Montserrat text_color_blue mg_bot_1_half_rem">Nicholas G. Murray
                                            <small>Department - Paint</small></h5>
                                    </div>
                                </div>
                                <div class="row align-items-center row_spacing_5">
                                    <div class="col-sm-6 col-md-6  col-lg-4">
                                        <div class="row align-items-center row_spacing_5">
                                            <div class="col-sm-6 text-sm-end">
                                                <div class="form-group">
                                                    <select class="form-select max_width_170" aria-label="Percentage Pay">
                                                        <option selected>Start Date</option>
                                                        <option value="1">Start Date</option>
                                                        <option value="2">Start Date</option>
                                                        <option value="3">Start Date</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 text-sm-end">
                                                <div class="form-group">
                                                    <select class="form-select max_width_170" aria-label="Percentage Pay">
                                                        <option selected>End Date</option>
                                                        <option value="1">End Date</option>
                                                        <option value="2">End Date</option>
                                                        <option value="3">End Date</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6  col-lg-8 text-sm-end">
                                        <button type="button" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Add New Document</button>
                                    </div>
                                </div>
                            </div>
                            <div class="no_more_tables table_to_be_used mg_top_half_rem">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Day</th>
                                            <th>Check In</th>
                                            <th>Checkout</th>
                                            <th>Hours</th>
                                            <th>Total Billed</th>
                                            <th>Employee's 30%</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-title="Date">11/03/2021</td>
                                            <td data-title="Day">Thu</td>
                                            <td data-title="Check In">09:00 AM</td>
                                            <td data-title="Checkout">06:00 PM</td>
                                            <td data-title="Hours">6:00</td>
                                            <td data-title="Total Billed">$ 530.00</td>
                                            <td data-title="Employee's 30%">$ 159.00</td>
                                            <td data-title="Action"><a href="#" class="font_weight_700">Job Details</a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Date">05/04/2021</td>
                                            <td data-title="Day">Mon</td>
                                            <td data-title="Check In">09:20 AM</td>
                                            <td data-title="Checkout">06:10 PM</td>
                                            <td data-title="Hours">5:30</td>
                                            <td data-title="Total Billed">$ 960.00</td>
                                            <td data-title="Employee's 30%">$ 288.00</td>
                                                <td data-title="Action"><a href="#" class="font_weight_700">Job Details</a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Date">22/04/2021</td>
                                            <td data-title="Day">Fri</td>
                                            <td data-title="Check In">09:10 AM</td>
                                            <td data-title="Checkout">06:20 PM</td>
                                            <td data-title="Hours">6:10</td>
                                            <td data-title="Total Billed">$ 480.00</td>
                                            <td data-title="Employee's 30%">$ 144.00</td>
                                                <td data-title="Action"><a href="#" class="font_weight_700">Job Details</a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Date">30/05/2021</td>
                                            <td data-title="Day">Wed</td>
                                            <td data-title="Check In">09:30 AM</td>
                                            <td data-title="Checkout">05:45 PM</td>
                                            <td data-title="Hours">5:30</td>
                                            <td data-title="Total Billed">$ 600.00</td>
                                            <td data-title="Employee's 30%">$ 188.00</td>
                                            <td data-title="Action"><a href="#" class="font_weight_700">Job Details</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- =============== -->
                        <!-- =============== -->
                    </div>
                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents_tab">
                        <!-- ================ -->
                        <div class="table_area">
                            <div class="table_area_head">
                                <h5 class="font_weight_600 font_family_Montserrat text_color_orange mg_bot_1_half_rem">Documants</h5>
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h5 class="font_weight_400 font_family_Montserrat text_color_blue mg_bot_1_half_rem">Nicholas G. Murray
                                            <small>Department - Paint</small></h5>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <button type="button" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Add New Document</button>
                                    </div>
                                </div>
                            </div>
                            <div  class="no_more_tables table_to_be_used mg_top_half_rem">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Document Name</th>
                                            <th>Expiry Date</th>
                                            <th>Document ID</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td data-title="Document Name">Vestibulum tristique</td>
                                            <td data-title="Expiry Date">11/03/2021 </td>
                                            <td data-title="Document ID">4485 - 374670820 - 971 </td>
                                            <td data-title="Image"><img src="images/Image_blank.png" alt=""></td>
                                            <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Document Name">Vestibulum tristique</td>
                                            <td data-title="Expiry Date">11/03/2021 </td>
                                            <td data-title="Document ID">4485 - 374670820 - 971 </td>
                                            <td data-title="Image"><img src="images/Image_blank.png" alt=""></td>
                                            <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Document Name">Vestibulum tristique</td>
                                            <td data-title="Expiry Date">11/03/2021 </td>
                                            <td data-title="Document ID">4485 - 374670820 - 971 </td>
                                            <td data-title="Image"><img src="images/Image_blank.png" alt=""></td>
                                            <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                        </tr>
                                        <tr>
                                            <td data-title="Document Name">Vestibulum tristique</td>
                                            <td data-title="Expiry Date">11/03/2021 </td>
                                            <td data-title="Document ID">4485 - 374670820 - 971 </td>
                                            <td data-title="Image"><img src="images/Image_blank.png" alt=""></td>
                                            <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- =============== -->
                    </div>
                </div>
            </div>
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->
        </div>
        <!-- ====================== -->
         <!-- ====================== -->
    <!-- Modal -->
    <div class="modal fade custom_base_model custom_base_model_medium" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal_content_inner">
                    <div class="modal-body">
                        <div class="modal-header">
 <h4 class="modal-title">Paint Team
                            <small>Department - Paint</small></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

                        <!-- ========== -->
                        <div  class="no_more_tables table_to_be_used mg_top_2rem">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Lead</th>
                                        <th>Name</th>
                                        <th>Contract</th>
                                        <th>Percentage</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-title="Lead">
                                            <!-- ====== -->
                                            <div class="custom_checkbox">
                                                <label>
                                                    <input type="checkbox" data-ng-model="example.check">
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                            <!-- ====== -->
                                        </td>
                                        <td data-title="Name">William T. Kaminski</td>
                                        <td data-title="Contract">7/19/2020 - 4/22/2022</td>
                                        <td data-title="Percentage">10%</td>
                                        <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Lead">
                                            <!-- ====== -->
                                            <div class="custom_checkbox">
                                                <label>
                                                    <input type="checkbox" data-ng-model="example.check">
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                            <!-- ====== -->
                                        </td>
                                        <td data-title="Name">Edward L. Chance</td>
                                        <td data-title="Contract">7/19/2020 - 4/22/2022</td>
                                        <td data-title="Percentage">15%</td>
                                        <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Lead">
                                            <!-- ====== -->
                                            <div class="custom_checkbox">
                                                <label>
                                                    <input type="checkbox" data-ng-model="example.check">
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                            <!-- ====== -->
                                        </td>
                                        <td data-title="Name">John M. Aguilar</td>
                                        <td data-title="Contract">7/19/2020 - 4/22/2022</td>
                                        <td data-title="Percentage">12%</td>
                                        <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                    </tr>
                                    <tr>
                                        <td data-title="Lead">
                                            <!-- ====== -->
                                            <div class="custom_checkbox">
                                                <label>
                                                    <input type="checkbox" data-ng-model="example.check">
                                                    <span class="box"></span>
                                                </label>
                                            </div>
                                            <!-- ====== -->
                                        </td>
                                        <td data-title="Name">Jeff J. Dukes</td>
                                        <td data-title="Contract">7/19/2020 - 4/22/2022</td>
                                        <td data-title="Percentage">12%</td>
                                        <td data-title="Action"><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Edit</a><span class="seprator">|</span><a class="table_btn_link font_12 font_weight_700 text_uppercase" href="#">Delete </a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- ========== -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <button class="btn btn_orange  border_radius_5 font_14 text_uppercase font_weight_600">Add Team Member</button>
                                </div>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <div class="form-group">
                                    <button class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">Confirm</button>
                                    <button class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <!-- ========== -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ================== -->
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log('Dashboard Component mounted.')
        }
    }
</script>
