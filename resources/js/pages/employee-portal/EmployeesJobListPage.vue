<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="dashboard_content_area" v-if="!loading">

            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="h4 heading_text">My Jobs</h1>
                </div>
                <Navigation></Navigation>

            </div>
            <div class="row mg_top_bot_40_on_desktop row_spacing_2_on_mobiles">
                <div class="col-3 col-sm-3 col-md-3">
                    <button v-on:click="jobfilter = 'today'"
                        class="btn_big_medium btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">Today's
                        Jobs</button>
                </div>
                <div class="col-3 col-sm-3 col-md-3">
                    <button v-on:click="jobfilter = 'all-jobs'"
                        class="btn_big_medium btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">All
                        Jobs</button>
                </div>
                <div class="col-3 col-sm-3 col-md-3">
                    <button v-on:click="jobfilter = 'completed'"
                        class="btn_big_medium btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">Completed</button>
                </div>
                <div class="col-3 col-sm-3 col-md-3">
                    <button v-on:click="jobfilter = 'not-completed'"
                        class="btn_big_medium btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">Not
                        Completed</button>
                </div>
                <div class="col-3 col-sm-3 col-md-3 mt2">
                    <button v-on:click="jobfilter = 'overdue'"
                        class="btn_big_medium btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">Overdue
                        Jobs</button>
                </div>
                <div class="col-3 col-sm-3 col-md-3 mt2">
                    <button v-on:click="jobfilter = 'scheduled'"
                        class="btn_big_medium btn  btn_orange border_radius_5  font_14_on_desktop_and_9_mobiles font_weight_600 btn__Block btn_less_pd_on_mobile">Scheduled
                        Jobs</button>
                </div>


            </div>

            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ================================= -->

            <div class="table_area mg_bot_30_on_all_devices jobs_table">
                <div class="no_more_tables table_to_be_used table_with_widths add_space">
                    <table class="table ">
                        <thead class="thead_new">

                            <tr>
                                <th class="header-sort" :class="[sortBy == 'id' && sortOrder == 'desc' ? 'desc' : '']"
                                    @click="sorting('id')">Job # </th>
                                <th>Service Type</th>
                                <th>Property</th>
                                <th>Unit #</th>
                                <th>Unit Type</th>
                                <th class="header-sort"
                                    :class="[sortBy == 'apartment_status' && sortOrder == 'desc' ? 'desc' : '']"
                                    @click="sorting('apartment_status')">Unit Status</th>
                                <th class="header-sort"
                                    :class="[sortBy == 'scheduled_date' && sortOrder == 'desc' ? 'desc' : '']"
                                    @click="sorting('scheduled_date')">Scheduled Date</th>
                                <th class="header-sort"
                                    :class="[sortBy == 'scheduled_time' && sortOrder == 'desc' ? 'desc' : '']"
                                    @click="sorting('scheduled_time')">Scheduled Time</th>
                                <th class="text-md-end">Action</th>
                                <!-- <th class="tb_width_4">Action</th>-->
                            </tr>
                        </thead>
                        <tbody class="tbody_new" v-if="jobs.length > 0" v-for="(item, index) in jobs" :key="item.id">
                            <tr>
                                <td data-title="id"> {{ item.job_id }} </td>
                                <td data-title="Service">
                                    <span class="job__dot job_red__dot" v-if="item.status == 'red'"></span>
                                    <span class="job__dot job_green__dot" v-if="item.status == 'green'"></span>
                                    <span class="job__dot job_orange__dot" v-if="item.status == 'yellow'"></span>
                                    {{ item.service ? removeFirstWord(item.service.category) : '' }}
                                </td>
                                <td data-title="Property">{{ item.job.property.title }}</td>
                                <td data-title="Unit #">{{ item.job.apartment_number }}</td>
                                <td data-title="Unit Type">{{ item.job?.apartment_type?.type }}</td>
                                <td data-title="Unit Status" class="text_capitalize">{{ item.job.apartment_status }}</td>
                                <td data-title="Scheduled Date" v-if="item.scheduled_date">{{ item.scheduled_date |
                                    formatDate }}</td>
                                <td data-title="Scheduled Date" v-else="item.scheduled_date">--</td>
                                <td data-title="Time">
                                    <span v-if="item.anytime == 1">AnyTime</span>
                                    <span v-else-if="item.anytime == 0 && item.scheduled_time">{{ item.scheduled_time |
                                        formatTime }}</span>
                                    <span v-else>--</span>
                                </td>
                                <td class="text-md-end" data-title="Action">
                                    <!-- <router-link   v-bind:to="'/view-my-job/' + item.id" class="table_btn_link font_12 font_weight_700 text_uppercase" href="">View Detail</router-link> -->
                                    <a href="#" id="dropdownMenuButton" class="no_action custom-action-bar-icon"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            viewBox="0 0 16 16" class="bi bi-three-dots-vertical no_action">
                                            <path
                                                d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                            </path>
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu custom-action-dropdown" aria-labelledby="dropdownMenuButton">
                                        <router-link v-bind:to="'/view-my-job/' + item.id"
                                            class="table_btn_link font_12 font_weight_700 text_uppercase" href="">View
                                            Detail</router-link>
                                    </div>
                                </td>
                            </tr>

                            <!-- ===================== -->
                            <!-- ===================== -->
                            <!-- ===================== -->
                        </tbody>
                    </table>
                    <div v-if="!loading && jobs.length <= 0">
                        <EmptySearch></EmptySearch>
                    </div>
                </div>
            </div>
            <p><span class="bottom_dots"> <span class="job__dot job_green__dot"></span>Completed</span><span
                    class="bottom_dots"> <span class="job__dot job_red__dot"></span>Pending</span> <span
                    class="job__dot job_orange__dot"></span>In Progress</span></p>
            <!-- ================================= -->
            <!-- ================================= -->
            <!-- ============================================================================================= -->
        </div>
    </div>
