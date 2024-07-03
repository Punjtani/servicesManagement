<template>
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
 <h4 class="modal-title"> Information Portal</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

        <div class="modal_content_inner">
        <div class="dashboard_content_area">
          <div class="row">
            <div class="col-sm-12">
              
              <!--<h2>On property level</h2>
              <h3>On General level – PMC info </h3>
              <h5>On property level we need this:</h5>-->
            </div>
          </div>
          <div class="row ">
            <div class="col-md-5">
              <h5 class="boxed font_weight_600 font_family_Montserrat text_color_black  mg_bot_1rem_on_mobiles">COMPLEX INFO</h5>
              <div class="row row_spacing_all_5">
                <div class="col-sm-3 col-md-3">
                  <label>Select Site</label>
                </div>
                <div class="col-sm-9 col-md-9">
                  <div class="form-group_all">
                  
                    <multiselect
                      v-model.trim="property"
                      :show-labels="false"
                      track-by="title"
                      label="title"
                      placeholder="Select Community"
                      :select-label="''"
                      :options="properties"
                      :searchable="true"
                      @select="selectProperty">
                    </multiselect>
                  </div>
                </div>
              </div>
              <div class="row row_spacing_all_5" v-if="property">
                <hr class="hr_all_screens">
                <div class="row row_spacing_all_5" v-if="property.title">
                  <div class="col-sm-3 col-md-3">
                    <label>Title</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.title}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="property.street1">
                  <div class="col-sm-3 col-md-3">
                    <label>Service Address</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.street1}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="property.city">
                  <div class="col-sm-3 col-md-3">
                    <label>City</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.city}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="property.state">
                  <div class="col-sm-3 col-md-3">
                    <label>State</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.state}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="property.zipcode">
                  <div class="col-sm-3 col-md-3">
                    <label>Zip Code</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.zipcode}}</label>
                    </div>
                  </div>
                </div>

                <div class="row row_spacing_all_5" v-if="property.country">
                  <div class="col-sm-3 col-md-3">
                    <label>Country</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.country}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="property.phone">
                  <div class="col-sm-3 col-md-3">
                    <label>Phone</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.phone}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="property.tax_type">
                  <div class="col-sm-3 col-md-3">
                    <label>State Tax</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.tax_type.name}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="property.tax_rate">
                  <div class="col-sm-3 col-md-3">
                    <label>Tax Rate</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{property.tax_rate}}%</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="property.is_quote== 1 && property.quote_attatchment.length > 0">
                  <div class="col-sm-3 col-md-3">
                    <label>Attachment</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <div class="row align-items-center no-gutters attachments_box">
                        <div class="col-6 col-md-3 cols_5" v-for="(item1, index1) in property.quote_attatchment">                            
                            <div class="img__box">
                                <img :src="'/'+item1.file_name">
                            </div>                               
                        </div> 
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-6">
              <h5 class="boxed font_weight_600 font_family_Montserrat text_color_black  mg_bot_1rem_on_mobiles">STAFF INFO </h5>
              <div v-if="staff">
                <hr class="hr_all_screens">
                <div class="row row_spacing_all_5">
                  <div class="col-sm-3 col-md-3">
                    <label>First Name</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{staff.first_name}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5" v-if="staff.middle_name">
                  <div class="col-sm-3 col-md-3">
                    <label>Middle Name</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{staff.middle_name}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5">
                  <div class="col-sm-3 col-md-3">
                    <label>Last Name</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{staff.last_name}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5">
                  <div class="col-sm-3 col-md-3">
                    <label>Email</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{staff.email}}</label>
                    </div>
                  </div>
                </div>
                <div class="row row_spacing_all_5">
                  <div class="col-sm-3 col-md-3">
                    <label>Role</label>
                  </div>
                  <div class="col-sm-9 col-md-9">
                    <div class="form-group_all">
                      <label>{{staff.roles.name}}</label>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ============= -->
              <hr class="hr_all_screens">
              <div class="rols_box">
                <h4>Note</h4>
                <h6>Roles options:</h6>
                <p>Property Manager <br> Assistant Manager <br> Maintenance Supervisor <br> Turnover Coordinator </p>
              </div>
            </div>
          </div>
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
          property:"",
          staff:""

        }
    },
    watch: {
        'client'(client) {
          // console.log(client);
          this.properties = client.properties;
          this.staffList = client.staff;
        }
    },
    mounted(){
    },

    methods : {
      selectProperty(property){
        this.property=property;
        // console.log(this.property);
        this.staff = this.staffList.find(item => (item.id == property.staff_id))
      }
    }

}

</script>