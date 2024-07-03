<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <div class="row align-items-center">
                <div class='col-sm-6'>
                   <h1 class="h4 heading_text">Refinish Specs</h1>
                </div>
                <!-- v-if="refinish_spec.length > 0" -->
                 <div class='col-sm-6 text-end'>
                    <Navigation></Navigation>
                </div>
            </div>
            <div class="table_area">
                <div class="no_more_tables table_to_be_used mg_top_1rem">
                    <table class="table" >
                        <thead>
                            <tr>
                                <th>Primer Color</th>
                                <th>Multispect Color</th>
                            </tr>
                        </thead>
                        <tbody v-if="refinish_spec.length > 0">
                            <tr v-for="(item , index) in refinish_spec" :key="item.id">
                                <td data-title="Primer Color"><span v-if="item.paint_color && item.paint_color.name">{{ item.paint_color.name }}</span><span v-else>--</span></td>
                                <td data-title="Multispect Color"><span v-if="item.multi_spect_color && item.multi_spect_color.name">{{ item.multi_spect_color.name }}</span><span v-else>--</span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && refinish_spec.length <=0">
                        <EmptySearch></EmptySearch>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import {required, minLength, maxLength, email, url, decimal} from 'vuelidate/lib/validators'
import Loader from "../../LoaderSpinner";
import EmptySearch from "../../EmptySearch";
import roleData from '../../../data.js';
import Navigation from '../../../components/Navigation';
export default {
    name: "RefinishSpecs",
    components: {
        'Loader': Loader,
        'EmptySearch':EmptySearch,
         'Navigation':Navigation
    },
    data() {
        return {
            loading: false,
            refinish_spec: [],
            property_id: this.$route.params.id,
            roleData:roleData,
            role: "",
            client_id:""
        }
    },
    mounted() {
        this.loading = true;
        this.getRecord(this.property_id);
        this.role = localStorage.getItem("role");
    },
    methods: {
        getRecord(id) {
            axios.get('/api/client-refinish-spec/' + id + "/edit")
            .then((response) => {
                // console.log(response);
                this.refinish_spec = response.data.ClientRefinishSpec;
                if(this.refinish_spec.length>0){
                    this.client_id = this.refinish_spec[0].property.client_id;
                }
                this.loading = false;
            });
        },
    }
}
</script>

<style scoped>

</style>

