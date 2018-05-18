
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('vue');
import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import laravelVuePagination from 'laravel-vue-pagination';
//components
import example from './components/ExampleComponent.vue';
import navbar from './components/navbar.vue';
import speakers from './components/speakers.vue';
import speaker from './components/speaker.vue';
import modal from './components/modal.vue';

Vue.use(VueRouter);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('pagination', laravelVuePagination);
Vue.component('example-component', example);
Vue.component('navbar', navbar);
Vue.component('speakers', speakers);
Vue.component('speaker', speaker);
Vue.component('modal', modal);

//router
// const router = new VueRouter({
//     mode: 'history',
//     routes: [
//       { path: '/', component: example },
//       { path: '/about', component: navbar }
//     ]
// });

const app = new Vue({
    el: '#app'
})