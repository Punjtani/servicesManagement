<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="dashboard_content_area">
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">Unit Types</h1>
                </div>
                <Navigation></Navigation>
            </div>
            <div id="success-alert" class="alert alert-success" v-if="successmsg !== ''">
                <strong>{{ successmsg }}</strong>
            </div>

            <div class="alert alert-danger" v-if="deletemsg !== ''">
                <strong>{{ deletemsg }}</strong>
            </div>
            <!-- ================ -->
            <div class="table_area">
                <div id="appartment_type_table" class="no_more_tables table_to_be_used mg_top_1rem">
                    <div class="custom__search">
                        <div class="row g-0">
                            <div class="col-sm-8 col-md-8 col-lg-6">
                                <SingleFieldSearch @changetable="changeTableAppartment('', true)"
                                    :table_data="table_appartment"></SingleFieldSearch>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-6 text-sm-end">
                                <button type="button"
                                    class="btn_big_medium btn btn_medium btn_orange border_radius_5 text-uppercase font_14 font_weight_600 mg_top_on_mobile_10"
                                    data-bs-toggle="modal" data-bs-target="#appartmentTypesAdd">Add new
                                </button>
                            </div>
                        </div>
                    </div>
                    <table class="table draggable">
                        <thead>
                            <tr>
                                <th class="header-sort min_max_80" :class="[sortBy == 'id' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('id')">Apartment #</th>
                                <th class="header-sort" :class="[sortBy == 'type' && sortOrder == 'desc'  ? 'desc' : '']" @click="sorting('type')">Unit Types</th>
                                <th class="text_end_only_desktop">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="appartment_types.length > 0">
                            <sortable v-for="(item, index) in appartment_types" :key="item.id" :index="index"
                                v-model="dragData" drag-direction="vertical" replace-direction="vertical"
                                @sortend="sortend($event, appartment_types)">
                                <tr v-if="!loading">
                                    <td data-title="Apartment #">{{ item.id }}</td>
                                    <td data-title="Name">{{ item.type }}</td>
                                    <td data-title="Action" class="text_end_only_desktop">
                                        <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" viewBox="0 0 16 16"
                                                class="bi bi-three-dots-vertical no_action">
                                                <path
                                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                </path>
                                            </svg>
                                        </a>
                                        <div class="dropdown-menu custom-action-dropdown"
                                            aria-labelledby="dropdownMenuButton">

                                            <a href="#"
                                                class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                data-bs-toggle="modal" data-bs-target="#appartmentTypesEdit"
                                                @click="editIdMethodAppartment(item.id)">Edit</a>
                                            <a data-bs-toggle="modal" data-bs-target="#deleteModel"
                                                class="action_link table_btn_links font_12 font_weight_700 text_uppercase dropdown-item custom-action-bar"
                                                href="#"
                                                @click="deleteRequest(item.id, 'Apartment', 'appartment-type')">Delete </a>

                                        </div>
                                    </td>
                                </tr>
                            </sortable>
                        </tbody>
                    </table>
                    <div class="inline_buttonss text-end">
                        <button @click="saveSort"
                            class="btn btn_medium btn_orange mg_top_14 border_radius_5 text-uppercase font_14 font_weight_600">Save</button>
                    </div>
                    <div v-if="!loading && appartment_types.length <= 0">
                        <EmptySearch></EmptySearch>
                    </div>
                    <Pagination v-if="appartment_types.length > 0 && table_appartment.last_page > 1"
                        @changetable="changeTableAppartment" :table_data="table_appartment"></Pagination>

                </div>

                <!-- =============== -->
            </div>
            <div class="modal fade custom_base_model custom_base_model_small" id="appartmentTypesAdd" tabindex="-1"
                aria-labelledby="appartmentTypesAddLabel" aria-hidden="true" data-bs-backdrop="static">
                <add-appartment-type @closeEvent="forceRerender"></add-appartment-type>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="appartmentTypesEdit" tabindex="-1"
                aria-labelledby="appartmentTypesEditLabel" aria-hidden="true" data-bs-backdrop="static">
                <EditAppartmentType @closeEvent="forceRerender" :appartmentTypeId="appartmentTypeId"></EditAppartmentType>
            </div>

            <div class="modal fade custom_base_model custom_base_model_small" id="deleteModel" tabindex="-1"
                aria-labelledby="deleteModel" aria-hidden="true" data-bs-backdrop="static">
                <DeleteModel @closeEvent="deleteRecord" :type="deleteTitle"></DeleteModel>
            </div>
        </div>
    </div>
