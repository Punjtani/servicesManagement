<template>
    <div>
        <div v-if="loading">
            <loader></loader>
        </div>
        <div v-if="!loading" class="dashboard_content_area">

            <div class="row">
                <div class="col-sm-6 col-8"><h1 class="h4 heading_text">Calendar</h1></div>
                <Navigation></Navigation>

            </div>
            <div class="card p-3">
                <div>
                    <div class="form-group" >
                                <label>Choose Date</label>
                                <!--<datepicker
                                    placeholder = "Select date"
                                    format="MM/dd/yyyy"
                                    input-class="form-control"
                                    v-model.trim="selectedDate"
                                    @selected="handleDateChange()"
                                    >
                                </datepicker>-->
                                <datepicker
                                  placeholder = "Select date"
                                  format="MM-DD-YYYY"
                                  input-class="form-control"
                                  v-model.trim="selectedDate"
                                  :clearable="false"
                                   @change="handleDateChange()"
                                  >
                                </datepicker>
                    </div>
                </div>
                <FullCalendar ref="fullCalendar" :options="calendarOptions"  :plugins="calendarPlugins">
                    <template v-slot:eventContent="arg">
                        <div class="wrap_popup">
              <div class="p-1" :id="'tooltip-target'+arg.event.id" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;width: 95%;">
                <b>{{ arg.event.title }}</b><br />
                <b style="color:black">{{ arg.event.extendedProps.unit }}</b><br />
                <b>{{ arg.event.extendedProps.serviceType }}</b><br />
            </div>
              <b-tooltip
                :target="'tooltip-target'+arg.event.id"
                triggers="hover"
                class="tooltip_box"
              >
                <div class="wrap_popup_inner">
                  <div class="grey_box common_box">
                    <h6>
                     {{ arg.event.title }}
                    </h6>
                  </div>
                  <div class="details_box common_box">
                    <h6>Details</h6>
                    <p>
                     Apartment no: {{ arg.event.extendedProps.unit }}
                    </p>
                    <p>
                     Service: {{ arg.event.extendedProps.serviceType }}
                    </p>
                  </div>
                  <div class="team_box common_box">
                    <h6>Assigned Team ({{ arg.event.extendedProps.employee.type==1?"Individual":(arg.event.extendedProps.employee.type==2?"Crew":"N/A")}})</h6>
                    <span class="small_grey_Box" v-if="arg.event.extendedProps.employee.type==1">{{arg.event.extendedProps.employee.name}}</span>
                  </div>
                  <!-- <div class="location_box common_box">
                    <h6>Location</h6>
                    <p>
                      University Sqaure Apartment Homes PTR 8005-202 Occupied
                      Drywall repair
                    </p>
                  </div> -->
                  <div class="start_end_box common_box">
                    <div class="row">
                      <div class="col-12">
                        <h6>Schedule Date</h6>
                        <span>{{ arg.event.extendedProps.scheduleDate | formatDate }}</span><br />
                        <span>{{ arg.event.extendedProps.scheduleTime }}</span>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="start_end_box common_box">
                    <div class="row">
                      <div class="col-6 border_right">
                        <h6>Start</h6>
                        <span>06/11/2022</span><br />
                        <span>8:00 AM</span>
                      </div>
                      <div class="col-6 text_right">
                        <h6>Ends</h6>
                        <span>06/11/2022</span> <br />
                        <span>8:00 AM</span>
                      </div>
                    </div>
                  </div> -->
                  <div class="btn_boxs common_box">
                    <div class="row">
                      <div class="col-6">
                        <router-link class="btn btn_orange border_radius_5 btn_small_all" v-bind:to="'/edit-job/' + arg.event.extendedProps.jobId">Edit</router-link>
                      </div>
                      <!-- <div class="col-6">
                        <button
                          class="btn btn_orange border_radius_5 btn_small_all"
                        >
                          View Details
                        </button>
                      </div> -->
                    </div>
                  </div>
                </div>
              </b-tooltip>
            </div>
                    </template>
                </FullCalendar>
                <small class="text-primary mt-3"
                    >Note: Scheduled-><span class="text-danger">Red</span> |
                    Completed-> <span class="text-success">Green</span>
                </small>
            </div>
        </div>
    </div>
</template>
<script>
import Loader from "../LoaderSpinner";
import "@fullcalendar/core/vdom"; // solves problem with Vite
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
// import Datepicker from 'vuejs-datepicker';
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import Navigation from "../../components/Navigation";

export default {
    components: {
        Loader: Loader,
        FullCalendar, // make the <FullCalendar> tag available
       'Datepicker': Datepicker,
       'Navigation': Navigation,
    },
    data() {
        return {
            loading: true,
            selectedDate:new Date(),
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin],
                initialView: "dayGridMonth",
                customButtons: {
                    datePicker: {
                    text: 'Date',
                    click: function() {
                        // alert('clicked the custom button!');
                    }
                    }
                },
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "",
                },
                editable: false,
                selectable: false,
                weekends: true,
                events: [],
                dayMaxEvents: 10
                // eventClick: function(info) {
                //     alert("JOB: " + info.event.title);
                // }
            },
            calendarPlugins: [ dayGridPlugin, interactionPlugin ]
        };
    },
    mounted() {
        this.getScheduleJobs();
        setTimeout(()=>{
            let calendarApi=this.$refs.fullCalendar.getApi()
            calendarApi.gotoDate(this.selectedDate)
        },1000)

    },
    methods: {
        getScheduleJobs() {
            this.loading = true;
            axios.get("/api/jobs-calendar").then((response) => {
                this.loading = false;
                // // console.log(response.data.jobs);
                this.calendarOptions.events = response.data.jobs;
                // this.calendarOptions.events = [
                //     {
                //         title: "Test JOB 1",
                //         start: "2022-03-11",
                //         extendedProps: {
                //             status: "done"
                //         }
                //     },
                //     {
                //         title: "Test JOB 2",
                //         start: "2022-03-12",
                //         backgroundColor: "brown",
                //         borderColor: "red"
                //     },
                //     { title: "Test JOB 3", date: "2022-03-15" }
                // ];
                // this.jobs.forEach(item => {
                //     item.total = 0
                //     // item.scheduled_date = ''
                //     item.requested_job_service.forEach(item1 =>{
                //         item.total += Number(item1.requested_job_sub_service[0].total)
                //         // item.scheduled_date = item1.scheduled_date > item.scheduled_date ? item1.scheduled_date : item.scheduled_date
                //     });
                // })
            });
        },
        handleDateChange(){
            setTimeout(()=>{
            let calendarApi=this.$refs.fullCalendar.getApi()
            calendarApi.gotoDate(this.selectedDate)
            //scroll to specific date
            const dateSelected= this.selectedDate;
            const offset = this.selectedDate.getTimezoneOffset()
            this.selectedDate = new Date(this.selectedDate.getTime() - (offset*60*1000))
            this.selectedDate=this.selectedDate.toISOString().split('T')[0]
            const element=document.querySelector(`[data-date="`+this.selectedDate+`"]`)
            var top = element.offsetTop;
            window.scrollTo(0, top);
            this.selectedDate = dateSelected;
            // // console.log("finished");
        },1000)

        }
    },
};
</script>
