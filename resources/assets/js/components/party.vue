<template>
    <div class="container">
        <div class="party-container">
            <div v-if="ajaxDoneParty" class="row mr-0">
                <div class="w-100 bg-img">
                    <div class="container-fluid party-data">
                        <div class="party-info">
                            <div class="party-img">
                                <div v-if="ajaxData.partyData.data.data.image">
                                    <img :src=" '../img/parties/' + ajaxData.partyData.data.data.image" class="img-fluid" style="margin: 5px 0 5px 0;">
                                </div>
                                <div v-else>
                                    <img :src="'../img/parties/polical_party_default_image.png'" class="img-fluid" style="margin: 5px 0 5px 0;">
                                </div>
                            </div>
                            <div class="party-name">
                                <div class="party-name-info">
                                    <h1 class="text-left">{{ajaxData.partyData.data.data.fullname_el}}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <vs-tabs vs-color='#17a2b8'>
                        <vs-tab vs-label="Speakers">
                            <div class="p-3 tab-pane fade show speakers-container mb-0">
                                <div v-if="ajaxDoneSpeakers && noDataSpeakers == false">
                                    <pagination :data="ajaxData.speakersData.data.meta" @pagination-change-page="changePage" :limit=1>
                                        <span slot="prev-nav">&lt;</span>
                                        <span slot="next-nav">&gt;</span>
                                    </pagination>
                                    <div v-for="speaker in ajaxData.speakersData.data.data" :key="speaker.speaker_id" class="speakers py-2 mt-2">
                                        <div class="row">
                                            <router-link :to="'/speaker/' + speaker.speaker_id" class="person-link">
                                                <img :src=" '/img/' + printImg(speaker.image) " class="person-img">
                                                <p class="person-name text-left">{{speaker.greek_name}}</p>
                                            </router-link>
                                        </div>
                                    </div>
                                    <pagination :data="ajaxData.speakersData.data.meta" @pagination-change-page="changePage" :limit=1 class="mt-4">
                                        <span slot="prev-nav">&lt;</span>
                                        <span slot="next-nav">&gt;</span>
                                    </pagination>
                                </div>
                                <div v-else>
                                    <h5>No speakers found</h5>
                                </div>
                            </div>
                        </vs-tab>
                        <!-- <vs-tab vs-label="Membership">
                            
                        </vs-tab> -->
                    </vs-tabs>
                </div>
            </div>
            <div v-else>
                <div class="m-auto d-block lds-css ng-scope" style="width: 200px; height: 200px;"><div style="width:100%;height:100%" class="lds-ripple"><div></div><div></div></div></div>
            </div>
        </div>
    </div>

</template>


<style lang="scss" scoped>
    $ContainerColor: white;

    .party-container {
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
    .party-info {
        display: table;
    }

    .party-data {
        /* background:  rgba(0,0,0,0.4); */
        color: white;
    }

    .party-img {
        width: 70px;
        vertical-align: top;
    }

    .party-name {
        vertical-align: middle;
        padding-left: 10px;
    }

    .party-name p {
        font-size: 1.33333em;
    }

    .party-data .party-info .party-name,
    .party-data .party-info .party-img {
        display: table-cell;
    }

    .party-data .party-info .party-name h1 {
        font-size: 1.5rem;
        margin: 0px;
        padding: 0px;
        line-height: 1em;
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
    .person-link{
        cursor: hand;
        cursor: pointer;
        display: block;
        padding: 1.2em 0 1.2em 60px;
        border-top: 1px solid #f3f1eb;
        position: relative;
        color: inherit;
        width: fit-content;
    }
    .person-img {
        position: absolute;
        top: 1.2em;
        left: 0;
        width: 45px;
    }
    .person-name{
        color: inherit;
        margin: 0;
        line-height: 1.2em;
        font-size: 1.4rem;
    }
    .person-name:hover{
        color:#62b356;
    }
    .person-link:hover, .person-link:focus {
        text-decoration: none;
        color: inherit;
    }
    .person-link:hover .person-name, .person-link:focus .person-name {
        color: #62b356;
        text-decoration: underline;
    }
    @media (max-width: 460px) {
        .person-name{
            font-size: 1.2rem;
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
        data(){
            return {
                ajaxData: {
                    speakersData: [],
                    partyData: []
                },
                finalName: null,
                conferenceDate: null,
                showDate: true,
                currentTab: 'Speakers',
                defaultImg: 'polical_party_default_image.png',
                noDataSpeakers: true,
                noDataParty: true,
                loading: true,
                ajaxDoneParty: false,
                ajaxDoneSpeakers: false,
            }
        },
        methods:{
            printImg(img) {
                // console.log(img)
                if (img == 'default_speaker_icon.png' || img == null) {
                    return 'default_speaker_icon.png';

                } else {
                    return img;
                }
            },
            changePage(page) {
                var self = this;
                axios.get(this.api_path + 'party/' + this.ajaxData.partyData.data.data.party_id + '/speakers?page=' + page)
                .then(function(response) {
                    self.ajaxData.speakersData = response;
                })
            },
            getPartySpeeches(){
                var self = this;

                setTimeout(() => {
                    api.call('get', this.api_path + 'speeches/party/party_id/' + this.finalName)
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

            },
            getPartyData(){
                var self = this

                setTimeout(() => {
                    api.call('get', this.api_path + 'party/name/' + this.finalName)
                        .then(function (response) {
                            if (response.status == 200) {
                                self.noDataParty = false
                                self.ajaxData.partyData = response
                                
                                self.getPartySpeakers()

                            } else {
                                self.noDataParty = true
                            }
                            self.ajaxDoneParty = true
                        })
                }, 500)
                
            },
            getPartySpeakers() {
                var self = this

                setTimeout(() => {
                    api.call('get', this.api_path + 'party/' + this.ajaxData.partyData.data.data.party_id + '/speakers')
                        .then(function (response) {
                            if (response.status == 200) {
                                self.noDataSpeakers = false
                                self.ajaxData.speakersData = response
                            } else {
                                self.noDataSpeakers = true
                            }
                            self.ajaxDoneSpeakers = true
                        })
                }, 500)
            },
            // searchPartySpeeches() {
            //     return
            // }
        },
        computed:{
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        created() {
            this.loading = false
            
            this.finalName = this.$route.params.party_name
            this.finalName = this.finalName.replace(/\+/g, " ")
            
            // this.getPartySpeeches()
            this.getPartyData()
        }
    }
</script>