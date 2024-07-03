<template>
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Information Portal</h4>
                <button type="button" data-bs-dismiss="modal" @click="closeModel()" aria-label="Close" class="btn-close"></button> 
            </div>
            <div class="modal_content_inner">
                <div class="dashboard_content_area">
                   
                    <!-- select site -->
                    <div v-if="properties.length > 0" class="row">
                        <div class="col-12 select_area">
                            <label for="select"> Select Property</label>
                            <multiselect
                                v-model.trim="property"
                                :show-labels="false"
                                track-by="title"
                                label="title"
                                placeholder="Select Property"
                                :select-label="''"
                                :options="properties"
                                :searchable="true"
                                @select="selectProperty"
                                @remove="removeProperty">
                            </multiselect>
                        </div>

                    </div>
                    <!-- end select site -->
                    <div class="row" v-if="property">
                        <div class="col-12">
                            <h4 class="main_heading">Complex info</h4>
                        </div>
                        <div class="col-12">
                            <!-- <h6 class="sub_heading">Title</h6> -->
                            <h6 class="sub_heading">{{property.title}}</h6>
                        </div>
                        <div class="col-6" v-if="property.phone">
                            <h6 class="sub_heading">phone</h6>
                            <p>{{property.phone}} </p>
                        </div>
                        <div class="col-6">
                            <h6 class="sub_heading">Services Address</h6>
                            <p>{{property.street1}}
                            {{ property.city }} {{ property.state }}, {{property.zipcode}}</p>
                        </div>
                        <div class="col-6">
                            <!-- <h6 class="sub_heading">State Tax</h6>
                            <p v-if="property.tax_type">{{property.tax_type.name}}</p> -->
                        </div>
                        <hr v-if="(staffList.length > 0 || clientStaffList.length > 0)">
                    </div>
                    <div class="row" v-if="staffList.length > 0 || clientStaffList.length > 0">
                        <div class="col-12">
                            <h4 class="main_heading">Staff info</h4>
                                <!-- <div class="row">                                
                                    <div class="col-6">
                                        <h6 class="sub_heading">Staff Name</h6>
                                    </div>
                                    <div class="col-6">
                                        <h6 class="sub_heading">Staff Role</h6> 
                                    </div>
                                </div> -->
                                <div class="row" v-if="(clientStaffList.length > 0 || staffList.length > 0)">
                                    <div class="col-md-12">
                                        <div class="staff_info_tbl">
                                            <table class="table_to_be_used ">
                                                <thead>
                                                    <tr>
                                                        <th>Staff Name</th>
                                                        <th>Staff Role</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="clientStaffList.length > 0" v-for="staff in clientStaffList" >
                                                        <td data-title="Staff Name">{{staff.user.first_name}} {{staff.user.middle_name}} {{staff.user.last_name}}</td>
                                                        <td data-title="Staff Role">{{staff.staff_roles.name}}</td>
                                                    </tr>
                                                    <tr v-if="staffList.length > 0"  v-for="staff in staffList" >
                                                        <td data-title="Staff Name">{{staff.user.first_name}} {{staff.user.middle_name}} {{staff.user.last_name}}</td>
                                                        <td data-title="Staff Role">{{staff.staff_roles.name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- <div class="col-6">
                                        <div v-for="staff in staffList">
                                            <p>{{staff.user.first_name}} {{staff.user.middle_name}} {{staff.user.last_name}}</p>
                                        </div>  
                                    </div>
                                    <div class="col-6">
                                        <div v-for="staff in staffList">
                                            <p></p>
                                        </div> 
                                    </div> -->
                                    <!--<div class="row row_spacing_all_5">
                                        <div class="col-sm-3 col-md-3">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-sm-9 col-md-9">
                                            <div class="form-group_all">
                                                <label>{{staff.user.email}}</label>
                                            </div>
                                        </div>
                                    </div>-->
                                </div>
                                <!-- <div class="row" v-if="clientStaffList.length > 0">
                                    <div class="col-6">
                                        <div v-for="staff in clientStaffList">
                                            <p>{{staff.user.first_name}} {{staff.user.middle_name}} {{staff.user.last_name}}</p>
                                        </div>  
                                    </div>
                                    <div class="col-6">
                                        <div v-for="staff in clientStaffList">
                                            <p>{{staff.staff_roles.name}}</p>
                                        </div> 
                                    </div>
                                </div> -->
                        </div>
                    </div>
                    <!--<div v-if="property && staffList.length <=0">
                        <div class="alert alert-danger">
                            <strong>No Staff Found!</strong>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</template>
<script>
import Multiselect from 'vue-multiselect';
export default {
    name: "view_client_details",
    props: ['client'],
    components: {
      Multiselect,
    },
    data() {
        return {
          properties:[],
          staffList:[],
          clientStaffList:[],
          property:"",
          staff:""
        }
    },
    watch: {
        'client'(client) {
          this.properties = client.properties; 
          if(client.staff){
            this.clientStaffList = client.staff;
          }
        }
    },
    mounted(){
    },
    methods : {
      selectProperty(property){
        this.property=property;
        this.staffList=property.staff;
      },
      closeModel(){
        this.property="";
        this.staffList="";
        this.$emit('closeInfoPopup');
      },
      removeProperty(){
        this.staffList=[];
      }
    }

}

</script>