</template>
<script>
import AddAppartmentType from "./Add";
import EditAppartmentType from "./Edit";
import DeleteModel from "../deleteModel";
import Loader from "../LoaderSpinner";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import Sortable from 'vue-drag-sortable'
import axios from "axios";
import Navigation from "../../components/Navigation";
export default {
    components: {
        'AddAppartmentType': AddAppartmentType,
        'EditAppartmentType': EditAppartmentType,
        'DeleteModel': DeleteModel,
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'Sortable': Sortable,
        'Navigation': Navigation
    },
    data() {
        return {
            successmsg: "",
            deletemsg: "",
            appartment_types: [],
            service_types: [],
            has_error: false,
            loading: true,
            appartmentTypeId: "",
            serviceTypeId: "",
            deleteTitle: "",
            deleteId: "",
            deleteApi: "",
            dragData: {},
            table_appartment: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },
            sortOrder: "",
            sortBy: "sort",

        }
    },
    mounted() {
        this.getAppartmentTypes();
    },
    methods: {
        sorting(sortby) {
           this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.getAppartmentTypes();
        },
        getAppartmentTypes() {
            this.loading = true;
            axios.get('/api/appartment-type?page=' + this.table_appartment.current_page + '&size=' + this.table_appartment.per_page + '&search=' + this.table_appartment.search + '&sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy)
                .then((response) => {
                    this.table_appartment.last_page = response.data.appartment_types.last_page;
                    this.appartment_types = response.data.appartment_types.data;
                    this.loading = false;
                });
        },

        forceRerender(msg) {
            this.appartmentTypeId = "";
            this.successmsg = "";
            // console.log(msg);

            if(msg!="no-refresh")
            {
                this.refreshRecord();
                this.successmsg = msg;
            }

            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },

        refreshRecord() {
            this.getAppartmentTypes();
        },
        editIdMethodAppartment(id) {
            this.appartmentTypeId = id;
        },

        deleteRequest(id, title, api) {
            this.deleteId = id;
            this.deleteTitle = title;
            this.deleteApi = api;
        },
        sortend(e, list) {
            const { oldIndex, newIndex } = e
            this.rearrange(list, oldIndex, newIndex)
        },
        rearrange(array, oldIndex, newIndex) {
            if (oldIndex > newIndex) {
                array.splice(newIndex, 0, array[oldIndex])
                array.splice(oldIndex + 1, 1)
            }
            else {
                array.splice(newIndex + 1, 0, array[oldIndex])
                array.splice(oldIndex, 1)
            }
        },
        deleteRecord(val) {
            if (val == 0) {
                this.deleteId = "";
                this.deleteTitle = "";
                this.deleteApi = "";
                return
            }
            axios.delete('/api/' + this.deleteApi + '/' + this.deleteId)
                .then((response) => {
                    this.deletemsg = response.data.msg;
                    if (response.data.error) {
                        this.deletemsg = response.data.error
                    }
                    this.refreshRecord();
                });
            setTimeout(() => {
                this.deletemsg = "";
            }, 3000);
        },

        changeTableAppartment(val, search = false) {
            this.table_appartment.current_page = val.current_page;
            if (search) {
                this.table_appartment.current_page = 1;
            }
            this.getAppartmentTypes();
        },
        saveSort() {
            // console.log(this.appartment_types);
            axios.post('/api/appartment-type/save', { 'appartment': this.appartment_types })
                .then(response => {
                    this.getAppartmentTypes()
                })
                .catch(err => {
                    this.has_error = true;
                })

        }



    }
}
</script>
