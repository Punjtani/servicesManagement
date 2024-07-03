<template>
    <div class="dashboard_header">
        <div class="row align-items-center">
            <div class="col-12  col-md-6">
                <router-link  to="/dashboard" href="/">
                    <div class="dashboard_header_logo">
                    <img src="/images/logo.png" alt="">
                </div>
                </router-link>
            </div>
            <div class="col-12 col-md-6">
                <div class="dashboard_header_user user_inheader">
                    <div class="user_img">
                        <div class="dropdown">
                            <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <img v-if="user.profile_image" :src="user.profile_image" alt="user_img">
                                <img v-else-if="!user.profile_image" class="user_side_user_img" src="/images/user_img.png" alt="user_img">
                                <div class="user_info">
                                    <h6>{{user.first_name}} {{user.middle_name}} {{user.last_name}}
                                        <small>{{user.email}}</small>
                                    </h6>
                                </div>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <!--<li><a class="dropdown-item" href="#">{{user.first_name}} {{user.middle_name}} {{user.last_name}}</a></li>
                                <li><a class="dropdown-item" href="#">{{user.email}}</a></li>
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>-->
                                <li><a class="dropdown-item" href="javascript:void(0)" @click="logout()">Sign Out </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['test'],
        data() {
            return {
                user: ""
            }
        },
        mounted() {
            this.userInfo();
        },
        methods: {
            clickEvent() {
                this.$emit('clicked', 'testakbgdh')
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
                        }
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

            }
        }
    }
</script>