</template>
<script>


import Loader from "../LoaderSpinner";

import axios from "axios";
import Pagination from "../Pagination";
import SingleFieldSearch from "../SingleFieldSearch";
import EmptySearch from "../EmptySearch";
import Navigation from "../../components/Navigation";

export default {
    components: {
        'Loader': Loader,
        'Pagination': Pagination,
        'SingleFieldSearch': SingleFieldSearch,
        'EmptySearch': EmptySearch,
        'Navigation': Navigation
    },
    data() {
        return {
            successmsg: "",
            deletemsg: "",
            has_error: false,
            loading: true,
            jobfilter: "",
            jobId: "",
            subServiceId: "",
            jobs: [],
            table_jobs: {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },
            sortOrder: "desc",
            sortBy: "id",
        }
    },
    mounted() {

        this.refreshRecord();
    },
    watch: {
        '$route.params.service_id'(val) {
            if (!val) {
                this.formReset();
            }
        },
        'jobfilter'(val) {
            this.refreshRecord();
        },
    },
    methods: {
        refreshRecord() {
            this.loading = true;
            this.getJobs();

        },
        sorting(sortby) {
            this.sortOrder = this.sortBy != sortby || this.sortOrder === "asc" ? "desc" : "asc";
            this.sortBy = sortby;
            this.refreshRecord();
        },
        getJobs() {
            if (this.$route.params.job_id) {
                this.jobId = this.$route.params.job_id;
            }
            axios.get('/api/employee/jobs?' + 'sort_order=' + this.sortOrder + '&sort_by=' + this.sortBy + '&jobId=' + this.jobId + '&jobfilter=' + this.jobfilter)
                .then((response) => {
                    // this.table_jobs.last_page=response.data.jobs.last_page;
                    this.jobs = response.data.jobs;
                    this.loading = false;

                    this.jobServicesStatus();
                });
        },
        jobServicesStatus() {
            this.jobs.forEach(job => {
                var service_jobs = this.jobs.filter(service_job => (job.service_id == service_job.service_id && job.job_id == service_job.job_id))
                if (service_jobs) {
                    var red = service_jobs.find(job => (job.end_date == null && job.start_date == null))
                    var green = service_jobs.find(job => (job.end_date != null))
                    var yellow = service_jobs.find(job => (job.end_date == null && job.start_date != null))
                    if (red) {
                        job.status = 'red';
                    } else if (yellow) {
                        job.status = 'yellow';
                    } else {
                        job.status = 'green';
                    }
                }


            });
            // console.log(this.jobs)
        },
        forceRerender(msg) {
            this.successmsg = "";
            // console.log(msg);
            this.refreshRecord();
            this.successmsg = msg;
            setTimeout(() => {
                this.successmsg = "";
            }, 3000);
        },
        formReset() {
            this.loading = true;
            this.table_jobs = {
                current_page: 1,
                per_page: 25,
                last_page: 0,
                search: "",
            },
                this.jobId = "";
            this.subServiceId = "";
            this.$forceUpdate();
            this.getJobs()
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

<style scoped>.job_orange__dot {
    background-color: #FFA500;
}</style>
