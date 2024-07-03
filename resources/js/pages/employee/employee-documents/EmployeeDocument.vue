<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading">
            <div class="dashboard_content_area">
                <div class="row">
                    <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Employee Documents</h1></div>
                    <Navigation></Navigation>

                </div>
                <div id="success-alert" class="alert alert-success" v-if="successmsg!==''">
                    <strong>{{ successmsg }}</strong>
                </div>
                <div id="success-alert" class="alert alert-danger" v-if="deletemsg!==''">
                    <strong>{{ deletemsg }}</strong>
                </div>
                <div id="success-alert" class="alert alert-danger" v-if="errormsg!==''">
                    <strong>{{ errormsg }}</strong>
                </div>
                <div class="custom__search">
                    <div class="row g-0">
                        <div class="col-sm-8 col-md-8 col-lg-6">
                            <SingleFieldSearch @changetable="changeTableDocuments('',true)"
                                                    :table_data="table_documents"></SingleFieldSearch>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 text-sm-end">
                            <button type="button"
                                    @click="AddDocumentButton"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                    data-bs-toggle="modal" data-bs-target="#addDocument">Add new
                            </button>
<!--                            <router-link to="/employees" class="btn_big_medium btn btn_medium btn_blue border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10">Back</router-link>-->
                        </div>
                    </div>
                </div>
                <div class="no_more_tables table_to_be_used">
                        <table class="table table_with_upper_heading">
                            <thead>
                            <tr>
                                <th :style="{ textTransform: 'none' }" >Document Name</th>
                                <th :style="{ textTransform: 'none' }">Document Expiration Date</th>
                                <th :style="{ textTransform: 'none' }">Document ID</th>
                                <th :style="{ textTransform: 'none' }" class="text_end_only_desktop">Action</th>
                            </tr>
                            </thead>
                            <tbody v-if="all_documents.length > 0">
                                <tr v-for="item in all_documents">
                                    <td data-title="Document Name">{{item.document_title}}</td>
                                    <td v-if="item.expiry_date" data-title="Document Expiry">{{item.expiry_date | formatDate}}</td>
                                    <td v-if="!item.expiry_date" data-title="Document Expiry">--</td>
                                    <td data-title="Document ID">{{item.document_number}}</td>
                                    <td data-title="Action" class="text_end_only_desktop">
                                        <!-- <a target="_blank" class="table_btn_link font_12 font_weight_700 text_uppercase" :href="'/'+item.file_name">Preview</a> -->
                                        <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path></svg>
                                    </a>
                                    <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">

                                        <a href="#" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"  @click="previewDocument(item.file_name)"  data-bs-toggle="modal" data-bs-target="#viewDocument">Preview</a>

                                        <a href="#" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" @click="editDocument(item.id)" data-bs-toggle="modal" data-bs-target="#addDocument">Edit</a>

                                        <a href="#" class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar" @click="deleteRequest(item.id)" data-bs-toggle="modal" data-bs-target="#deleteModel" >Delete</a>

                                    </div>
<!--
                                        <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase"  @click="previewDocument(item.file_name)"  data-bs-toggle="modal" data-bs-target="#viewDocument">Preview</a>

                                        <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase" @click="editDocument(item.id)" data-bs-toggle="modal" data-bs-target="#addDocument">Edit</a>

                                        <a href="#" class="table_btn_link font_12 font_weight_700 text_uppercase" @click="deleteRequest(item.id)" data-bs-toggle="modal" data-bs-target="#deleteModel" >Delete</a>
                                 -->
                                   </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="!loading && all_documents.length <=0">
                            <EmptySearch></EmptySearch>
                        </div>
                        <Pagination v-if="all_documents.length > 0 && table_documents.last_page > 1" @changetable="changeTableDocuments"
                                :table_data="table_documents"></Pagination>
                    </div>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                    aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                    <DeleteModel @closeEvent="deleteRecord"></DeleteModel>
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="addDocument" tabindex="-1"
                 aria-labelledby="addDocumentLabel" aria-hidden="true" data-bs-backdrop="static">
                <AddDocument :documentId="documentId" @closeEvent="forceRerender" @popupCloseEvent="popupCloseEvent" :type="type"></AddDocument>
            </div>
            <div class="modal fade custom_base_model custom_base_model_large" id="viewDocument" tabindex="-1"
                 aria-labelledby="addDocumentLabel" aria-hidden="true" data-bs-backdrop="static">
                <ViewDocument :documentPath="documentPath" :documentType="documentType" @previewCloseEvent="previewCloseEvent"></ViewDocument>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Loader from "../../LoaderSpinner";
