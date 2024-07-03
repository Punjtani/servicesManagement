<template>
<div class="login_page" style="background: url('images/login_bg.jpg'); background-size: cover; background-position: center center; background-repeat: no-repeat;">
    <div v-if="loading">
        <loader></loader>
    </div>
    <div class="login_page_img">
        <img src="images/login_bg.jpg" alt="">
    </div>
    <div class="login_page_box">
        <div class="login_page_inner text-center">
            <div class="login_logo"><img src="images/logo.png" alt=""></div>
            <div class="h1 login_header text-uppercase">Send Reset Email</div>
            <div class="login_body">
                <form autocomplete="off" @submit.prevent="sendLink" method="post">
                    <div class="form-group">
                        <input v-model="email" type="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="d-grid gap-2 login_btn_box">
                        <button class="login_btn btn btn_orange text-uppercase " type="submit">Send Email</button>
                        <router-link to="/login" class="login_btn btn btn_orange text-uppercase ">Back</router-link>
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
                email: "",
                has_error: false,
                successmsg:"",
                errormsg:"",
                loading:false,
                submit:false, 
            }
    },
    mounted() {
    },
    methods: {
        sendLink() {
            this.submit = true;
            this.loading = true;
            axios.post('/api/auth/forgot-password', {email:this.email})
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
        }
    }
}
</script>
