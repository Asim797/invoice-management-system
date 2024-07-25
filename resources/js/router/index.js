import {createRouter, createWebHistory } from "vue-router";

import invoiceIndex from '../components/invoices/index.vue';
import invoiceCreate from '../components/invoices/create.vue';
import invoiceEdit from '../components/invoices/edit.vue';
import invoiceShow from '../components/invoices/show.vue';
import notFound from '../components/NotFound.vue'

const routes = [
    {
        path: '/',
        component: invoiceIndex
    },
    {
        path: '/invoices/create',
        component: invoiceCreate
    },
    {
        path: '/invoices/show/:id',
        component: invoiceShow,
        props: true,
    },
    {
        path: '/invoices/edit/:id',
        component: invoiceEdit,
        props: true,
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound
    },
]


const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router;
