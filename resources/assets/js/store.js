import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        api_path: '',
        user: null
    },
    mutations: {
        SAVE_USER: (state,user) => {
            state.user = user
        },
        CHECK_USER: state => {
            if( window.localStorage.getItem('user') ){
                state.user = JSON.parse(window.localStorage.getItem('user'))
            }
        },
        GET_PATH: state => {
            state.api_path = process.env.NODE_ENV == 'production' ? 'http://95.85.38.123/api/v1/' : 'http://127.0.0.1:8000/api/v1/'
        }
    },
    actions: {

    },
    getters: {
        get_user: state => {
            return state.user
        },
        get_api_path: state => {
            return state.api_path
        }
    }
})