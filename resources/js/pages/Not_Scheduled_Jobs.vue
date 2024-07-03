<template>
    <div>
        <!-- ======== -->
            <div class="dashboard_content_area">
                <h1 class="h4 heading_text">UnScheduled Jobs</h1>
                <div class="table_area">
                    <div class="table_area_head pd_top_0">
                        <div class="row align-items-center">
                            <div class="col-sm-8 col-md-8 col-lg-6">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <select class="form-select" aria-label="Percentage Pay">
                                                <option selected>Select Community</option>
                                                <option value="1">Select Community</option>
                                                <option value="2">Select Community</option>
                                                <option value="3">Select Community</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <select class="form-select" aria-label="Percentage Pay">
                                                <option selected>Service Category</option>
                                                <option value="1">Service Category</option>
                                                <option value="2">Percentage Pay</option>
                                                <option value="3">Percentage Pay</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-6 text-sm-end">
                                <div class="form-group">
                                    <button type="button" class="btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Add new Jobs</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div  class="no_more_tables table_to_be_used">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>Unit #</th>
                                    <th>Categoory</th>
                                    <th>Assigned To</th>
                                    <th>Requested Date</th>
                                    <th>Approved</th>
                                    <th>Schedule at</th>
                                    <th>Billed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-title="Unit #">11-B</td>
                                    <td data-title="Categoory">Paint</td>
                                    <td data-title="Assigned To">Paint Team A</td>
                                    <td data-title="Requested Date">30/06/2021</td>
                                    <td data-title="Approved">30/06/2021 </td>
                                    <td data-title="Schedule at">05/07/2021</td>
                                    <td data-title="Billed">17/2021</td>
                                </tr>
                                <tr>
                                    <td data-title="Unit #">18-C</td>
                                    <td data-title="Categoory">Janitorial</td>
                                    <td data-title="Assigned To">Angelo Curtis</td>
                                    <td data-title="Requested Date">30/06/2021</td>
                                    <td data-title="Approved">30/06/2021 </td>
                                    <td data-title="Schedule at">05/07/2021</td>
                                    <td data-title="Billed">17/2021</td>
                                </tr>
                                <tr>
                                    <td data-title="Unit #">39-A</td>
                                    <td data-title="Categoory">Refinishing</td>
                                    <td data-title="Assigned To">PJames Whittle</td>
                                    <td data-title="Requested Date">30/06/2021 </td>
                                    <td data-title="Approved">30/06/2021</td>
                                    <td data-title="Schedule at">05/07/2021</td>
                                    <td data-title="Billed">17/2021</td>
                                </tr>
                                <tr>
                                    <td data-title="Unit #">56-W</td>
                                    <td data-title="Categoory">Refinishing</td>
                                    <td data-title="Assigned To">Refinishing Team A</td>
                                    <td data-title="Requested Date">30/06/2021</td>
                                    <td data-title="Approved">30/06/2021 </td>
                                    <td data-title="Schedule at">05/07/2021</td>
                                    <td data-title="Billed">17/2021</td>
                                </tr>
                                <tr>
                                    <td data-title="Unit #">48-X</td>
                                    <td data-title="Categoory">Paint</td>
                                    <td data-title="Assigned To"><a href="" class="table_btn_link font_14 font_weight_700">Assign</a></td>
                                    <td data-title="Requested Date">16/07/2021</td>
                                    <td data-title="Approved">17/06/2021</td>
                                    <td data-title="Schedule at"><a href="" class="table_btn_link font_14 font_weight_700">Schedule Job</a></td>
                                    <td data-title="Billed">30/2021</td>
                                </tr>
                                <tr>
                                    <td data-title="Unit #">82-A</td>
                                    <td data-title="Categoory">Carpet Cleaning</td>
                                    <td data-title="Assigned To">Carpet Cleaning Team A </td>
                                    <td data-title="Requested Date">19/05/2021</td>
                                    <td data-title="Approved">19/06/2021</td>
                                    <td data-title="Schedule at"><a href="" class="table_btn_link font_14 font_weight_700">Schedule Job</a></td>
                                    <td data-title="Billed">22/2021</td>
                                </tr>
                                <tr>
                                    <td data-title="Unit #">66-O</td>
                                    <td data-title="Categoory">Flooring</td>
                                    <td data-title="Assigned To">Wallace Black</td>
                                    <td data-title="Requested Date">23/05/2021</td>
                                    <td data-title="Approved">23/06/2021 </td>
                                    <td data-title="Schedule at"><a href="" class="table_btn_link font_14 font_weight_700">Schedule Job</a></td>
                                    <td data-title="Billed">04/2021</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ==============================    -->
    </div>
</template>

<script>
    export default {
        mounted() {
            // console.log('Dashboard Component mounted.')
        }
    }
</script>
