<template>
    <div class="container">
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
                            <div class="input-group search-div">
                                <input class="form-control" 
                                    v-model.trim="search_string" 
                                    @keypress.enter="searchSpeakerSpeeches" 
                                    type="text" 
                                    placeholder="Search this speaker's speeches">
                                <span class="input-group-append">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <!-- <a @click="currentTab = 'Information' " class="nav-item nav-link active" id="nav-information-tab" data-toggle="tab" href="#nav-information" role="tab" aria-controls="nav-information" aria-selected="true">Information</a> -->
                            <a @click="currentTab = 'Speeches' " class="nav-item nav-link active" id="nav-speeches-tab"
                                data-toggle="tab" href="#nav-speeches" role="tab" aria-controls="nav-speeches"
                                aria-selected="true">Speeches</a>
                            <a @click="currentTab = 'Membership' " class="nav-item nav-link" id="nav-membership-tab"
                                data-toggle="tab" href="#nav-membership" role="tab" aria-controls="nav-membership"
                                aria-selected="false">Membership</a>
                        </div>
                    </nav>
                    <div class="tab-content py-3" id="nav-tabContent">
                        <div id="nav-speeches" 
                            class="p-3 tab-pane fade show speeches-container active" 
                            role="tabpanel"
                            aria-labelledby="nav-speeches-tab">
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
                        <div class="tab-pane fade" id="nav-membership" role="tabpanel" aria-labelledby="nav-membership-tab">{{currentTab}}</div>
                    </div>
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

    // .speeches-container {
    //     height: 630px;
    //     overflow-y: scroll;
    // }

    /* .speeches-container .speeches:not(:last-child){
        border-bottom: 1px dashed #17a2b8;
    }*/
    /* .speeches:last-child{
        border-bottom: none;
    } */
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
    export default {
        props: {
            name: String,
            path: String
        },
        data() {
            return {
                ajaxData: {
                    speechesData: [],
                    speakerData: []
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
                search_string: null
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

                axios.get(this.api_path + url)
                    .then(function (response) {
                        if (response.status == 200 && response.statusText == "OK") {
                            self.ajaxData.speechesData = response
                        }
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
            },
            IsJsonString(str) {
                var json;
                try {
                    json = JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return json;
            },
            printImg(img) {
                if (img == 'default-speaker.jpg' || img == null) {
                    return this.defaultImg;
                } else {
                    return img;
                }
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
                
                setTimeout(() => {
                    axios.get(this.api_path + 'speaker/name/' + this.finalName + '/speeches')
                        .then(function (response) {
                            if (response.status == 200 && response.data.data.length > 0) {
                                self.noDataSpeeches = false
                                self.ajaxData.speechesData = response
                            } else {
                                self.noDataSpeeches = true
                            }
                            self.ajaxDoneSpeeches = true
                        })
                }, 1000)
            },
            getSpeakerData() {
                const self = this

                setTimeout(() => {
                    axios.get(this.api_path + 'speaker/name/' + this.finalName)
                        .then(function (response) {
                            if (response.status == 200 && response) {
                                self.noDataSpeaker = false
                                self.ajaxData.speakerData = response.data.data
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

                setTimeout(() => {
                    api.call('post', this.api_path + 'speaker/speeches/search/', search_data)
                        .then(function (response) {
                            if (response.status == 200) {
                                self.noDataSpeeches = false
                                self.ajaxData.speechesData = response

                            } else {
                                self.noDataSpeeches = true
                            }

                            self.ajaxDoneSpeeches = true
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
        }
    }
</script>