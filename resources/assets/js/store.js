import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        api_path: '',
        user: null,
        conference_speech_comments: []
    },
    mutations: {
        SAVE_USER: (state,user) => {
            state.user = user
        },
        CHECK_USER: state => {
            if( window.localStorage.getItem('user') ){
                state.user = JSON.parse(window.localStorage.getItem('user'))
            }else{
                state.user = null
            }
        },
        GET_PATH: state => {
            state.api_path = process.env.NODE_ENV == 'production' ? 'https://greekparliament.info/api/v1/' : 'http://127.0.0.1:8000/api/v1/'
        },
        SAVE_CONFERENCE_SPEECH_COMMENTS: (state,comments) => {
            state.conference_speech_comments = comments
        }
    },
    actions: {
        GET_COMMENTS_CONFERENCE: (context,conferece_date) => {
            api.call('get', context.state.api_path + 'comments/' + conferece_date)
            .then(  data => {
                if( data.data && data.statusText == "OK" && data.status == 200 ){
                    context.commit('SAVE_CONFERENCE_SPEECH_COMMENTS',data.data.data)
                }
            })
        }
    },
    getters: {
        get_user: state => {
            return state.user
        },
        get_api_path: state => {
            return state.api_path
        },
        get_conference_speech_comments: state => {
            return state.conference_speech_comments
        }
    }
})