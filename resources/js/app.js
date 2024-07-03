/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vue from 'vue';

import App from './App.vue';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import axios from 'axios';
import {routes} from './routes/routes';
import {store} from "./store";
import Vuelidate from 'vuelidate';
import moment from 'moment'
import { BootstrapVue,BootstrapVueIcons } from 'bootstrap-vue'



axios.defaults.headers.common['Authorization']  = 'Bearer ' + localStorage.getItem('auth') || '';
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});


// Add a response interceptor
axios.interceptors.response.use(async function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
}, async (error) => {
    // console.log(error);
    if (!error.response) {
        // alert('NETWORK ERROR')
    } else {
        const code = error.response.status
        const response = error.response.data
        const originalRequest = error.config;

        if ((code === 401 || code == 405) && !originalRequest._retry) {
            originalRequest._retry = true
            if (router.currentRoute.path !== '/login') {
                 store.commit('clearToken');
                await router.push('/login');
              }
        }

        return Promise.reject(error)
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(Vuelidate)
// Vue.use(moment)
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)




Vue.filter('formatDateTime', function(value) {
    // // console.log('function')
    if (value) {
        return moment(String(value)).format('MM/DD/YYYY hh:mm a')
    }
});
Vue.filter('formatDate', function(value) {
    // // console.log('function')
    if (value) {
        let date = moment(String(value)).format('MM/DD/YYYY');
        return date.toLowerCase() == "invalid date" ? "--" : date;
    }
});
Vue.filter('formatFullDate', function(inputDate) {
    // // console.log('function')
    if (inputDate) {
        const originalDate = new Date(inputDate);

        const year = originalDate.getFullYear();
        const month = originalDate.getMonth() + 1; // Months are zero-based
        const day = originalDate.getDate();

        const formattedDate = `${month.toString().padStart(2, '0')}/${day.toString().padStart(2, '0')}/${year}`;

        return formattedDate;
    }
});
Vue.filter('formatTitle', function(value) {
    // // console.log('function')
    if (value) {
       return  value.replace('_', ' ');

    }
});

Vue.filter('formatTime',function(value){
    if (value) {
        if(value !== "Any Time"){
            return moment(value, 'hh:mm a').format('hh:mm a')
        }else{
            return value
        }
    }
});
Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    });
    return formatter.format(value);
});



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    routes: routes
});
router.beforeEach(async(to, from, next) => {
    // let path =  window.location.pathname.split('/')[1];
    // console.log(from.fullPath);
    if (to.path === '/') {
        await next('/login');
    }

    if (true) {
        if (store.state.token !== "")
        {
            try {
                let res = await axios.get('api/check-token');
                if(res)
                {

                    if(to.path === '/login')
                    {
                       await router.push({path: '/dashboard', replace:true});
                    }
                    else {await next(); }

                }
            } catch(e){
                store.commit('clearToken');
                await next();
            }
        }

    }
    let path =  to.path.split('/')[1];
    document.body.setAttribute("id", path);
    // // console.log('Path without params:', getpath);

    localStorage.setItem('lastRoute',from.fullPath);
    await next();
});


const app = new Vue({
    el: '#app',
    router: router,
    store: store,
    render: h => h(App)
});
