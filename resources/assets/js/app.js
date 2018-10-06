
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
import store from './store'
import axios from 'axios'
import laravelVuePagination from 'laravel-vue-pagination'
//import MultiSelect from 'vue-multiselect'
import MultiSelect from './multiselect_custom'
import datepicker from 'vuejs-datepicker'
import moment from 'moment'
import ToggleButton from 'vue-js-toggle-button'
import ReadMore from 'vue-read-more'
import Vuelidate from 'vuelidate'
import TextareaAutogrow from 'vue-textarea-autogrow'
import VueChatScroll from 'vue-chat-scroll'

import 'es6-promise/auto'
import Vuesax from 'vuesax'
import VueFrappe from 'vue2-frappe';

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
import comments from './components/comments.vue'
import PieChart from './Chart_Components/pie_chart.js'
import LineChart from './Chart_Components/line_chart.js'
import DashboardTableBody from './components/dashboard_table_body.vue'
import TransitionExpand from './other_components/transition-expand.vue'


import 'vuesax/dist/vuesax.css' //Vuesax styles
import 'material-icons/iconfont/material-icons.css';
import VueChartkick from 'vue-chartkick'

// must be before auth
window.api = new Api()

window.auth = new Auth()

Vue.use(VueRouter)
Vue.use(ToggleButton)
Vue.use(ReadMore)
Vue.use(Vuelidate)
Vue.use(VueChatScroll)
Vue.use(Vuesax)
Vue.use(VueFrappe)
Vue.use(VueChartkick)

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
Vue.component('comments', comments)
Vue.component('TextareaAutogrow', TextareaAutogrow)
Vue.component('pie-chart', PieChart)
Vue.component('line-chart', LineChart)
Vue.component('expand-table-data', DashboardTableBody)
Vue.component('transition-expand', TransitionExpand)

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
    store,
    beforeCreate() {
        this.$store.commit('CHECK_USER')
        this.$store.commit('GET_PATH')
	},
    data: {
        host: process.env.NODE_ENV == 'production' ? 'https://greekparliament.info' : 'http://127.0.0.1:8000'
    }
})