<template>
    <div class="container">
        <div v-if="ajaxData.speakersData.data" class="row mt-5 speakers-bg">
            <!-- <search-input
                btn_text="Find"
                placeholder_text="Find speaker"
            ></search-input> -->
            <div class="mt-5 col-12 col-md-6 speaker-search">
                <search-plugin></search-plugin>
            </div>
            <!-- <div class="row w-100">
                <div class="col-12">
                    <p>{{search_result_msg}}</p>
                </div>
            </div> -->
            <div v-if="speaker_search_result_msg" class="row w-100">
                <div class="row w-100" v-if="!is_search_msg_empty && showResults" style="width:100%">
                    <!-- <div class="col-12 text-center"><p>{{search_result_msg}}</p></div> -->
                    <!-- <div class="col-12">
                        <pagination :data="ajaxData.search_data.data.meta" @pagination-change-page="changePageSpeaker" :limit=2>
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div> -->
                    <div class="col-12"  v-for="speaker in ajaxData.search_data.data.data" :key="speaker.id" style="margin-bottom: 15px;">
                        
                    </div>
                    <!-- <div class="col-12">
                        <pagination :data="ajaxData.search_data.data.meta" @pagination-change-page="changePageSpeaker" :limit=2>
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div> -->
                </div>
                <div class="row w-100" v-else>
                    <div class="col-12" style="padding-left: 2.5rem;">
                        <pagination :data="ajaxData.speakersData.data.meta" @pagination-change-page="changePage" :limit=1>
                            <span slot="prev-nav">&lt;</span>
                            <span slot="next-nav">&gt;</span>
                        </pagination>
                    </div>
                    <div class="col-12 speaker"  v-for="speaker in ajaxData.speakersData.data.data" :key="speaker.id" style="margin-bottom: 15px;">
                        <a :href="/speaker/ + speaker.greek_name" class="person-link">
                            <img :src="path + '/' + printImg(speaker.image) " class="person-img">
                            <h2 class="person-name text-left">{{speaker.greek_name}}</h2>
                            <p class="person-membership text-left">
                                <span class="party-name">{{speaker.party_fullname}}</span>
                                <span class="party-indicator" :style="{ backgroundColor: speaker.color }"></span>
                            </p>
                        </a>
                    </div>
                    <div class="col-12" style="padding-left: 2.5rem;">
                        <pagination :data="ajaxData.speakersData.data.meta" @pagination-change-page="changePage" :limit=1>
                            <span slot="prev-nav">&lt;</span>
                            <span slot="next-nav">&gt;</span>
                        </pagination>
                    </div>
                </div>
            </div>
            <div v-else></div>
        </div>
        <div v-else>
            <img :src="path + '/Spinner.gif' "/>
        </div>
    </div>
</template>

<style scoped>
    .speaker-search, .speaker{
        padding-left: 2rem;
    }
    .party-indicator{
        content: "";
        display: inline-block;
        height: 0.6em;
        width: 0.6em;
        -moz-border-radius: 0.6em;
        -webkit-border-radius: 0.6em;
        border-radius: 0.6em;
        margin-right: 0.3em;
        vertical-align: 0.05em;
    }
    .speakers-bg{
        background-color: #ffffff;
    }
    .person-link{
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
    .person-membership {
        margin: 0.2em -0.3em 0 0;
        line-height: 1.4em;
        color: #878582;
    }
    @media (max-width: 460px) {
        .person-name{
            font-size: 1.2rem;
        }
    }
</style>

<script>
    export default {
        props: {
            //speakers: Object,
            path: String
        },
        data(){
            return {
                ajaxData: {
                    speakersData: [],
                    search_data: []
                },
                selected_speaker: null,
                show_modal: false,
                defaultImg: 'default_speaker_icon.png',
                search_msg: '',
                search_result_msg: null,
                showResults: false
            }
        },
        methods:{
            showModal(speaker){
                this.show_modal = true;
                this.selected_speaker = speaker;
            },
            findSpeaker(){
                var self = this;
                axios.get(this.$root.host+'/api/v1/speakers/search/'+self.search_msg)
                .then(function(response){
                    if(response.status == 200 && response.data.data.length > 0){
                        self.ajaxData.search_data = response;
                        self.search_result_msg = "Search Results";
                        self.showResults = true;
                    }else{
                        self.search_result_msg = 'No results found';
                        self.showResults = false;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                }); 
            },
            changePage(page){
                var self = this;
                axios.get(this.$root.host+'/api/v1/speakers?page=' + page)
                .then(function(response){
                    self.ajaxData.speakersData = response;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePageSpeaker(page){
                var self = this;
                axios.get(this.$root.host+'/api/v1/speakers?page=' + page+'&name='+this.search_msg)
                .then(function(response){
                    self.ajaxData.search_data = response;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            isJsonString(str) {
                var json;
                try {
                    json = JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return json;
            },
            getSpeakers(){
                var self = this;
                axios.get(this.$root.host+'/api/v1/speakers')
                .then(function(response){
                    self.ajaxData.speakersData = response;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            printImg(img){
                if(img == 'default-speaker.jpg' || img == null){
                    return this.defaultImg;
                }else{
                    return img;
                }
            },
            checkLinks(speaker){
                if(speaker.wiki_el != '' || speaker.wiki_en != '' || speaker.website != '' || speaker.twitter != ''){
                    return true;
                }else{
                    return false;
                }
            }
        },
        computed:{
            is_search_msg_empty(){
                //when search_msg is empty return true to show the initial "data"
                if(this.search_msg.length > 0){
                    return false;
                }else{
                    if(this.search_msg.length == 0){
                        this.showResults = false;
                        this.search_result_msg = null;
                        return true;
                    }
                }
            },
            speaker_search_result_msg(){
                if(this.search_result_msg == 'Search Results' || this.search_result_msg == null){
                    return true;
                }else{
                    console.log(this.search_msg.length)
                    if(this.search_msg.length == 0){
                        this.showResults = false;
                        this.search_result_msg = '';
                        return false;
                    }
                }
            }
        },
        created() {
            this.getSpeakers();
        }
    }
</script>
