<template>
    <div>
        <div v-if="loading || filterLoader">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">
            <h1 class="h4 heading_text">Paint Specs</h1>
            <div class="row align-items-center" >
                <div class="col-sm-6">
                    <div class="form-group">
                        <label v-if="paint_spec.length > 0">Paint Provider: {{paint_spec[0].paint_provider}}</label>
                    </div>
                </div>
                <div class='col-sm-6 text-end d-flex justify-content-end'>
                    <p @click="$router.go(-1)" class="h3 mb-2 cursor-pointer history_back_btn" v-bind:class="{ 'disable-backward': !canGoBack }" ><b-icon icon="arrow-left-circle-fill"></b-icon></p>
                    <p @click="$router.go(1)"  class="h3 mb-2 cursor-pointer history_farward_btn" v-bind:class="{ 'disable-forward': !canGoForward }"><b-icon icon="arrow-right-circle-fill"></b-icon></p>
                </div>
            </div>
            <div class="table_area">
                <div class="no_more_tables table_to_be_used mg_top_1rem">
                    <table class="table" >
                        <thead>
                            <tr>
                                <th>Surface</th>
                                <th>Manufacturer</th>
                                <th>Color</th>
                                <th>Finish</th>
                                <th>Grade</th>
                                <th>Coat</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody v-if="paint_spec.length > 0">
                            <tr v-for="(item , index) in paint_spec" :key="item.id">
                                <td data-title="Surface"><span v-if="item.paint_surface">{{ item.paint_surface.name }}</span><span v-else></span></td>
                                <td data-title="Manufacturer"><span v-if="item.paint_manufacturer">{{ item.paint_manufacturer.name }}</span><span v-else></span></td>
                                <td data-title="Color"><span v-if="item.paint_color">{{ item.paint_color.name }}</span><span v-else></span></td>
                                <td data-title="Finish"><span v-if="item.paint_finish">{{ item.paint_finish.name }}</span><span v-else></span></td>
                                <td data-title="Grade"><span v-if="item.paint_grade">{{ item.paint_grade.name }}</span><span v-else></span></td>
                                <td data-title="Coat" class="min_max_80"><span v-if="item.coats">{{ item.coats }}</span><span v-else></span></td>
                                <td data-title="Method"><span v-if="item.paint_method">{{ item.paint_method.name }}</span><span v-else></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!loading && paint_spec.length <=0">
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

export default {
    name: "PaintSpecs",
    components: {
        'Loader': Loader,
        'EmptySearch':EmptySearch,
    },
    data() {
        return {
            loading: false,
            paint_spec: [],
            filterLoader:false,
            property_id: this.$route.params.id,
            roleData:roleData,
            role: "",
        }
    },
    computed: {
        canGoForward() {
           let canGoForw = false;
try {
    canGoForw =  window.navigation.canGoForward;
} catch (error) {
      if (window.history && typeof window.history.goForward === 'function') {         canGoForw = window.history.goForward();
    } else if (window.history && (window.history.length > 1 && window.history.length > (history.state && history.state.index || 0))) {
        canGoForw = true;
    } else {
        canGoForw = false;
    }
}
return canGoForw;
        },
        canGoBack() {
            let canGo = false;
try {
    canGo =  window.navigation.canGoBack;
} catch (error) {
    if (window.history && typeof window.history.goBack === 'function') {
        canGo =  true;
    } else if (window.history && window.history.length > 1) {
        canGo = true;
    } else {
        canGo = false;
    }
}
return canGo;
        }
    },
    mounted() {
        this.loading = true;
        this.getRecord(this.property_id);
        this.role = localStorage.getItem("role");
    },
    methods: {
        getRecord(id) {
            this.filterLoader = true;
            axios.get('/api/client-paint-spec/' + id + "/edit")
            .then((response) => {
                // console.log(response);
                this.paint_spec = response.data.clientPaintSpec;
                if(this.paint_spec.length > 0){
                    this.client_id = this.paint_spec[0].property.client_id;
                }
                this.loading = false;
                this.filterLoader = false;
            });
        },
    }
}
</script>

<style scoped>

</style>

