<template>
    <div class="login_page" style="background: url('/images/login_bg.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
        <div v-if="loading">
            <loader></loader>
        </div>
        <div class="login_page_img">
            <img src="/images/login_bg.jpg" alt="">
        </div>
        <div class="login_page_box">
            <div class="login_page_inner text-center">
                <div class="login_logo"><img src="/images/logo.png" alt=""></div>
                <div class="h1 login_header text-uppercase">Reset Password</div>
                <div class="login_body">
                    <form autocomplete="off" @submit.prevent="onSubmit" method="post">
                        <div class="form-group">
                            <input v-model="submit_form.email" type="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="form-group">
                            <input v-model="submit_form.password" type="password" class="form-control" placeholder="New Password" required>
                        </div>
                        <div class="form-group">
                            <input v-model="submit_form.password_confirmation" type="password" class="form-control" placeholder="Confirm New Password" required>
                        </div>
                        <div class="d-grid gap-2 login_btn_box">
                            <button class="login_btn btn btn_orange text-uppercase " type="submit">Reset Password</button>
                        </div>
                    </form>
                </div>
                <br>
                <div id="success-alert" class="alert alert-success" v-if="successmsg!==''"> <strong>{{ successmsg }}</strong> </div>
                <div id="errir-alert" class="alert alert-danger" v-if="errormsg!==''"> <strong>{{ errormsg }}</strong> </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Loader from "./LoaderSpinner";
export default {
    components:{
        'Loader': Loader,    
    },
    data() {
        return {
                submit_form:{
                    email:"",
                    password: "",
                    password_confirmation: "",
                    token:"",
                },
                has_error: false,    
                loading:false,   
                submit:false,    
                successmsg:"",   
                errormsg:"",  
            }
    },
    mounted() {
        this.submit_form.token = this.$route.params.token;
        if(!this.submit_form.token){
            this.$router.push('/login');            
        }
    },
    methods: {
        onSubmit() {
            if(this.submit_form.password_confirmation !== this.submit_form.password){
                this.errormsg = 'Password mismatch';
                setTimeout(() => {                                          
                    this.errormsg = "";
                }, 1000);
                return;
            }
            this.loading = true;
            this.submit = true;
            axios.post('/api/auth/reset-password',this.submit_form)
                .then(res => {
                    if (!res.data.errormsg) {
                        this.successmsg = res.data.msg;
                        this.loading = false;
                        this.submit = false;                                            
                        setTimeout(() => {
                            this.successmsg = "";
                            this.$router.push('/login')
                        }, 3000);
                    }else{
                        this.errormsg = res.data.errormsg;
                        this.loading = false;
                        this.submit = false;                                            
                        setTimeout(() => {
                            this.errormsg = "";
                        }, 3000);
                    }
                })
                .catch(err => {
                    this.has_error = true;
                    this.loading = false;
            })
        },
    }
}
</script>
