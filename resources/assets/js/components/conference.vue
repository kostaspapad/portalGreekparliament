<template>
    <div class="container">
        <div class="conferences-container">
            <div v-if="ajaxDoneConfInfo">
                <div class="conference-title-box py-4 pl-4">
                    <h2 class="font-weight-bold conference-title">{{ $t("conference.conference") }} · {{conf_date}}</h2>
                    <div>{{ajaxData.conferenceInfo.data.data.session}}</div>
                    <div>{{ajaxData.conferenceInfo.data.data.time_period}}</div>
                </div>
                <vs-tabs color='#007bff'>
                    <vs-tab vs-label="Συνδεδρίαση">
                        <div class="row w-100">
                            <div class="col-12 pt-3">
                                <span v-if="ajaxData.conferenceInfo">
                                    <a :href="startUrl + '/' + ajaxData.conferenceInfo.data.data.pdf_loc + '/' + ajaxData.conferenceInfo.data.data.pdf_name">
                                        {{ $t("conference.pdf") }} <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                                    </a>
                                    <a :href="startUrl + '/' + ajaxData.conferenceInfo.data.data.doc_location + '/' + ajaxData.conferenceInfo.data.data.doc_name">
                                        {{ $t("conference.word") }} <i class="fas fa-file-word" style="color: #007bff;"></i>
                                    </a>
                                </span>
                            </div>
                            <div v-if="ajaxData.isLoaded" class="col-12 pl-3 mt-2">
                                <pagination :data="ajaxData.conferenceData.data.meta" @pagination-change-page="changePage" :limit=1>
                                    <span slot="prev-nav">&lt;</span>
                                    <span slot="next-nav">&gt;</span>
                                </pagination>
                                <vs-input
                                    vs-icon="search" 
                                    placeholder="Αναζήτηση" 
                                    v-model.trim="search_string"
                                    @keypress.enter="searchConferenceSpeeches"
                                    style="width: 235px;color:inherit;"
                                />
                            </div>
                            <div v-if="ajaxDoneConfSpeeches" class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div v-for="conference in ajaxData.conferenceData.data.data" :key="conference.speech_id">
                                    <speech :speech="conference"></speech>
                                </div>
                            </div>
                            <div v-else>
                                <h4>{{ $t("conference.no_speeches_avail") }}</h4>
                            </div>
                            <div v-if="ajaxData.isLoaded" class="col-12" style="padding-left: 2.5rem;">
                                <pagination :data="ajaxData.conferenceData.data.meta" @pagination-change-page="changePage" :limit=1>
                                    <span slot="prev-nav">&lt;</span>
                                    <span slot="next-nav">&gt;</span>
                                </pagination>
                            </div>
                        </div>
                    </vs-tab>
                    <vs-tab vs-label="Δεδομένα">
                        <!-- <div class="chart-btn-div pointer d-inline-block mb-2" @click="showChart = !showChart" :class="showChart ? 'hide-text' : 'show-text'">
                            <span v-if="!showChart">{{ $t("conference.show_chart") }}</span>
                            <span v-else class="hide-letters">{{ $t("conference.hide_chart") }}</span>
                        </div> -->
                        <!-- <transition v-if="user" name="slide-fade"> -->
                        <!-- <transition name="slide-fade"> -->
                        <!-- <div class="m-auto" v-if="showChart"> -->
                        <h4 class="error"><b>{{ $t("conference.data_alert") }}</b></h4>
                        <pie-chart 
                            v-if="isLoaded"
                            :chart-data="ajaxData.party_count_speeches.party_count" 
                            :chart-labels="ajaxData.party_count_speeches.party_names"
                            :chart-bg-colors="ajaxData.party_count_speeches.party_colors"
                            :width="325">
                        </pie-chart>
                    </vs-tab>
                </vs-tabs>
            </div>
            <div v-else class="col-12 col-sm-12 col-md-12 col-lg-12">
                <img :src=" '../../img' + '/Spinner.gif' " class="m-auto d-block"/>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .conferences-bg {
        background-color: #ffffff;
    }
    .conferences-container {
        background-color: white;
        border-radius: 5px;
    }

    .conference-title-box {
        background-color: #dbf0ff;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    @media only screen and (min-width: 320px) and (max-width: 400px) {
        .conference-title {
            font-size: 1.3rem;
        }
    }
    .pickerDiv{
        margin-bottom: 10px;
    }
    .conversation{
        margin: 15px 0 15px 0;
        text-align: left;
    }
    .speech_speaker_name{
        /* font-style: italic; */
        font-weight: bold;
        /* color: white; */
    }
    .speech_speaker_img{
        border-radius: 60px;
        border: 2px solid #35495e;
        max-width: 130px !important;
        /* max-width: 80px; */
        /* width: 70%; */
        /* padding: 5px; */
        /* margin: 13px 0 0 5px; */
    }
    .speech{
        /* margin: -50px 0 0 90px; */
        text-align: justify;
    }
