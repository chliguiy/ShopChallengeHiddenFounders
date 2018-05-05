import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import Dashboard from './components/Dashboard.vue';
import Home from './components/Home.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';

import Preferred from './components/Preferred.vue';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
import BootstrapVue from 'bootstrap-vue'

Vue.use(BootstrapVue);

axios.defaults.baseURL = 'http://localhost:8000/api';
Vue.prototype.$user_id=null; 
const router = new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Dashboard,
              meta: {
                auth: true
            }
        },
          {
            path: '/preferred',
            name: 'preferred',
            component: Preferred,
              meta: {
                auth: true
            }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: {
                auth: false
            }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                auth: false
            }
        },
        {
            path: '/newshop',
            name: 'newshop',
            component: Home,
            meta: {
                auth: true
            }
        }
    ]
});

Vue.router = router

Vue.use(require('@websanova/vue-auth'), {
  auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
  http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
  router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
})

App.router = Vue.router

new Vue(App).$mount('#app');
