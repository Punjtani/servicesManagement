<template>
    <div>
        <div class="dashboard_content_area">
            <h1 class="h4 heading_text">Work Requests</h1>
            <div class="table_area">
                <div class="no_more_tables table_to_be_used mg_top_14 table_with_less_spacing">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Requested</th>
                                <th>Title</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-title="Client">J. Brannam</td>
                                <td data-title="Requested">11/03/2021</td>
                                <td data-title="Title">Quisque in nulla quis leo aliquam porta.</td>
                                <td data-title="Contact">770-670-0012 / HenryJFernandes@armyspy.com	</td>
                                <td data-title="Action"><a href="#" class="font_weight_700" data-bs-toggle="modal" data-bs-target="#workRequestsDetailsModal">Details</a></td>
                            </tr>
                            <tr>
                                <td data-title="Client">DGS VolMAX</td>
                                <td data-title="Requested">22/04/2021</td>
                                <td data-title="Title">Fusce ut enim ac arcu lobortis egestas.</td>
                                <td data-title="Contact">605-283-3322 / CarlPJohnson@armyspy.com</td>
                                <td data-title="Action"><a href="#" class="font_weight_700" data-bs-toggle="modal" data-bs-target="#workRequestsDetailsModal">Details</a></td>
                            </tr>
                            <tr>
                                <td data-title="Client">Highland</td>
                                <td data-title="Requested">30/04/2021</td>
                                <td data-title="Title">Aenean dictum ipsum vel quam</td>
                                <td data-title="Contact">570-647-8873 / KristopherPFuller@teleworm.us</td>
                                <td data-title="Action"><a href="#" class="font_weight_700" data-bs-toggle="modal" data-bs-target="#workRequestsDetailsModal">Details</a></td>
                            </tr>
                            <tr>
                                <td data-title="Client">Gill Company</td>
                                <td data-title="Requested">06/05/2021</td>
                                <td data-title="Title">Morbi sagittis urna rhoncus, ullamcorper</td>
                                <td data-title="Contact">616-988-9858 / JohnBRouse@armyspy.com</td>
                                <td data-title="Action"><a href="#" class="font_weight_700" data-bs-toggle="modal" data-bs-target="#workRequestsDetailsModal">Details</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- ================================= -->
        <!-- ================================= -->
        <!-- ================================= -->
        <!-- ====================== -->
        <!-- Modal -->
        <div class="modal fade custom_base_model custom_base_model_large work_requests_model" id="workRequestsDetailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
 <h4 class="modal-title"> Work Request</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

                    <div class="modal_content_inner">
                        <div class="modal-body">
                            <div class="form_box">
                                <div class="row row_spacing_5 base_row">
                                    <div class="col-md-3 col-lg-3">
                                            <label>Cpntact Name</label>
                                    </div>
                                    <div class="col-md-9 col-lg-9">
                                        <div class="row row_spacing_5">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="First Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Last Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row row_spacing_5">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Company Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                 <div class="row row_spacing_5 base_row">
                                    <div class="col-md-3 col-lg-3">
                                            <label>Contact Details</label>
                                    </div>
                                    <div class="col-md-9 col-lg-9">
                                        <div class="row row_spacing_5">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Email Address">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Phone Number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row_spacing_5 base_row">
                                    <div class="col-md-3 col-lg-3">
                                            <label>Address</label>
                                    </div>
                                    <div class="col-md-9 col-lg-9">
                                            <div class="row row_spacing_5">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Street address">
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="row row_spacing_5">
                                            <div class="col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="City ">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="form-group">
                                                     <select class="form-select" aria-label="Percentage Pay">
                                                        <option selected>State</option>
                                                        <option value="1">State</option>
                                                        <option value="2">State</option>
                                                        <option value="3">State</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="ZIP">
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row row_spacing_5">
                                    <div class="col-md-3 col-lg-3">
                                            <label>Description</label>
                                    </div>
                                    <div class="col-md-9 col-lg-9">
                                        <div class="form-group">
                                            <textarea class="form-control" placeholder="I need some wotk done    . . . . ."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row_spacing_5 base_row">
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label>Attachments</label>
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-lg-9">
                                        <div class="col-md-12 cols_in_5 cols_in_5_less_Spacing">
                                            <div class="row align-items-center">
                                                <div class="col-md-3 cols_5">
                                                    <div class="form-group">
                                                        <img src="images/image_placeholder_small.png" alt="image_placeholder_small">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 cols_5">
                                                    <div class="form-group">
                                                        <img src="images/image_placeholder_small.png" alt="image_placeholder_small">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 cols_5">
                                                    <div class="form-group">
                                                        <img src="images/image_placeholder_small.png" alt="image_placeholder_small">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 cols_5">
                                                    <div class="form-group">
                                                        <img src="images/image_placeholder_small.png" alt="image_placeholder_small">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 cols_5">
                                                    <div class="form-group">
                                                        <img src="images/image_placeholder_small.png" alt="image_placeholder_small">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  <div class="row row_spacing_5">
                                    <div class="col-md-3 col-lg-3">
                                    </div>
                                    <div class="col-md-9 col-lg-9">
                                        <div class="inline_buttons">
                                            <div class="row">
                                                <div class="col-6 col-sm-8">
                                                    <button class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">Confirm</button>
                                                </div>
                                                <div class="col-6 col-sm-4">
                                                    <button class="btn btn_blue  border_radius_5 font_14 text_uppercase font_weight_600">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        mounted() {
            // console.log('Dashboard Component mounted.')
        }
    }
</script>