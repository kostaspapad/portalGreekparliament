
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
import MultiSelect from 'vue-multiselect';
import datepicker from 'vuejs-datepicker';
import moment from 'moment';
import ToggleButton from 'vue-js-toggle-button';
import ReadMore from 'vue-read-more';

//components
import example from './components/ExampleComponent.vue';
import navbar from './components/navbar.vue';
import speakers from './components/speakers.vue';
import parties from './components/parties.vue';
import party from './components/party.vue';
import speaker from './components/speaker.vue';
import conferences from './components/conferences.vue';
import conference from './components/conference.vue';
import modal from './components/modal.vue';

Vue.use(VueRouter);
Vue.use(ToggleButton);
Vue.use(ReadMore);

// register globally
Vue.component('pagination', laravelVuePagination);
Vue.component('example-component', example);
Vue.component('navbar', navbar);
Vue.component('parties', parties);
Vue.component('party', party);
Vue.component('speakers', speakers);
Vue.component('speaker', speaker);
Vue.component('conferences', conferences);
Vue.component('conference', conference);
Vue.component('modal', modal);
Vue.component('multiselect', MultiSelect);
Vue.component('datepicker', datepicker);

//router
// const router = new VueRouter({
//     mode: 'history',
//     routes: [
//       { path: '/', component: example },
//       { path: '/about', component: navbar }
//     ]
// });

const app = new Vue({
    el: '#main_app',
    data: {
        host: process.env.NODE_ENV == 'production' ? 'http://95.85.38.123' : 'http://127.0.0.1:8000'
    }
})