
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
//import MultiSelect from 'vue-multiselect';
import MultiSelect from './multiselect_custom';
import datepicker from 'vuejs-datepicker';
import moment from 'moment';
import ToggleButton from 'vue-js-toggle-button';
import ReadMore from 'vue-read-more';
import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

//components
import navbar from './components/navbar.vue';
import speakers from './components/speakers.vue';
import parties from './components/parties.vue';
import party from './components/party.vue';
import speaker from './components/speaker.vue';
import conferences from './components/conferences.vue';
import conference from './components/conference.vue';
import modal from './components/modal.vue';
import searchInput from './components/searchInput.vue';
import search from './components/searchSpeaker.vue';

Vue.use(VueRouter);
Vue.use(ToggleButton);
Vue.use(ReadMore);
Vue.use(VueInternationalization);

// register globally
Vue.component('pagination', laravelVuePagination);
Vue.component('navbar', navbar);
Vue.component('parties', parties);
Vue.component('party', party);
Vue.component('speakers', speakers);
Vue.component('speaker', speaker);
Vue.component('conferences', conferences);
Vue.component('conference', conference);
Vue.component('modal', modal);
Vue.component('custom-multiselect', MultiSelect);
Vue.component('datepicker', datepicker);
Vue.component('search-input', searchInput)
Vue.component('search-plugin', search)



Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

// Localization
const lang = document.documentElement.lang.substr(0, 2); 
const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});
//router
// const router = new VueRouter({
//     mode: 'history',
//     routes: [
//       { path: '/', component: example },
//       { path: '/about', component: navbar }
//     ]
// });

const app = new Vue({
    el: '#app',
    i18n,
    data: {
        host: process.env.NODE_ENV == 'production' ? 'http://95.85.38.123' : 'http://127.0.0.1:8000'
    }
})