import Pagination from "../../Pagination";
import EmptySearch from "../../EmptySearch";
import DeleteModel from "../../deleteModel";
import AddDocument from "./Add";
import SingleFieldSearch from "../../SingleFieldSearch";
import UploadFileComponent from "../../../components/UploadFileComponent";
import {required} from 'vuelidate/lib/validators';
import ViewDocument from './preview.vue';
import Navigation from "../../../components/Navigation";
export default {
    components: {
        'Loader': Loader,
        'Pagination': Pagination,
        'EmptySearch':EmptySearch,
        'AddDocument':AddDocument,
        UploadFileComponent: UploadFileComponent,
        'DeleteModel': DeleteModel,
        // 'EmptySearch':EmptySearch,
        'SingleFieldSearch':SingleFieldSearch,
        'ViewDocument':ViewDocument,
        'Navigation':Navigation,
    },
    data() {
        return {
            acceptTypes: ['image/png','image/jpeg','.pdf'],
            has_error: false,
            loading: false,
            successmsg: "",
            errormsg:"",
            deletemsg:"",
            deleteId:"",
            id: this.$route.params.id,
            document_form:{
                title: "",
                expiry: "",
                filePath:"",
            },
            all_documents:[],
            table_documents:{
                current_page : 1,
                per_page : 25,
                last_page: 0,
                search: "",
            },
            filenames:null,
            submit: false,
            documentId:"",
            type:"",
            documentPath:"",
            documentType:"",
        }
    },
    validations: {
        document_form: {
            title: {
                required,
            },
            expiry: {
                required,
            },
        }
    },
    mounted() {
        this.loading=true;
        this.getRecords();

    },
    methods: {
        getRecords() {
            this.loading=true;
            axios.get('/api/get-employee-documents/'+this.id+'?page='+this.table_documents.current_page+'&size='+this.table_documents.per_page + '&search='+this.table_documents.search)
                .then((response) => {
                    // console.log(response.data);
                    this.table_documents.last_page=response.data.documents.last_page;
                    this.all_documents = response.data.documents.data;
                    this.loading=false;
                });
        },
        deleteRequest(id){
            this.deleteId = id;
        },
        deleteRecord(val){
            if (val == 0) {
                this.deleteId = "";
                return
            }
            this.loader = true;
            axios.delete('/api/delete-employee-documents/'+this.deleteId)
                .then((response) => {
                    this.deletemsg = response.data.msg;
                    if(response.data.error){
                        this.deletemsg =response.data.error
                    }
                    this.getRecords();
                    this.loader = false;
                });
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        },
        changeTableDocuments(val, search = false){
            this.table_documents.current_page = val.current_page;
            if(search){
                this.table_documents.current_page = 1;
            }
            this.getRecords();
        },
        forceRerender(msg,error = ''){
            this.documentId = "";
            if(error != ''){
                this.errormsg = msg;
                setTimeout(() => {
                    this.errormsg = "";
            }, 3000);
            }else{
                this.successmsg = msg;
                setTimeout(() => {
                this.successmsg = "";
            }, 3000);
            }
            this.getRecords();
        },
        editDocument(id){
            this.documentId = id;
            this.type = "Edit";
        },
        AddDocumentButton(){
            this.type = "Add";
        },
        popupCloseEvent(){
            this.documentId = "";
        },
        previewDocument(path){
            var ext = path.substring(path.lastIndexOf(".")+1);
            if(ext == 'pdf'){
                this.documentType = 'pdf';
            }
            this.documentPath = path;
        },
        previewCloseEvent(){
            this.documentPath="";
            this.documentType="";
        }
    }

}
</script>
