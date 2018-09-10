<template>
    <div class="">
        <div v-if="ajaxData" class="row mr-0">
           <!-- <div class="col-12 col-sm-4 col-md-4 col-lg-3 mt-2"> -->
            <!-- <div class="w-100 bg-img" :style="{ backgroundColor: ajaxData.speakerData.color }"> -->
            <div class="w-100 bg-img">
                <div class="container-fluid speaker-data">
                    <div class="speaker-info">
                        <div class="speaker-img">
                            <img :src="path + '/' + ajaxData.speakerData.image " class="img-fluid" style="margin: 5px 0 5px 0;">
                        </div>
                        <div class="speaker-name">
                            <div class="speaker-name-info">
                                <h1 class="text-left">{{ajaxData.speakerData.greek_name}}
                                    <!-- <span v-if="ajaxData.speakerData.greek_name != '' && ajaxData.speakerData.english_name != '' ">/</span> 
                                    {{ajaxData.speakerData.english_name}} -->
                                </h1>
                                <p>{{ajaxData.speakerData.email}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="speaker-social-links">
                        
                        <!-- <span><a :href="ajaxData.speakerData.wiki_el">wiki_el</a></span>
                        <span>
                            <a :href="ajaxData.speakerData.wiki_en">
                                wiki_en
                                <i class="fab fa-wikipedia-w"></i>
                            </a>
                        </span>
                        <span>
                            <a :href="ajaxData.speakerData.twitter">
                                Twitter
                                <i class="fab fa-twitter" style="color: #007bff;"></i>
                            </a>
                        </span> -->
                        <span v-if="ajaxData.speakerData.wiki_el == '' "><i class="fab fa-wikipedia-w iconsFont"></i>(EL) </span>
                        <span v-else><a :href="ajaxData.speakerData.wiki_el"><i class="fab fa-wikipedia-w iconsFont iconsColor"></i></a>(EL) </span>
                        <span v-if="ajaxData.speakerData.wiki_en == '' "><i class="fab fa-wikipedia-w iconsFont"></i>(EN) </span>
                        <span v-else><a :href="ajaxData.speakerData.wiki_en"><i class="fab fa-wikipedia-w iconsFont iconsColor"></i></a>(EN) </span>
                        <span class="twitter" v-if="ajaxData.speakerData.twitter == '' ">
                            <i class="fab fa-twitter iconsFont"></i>
                        </span>
                        <span class="twitter" v-else>
                            <a :href="ajaxData.speakerData.twitter">
                                
                                <i class="fab fa-twitter iconsFont iconsColor"></i>
                            </a>
                        </span>
                    </div>
                </div>
                <div class="col-12 pr-5 mb-4 mt-4 float-right">
                    <div class="float-right">
                        <div class="input-group search-div">
                            <input class="form-control" type="search" placeholder="Search this speaker's speeches">
                            <span class="input-group-append">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- <div v-if="ajaxData.speakerData" class="mt-2">
                    <div>
                        {{ajaxData.speakerData.greek_name}} 
                        <span v-if="ajaxData.speakerData.greek_name != '' && ajaxData.speakerData.english_name != '' ">/</span> 
                        {{ajaxData.speakerData.english_name}}
                    </div>
                    <div>
                        <span>{{ajaxData.speakerData.email}}</span>
                    </div>
                    <div>
                        <a :href="ajaxData.speakerData.wiki_el">wiki_el</a>
                    </div>
                    <div>
                        <a :href="ajaxData.speakerData.wiki_en">wiki_en</a>
                    </div>
                    <div>
                        <a :href="ajaxData.speakerData.twitter">Twitter</a>
                    </div>
                </div> -->
           </div>
                <!-- <div class="col-12 col-sm-8 col-md-8 col-lg-9 mt-2"> -->
                <div class="container  mt-2 p-0">
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
                        <!-- <div class="tab-pane fade show active" id="nav-information" role="tabpanel" aria-labelledby="nav-information-tab">
                        
                    </div> -->
                        <div class="tab-pane fade show speeches-container active" id="nav-speeches" role="tabpanel"
                            aria-labelledby="nav-speeches-tab">
                            <div v-if="ajaxDoneSpeeches">
                                <div v-for="speech in ajaxData.speechesData.data.data" :key="speech.speech_id" class="speeches py-2">
                                    <div class="row">
                                        <small><a :href="`/speaker/${speech.greek_name}`" class="text-info">{{speech.greek_name}}</a> · {{speech.speech_conference_date}}</small>
                                    </div>
                                    <div class="">
                                        <p class="text-left">{{speech.speech}}</p>
                                    </div>
                                </div>
                                <div class="col-12 mt-5" style="padding-left: 2.5rem;">
                                    <pagination :data="ajaxData.speechesData.data.meta" @pagination-change-page="changePage" :limit=1>
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
                <img :src="path + '/Spinner.gif' " />
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
    .bg-img{
        background-image: url(/img/bgvouli.jpg);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: contain;
    }
    .speeches-container{
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
    .speaker-info{
        display: table;
    }
    .speaker-data{
        /* background:  rgba(0,0,0,0.4); */
        color: white;   
    }
    .speaker-img{
        width: 70px;
        vertical-align: top;
    }
    .speaker-name{
        vertical-align: middle;
        padding-left: 10px;
    }
    .speaker-name p{
        font-size: 1.33333em;
    }
    .speaker-data .speaker-info .speaker-name,.speaker-data .speaker-info .speaker-img{
        display: table-cell;
    }
    .speaker-data .speaker-info .speaker-name h1{
        font-size: 1.5rem;
        margin: 0px;
        padding: 0px;
        line-height: 1em;
    }
    .speaker-data .speaker-info .speaker-social-links span a{
        color: inherit!important;
    }
    .speaker-data .speaker-info .speaker-social-links span a:hover{
        color: #62b356;
    }
    .search-div{
        width: 18rem;
    }
    .search-div input{
        border-right-style: none;
        border-radius: 2px;
        font-style: italic;
    }
    .search-div input:focus{
        border-color: #ced4da;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .search-div span{
        padding: 13px 16px;
        background-color: #fff;
        border: 1px solid;
        border-color: #ced4da;
        border-radius: 2px;
    }
    .nav-tabs .nav-link.active{
        background: transparent;
        border-color: transparent;
        border-bottom-color: #6c6b68;
    }
    @media (max-width: 352px) { 
        nav{
            font-size: 0.78rem;
        }
    }
    @media (max-width: 768px) { 
        .bg-img{
            background-size: auto;
        }
    }
    @media (min-width: 1040px) { 
        .speaker-social-links{
            position: absolute;
            right: 2.6em;
            margin-top: -1em;
        }
    }
    @media (max-width: 1040px) { 
        .speaker-social-links{
           text-align: left;
        }
    }
</style>

<script>
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
                order_orientation: 'asc'
            }
        },
        methods: {
            sortBy(sortField, sortOrientation = null){
                if(sortField){
                    this.order_field = sortField
                }
                if(sortOrientation == 'asc'){
                    this.order_orientation = 'desc'
                }else{
                    this.order_orientation = 'asc'
                }
                if(this.order_field && this.order_orientation){
                    this.getSpeakers()
                }
            },
            changePage(page){
                //for pagination
                var self = this
                let url = null

                if (this.finalName) {
                    url = '/api/v1/speeches/speaker/name/' + this.finalName + '?page=' + page
                }
                
                axios.get(this.$root.host + url)
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
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
                const self = this;

                setTimeout( () => {
                    axios.get(this.$root.host + '/api/v1/speeches/speaker/name/' + this.finalName)
                    .then(function (response) {
                        if (response.status == 200 && response) {
                            self.noDataSpeeches = false
                            self.ajaxData.speechesData = response
                        } else {
                            self.noDataSpeeches = true
                        }
                        self.ajaxDoneSpeeches = true 

                    }).catch(function (error) {
                        console.log(error);
                    });

                }, 1000)
            },
            getSpeakerData() {
                const self = this

                setTimeout( () => {
                    axios.get(this.$root.host + '/api/v1/speaker/name/' + this.finalName)
                    .then(function (response) {
                        if (response.status == 200 && response) {
                            self.noDataSpeaker = false
                            self.ajaxData.speakerData = response.data.data
                        } else {
                            self.noDataSpeaker = true
                        }
                        self.ajaxDoneSpeaker = true

                    }).catch(function (error) {
                        console.log(error);
                    });

                } ,1000)
            }
        },
        computed: {

        },
        created() {
            this.loading = false
            this.finalName = decodeURIComponent(this.name)
            this.finalName = this.finalName.replace(/\+/g, " ")
            console.log(this.finalName)
            this.getSpeakerSpeeches()
            this.getSpeakerData()
        }
    }
</script>