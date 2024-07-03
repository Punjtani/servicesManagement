<template>
    <div>
        <!-- ======== -->
        <div class="dashboard_content_area">
            <h1 class="h4 heading_text">Invoices</h1>
            <div class="table_area">
                <div class="table_area_head pd_top_10">
                    <div class="row align-items-center row_spacing_5">
                        <div class="col-sm-4 col-md-4 col-lg-6 ">
                                <div class="row align-items-center row_spacing_5">
                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Search Invoices">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-6"></div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-md-8 col-lg-6 text-sm-end">
                            <div class="row align-items-center row_spacing_5">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select" aria-label="Percentage Pay">
                                            <option selected>Due</option>
                                            <option value="1">Due</option>
                                            <option value="2">Due</option>
                                            <option value="3">Due</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select" aria-label="Percentage Pay">
                                            <option selected>Invoice Type</option>
                                            <option value="1">Invoice Type</option>
                                            <option value="2">Invoice Type</option>
                                            <option value="3">Invoice Type</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="no_more_tables table_to_be_used mg_top_14">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Client</th>
                                <th>Invoice Date</th>
                                <th>Job Title</th>
                                <th>Bill</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-title="Invoice #">SL-2021-12365</td>
                                <td data-title="Client">J. Brannam	</td>
                                <td data-title="Invoice Date">11/03/2021</td>
                                <td data-title="Job Title">Quisque in nulla quis leo aliquam porta. </td>
                                <td data-title="Bill">$ 530.00</td>
                                <td data-title="Status">Paid</td>
                                <td data-title="Action"><a href="#" class="font_weight_700">Details</a></td>
                            </tr>
                            <tr>
                                <td data-title="Invoice #">SL-2021-23659</td>
                                <td data-title="Client">DGS VolMAX</td>
                                <td data-title="Invoice Date">22/04/2021</td>
                                <td data-title="Job Title">Fusce ut enim ac arcu lobortis egestas.</td>
                                <td data-title="Bill">$ 960.00</td>
                                <td data-title="Status">Not Paid</td>
                                <td data-title="Action"><a href="#" class="font_weight_700">Details</a></td>
                            </tr>
                            <tr>
                                <td data-title="Invoice #">SL-2021-25444</td>
                                <td data-title="Client">Highland </td>
                                <td data-title="Invoice Date">30/04/2021</td>
                                <td data-title="Job Title">Aenean dictum ipsum vel quam</td>
                                <td data-title="Bill">$ 480.00</td>
                                <td data-title="Status">Due</td>
                                <td data-title="Action"><a href="#" class="font_weight_700">Details</a></td>
                            </tr>
                            <tr>
                                <td data-title="Invoice #">SL-2021-65003</td>
                                <td data-title="Client">Gill Company</td>
                                <td data-title="Invoice Date">06/05/2021</td>
                                <td data-title="Job Title">Morbi sagittis urna rhoncus, ullamcorper</td>
                                <td data-title="Bill">$ 600.00</td>
                                <td data-title="Status">Not Due</td>
                                <td data-title="Action"><a href="#" class="font_weight_700">Details</a></td>
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
