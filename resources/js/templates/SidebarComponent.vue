<template>
    <div>
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-brand">
                <button @click="handleToggle"  class="btn btn-default toogle__Btn">
                    <div  class="toogle__Btn_icon close_sidebar">
                        <svg class="close_sidebar_icon">
                            <use xlink:href="#remove"/>
                        </svg>
                    </div>
                    <div  class="toogle__Btn_icon open_sidebar">
                        <svg class="close_sidebar_icon">
                            <use xlink:href="#menu"/>
                        </svg>
                    </div>
                </button>
            </div>
            <div class="sidebar-content">
                <div class="sidebar-header">
                    <div class="user-info">
                        <span class="user-name" v-if="(role == roleData.admin) || (role == roleData.super_admin)">Services <br> Management Portal - Admin</span>
                        <span class="user-name" v-if="(role == roleData.employee)">Services <br> Management Portal - Employee</span>
                        <span class="user-name" v-if="(role == roleData.client) || (user.role_type == 'Client')">Services <br> Management Portal - Client</span>
                    </div>
                </div>
                <div class="sidebar-menu user_side_menu">
                    <ul>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed employe-portal-wrap" data-bs-toggle="collapse" data-bs-target="#user-collapse"
                               aria-expanded="false">
                                <img class="user_side_user_img" src="images/user_img.png" alt="user_img">
                                <span>{{user.first_name}} {{user.middle_name}} {{user.last_name}}</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="user-collapse">
                                <ul>

                                    <li>
                                        <a href="javascript:void(0)" @click="logout()">Sign Out </a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- ====== -->
                <div class="sidebar-menu">
                    <ul v-for="(menu,index) in menus.filter((menu)=>menu.can_view)" :key="index">
                        <li class="sidebar-dropdown" v-if="menu.hasSubMenu">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" v-bind="{'data-bs-target':'#'+menu.collapse}"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use v-bind="{'xlink:href':'#'+menu.icon}"/>
                                </svg>
                                <span>{{truncateString(menu.name)}}</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" :id="menu.collapse">
                                <ul>
                                    <li v-if="parent == 'null'" v-for="(subMenu,subIndex) in menu.sub.filter((subMenu)=>subMenu.can_view)" :key="subIndex" @click="handleToggle">
                                        <router-link v-if="(subMenu.name=='Quotes' || subMenu.name=='View Profile' ) && user.client" :to="subMenu.route+user.client.id">{{truncateString(subMenu.name)}} <span v-if="subMenu.count != undefined" class="badge">{{subMenu.count}}</span></router-link>
                                        <router-link v-else-if="subMenu.name!='Quotes' && subMenu.name!='View Profile'" :to="subMenu.route" @click.native="reloadComponent(subMenu.route)">{{truncateString(subMenu.name)}} <span v-if="subMenu.count != undefined" class="badge">{{subMenu.count}}</span></router-link>
                                    </li>
                                    <li v-if="parent !== 'null'" v-for="(subMenu,subIndex) in menu.sub.filter((subMenu)=>subMenu.can_view)" :key="subIndex" @click="handleToggle">
                                        <router-link v-if="(subMenu.name=='View Profile' )" :to="'/client-property/'+user.staff.parent_id">{{truncateString(subMenu.name)}}</router-link>
                                        <router-link v-else-if="(subMenu.name=='Quotes')"  :to="subMenu.route+user.staff.parent_id">{{truncateString(subMenu.name)}}<span v-if="subMenu.count != undefined" class="badge">{{subMenu.count}}</span></router-link>
                                        <router-link v-else :to="subMenu.route" @click.native="reloadComponent(subMenu.route)">{{truncateString(subMenu.name)}} <span v-if="subMenu.count != undefined" class="badge">{{subMenu.count}}</span></router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li  @click="handleToggle"  v-else>
                            <router-link  :to="menu.route" ><svg class="sidebaricon">
                                    <use v-bind="{'xlink:href':'#'+menu.icon}"/>
                                </svg>{{truncateString(menu.name)}}</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import roleData from '../data.js';