</style>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex'
    export default {
        props: {
            //conf_date: null,
            path: String
        },
        data() {
            return {
                ajaxData: {
                    conferenceData: [],
                    conferenceInfo: null,
                    party_count_speeches: {
                        party_names: [],
                        party_count: [],
                        party_colors: []
                    },
                    isLoaded: false
                },
                conf_date: null,
                defaultImg: 'default_speaker_icon.png',
                startUrl: 'https://www.hellenicparliament.gr',
                ajaxDoneConfInfo: false,
                ajaxDoneConfSpeeches: false,
                noDataConfInfo: true,
                noDataConfSpeeches: true,
                isLoaded: false,
                showChart: false,
                startInterval: false,
                search_string: null,
                order_orientation: 'desc',
                order_field: 'conference_date'
            }
        },
        methods:{
            searchConferenceSpeeches() {
                if(this.search_string === ''){
                    this.getConferenceSpeeches();
                }
                this.ajaxData.isLoaded = false
                setTimeout(() => {
                    api.call('get', this.api_path + 'conference/' + this.conf_date + '/speeches/search/' + this.search_string)
                        .then( response => {
                            if (response.status == 200) {
                                this.noDataConfSpeeches = false
                                this.ajaxData.conferenceData = response
                                this.ajaxData.isLoaded = true
                            } else {
                                this.noDataConfSpeeches = true
                            }

                            this.ajaxDoneConfSpeeches = true
                        })
                }, 500)
            },
            changePage(page) {
                axios.get(this.api_path + 
                    'conference/' + this.conf_date + 
                    '/speeches?page=' + page
                )
                .then(response => {
                    this.ajaxData.conferenceData = response;
                })
            },
            isJsonString(str) {
                //decode JSON
                var json;
                try {
                    json = JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return json;
            },
            getConferenceSpeeches(){
                setTimeout( () => {
                    this.ajaxData.isLoaded = false
                    axios.get(this.api_path + 'conference/'+ this.conf_date +'/speeches')
                    .then(response => {
                        if(response.status == 200 && response.statusText == "OK"){
                            this.noDataConfSpeeches = false
                            this.ajaxData.conferenceData = response
                        } else {
                            this.noDataConfSpeeches = true
                        }
                        this.ajaxDoneConfSpeeches = true
                        this.ajaxData.isLoaded = true
                    })
                    .catch(error => {
                        console.log(error)
                    });
                }, 1000)
            },
            getConferenceInfo(){
                setTimeout( () => {
                    axios.get(this.api_path + 'conference/date/'+ this.conf_date)
                    .then(response => {
                        if(response.status == 200 && response.statusText == "OK"){
                            this.noDataConfInfo = false
                            //because we only get one conference we add [0] to get it
                            this.ajaxData.conferenceInfo = response
                        } else {
                            this.noDataConfInfo = true
                        }
                        this.ajaxDoneConfInfo = true
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 1000)
            },
            getPartyCountByConference() {
                api.call('get', this.api_path + 'conferences/count-party-speeches/' + this.conf_date)
                .then( response => {
                    if(response.status == 200 && response.statusText == "OK" && response.data){
                        response.data.forEach( element => {
                            this.ajaxData.party_count_speeches.party_names.push(element.fullname_el)
                            this.ajaxData.party_count_speeches.party_count.push(element.party_count)
                            this.ajaxData.party_count_speeches.party_colors.push(element.color)
                            this.isLoaded = true
                        })
                    }

                })
            },
            ...mapActions([
                'GET_COMMENTS_CONFERENCE'
            ])
        },
        computed:{
            ...mapGetters({
                user: 'get_user'
            }),
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        watch: {
            startInterval: function(newVal) {
                if(newVal){
                    setInterval( () => {
                        this.GET_COMMENTS_CONFERENCE(this.conf_date)
                    },60000);
                }
            }
        },
        created() {
            this.$route.params.conference_date ? this.conf_date = this.$route.params.conference_date : null          
            this.getConferenceInfo()
            this.getConferenceSpeeches()
            
            this.getPartyCountByConference()
            if(this.user){
                //put interval HERE
                // this.GET_COMMENTS_CONFERENCE(this.conf_date)
                this.startInterval = true
            }
        }
    }
</script>
