import bookingApprovalTable from "./components/Admin/bookingApprovalTable";

require('./bootstrap');

import Vue from 'vue'
import vuetify from './vuetify'
import VueRouter from 'vue-router'
import AdminCalender from './components/Admin/calenderAdmin.vue'
import table from './components/Admin/productTable.vue'
import Admin from './views/admin.vue'
import User from './views/app.vue'
import detailsPage from "./components/Admin/detailsPage";

Vue.use(VueRouter)

const routes = [
    { path: '/calender', component: AdminCalender },
    { path: '/products', component: table },
    { path: '/approvals', component: bookingApprovalTable },
    { path: '/details', component: detailsPage },
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