export default {

    props : ['allJobsCount'],
    data() {
        return {
            roleData : roleData,
            role: "",
            user: "",
            menus:[],
            parent:"",
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        this.parent = localStorage.getItem("parent");
        this.userInfo();
        this.getMenu();
        this.$forceUpdate();
    },
    methods: {
        reloadComponent(page) {
            if(this.$route.path == page)
            {
                this.$emit('clicked')
                window.location.reload();
            }
        },
        handleToggle() {
               this.$emit('clicked')
            },
        userInfo(){
            axios.get('/api/auth/user')
            .then(response => {
                if (response.data.error) {
                    this.errormsg = response.data.error;
                    setTimeout(() => {
                        this.errormsg = "";
                    }, 3000);
                } else {
                    this.user = response.data.data;
                    if(this.user.roles.role_type){
                        this.user.role_type = this.user.roles.role_type;
                    }else{
                        this.user.role_type = "";
                    }
                    // console.log(this.user);
                }
            })
            .catch(err => {
                this.has_error = true;
            })
        },
        getMenu(){
            axios.get('/api/role-permissions/get/menu')
                .then(response => {
                    this.menus=response.data.menus
                    this.$emit('updateCount');
                })
                .catch(err => {
                    this.has_error = true;
                })

        },
        logout() {
            this.$store.commit('clearToken');
            axios.get('/api/auth/logout')
                .then(response => {
                    if (response.data.error) {
                        this.errormsg = response.data.error;
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    } else {
                        this.$router.push('/login')
                    }
                })
                .catch(err => {
                    this.has_error = true;
                })
        },
        shortNumber(num) {
            try {
                if (num < 1000) return num;
                // Define the suffixes for thousands, millions, etc.
                const suffixes = ["", "k", "M", "B", "T"];
                // Find the index of the appropriate suffix by dividing the number by 1000 repeatedly
                let index = 0;
                while (num >= 1000 && index < suffixes.length - 1) {
                    num /= 1000;
                    index++;
                }

                // Round the number to one decimal place and append the suffix
                return num.toFixed(1) + suffixes[index];
            } catch(e){ return '0' }
            },
            truncateString(str, maxLength=16) {
                // check if the string length is greater than the maximum length
                if (str.length > maxLength) {
                    // return the substring from the start to the maximum length, plus "..."
                    return str.substring(0, maxLength) + "...";
                } else {
                    // return the original string
                    return str;
                }
            },
        updateMenu(val){
            if(this.menus.length > 0){
                this.menus.forEach(data => {

                if(['Jobs', "Clients", 'Profile', 'Employees' ,'History', 'Invoices', 'Users']
                    .includes(data.name) && data.hasSubMenu){
                    data.sub.forEach(sub => {
                    //    console.log(val);
                    //    alert(val.client_count);

                        if(sub.name == 'All Jobs'){
                            sub.count = val.all_jobs;
                        }else if (sub.name == 'Requests'){
                            sub.count = val.request;
                        }else if (sub.name == 'Cancelled Jobs'){
                            sub.count = val.cancelled_jobs;
                        } else if(sub.name == "Missing PO's Jobs") {
                            sub.count = val.missing_pos;
                        } else if (sub.name == "All Quotes" || sub.name == "Quotes") {
                            sub.count = val.qoute_counts;
                        } else if (sub.name == "All Employees") {
                            // sub.count = val.employee_counts;
                        } else if (sub.name == "Job History") {
                            // sub.count = val.job_history_count;
                        } else if (sub.name == "All Invoices") {
                            //  sub.count = val.invoices_count;
                        } else if (sub.name == "Past Due") {
                            //  sub.count = val.pastDueCount;
                        } else if (sub.name == "Awaiting Payment"){
                            // sub.count = val.awaiting_payment;
                        }else if (sub.name == "Cancelled Invoices"){
                            // sub.count = val.canceled_invoice;
                        } else if(sub.name == "All Users") {
                            // sub.count = val.user_count;
                        }
                        else if(sub.name == "All PMC") {
                            // sub.count = val.client_counts;
                        } else if (sub.name === "Scheduled Jobs") {
                            sub.count = val.scheduled;
                        } else if (sub.name === "Unscheduled Jobs") {
                            sub.count = val.not_scheduled;
                        } else if (sub.name === "Unassigned Jobs") {
                          sub.count = val.assigned;
                        } else if (sub.name === "Partial Scheduled Jobs") {
                            sub.count = val.partial_scheduled;
                        } else if (sub.name === "Completed Jobs") {
                            sub.count = val.completed;
                        } else if (sub.name === "Completed Billed Jobs") {
                            sub.count = val.completed_billed_jobs;
                        } else if (sub.name === "Completed Ready to Bill Jobs") {
                            sub.count = val.ready_to_bill;
                        } else if (sub.name === "Not Ready to Bill Jobs") {
                            sub.count = val.not_ready_to_bill;
                        } else if (sub.name === "Completed Not Ready to Bill Jobs") {
                            sub.count = val.completed;
                        } else if (sub.name === "Today's Jobs") {
                            sub.count = val.today;
                        } else if (sub.name == "Overdue Jobs")
                        {
                            sub.count = val.overdue;
                        }

                        if (sub.count)
                        {
                            sub.count = this.shortNumber(sub.count);
                        }

                    })
                    this.$forceUpdate();
                }
                });
            }
        }
    },
    watch:{
        'allJobsCount'(val) {
            this.updateMenu(val);
        },
    }
}
</script>
