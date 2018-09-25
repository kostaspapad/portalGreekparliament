import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        title: 'Hello 2',
        user: null
    },
    mutations: {
        SAVE_USER: (state,user) => {
            state.user = user
        }
    },
    actions: {

    },
    getters: {
        get_user: state => {
            return state.user
        }
    }
})