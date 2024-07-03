<template>
    <div class="login_page" style="background: url('images/login_bg.jpg');    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;">
        <div class="login_page_box">
            <div class="login_page_inner text-center">
                <div class="login_logo"><img src="images/logo.png" alt=""></div>
                <div class="h1 login_header text-uppercase">Login</div>
                <div class="login_body">

                <div class="alert alert-danger" v-if="has_error">
                                    <p>Erreur, impossible de se connecter avec ces identifiants.</p>
                                </div>
                    <form autocomplete="off" @submit.prevent="login" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="User Name">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="d-grid gap-2 login_btn_box">
                            <button class="login_btn btn btn_orange text-uppercase " type="submit">Login</button>
                        </div>
                        <div class="remember_me">
                            <div class="custom_checkbox">
                                <label>
                                    <input type="checkbox" checked>
                                    <span class="box"></span>Remember Me
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
