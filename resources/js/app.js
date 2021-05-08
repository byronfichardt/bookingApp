require('./bootstrap');

import Vue from 'vue'
import vuetify from './vuetify'
import { Datetime } from 'vue-datetime'
import VueRouter from 'vue-router'
import AdminCalender from './components/calenderAdmin.vue'
import table from './components/table.vue'

Vue.use(VueRouter)

Vue.use(Datetime)

//Main pages

import Admin from './views/admin.vue'
import User from './views/app.vue'

const routes = [
    { path: '/calender', component: AdminCalender },
    { path: '/products', component: table },
]

const router = new VueRouter({
    routes
})

export const bus = new Vue();

const admin = new Vue({
    router,
    el: '#admin',
    vuetify,
    render: h => h(Admin),
    components: { Admin }
});

const user = new Vue({
    el: '#user',
    vuetify,
    render: h => h(User),
    components: { User }
});



