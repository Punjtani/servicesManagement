<template>
    <div>
       <div v-if="loading">
            <loader></loader>
        </div>
        <!-- start client Prices data -->
        <div class="client_prices_area"  ref="document">
            <div class="inline_buttonss"  >
                <div class="row mb-3">

                    <div class="col-sm-6 col-8"><h1 class="h4 heading_text">View Property Prices</h1></div>
                    <Navigation></Navigation>
                    <div class="col-12 col-sm-12 text-end justify-content-end align-items-center" v-if="property">
                        <button @click="exportToPDF" data-html2canvas-ignore="true" class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600">Download Pricing</button>
                    </div>
                </div>
            </div>
            <!-- top block -->
            <div class="client_prices_block_top view_pricing_page_header">
                <!-- head area -->
                <div class="head_area">
                    <div class="row">
                        <div class="col">
                            <h6>
                                Apartments Type
                            </h6>
                        </div>

                        <div v-if="item.sub_services.length>0 && removeFirstWord(item.category) != 'Refinishing' && removeFirstWord(item.category) != 'Flooring'" v-for="(item , index) in services" :key="item.id" class="col">
                            <h6>
                                {{removeFirstWord(item.category)}}
                            </h6>
                        </div>
                    </div>
                </div>
                <!-- end head area -->
                <!-- body area start -->
                <div class="body_area for_resposive_before">
                    <!-- row start -->
                    <div class="row" v-for="(item , index) in appartmentTypes" v-if="item.type.toLowerCase() != 'project'" :key="item.id">
                        <div class="col">
                            <h6  data-title="Apartments Type" >
                                {{item.type}}
                            </h6>
                        </div>
                        <div class="col" v-if="item1.sub_services.length>0 &&  removeFirstWord(item1.category) != 'Refinishing' && removeFirstWord(item1.category) != 'Flooring'" v-for="(item1 , index1) in services" :key="item1.id">
                            <h6 class="bold_item" :data-title="removeFirstWord(item1.category)" v-if="item1[item.type]">
                                ${{(Math.round(item1[item.type] * 100) / 100).toFixed(2)}}
                            </h6>
                            <h6 class="bold_item" :data-title="removeFirstWord(item1.category)" v-else>
                                --
                            </h6>
                        </div>
                        <!--<div class="col last-item">
                            <h6 class="bold_item">
                                650 -­ 800
                            </h6>
                        </div>-->
                    </div>
                    <!-- row end  -->

                </div>

                <!-- body area end  -->
            </div>
            <!-- top block end  -->
            <!--<div class="info_area">
                <div class="row">
                    <div class="col-md-12">
                        <p v-for="(note,index) in notes">{{note.description}}</p>
                    </div>
                </div>
            </div>-->
            <!-- info area end  -->
            <!-- bottom blocks -->
            <div class="botton_blocks">
                <div class="row grid" >
                    <div class="col-md-6 grid-item" v-if="" v-for="index in services.map((_,i)=>i)" :key="index">
                        <div class="left_block">
                            <!-- block start -->
                            <div class="inner_block">
                                <div v-if="services[index].sub_services.length > 0" class="head_area ">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6>{{removeFirstWord(services[index].category)}}
                                                <!--<a v-if="services[index].notes && services[index].notes.length > 0" class="link_view_notes" data-toggle="collapse" v-bind:href="'#notesCollapse-'+services[index].id" role="button" aria-expanded="false" aria-controls="notesCollapse">view notes <svg class="sidebaricon_chevron"><use xlink:href="#right-chevron"></use></svg></a>-->
                                            </h6>
                                        </div>
                                        <!--<div v-if="services[index].notes && services[index].notes.length > 0" class="col-md-12">
                                            <div class="collapse notes_box_colleps" v-bind:id="'notesCollapse-'+services[index].id" >
                                                <div v-for="(note) in services[index].notes" class="">
                                                    <p>{{note.description}}</p>
                                                </div>
                                            </div>
                                        </div>-->

                                    </div>
                                </div>
                                <div class="body_area">

                                    <div class="row" v-for="(item1 , index1) in services[index].sub_services" :key="item1.id">
                                        <div class="col-md-8 col-8">
                                            <h6>
                                                {{item1.name}}
                                            </h6>
                                        </div>
                                        <div class="col-md-4 col-4 last-item" v-if="item1.is_independent == 0">
                                            <h6 class="bold_item">${{getMinVal(item1.client_pricing)}} - ${{getMaxVal(item1.client_pricing)}}</h6>
                                        </div>
                                         <div class="col-md-4 col-4 last-item" v-else>
                                            <h6 class="bold_item">${{item1.client_pricing[0].price}} {{item1.client_pricing[0].price_tag ? item1.client_pricing[0].price_tag : ''}}</h6>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- block end  -->
                        </div>
                    </div>

                </div>


            </div>
            <!-- botton blocks end  -->
        </div>
        <!-- end client prices -->
    </div>
