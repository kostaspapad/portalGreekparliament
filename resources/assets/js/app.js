
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//import Vue from 'vue';
window.Vue = require('vue') // requiring the Vue
import VueRouter from 'vue-router'
import Api from './api'
import Auth from './auth'
import router from './routes'
import axios from 'axios'
import laravelVuePagination from 'laravel-vue-pagination'
//import MultiSelect from 'vue-multiselect'
import MultiSelect from './multiselect_custom'
import datepicker from 'vuejs-datepicker'
import moment from 'moment'
import ToggleButton from 'vue-js-toggle-button'
import ReadMore from 'vue-read-more'
import Vuelidate from 'vuelidate'


//components
// import navbar from './components/navbar.vue'
import speakers from './components/speakers.vue'
import parties from './components/parties.vue'
import party from './components/party.vue'
import speaker from './components/speaker.vue'
import conferences from './components/conferences.vue'
import conference from './components/conference.vue'
import searchInput from './components/searchInput.vue'
import search from './components/searchSpeaker.vue'
import speech from './components/speech.vue'
import Vuex from 'vuex'
import 'es6-promise/auto'

// must be before auth
window.api = new Api()

window.auth = new Auth()

Vue.use(VueRouter)
Vue.use(ToggleButton)
Vue.use(ReadMore)
Vue.use(Vuelidate)
Vue.use(Vuex)

// register globally
Vue.component('pagination', laravelVuePagination)
// Vue.component('navbar', navbar)
Vue.component('parties', parties)
Vue.component('party', party)
Vue.component('speakers', speakers)
Vue.component('speaker', speaker)
Vue.component('conferences', conferences)
Vue.component('conference', conference)
Vue.component('custom-multiselect', MultiSelect)
Vue.component('datepicker', datepicker)
Vue.component('search-input', searchInput)
Vue.component('search-plugin', search)
Vue.component('speech', speech)
Vue.component('favorite', require('./components/favorite.vue'))
Vue.component('vue-layout', require('./views/Layout.vue'))

//filters
Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})
Vue.filter('capitalizeAll', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.toUpperCase()
})

window.Event = new Vue;


const app = new Vue({
    el: '#app',
    router,
    data: {
        host: process.env.NODE_ENV == 'production' ? 'http://95.85.38.123' : 'http://127.0.0.1:8000'
    }
})