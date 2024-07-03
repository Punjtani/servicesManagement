<template>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
 <h4 class="modal-title" v-if="name == 'property'" >Property</h4>
                <h4 class="modal-title" v-if="name == 'quote'" >Quote</h4>
 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

            <div class="modal_content_inner">
                 <div  class="no_more_tables table_to_be_used mg_top_1rem no_more_tables_collapsed">
                     <table class="table mg_0">
                        <thead>
                            <tr>
                                <th v-if="Property.title">Title</th>
                                <th v-if="Property.street1">Service Address</th>
                                <!--<th v-if="Property.street2">Street-2</th>-->
                                <th v-if="Property.city">City</th>
                                <th v-if="Property.state">State</th>
                                <th v-if="Property.zipcode">Zipcode</th>
                                <th v-if="Property.phone">Phone</th>
                                <!--<th v-if="Property.country">Country</th>-->
                                <th v-if="Property.tax_type">State Tax</th>
                                <th v-if="Property.tax_rate">Tax Rate</th>
                                <th v-if="Property.is_quote== 1 && Property.quote_attatchment.length > 0">Attachments</th>
                                <!--<th v-if="Property.suppliers">Suppliers</th>
                                <th v-if="Property.appartment_types">Apartment Types</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td v-if="Property.title" data-title="Title">{{ Property.title }}</td>
                                <td  v-if="Property.street1" data-title="Service Address">{{ Property.street1 }}</td>
                                <!--<td v-if="Property.street2" data-title="Street-2">{{ Property.street2 }}</td>-->
                                <td v-if="Property.city" data-title="City">{{ Property.city }}</td>
                                <td v-if="Property.state" data-title="State">{{ Property.state }}</td>
                                <td v-if="Property.zipcode" data-title="Zipcode">{{ Property.zipcode }}</td>
                                <td v-if="Property.phone" data-title="Phone">{{ Property.phone }}</td>
                                <!--<td v-if="Property.country" data-title="Country">{{ Property.country }}</td>-->
                                <td v-if="Property.tax_type" data-title="State Tax">{{ Property.tax_type? Property.tax_type.name:""}}</td>
                                <td v-if="Property.tax_rate" data-title="Tax Rate">{{ Property.tax_rate }}%</td>
                                <td v-if="Property.is_quote== 1 && Property.quote_attatchment.length > 0" data-title="Attachments">
                                    <div class="row align-items-center no-gutters attachments_box">
                                    <div class="col-6 col-md-3 cols_5" v-for="(item1, index1) in Property.quote_attatchment">
                                        <div class="img__box">
                                            <img :src="'/'+item1.file_name">
                                        </div>
                                    </div>
                                    </div>
                                </td>
                                <!--<td v-if="Property.suppliers" data-title="Suppliers">{{ Property.suppliers }}</td>
                                <td v-if="Property.appartment_types" data-title="Apartment Types">
                                    <span  v-for="(appartment, index) in Property.appartment_types">
                                        {{appartment.type}}{{index == Property.appartment_types.length-1 ? '':', '}}
                                    </span>
                                </td>-->
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</template>
<script>
import roleData from '../../../data.js';
export default {
    name: "view_property",
    components : {
        'roleData': roleData,
    },
    props: ['Property', 'name'],
    data() {
        return {
            role: "",
            roleData: roleData,

        }
    },
    watch:{
      'Property'(val){
          this.Property = val
      }
    },

    mounted(){
        this.role = localStorage.getItem("role");
        // console.log(this.Property);
    },

    methods : {
        ApproveQuote(){
            axios.get('/api/client-quote-approve/' + this.Property.id).then((response) => {
                // console.log(response);
               this.$emit('closeEvent', "Quote has been approved");
               $("#PropertyView").modal('hide');
            });
        }
    }

}

</script>

<style scoped>

</style>
