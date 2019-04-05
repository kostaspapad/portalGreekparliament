<template>
    <div class="container pt-2 pb-2">
        <div class="speaker-container">
            <div v-if="ajaxDoneSpeaker" class="row mr-0">
                <div class="w-100 bg-img">
                    <div class="container-fluid speaker-data">
                        <!-- Speaker info -->
                        <div class="speaker-info">
                            <div class="speaker-img">
                                <img :src=" '/img' + '/' + ajaxData.speakerData.image " class="img-fluid" style="margin: 5px 0 5px 0;">
                            </div>
                            <div class="speaker-name">
                                <div class="speaker-name-info">
                                    <h1 class="text-left">{{ajaxData.speakerData.greek_name}}</h1>
                                    <p>{{ajaxData.speakerData.email}}</p>
                                </div>
                            </div>
                        </div>
                        <!-- End of speaker info -->
                        <!-- Social links -->
                        <div class="float-left">
                            <div class="row">
                            <div v-if="ajaxData.speakerData.wiki_el"><span class="text-success">(el)</span><a :href="`${ajaxData.speakerData.wiki_el}`"><span class="fab fa-wikipedia-w iconsFont iconsColor"></span></a></div>
                            <div v-if="ajaxData.speakerData.wiki_en"><span class="text-success">(en)</span><a :href="`${ajaxData.speakerData.wiki_en}`"><span class="fab fa-wikipedia-w iconsFont iconsColor"></span></a></div>
                            <div v-if="ajaxData.speakerData.twitter"><a :href="`${ajaxData.speakerData.twitter}`"><span class="fab fa-twitter iconsFont iconsColor"></span></a></div>
                            </div>·
                        </div>
                        <!-- End of social links -->
                    </div>
                    <!-- Search input -->
                    <div class="col-12 pr-5 mb-4 mt-4 float-right">
                        <div class="float-right">
                             <vs-input
                                icon-pack="fas"
                                icon="fa-search"  
                                placeholder="Αναζήτηση λέξεων ομιλητή" 
                                description-text="Γράψτε μόνο με πεζά γράμματα και χρησιμοποιήστε τόνους."
                                v-model.trim="search_string"
                                @keypress.enter="searchSpeakerSpeeches"
                                class="speech-search-input d-inline-block"
                            />
                            <div v-if="search_string" class="d-inline-block search-clear-icon">
                                <span @click="clearInput" class="speech-search-input-icon pointer"><i class="fas fa-times-circle"></i></span>
                            </div>
                        </div>
                    </div>
                    <!-- End of search input -->
                </div>
                <div class="container">
                    <vs-tabs vs-color='#17a2b8'>
                        <vs-tab vs-label="Ομιλίες">
                            <div id="top_of_periods"></div>
                            <div v-if="showLinks" class="row period-mode">
                                <div v-if="!period.has_conf_dates" class="conf-period-title text-center w-100"><h3>Δεν υπάρχουν διαθέσιμες συνεδριάσεις.</h3></div>
                                <div v-if="period.has_conf_dates" class="conf-links col-12 col-sm-3 col-md-3 col-lg-2 mt-3" id="conf-links">
                                    <h6 class="conf-period-title d-none d-sm-block">Συνεδριάσεις</h6>
                                    <h6 class="conf-period-title text-center d-block d-sm-none">Συνεδριάσεις</h6>
                                    <!-- Conferences dates -->
                                    <!-- <transition-group name="test" tag="div" enter-active-class="animated fadeInLeft" leave-active-class="animated fadeOutLeft"> -->
                                        <div v-if="!hidePeriods" class="periods speaker-profile-periods" :class="{animTest: hidePeriods}" key="show_period">
                                            <ul v-if="ajaxData.conferences.linksData.length" class="period-list">
                                                <li 
                                                    v-for="(period_data) in ajaxData.conferences.linksData" 
                                                    :key="period_data.conference_date"
                                                    @click="getSpeechesByConference(period_data)"
                                                    class="pointer"
                                                    :class="{periods_bg: period_data.conference_date ==  period.selected_period}"
                                                    v-if="period.ajaxDone"
                                                >
                                                    <span>{{ $helpers.myFormattedDate(period_data.conference_date,'el') }}</span>
                                                </li>
                                                <li 
                                                    v-for="(period_data) in ajaxData.conferences.linksData" 
                                                    :key="period_data.conference_date" 
                                                    class="pointer"
                                                    :class="{periods_bg: period_data.conference_date ==  period.selected_period}"
                                                    v-if="!period.ajaxDone"
                                                >
                                                    <span>{{ $helpers.myFormattedDate(period_data.conference_date,'el') }}</span>
                                                </li>
                                            </ul>
                                            <div class="d-block d-sm-none">
                                                <button class="btn p-2" @click="showHidePeriods">Συνεδριάσεις <i class="fas fa-eye-slash"></i></button>
                                            </div>
                                        </div>
                                        <!-- End of conferences dates -->
                                        
                                        <!-- Show the periods -->
                                        <div v-else class="d-block d-sm-none" key="hide_period">
                                            <button class="btn p-2" @click="hidePeriods = false">Συνεδριάσεις <i class="fas fa-eye"></i></button>
                                        </div>
                                        <!-- End of show the periods -->
                                    <!-- </transition-group> -->
                                </div>
                                <!-- Scroll to top btn -->
                                <div class="scroll-btn d-block d-sm-none">
                                    <a href="#" v-scroll-to=" {
                                            el: '#conf-links',
                                            duration: 800,
                                            offset: -100,
                                            easing: 'linear',
                                            force: true,
                                            x: false,
                                            y: true
                                        } 
                                    ">
                                        <i class="fas fa-chevron-circle-up"></i>
                                    </a>
                                </div>
                                <!-- End of scroll to top btn -->
                                <!-- Selected Period -->
                                <vs-divider class="d-block d-sm-none" />
                                <div class="col-12 col-sm-9 col-md-9 col-lg-10 selected-period-main-div mt-3" id="selected_period">
                                    <!-- Period has data -->
                                    <div v-if="period.ajaxDone  && period.selected_period_hasData">
                                        <div v-show="search.noDataMsg">
                                            <p>{{search.noDataMsg}}</p>
                                        </div>
                                        <!-- Search result -->
                                        <div v-if="search.speechesData.length" class="search-result">
                                            <vs-divider color="#636b6f">Αποτελέσματα</vs-divider>
                                            <div v-for="speech in search.speechesData" :key="speech.speech_id">
                                                <speech :speech="speech" isFromSearch=true isFromSpeakerProfile=true></speech>
                                            </div>
                                        </div>
                                        <!-- End of search result -->
                                        <!-- Not search result -->
                                        <div v-else-if="!search.speechesData.length">
                                            <div v-if="period.selected_period_hasData">
                                                <div class="text-link">
                                                    <h4 class="selected-period-title text-center d-none d-sm-block">
                                                        <span>Μετάβαση στην συνεδρίαση <i class="fas fa-long-arrow-alt-right"></i></span>
                                                        <router-link :to="'/conference/' + period.selected_period + '/speeches'" style="color: #1f74ff;">
                                                            {{ $helpers.myFormattedDate(period.selected_period,'el') }}
                                                        </router-link>
                                                    </h4>
                                                    <h4 class="selected-period-title text-center d-block d-sm-none" style="font-size: 0.8rem;">
                                                        <span>Μετάβαση στην συνεδρίαση <i class="fas fa-long-arrow-alt-right"></i></span>
                                                        <router-link :to="'/conference/' + period.selected_period + '/speeches'" style="color: #1f74ff;">
                                                            {{ $helpers.myFormattedDate(period.selected_period,'el') }}
                                                        </router-link>
                                                    </h4>
                                                    <pagination v-if="period.speeches.data.meta" :data="period.speeches.data.meta" @pagination-change-page="changePagePeriod" :limit=1>
                                                        <span slot="prev-nav">&lt;</span>
                                                        <span slot="next-nav">&gt;</span>
                                                    </pagination>
                                                </div>
                                                <!-- height: calc(579px + 1rem + 35px); -->
                                                <div 
                                                    class="selected-period speaker-speeches-content" 
                                                    :class="{paginationUnavailable: period.speeches.data.meta.last_page == 1}"
                                                >
                                                    <div v-for="period_speeches in period.speeches.data.data" :key="period_speeches.speech_id" class="p-3 mt-3">
                                                        <speech :speech="period_speeches" isFromSpeakerProfile=true></speech>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End not search result -->
                                    </div>
                                    <!-- End of period has data -->
                                    <div v-if="!period.ajaxDone">
                                        <h5>Loading <i class="fas fa-spinner fa-spin"></i></h5>
                                    </div>
                                    <!-- Error message of selected_period -->
                                    <div v-else-if="!period.selected_period_hasData && period.selected_period && period.ajaxDone" class="">
                                        <h4>{{period.selected_period}}</h4>
                                        <p>Δεν βρέθηκαν Ομιλίες γιαυτήν την περίοδο.</p>
                                    </div>
                                    <!-- End of error message of selected_period -->
                                </div>
                                <!-- End of selected period -->
                            </div>
                            <div v-else class="p-3 tab-pane fade show speeches-container mb-0">
                                <!-- Ajax is completed and has data -->
                                <div v-if="ajaxDoneSpeeches && ajaxData.isLoaded && noDataSpeeches == false">
                                    <div v-show="search.noDataMsg">
                                        <p>{{search.noDataMsg}}</p>
                                    </div>
                                    <div v-if="!search.speechesData.length" v-for="speech in ajaxData.speechesData.data.data" :key="speech.speech_id" class="speeches py-2">
                                        <speech :speech="speech" isFromSpeakerProfile=true></speech>
                                    </div>
                                    <div v-if="search.speechesData.length" class="search-result">
                                        <vs-divider color="#636b6f">Αποτελέσματα</vs-divider>
                                        <div v-for="speech in search.speechesData" :key="speech.speech_id">
                                            <speech :speech="speech" isFromSearch=true isFromSpeakerProfile=true></speech>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-5" style="padding-left: 2.5rem;">
                                        <pagination :data="ajaxData.speechesData.data.meta" @pagination-change-page="changePage"
                                            :limit=1>
                                            <span slot="prev-nav">&lt;</span>
                                            <span slot="next-nav">&gt;</span>
                                        </pagination>
                                    </div>
                                </div>
                                <!-- End of ajax is completed and has data -->
                                <!-- Ajax is not completed -->
                                <div v-else-if="noDataSpeeches == true && ajaxData.isLoaded == false">
                                    <h5>Loading <i class="fas fa-spinner fa-spin"></i></h5>
                                </div>
                                <!-- End of ajax is not completed -->
                                <!-- Speeches no data error message -->
                                <div v-else-if="noDataSpeeches">
                                    <h5>{{ $t("speaker.no_speeches_available") }}</h5>
                                </div>
                                <!-- End of speeches no data error message -->
                            </div>
                        </vs-tab>
                        <vs-tab vs-label="Θητεία">
                            <div class=" d-md-block d-lg-block mb-0">
                                <h4 class="error mb-3"><b>{{ $t("speaker.data_alert") }}</b></h4>
                                <timeline 
                                    :data="ajaxData.timelineData" 
                                    :colors="ajaxData.memberships.party_colors"
                                ></timeline>
                            </div>
                        </vs-tab>
                    </vs-tabs>
                </div>
            </div>
            <div v-else>
                <div class="m-auto d-block lds-css ng-scope" style="width: 200px; height: 200px;">
                    <div style="width:100%;height:100%" class="lds-ripple">
                        <div></div><div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<style lang="scss" scoped>

    $ContainerColor: white;

    .show-hide-periods { 
        background: cadetblue;
        position: fixed;
        right: 0;
        z-index: 2;
    }

    .speaker-container {
        background-color: $ContainerColor;
        border-radius: 5px;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .nav-tabs a {
        color: #17a2b8;
    }

    .bg-img {
        background-image: url(/img/bgvouli.jpg);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
    }

    .speeches-container {
        // height: 630px;
        // overflow-y: scroll;
    }

    .speaker-info {
        display: table;
    }

    .speaker-data {
        /* background:  rgba(0,0,0,0.4); */
        color: white;
    }

    .speaker-img {
        width: 70px;
        vertical-align: top;
    }

    .speaker-name {
        vertical-align: middle;
        padding-left: 10px;
    }

    .speaker-name p {
        font-size: 1.33333em;
    }

    .speaker-data .speaker-info .speaker-name,
    .speaker-data .speaker-info .speaker-img {
        display: table-cell;
    }

    .speaker-data .speaker-info .speaker-name h1 {
        font-size: 1.5rem;
        margin: 0px;
        padding: 0px;
        line-height: 1em;
    }

    .speaker-data .speaker-info .speaker-social-links span a {
        color: inherit !important;
    }

    .speaker-data .speaker-info .speaker-social-links span a:hover {
        color: #62b356;
    }

    .search-div {
        width: 18rem;
    }

    .search-div input {
        border-right-style: none;
        border-radius: 2px;
        font-style: italic;
    }

    .search-div input:focus {
        border-color: #ced4da;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    .search-div span {
        padding: 13px 16px;
        background-color: #fff;
        border: 1px solid;
        border-color: #ced4da;
        border-radius: 2px;
    }

    .nav-tabs .nav-link.active {
        background: transparent;
        border-color: transparent;
        border-bottom-color: #6c6b68;
    }

    @media (max-width: 352px) {
        nav {
            font-size: 0.78rem;
        }
    }

    @media (max-width: 768px) {
        .bg-img {
            background-size: auto;
        }
    }

    @media (min-width: 1040px) {
        .speaker-social-links {
            position: absolute;
            right: 2.6em;
            margin-top: -1em;
        }
    }

    @media (max-width: 1040px) {
        .speaker-social-links {
            text-align: left;
        }
    }
    .speech-search-input-icon {
        .fa-times-circle{
            color: #ffffff;
            background: #495057;
            border-radius: 50%;
        }
    }
</style>

<script>
    import { mapState, mapGetters } from 'vuex'
    import moment from 'moment'
    export default {
        props: {
            name: String,
            path: String
        },
        data() {
            return {
                ajaxData: {
                    isLoaded: false,
                    speechesData: [],
                    speakerData: [],
                    memberships: {
                        party_colors: [],
                        start_dates: [],
                        end_dates: [],
                        party_name_en: [],
                        party_name_el: []
                    },
                    conferences: {
                        linksData: []
                    },
                    timelineData: []
                },
                search: {
                    speechesData: [],
                    noDataMsg: null
                },
                period: {
                    ajaxDone: false,
                    has_conf_dates: false,
                    selected_period: null,
                    selected_period_hasData: false,
                    current_period_page: 0,
                    speeches: [],
                    pageNum: null
                },
                hidePeriods: false,
                finalName: null,
                speaker_id: null,
                conferenceDate: null,
                showDate: true,
                currentTab: 'Information',
                defaultImg: 'default_speaker_icon.png',
                ajaxDoneSpeaker: false,
                ajaxDoneSpeeches: false,
                noDataSpeaker: true,
                noDataSpeeches: true,
                loading: true,
                order_field: 'conference_date',
                order_orientation: 'asc',
                search_string: null,
                showChart: false,
                showLinks: true
            }
        },
        methods: {
            scrollToTop() {
                 window.scrollTo(0,100);
            },
            showHidePeriods(){
                this.hidePeriods = !this.hidePeriods
                this.scrollToTop()
                //to inlcude when we add animation
                // setTimeout( () => {
                // },1000)
            },
            clearInput() {
                this.search_string = ''
                if(this.search.speechesData){
                    this.search.speechesData = []
                }
                this.search.noDataMsg = ''
            },
            sortBy(sortField, sortOrientation = null) {
                if (sortField) {
                    this.order_field = sortField
                }
                if (sortOrientation == 'asc') {
                    this.order_orientation = 'desc'
                } else {
                    this.order_orientation = 'asc'
                }
                if (this.order_field && this.order_orientation) {
                    this.getSpeakers()
                }
            },
            changePagePeriod(page){
                if(this.period.pageNum == page){

                }else{
                    this.period.pageNum = page
                    let url = null
                    url = 'period/' + this.period.selected_period + '/speeches/'+ this.speaker_id +'?page=' + page + '&order_field=' + this.order_field + '&orientation=' + this.order_orientation
                    api.call('get',this.api_path + url)
                    .then( response => {
                        if (response.status == 200) {
                            if(response.data.data.length > 0){
                                this.period.selected_period_hasData = true
                                this.period.speeches = response
                            }else{
                                this.period.selected_period_hasData = false
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
                }
            },
            changePage(page) {
                //for pagination
                let url = null

                if (this.speaker_id) {
                    url = 'speaker/' + this.speaker_id + '/speeches' + '?page=' + page
                }
                this.ajaxData.isLoaded = false
                api.call('get',this.api_path + url)
                    .then( response => {
                        if (response.status == 200) {
                            this.ajaxData.speechesData = response
                            this.ajaxData.isLoaded = true
                        }
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
            },
            checkDate(conf_date) {
                if (this.conferenceDate == null) {
                    this.conferenceDate = conf_date
                } else {
                    if (this.conferenceDate == conf_date) {
                        this.showDate = false
                    } else {
                        this.conferenceDate = conf_date
                        this.showDate = true
                    }
                }
            },
            getSpeakerSpeeches() {
                this.ajaxData.isLoaded = false
                
                setTimeout(() => {
                    api.call('get',this.api_path + 'speaker/' + this.speaker_id + '/speeches')
                        .then( response => {
                            if (response.status == 200 && response.data.data.length > 0) {
                                this.noDataSpeeches = false
                                this.ajaxData.speechesData = response
                                this.ajaxData.isLoaded = true
                            } else {
                                this.noDataSpeeches = true
                            }
                            this.ajaxDoneSpeeches = true
                        })
                }, 1000)
            },
            getSpeakerConferences() {
                this.ajaxData.isLoaded = false
                this.period.ajaxDone = false
                setTimeout(() => {
                    api.call('get',this.api_path + 'speaker/' + this.speaker_id + '/conference_dates')
                        .then( response => {
                            if (response.status == 200 && response.data.data.length > 0) {
                                // this.noDataSpeeches = false
                                this.ajaxData.conferences.linksData = response.data.data
                                this.ajaxData.isLoaded = true
                                this.period.has_conf_dates = true
                            } else {
                                // this.noDataSpeeches = true
                                this.period.has_conf_dates = false
                            }
                            this.period.ajaxDone = true
                            // this.ajaxDoneSpeeches = true
                        })
                }, 1000)
            },
            clearSearchData(){
                this.search.noDataMsg = ''
                this.search.speechesData = []
                this.search_string = ''
            },
            goToSpeeches() {
                //scroll to the element that contains the speeches
                let options = {
                    easing: 'linear',
                    // offset: -60,
                    force: true,
                    x: false,
                    y: true
                }
                this.$scrollTo('#selected_period', 800, options)
            },
            getSpeechesByConference(period){
                this.goToSpeeches()
                if(this.search.noDataMsg || this.search.speechesData.length > 0){
                    this.clearSearchData()
                }
                this.period.ajaxDone = false
                if(this.period.selected_period == period.conference_date){
                    //if it's the same as the previous don't make a call to server
                    this.period.ajaxDone = true
                }else{
                    this.period.selected_period = period.conference_date
                    let data = {
                        speaker_id: this.speaker_id
                    };
                    setTimeout(() => {
                        let url = null
                        url = 'period/' + this.period.selected_period + '/speeches/'+ this.speaker_id + '?order_field=' + this.order_field + '&orientation=' + this.order_orientation
                        api.call('get',this.api_path + url)
                        .then( response => {
                            if (response.status == 200) {
                                if(response.data.data.length > 0){
                                    this.period.selected_period_hasData = true
                                    this.period.speeches = response
                                }else{
                                    this.period.selected_period_hasData = false
                                }
                            }
                            this.period.ajaxDone = true
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                    }, 1000)
                }
            },
            getSpeakerData() {
                setTimeout(() => {
                    api.call('get',this.api_path + 'speaker/' + this.speaker_id)
                        .then( response => {
                            if (response.status == 200 && response) {
                                this.noDataSpeaker = false
                                this.ajaxData.speakerData = response.data.data
                            } else {
                                this.noDataSpeaker = true
                            }
                            this.ajaxDoneSpeaker = true
                        })
                }, 1000)
            },
            searchSpeakerSpeeches() {
                if(this.search_string){
                    //the previous data
                    //let tmp_speech_data = this.ajaxData.speechesData.data.data.filter(speech => {
                    let tmp_speech_data = this.period.speeches.data.data.filter(speech => {
                        // console.log(speech)
                        // console.log(speech.speech.includes(this.search_string))
                        return speech.speech.toLowerCase().includes(this.search_string)
                        // return post.title.toLowerCase().includes(this.search.toLowerCase())
                    })
                    //console.log(tmp_speech_data)

                    //the result found something
                    if(tmp_speech_data.length > 0){
                        this.search.speechesData = tmp_speech_data
                        this.search.noDataMsg = ''
                    }else{
                        //didn't found something
                        if(this.search.speechesData){
                            this.search.speechesData = []
                        }
                        this.search.noDataMsg = 'Δεν βρέθηκαν ομιλίες που να περιλαμβάνουν τις λέξεις που δώσατε.'
                    }
                }else{
                    //if search_string is empty , clear the array and the no message text
                    if(this.search.speechesData){
                        this.search.speechesData = []
                    }
                    this.search.noDataMsg = ''
                }
                // let search_data = {
                //     'input': this.search_string,
                //     'speaker_id': this.ajaxData.speakerData.speaker_id
                // }
                // this.ajaxData.isLoaded = false
                // setTimeout(() => {
                //     api.call('post', this.api_path + 'speaker/speeches/search/', search_data)
                //         .then( response => {
                //             if (response.status == 200) {
                //                 this.noDataSpeeches = false
                //                 this.ajaxData.speechesData = response
                //                 this.ajaxData.isLoaded = true
                //             } else {
                //                 this.noDataSpeeches = true
                //             }

                //             this.ajaxDoneSpeeches = true
                //         })
                // }, 500)
            },
            onlyUnique(value, index, self) { 
                return self.indexOf(value) === index;
            },
            getTimelineMembershipData() {
                setTimeout(() => {
                    api.call('get', this.api_path + 'speaker/' + this.speaker_id + '/timeline')
                    .then( response => {
                        if(response.status == 200 && response.data){
                            response.data.forEach( element => {
                                if (element.end_date) {
                                    this.ajaxData.timelineData.push([element.fullname_el, element.start_date, element.end_date])
                                } else {
                                    this.ajaxData.timelineData.push([element.fullname_el, element.start_date, '2018-10-4'])
                                }

                                // Make colors unique
                                this.ajaxData.memberships.party_colors.push(element.color)
                                this.ajaxData.memberships.party_colors = Array.from(new Set(this.ajaxData.memberships.party_colors))
                            })
                        }
                    })
                }, 500)
            }
        },
        computed: {
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        created() {
            this.loading = false
            // this.finalName = decodeURIComponent(this.name)
            // this.finalName = this.finalName.replace(/\+/g, " ")
            //this.finalName = this.$route.params.speaker_name
            this.speaker_id = this.$route.params.speaker_id
            this.getSpeakerData()
            //this.getSpeakerSpeeches()
            this.getSpeakerConferences()
            this.getTimelineMembershipData()
        }
    }
</script>