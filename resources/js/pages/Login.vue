<template>
    <div class="login_page" style="background: url('images/login_bg.jpg');    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;">
      <div class="login_page_img">
          <img src="images/login_bg.jpg" alt="">
      </div>
        <div class="login_page_box">
            <div class="login_page_inner text-center">
                <div class="login_logo"><img src="images/logo.png" alt=""></div>
                <div class="h1 login_header text-uppercase">Login</div>
                <div class="login_body">

                    <div class="alert alert-danger" id="alert" v-if="has_error">
                        <p>Email or password is invalid. Please try again.</p>
                    </div>
                    <form autocomplete="off" @submit.prevent="login">
                        <div class="form-group">
                            <input   v-model="credentials.email" type="email" class="form-control" placeholder="User Name">
                        </div>
                        <div class="form-group">
                            <input  v-model="credentials.password" type="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="d-grid gap-2 login_btn_box">
                            <button class="login_btn btn btn_orange text-uppercase " type="submit">Login</button>
                        </div>
                        <div class="remember_me">
                            <div class="custom_checkbox">
<!--                                <label>-->
<!--                                    <input type="checkbox" checked>-->
<!--                                    <span class="box"></span>Remember Me-->
<!--                                </label>-->
                            </div>
                        </div>
                    </form>
                    <router-link to="/reset-password-request">Forgot Password?</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            credentials: {
                email: "",
                password: "",
            },
            has_error: false
        }
    },
    mounted() {
        if (this.$store.state.token !== "") {
            axios.get('api/check-token')
                .then(res => {
                    if (res) {
                        this.$router.replace('/dashboard');
                    }
                })
                .catch(err => {
                    this.$store.commit('clearToken');
                })
        }
    },
    methods: {
        login() {


            $(".alert").show();
            axios.post('/api/auth/login', this.credentials)
                .then(res => {
                    if (res.data.status) {

                        this.$store.commit('isPasswordUpdated', res.data.password_updated);
                        this.$store.commit('setToken', res.data.token);
                        this.$store.commit('setRole', res.data.role);
                        this.$store.commit('setParent', res.data.parent);
                        axios.defaults.headers.common['Authorization']  = 'Bearer ' + localStorage.getItem('auth') || '';

                        // if( res.data.role == 3){
                        //     this.$router.push('/my-jobs')
                        // }
                        // else{
                            this.$router.replace('/dashboard');
                        // }
                    }
                })
                .catch(err => {
                    this.has_error = true;
                    setTimeout(function () {
                        $(".alert").hide();
                        }, 3000);
                })

        }
    }
}
</script>