</template>
<script>
import axios from "axios";
import {required, minLength, maxLength, email, url, decimal} from 'vuelidate/lib/validators'
import Loader from "../../LoaderSpinner";
import roleData from '../../../data.js';
import html2pdf from 'html2pdf.js'
import Masonry from "masonry-layout";
import $ from "jquery";
import Navigation from "../../../components/Navigation";
export default {
    name: "ClientView",
    components: {
        'Loader': Loader,
        'html2pdf': html2pdf,
        'Navigation': Navigation
    },
    data() {
        return {

            loading: false,
            services: [],
            appartmentTypes: [],
            property:'',
            property_id:this.$route.params.id,
            clientPropertyPricing: [],
            dependent : false,
            independent: false,
            closeFlag:false,
            notes:[],
            // clientId:"",
            role:"",
            roleData: roleData,
            client_id:"",
        }
    },

    mounted() {
        this.loading = true;
        this.getRecord(this.property_id);
        this.role = localStorage.getItem("role");
    },
    methods: {
        getRecord(id) {
            axios.get('/api/client-pricing-view/' + id)
                .then((response) => {
                    // // console.log('pricing', response.data);
                    this.services = response.data.allServices;

                    this.services.forEach(service=>{
                        service.sub_services.forEach(data=>{
                        if (data.client_pricing){
                            data.client_pricing.forEach(clientPrice=>{
                                clientPrice.price = (Math.round(clientPrice.price * 100) / 100).toFixed(2);

                            });
                        }

                    });
                    });
                    this.clientPropertyPricing = response.data;
                    this.appartmentTypes = response.data.appartmentTypes;
                    this.property = response.data.property;
                    this.notes = response.data.notes;
                    this.client_id = response.data.property.client.id;
                    this.loading = false;

                }).then(() => this.loadScript());


        },
        exportToPDF () {
            html2pdf(this.$refs.document, {
                margin: 0.5,
                filename: 'Pricing.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { dpi: 192, letterRendering: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' },
                pagebreak: { mode:  'avoid-all' }
            })
        },
        loadScript(){
            var grid = document.querySelector('.grid');
            var msnry = new Masonry( grid, {
                            // options...
                            // columnWidth: '.grid-sizer',
                            itemSelector: '.grid-item',
                            percentPosition: true
                         });
            // console.log("loaded");
        },
        getMinVal(data){
            const min = Math.min(...data.map(o => o.price));
            return (Math.round(min * 100) / 100).toFixed(2);
        },
        getMaxVal(data){
            const max = Math.max(...data.map(o => o.price));
            return (Math.round(max * 100) / 100).toFixed(2);
        },
        removeFirstWord(str) {
            str = str.replace("PU ", "");
            str = str.replace("PT ", "");
            str = str.replace("CA ", "");
            str = str.replace("RF ", "");
            str = str.replace("JA ", "");
            str = str.replace("FL ", "");
            return str;
        },

    }
}

</script>
<style scoped>

</style>
