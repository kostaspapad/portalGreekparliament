
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
import laravelVuePagination from './laravel-vue-pagination-custom'
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
// import VueFrappe from 'vue2-frappe';

//components
// import navbar from './components/navbar.vue'
import speakers from './components/speakers.vue'
import parties from './components/parties.vue'
import party from './components/party.vue'
import navbar from './components/navbar.vue'
import foot from './components/foot.vue'
import speaker from './components/speaker.vue'
import conferences from './components/conferences.vue'
import conference from './components/conference.vue'
import searchInput from './components/searchInput.vue'
import search from './components/searchSpeaker.vue'
import speech from './components/speech.vue'
import comments from './components/comments.vue'
import PieChart from './Chart_Components/pie_chart.js'
// import LineChart from './Chart_Components/line_chart.js'
import DashboardTableBody from './components/dashboard_table_body.vue'
import TransitionExpand from './other_components/transition-expand.vue'
import Modal from './components/modal.vue'


import 'vuesax/dist/vuesax.css' //Vuesax styles
// import 'material-icons/iconfont/material-icons.css';
import VueChartkick from 'vue-chartkick'

import VueInternationalization from 'vue-i18n'
import Locale from './vue-i18n-locales.generated'
import VueScrollTo from 'vue-scrollto'
// import vSelect from 'vue-select'
// import InputTag from 'vue-input-tag'

import helpers from './Helpers/HelperFunc'

// must be before auth
window.api = new Api()

window.auth = new Auth()

Vue.use(VueRouter)
Vue.use(ToggleButton)
Vue.use(ReadMore)
Vue.use(Vuelidate)
Vue.use(VueChatScroll)
Vue.use(Vuesax)
// Vue.use(VueFrappe)
Vue.use(VueChartkick)
Vue.use(VueInternationalization)
Vue.use(VueScrollTo)
// Vue.component('v-select', vSelect)
// Vue.component('input-tag', InputTag)

// register globally
Vue.component('pagination', laravelVuePagination)
Vue.component('search', search)
Vue.component('navbar', navbar)
Vue.component('parties', parties)
Vue.component('party', party)
Vue.component('speakers', speakers)
Vue.component('speaker', speaker)
Vue.component('conferences', conferences)
Vue.component('foot', foot)
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
// Vue.component('line-chart', LineChart)
Vue.component('modal', Modal)
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

// const lang = document.documentElement.lang.substr(0, 2); 
const lang = navigator.language.substr(0, 2) || navigator.userLanguage.substr(0, 2);
// or however you determine your current app locale

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});

// global helper functions
const plugin = {
    install () {
        Vue.helpers = helpers
        Vue.prototype.$helpers = helpers
    }
}
Vue.use(plugin)

// process.env.NODE_ENV == 'production' ? Vue.config.devtools = false  : Vue.config.devtools = true 
Vue.config.performance = process.env.NODE_ENV !== 'production'
const app = new Vue({
    el: '#app',
    router,
    store,
    i18n,
    beforeCreate() {
        this.$store.commit('CHECK_USER')
        this.$store.commit('GET_PATH')
    },
    data: {
        host: process.env.NODE_ENV == 'production' ? 'greekparliament.info' : 'http://127.0.0.1:8000'
    }
})