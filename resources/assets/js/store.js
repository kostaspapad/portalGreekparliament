import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        api_path: '',
        user: null,
        conference_speech_comments: [],
        single_speech_comments: [],
        search_data: {
            hasDoneSearch: false,
            speakers: [],
            parties: [],
            tags: [
                { name: 'Μνημόνιο', code: 1 }
            ],
            dateRange: {
                startDate: null,
                endDate: null
            },
            singleDate: null,
            search_results: []
        },
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
            //state.api_path =  process.env.NODE_ENV == 'production' ? 'https://greekparliament.info/api/v1/' : 'http://127.0.0.1:8000/api/v1/'
            state.api_path =  process.env.NODE_ENV == 'production' ? 'api/v1/' : 'http://127.0.0.1:8000/api/v1/'
            // state.api_path =  '/api/v1/'
            // state.api_path = '/api/v1/'
        },
        SAVE_CONFERENCE_SPEECH_COMMENTS: (state,comments) => {
            state.conference_speech_comments = comments
        },
        SAVE_SPEECH_COMMENTS: (state,comments) => {
            state.single_speech_comments = comments
        },
        SAVE_SEARCH_DATA: (state,searchData) => {
            state.search_data.dateRange.startDate = searchData.ajax.dateRange.startDate
            state.search_data.dateRange.endDate = searchData.ajax.dateRange.endDate
            state.search_data.singleDate = searchData.ajax.singleDate
            state.search_data.parties = searchData.ajax.parties
            state.search_data.speakers = searchData.ajax.speakers
            state.search_data.tags = searchData.ajax.tags
            state.search_data.search_results = searchData.search_results
            
        },
        SAVE_HAS_DONE_SEARCH: (state,has_done_search) => {
            state.search_data.hasDoneSearch = has_done_search
        },
        RESET_SEARCH_DATA: state => {
            state.search_data.dateRange.startDate = null
            state.search_data.dateRange.endDate = null
            state.search_data.singleDate = null
            state.search_data.parties = []
            state.search_data.speakers = []
            state.search_data.tags = []
            state.search_data.search_results = []
        }
    },
    actions: {
        GET_COMMENTS_CONFERENCE: (context,argument) => {
            if(argument.choice == 'conference'){
                //to show all comments for all the conference
                api.call('get', context.state.api_path + 'comments/' + argument.data)
                .then(  data => {
                    if( data.data && data.statusText == "OK" && data.status == 200 ){
                        context.commit('SAVE_CONFERENCE_SPEECH_COMMENTS',data.data.data)
                    }
                })
            }else if(argument.choice == 'single_speech'){
                //to show only ciomment of one speech
                api.call('get', context.state.api_path + 'comments/speech/' + argument.data)
                .then(  data => {
                    if( data.data && data.statusText == "OK" && data.status == 200 ){
                        context.commit('SAVE_SPEECH_COMMENTS',data.data.data)
                    }
                })
            }
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
        },
        get_single_speech_comments: state => {
            return state.single_speech_comments
        },
        get_search_data: state => {
            return state.search_data
        }
    }
})