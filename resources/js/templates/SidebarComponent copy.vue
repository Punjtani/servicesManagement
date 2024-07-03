<template>
    <div>
        <nav v-if="role==roleData.admin || role == roleData.super_admin" id="sidebar" class="sidebar-wrapper">
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
                        <span class="user-name">Services <br> Management Portal</span>
                    </div>
                </div>
                <div class="sidebar-menu user_side_menu">
                    <ul>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#user-collapse"
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
                                        <a href="#">Sign Out </a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- ====== -->
                <div class="sidebar-menu">
                    <ul>
                        <li class="active">
                            <router-link  to="/dashboard" ><svg class="sidebaricon">
                                    <use xlink:href="#dashboard-interface"/>
                                </svg>Dashboard</router-link>
                        </li>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#Jobs-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#briefcase"/>
                                </svg>
                                <span>Jobs</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="Jobs-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/all-jobs" >All Jobs</router-link>
                                    </li>

                                    <li>
                                        <router-link  to="/requested-jobs" >Requests</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/cancelled-jobs" >Cancelled Jobs</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/jobs-calendar" >View Calendar</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                         <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#clients-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#user"/>
                                </svg>
                                <span>Clients</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="clients-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/clients" >All PMC</router-link>
                                    </li>
                                    <li>
                                        <router-link to="/client-quotes" >All Quotes</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#employees-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#user"/>
                                </svg>
                                <span>Employees</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="employees-collapse">
                                <ul>
                                    <li>
                                        <router-link to="/employees" >All Employees</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#invoices-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#invoice"/>
                                </svg>
                                <span>Invoices</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="invoices-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/invoices" >All Invoices</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#Users-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#user"/>
                                </svg>
                                <span>Users</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="Users-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/all-users" >All Users</router-link>
                                    </li>
                                    <li v-if="role == roleData.super_admin">
                                        <router-link  to="/all-roles" >Roles</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/permissions" >Permissions</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse"
                               data-bs-target="#Settings-collapse" aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#business-and-trade"/>
                                </svg>
                                <span>Settings</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="Settings-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/apartments">Unit types</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/services" >Services/(Categories)</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/department" >Division</router-link>
                                    </li>

                                    <!--<li>
                                        <router-link  to="/crew" >Crew Management</router-link>
                                    </li>-->

                                    <li>
                                        <router-link  to="/paint-values" >Paint Values</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/refinish-values" >Refinish Values</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/all-prices" >General Pricing</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/price-increment" >Yearly Pricing Increment</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/settings" >Settings</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <nav v-if="role==roleData.client || parent !== 'null'" id="sidebar1" class="sidebar-wrapper">
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
                        <span class="user-name"> <span v-if="parent !== 'null'">Staff</span>  <span v-else>Client  Management </span><br>  Portal</span>
                    </div>
                </div>
                <div class="sidebar-menu user_side_menu">
                    <ul>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#user-collapse"
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
                                        <a href="#">Sign Out </a>
                                    </li>


                                </ul>
                            </div>
                        </li>
                    </ul></div>
                <!-- ====== -->
                <div class="sidebar-menu">
                    <ul>
                        <li class="active">
                            <router-link  to="/dashboard" ><svg class="sidebaricon">
                                    <use xlink:href="#dashboard-interface"/>
                                </svg>Dashboard</router-link>
                        </li>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#Jobs-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#briefcase"/>
                                </svg>
                                <span>Jobs</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="Jobs-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/all-jobs" >All Jobs</router-link>
                                    </li>
                                    <li>
                                        <router-link  to="/jobs-calendar" >View Calendar</router-link>
                                    </li>
                                    <li v-if="role==roleData.client">
                                        <router-link  to="/required-po" >Missing PO's Jobs</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="sidebar-dropdown" v-if="parent=='null'">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#Users-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#user"/>
                                </svg>
                                <span>Profile</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="Users-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/clients" >View Profile</router-link>
                                    </li>
                                    <li v-if="user.client">
                                        <router-link  v-bind:to="'/client-quotes/'+ user.client.id" >Quotes</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="sidebar-dropdown" v-if="role==roleData.client">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#history-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#weekly-calendar-page-symbol"/>
                                </svg>
                                <span>History</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="history-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/client-history" >Job History</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="sidebar-dropdown" v-if="parent !== 'null'">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#properties-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#invoice"/>
                                </svg>
                                <span>Properties</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="properties-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/staff-property" >All Properties</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#invoices-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#invoice"/>
                                </svg>
                                <span>Invoices</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="invoices-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/invoices" >All Invoices</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- employee portl navigation-->
        <nav v-if="role==roleData.employee" id="sidebar" class="sidebar-wrapper">
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
                        <span class="user-name">Employee <br> Portal</span>
                    </div>
                </div>
                <div class="sidebar-menu user_side_menu">
                    <ul>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#user-collapse"
                               aria-expanded="false">
                                <img class="user_side_user_img" src="/images/user_img.png" alt="user_img">
                                <span>{{user.first_name}} {{user.middle_name}} {{user.last_name}}</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="user-collapse">
                                <ul>

                                    <li>
                                        <a href="javascript:void(0)" @click="logout()" >Sign Out </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- ====== -->
                <div class="sidebar-menu">
                    <ul>
                        <li class="active">
                            <router-link  to="/dashboard" >
                            <svg class="sidebaricon">
                                    <use xlink:href="#dashboard-interface"/>
                            </svg>Dashboard</router-link>
                        </li>
                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#Jobs-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#briefcase"/>
                                </svg>
                                <span>Jobs</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="Jobs-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/my-jobs" >All Jobs</router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="sidebar-dropdown">
                            <a href="#" class="collapsed" data-bs-toggle="collapse" data-bs-target="#TimeSheet-collapse"
                               aria-expanded="false">
                                <svg class="sidebaricon">
                                    <use xlink:href="#invoice"/>
                                </svg>
                                <span>Timesheet</span>
                                <svg class="sidebaricon_chevron">
                                    <use xlink:href="#right-chevron" />
                                </svg>
                            </a>
                            <div class="sidebar-submenu collapse" id="TimeSheet-collapse">
                                <ul>
                                    <li>
                                        <router-link  to="/my-time-sheet" >View Timesheet</router-link>
                                    </li>
                                </ul>
                            </div>
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

    data() {
        return {
            roleData : roleData,
            role: "",
            parent: null,
            user: ""
        }
    },
    mounted() {
        this.role = localStorage.getItem("role");
        this.parent = localStorage.getItem("parent");
        this.userInfo();
    },
    methods: {
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
                        // console.log(this.user.client.id);
                    }
                })
                .catch(err => {
                    this.has_error = true;
                })
        },
        logout() {
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
        }
    }
}
</script>
