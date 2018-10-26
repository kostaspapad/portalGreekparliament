<template>
    <div class="container">
        <div class="chart-btn-div pointer d-inline-block mb-2" @click="showChart = !showChart" :class="showChart ? 'hide-text' : 'show-text'">
            <span v-if="!showChart">Show chart</span>
            <span v-else class="hide-letters">Hide Chart</span>
        </div>
        <transition name="slide-fade">
            <div class="small m-auto" v-if="showChart">
                <line-chart 
                    v-if="ajaxData.isLoaded"
                    :chart-data="ajaxData.memberships.start_dates" 
                    :chart-labels="ajaxData.memberships.start_dates"
                    :chart-bg-colors="ajaxData.memberships.party_colors"
                    :chart-party-labels="ajaxData.memberships.party_name_en"
                    :height="325"
                >
                </line-chart>
            </div>
        </transition>
        <div class="speaker-container">
            <div v-if="ajaxDoneSpeaker" class="row mr-0">
                <div class="w-100 bg-img">
                    <div class="container-fluid speaker-data">
                        <div class="speaker-info">
                            <div class="speaker-img">
                                <img :src=" '../img' + '/' + ajaxData.speakerData.image " class="img-fluid" style="margin: 5px 0 5px 0;">
                            </div>
                            <div class="speaker-name">
                                <div class="speaker-name-info">
                                    <h1 class="text-left">{{ajaxData.speakerData.greek_name}}</h1>
                                    <p>{{ajaxData.speakerData.email}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="float-left">
                            <div class="row">
                            <div v-if="ajaxData.speakerData.wiki_el"><span class="text-success">(el)</span><a :href="`${ajaxData.speakerData.wiki_el}`"><span class="fa fa-wikipedia-w iconsFont iconsColor"></span></a></div>
                            <div v-if="ajaxData.speakerData.wiki_en"><span class="text-success">(en)</span><a :href="`${ajaxData.speakerData.wiki_en}`"><span class="fa fa-wikipedia-w iconsFont iconsColor"></span></a></div>
                            <div v-if="ajaxData.speakerData.twitter"><a :href="`${ajaxData.speakerData.twitter}`"><span class="fa fa-twitter iconsFont iconsColor"></span></a></div>
                            </div>·
                        </div>
                    </div>
                    <div class="col-12 pr-5 mb-4 mt-4 float-right">
                        <div class="float-right">
                             <vs-input
                                vs-icon="search" 
                                placeholder="Αναζήτηση" 
                                v-model.trim="search_string"
                                @keypress.enter="searchSpeakerSpeeches"
                                style="width: 235px;color:inherit;"
                            />
                        </div>
                    </div>
                </div>
                <div class="container">
                    <vs-tabs vs-color='#17a2b8'>
                        <vs-tab vs-label="Speeches">
                            <div class="p-3 tab-pane fade show speeches-container mb-0">
                                <div v-if="ajaxDoneSpeeches && noDataSpeeches == false">
                                    <div v-for="speech in ajaxData.speechesData.data.data" :key="speech.speech_id" class="speeches py-2">
                                        <div class="row">
                                            <div v-if="speech.greek_name == ajaxData.speechesData.data.data.greek_name">
                                                <small>
                                                    <p><ins><a :href="`/speaker/${speech.greek_name}`" class="text-info">{{speech.greek_name}}</a></ins></p>
                                                    · {{speech.speech_conference_date}}
                                                </small>
                                            </div>
                                            <div v-else>
                                                <small><a :href="`/speaker/${speech.greek_name}`" class="text-info">{{speech.greek_name}}</a>
                                                    · {{speech.speech_conference_date}}</small>
                                            </div>
                                        </div>
                                        <div class="">
                                            <p class="text-left">{{speech.speech}}</p>
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
                                <div v-else>
                                    <h5>No speeches found</h5>
                                </div>
                            </div>
                        </vs-tab>
                        <vs-tab vs-label="Membership">
                            <div class="d-none d-md-block d-lg-block mb-0">
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
                <img :src=" '../img' + '/Spinner.gif' " class="m-auto d-block"/>
            </div>
        </div>
    </div>

</template>

<style lang="scss" scoped>
    $ContainerColor: white;

    .speaker-container {
        background-color: $ContainerColor;
        border-radius: 5px;
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
                    timelineData: []
                },
                finalName: null,
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
            }
        },
        methods: {
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
            changePage(page) {
                //for pagination
                var self = this
                let url = null

                if (this.finalName) {
                    url = 'speaker/name/' + this.finalName + '/speeches' + '?page=' + page
                }
                this.ajaxData.isLoaded = false
                api.call('get',this.api_path + url)
                    .then( response => {
                        if (response.status == 200 && response.statusText == "OK") {
                            self.ajaxData.speechesData = response
                            self.ajaxData.isLoaded = true
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
                const self = this
                this.ajaxData.isLoaded = false
                
                setTimeout(() => {
                    api.call('get',this.api_path + 'speaker/name/' + this.finalName + '/speeches')
                        .then( response => {
                            if (response.status == 200 && response.data.data.length > 0) {
                                self.noDataSpeeches = false
                                self.ajaxData.speechesData = response
                                self.ajaxData.isLoaded = true
                            } else {
                                self.noDataSpeeches = true
                            }
                            self.ajaxDoneSpeeches = true
                        })
                }, 1000)
            },
            getSpeakerData() {
                const self = this
                this.ajaxData.isLoaded = false
                setTimeout(() => {
                    api.call('get',this.api_path + 'speaker/name/' + this.finalName)
                        .then( response => {
                            if (response.status == 200 && response) {
                                self.noDataSpeaker = false
                                self.ajaxData.speakerData = response.data.data
                                self.ajaxData.isLoaded = true
                            } else {
                                self.noDataSpeaker = true
                            }
                            self.ajaxDoneSpeaker = true
                        })
                }, 1000)
            },
            searchSpeakerSpeeches() {
                const self = this

                var search_data = {
                    'input': self.search_string,
                    'speaker_id': self.ajaxData.speakerData.speaker_id
                }
                this.ajaxData.isLoaded = false
                setTimeout(() => {
                    api.call('post', this.api_path + 'speaker/speeches/search/', search_data)
                        .then( response => {
                            if (response.status == 200) {
                                self.noDataSpeeches = false
                                self.ajaxData.speechesData = response
                                self.ajaxData.isLoaded = true
                            } else {
                                self.noDataSpeeches = true
                            }

                            self.ajaxDoneSpeeches = true
                        })
                }, 500)
            },
            onlyUnique(value, index, self) { 
                return self.indexOf(value) === index;
            },
            getTimelineMembershipData() {
                setTimeout(() => {
                    api.call('get', this.api_path + 'speaker/name/' + this.finalName + '/timeline')
                    .then( response => {
                        if(response.status == 200 && response.statusText == "OK" && response.data){
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
            this.finalName = this.$route.params.speaker_name
            this.getSpeakerSpeeches()
            this.getSpeakerData()
            this.getTimelineMembershipData()
        }
    }
</script>