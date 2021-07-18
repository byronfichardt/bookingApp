import bookingApprovalTable from "./components/Admin/bookingApprovalTable";

require('./bootstrap');

import Vue from 'vue'
import vuetify from './vuetify'
import VueRouter from 'vue-router'
import AdminCalender from './components/Admin/calenderAdmin.vue'
import table from './components/Admin/productTable.vue'
import blocked_dates_table from './components/Admin/BlockedDates/blockedDateTable.vue'

Vue.use(VueRouter)

//Main pages

import Admin from './views/admin.vue'
import User from './views/app.vue'

const routes = [
    { path: '/calender', component: AdminCalender },
    { path: '/products', component: table },
    { path: '/approvals', component: bookingApprovalTable },
    { path: '/blocked_dates', component: blocked_dates_table },
    { path: '', component: AdminCalender },
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



