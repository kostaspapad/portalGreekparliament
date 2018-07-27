<template>
    <div class="container">
        <div v-if="ajaxData.speakersData.data" class="row">
            <div class="row w-100 mt-2">
                <div class="col-12">
                    <div class="input-group mb-3" style="width:275px;">
                        <input v-model.trim="search_msg" @keypress.enter="findSpeaker" type="text" class="form-control py-2 border-right-0 border" placeholder="Search" style="/*width:200px;*/" />
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent"><i class="fa fa-search"></i></span>
                        </div>
                        <div style="margin: 2px 0 0 5px;">
                            <button @click="findSpeaker" class="btn" style="background-color:#17a2b8;color:white;">Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row w-100">
                <div class="col-12">
                    <p>{{search_result_msg}}</p>
                </div>
            </div>
            <div v-if="speaker_search_result_msg" class="row w-100">
                <div class="row w-100" v-if="!is_search_msg_empty && showResults" style="width:100%">
                    <!-- <div class="col-12 text-center"><p>{{search_result_msg}}</p></div> -->
                    <div class="col-12">
                        <pagination :data="ajaxData.search_data.data.meta" @pagination-change-page="changePageSpeaker" :limit=2>
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 card-deck"  v-for="speaker in ajaxData.search_data.data.data" :key="speaker.id" style="margin-bottom: 15px;">
                        <div class="card" >
                            <div v-if="speaker.image != '' " class="card-img-top-bg">
                                <img :src="path + '/' + printImg(speaker.image) " class="img-fluid img-style card-img-top">
                            </div>
                            <div v-else class="card-img-top">
                                <img :src="path + '/' + printImg(speaker.image) " class="img-fluid img-style card-img-top">
                            </div>
                            <div class="card-body">
                                <div class="names">
                                    <h5 class="card-title">Names </h5>
                                    <span v-if="speaker.greek_name != '' ">{{speaker.greek_name}}</span>
                                    <span v-if="speaker.greek_name != '' && speaker.english_name != '' ">/</span>
                                    <span v-if="speaker.english_name != '' ">{{speaker.english_name}}</span>
                                </div>
                                <div class="email addSpace">
                                    <h5 class="card-title">Email </h5>
                                    <p class="card-text">{{speaker.email}}</p>
                                </div>
                                <div class="website addSpace">
                                    <h5 class="card-title">Website </h5>
                                    <p class="card-text"><a :href="speaker.website">{{speaker.website}}</a></p>
                                </div>
                            </div>
                            <div class="links card-footer">
                                <div>
                                    <span v-if="speaker.wiki_el == '' "><i class="fab fa-wikipedia-w iconsFont"></i>(EL) </span>
                                    <span v-else><a :href="speaker.wiki_el"><i class="fab fa-wikipedia-w iconsFont iconsColor"></i></a>(EL) </span>
                                    <span v-if="speaker.wiki_en == '' "><i class="fab fa-wikipedia-w iconsFont"></i>(EN) </span>
                                    <span v-else><a :href="speaker.wiki_en"><i class="fab fa-wikipedia-w iconsFont iconsColor"></i></a>(EN) </span>
                                    <span class="twitter" v-if="speaker.twitter == '' ">
                                            <i class="fab fa-twitter-square iconsFont"></i>
                                    </span>
                                    <span class="twitter" v-else>
                                        <a :href="speaker.twitter">
                                            <i class="fab fa-twitter-square iconsFont" style="color:#17a2b8;"></i>
                                        </a>
                                    </span>
                                </div>
                                <div>
                                    <button @click="showModal(speaker)" class="btn btn-info btn-sm" style="margin-top:8px;">View more</button>
                                    <a :href="/speaker/ + speaker.greek_name" class="btn btn-info btn-sm" style="margin-top:8px;">Show speeches</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <pagination :data="ajaxData.search_data.data.meta" @pagination-change-page="changePageSpeaker" :limit=2>
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
                <div class="row w-100" v-else>
                    <div class="col-12">
                        <pagination :data="ajaxData.speakersData.data.meta" @pagination-change-page="changePage" :limit=2>
                            <span slot="prev-nav">&lt;</span>
                            <span slot="next-nav">&gt;</span>
                        </pagination>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 card-deck"  v-for="speaker in ajaxData.speakersData.data.data" :key="speaker.id" style="margin-bottom: 15px;">
                        <div class="card" >
                            <div v-if="speaker.image != '' " class="card-img-top-bg">
                                <img :src="path + '/' + printImg(speaker.image) " class="img-fluid img-style card-img-top">
                            </div>
                            <div v-else class="card-img-top">
                                <img :src="path + '/' + printImg(speaker.image) " class="img-fluid img-style card-img-top">
                            </div>
                            <div class="card-body">
                                <div class="names">
                                    <h5 class="card-title">Names </h5>
                                    <p class="card-text">
                                        <span v-if="speaker.greek_name != '' ">{{speaker.greek_name}}</span>
                                        <span v-if="speaker.greek_name != '' && speaker.english_name != '' ">/</span>
                                        <span v-if="speaker.english_name != '' ">{{speaker.english_name}}</span>
                                    </p>
                                </div>
                                <div class="email addSpace" v-if="speaker.email != '' ">
                                    <h5 class="card-title">Email </h5>
                                    <p class="card-text">{{speaker.email}}</p>
                                </div>
                                <div class="website addSpace" v-if="speaker.website != '' ">
                                    <h5 class="card-title">Website </h5>
                                    <p class="card-text"><a :href="speaker.website">{{speaker.website}}</a></p>
                                </div>
                                <div>
                                    
                                </div>
                            </div>
                            <div class="links card-footer">
                                <div>
                                    <span v-if="speaker.wiki_el == '' "><i class="fab fa-wikipedia-w iconsFont"></i>(EL) </span>
                                    <span v-else><a :href="speaker.wiki_el"><i class="fab fa-wikipedia-w iconsFont iconsColor"></i></a>(EL) </span>
                                    <span v-if="speaker.wiki_en == '' "><i class="fab fa-wikipedia-w iconsFont"></i>(EN) </span>
                                    <span v-else><a :href="speaker.wiki_en"><i class="fab fa-wikipedia-w iconsFont iconsColor"></i></a>(EN) </span>
                                    <span class="twitter" v-if="speaker.twitter == '' ">
                                            <i class="fab fa-twitter-square iconsFont"></i>
                                    </span>
                                    <span class="twitter" v-else>
                                        <a :href="speaker.twitter">
                                            <i class="fab fa-twitter-square iconsFont" style="color:#17a2b8;"></i>
                                        </a>
                                    </span>
                                </div>
                                <div>
                                    <button @click="showModal(speaker)" class="btn btn-info btn-sm" style="margin-top:8px;">View more</button>
                                    <a :href="/speaker/ + speaker.greek_name" class="btn btn-info btn-sm" style="margin-top:8px;">Show speeches</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
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
        <modal title="Details" :speaker="selected_speaker" modalClasses="mine" :is-large="true" v-if="show_modal" @close="show_modal = false">
            <div class="modal-body">
                <div class="img-body"><img :src="path + '/' + selected_speaker.image" class="" style="width: 30%;"></div>
                <div class="details-content text-left">
                    <!-- <table class="table bordeless">
                        <thead>
                            <th>Greek name</th>
                            <th>English name</th>
                            <th>Email</th>
                            <th>Links</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table> -->
                    <div>
                        <label>Greek name:</label>
                        <span v-if="selected_speaker.greek_name != '' ">{{selected_speaker.greek_name}}</span>
                        <span v-else>Not available</span>
                    </div>
                    <div>
                        <label>English name:</label>
                        <span v-if="selected_speaker.english_name != '' ">{{selected_speaker.english_name}}</span>
                        <span v-else>Not available</span>
                    </div>
                    <div>
                        <label>Email:</label>
                        <span v-if="selected_speaker.email != '' ">{{selected_speaker.email}}</span>
                        <span v-else>Not available</span>
                    </div>
                    <div v-if="checkLinks(selected_speaker)">
                        <label style="float:left;">Links:</label>
                        <ul style="display:inline-block;padding-left: 15px;">
                            <li v-if="selected_speaker.website != '' "><a :href="selected_speaker.website">website</a></li>
                            <li v-if="selected_speaker.wiki_el != '' "><a :href="selected_speaker.wiki_el">wiki-el</a></li>
                            <li v-if="selected_speaker.wiki_en != '' "><a :href="selected_speaker.wiki_en">wiki-en</a></li>
                            <li v-if="selected_speaker.twitter != '' "><a :href="selected_speaker.twitter">twitter</a></li>
                        </ul>
                    </div>
                    <div v-else>
                        <label>Links:</label>
                        <span>No Links</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" @click="show_modal = false">Close</button>
            </div>
        </modal>
    </div>
</template>

<style scoped>
    /* hide the blue outline */
    .form-control:focus {
        outline: 0 !important;
        border-color: initial;
        box-shadow: none;
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
                axios.get(self.$parent.host+'/api/v1/speakers/search/'+self.search_msg)
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
                axios.get(this.$parent.host+'/api/v1/speakers?page=' + page)
                .then(function(response){
                    self.ajaxData.speakersData = response;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePageSpeaker(page){
                var self = this;
                axios.get(this.$parent.host+'/api/v1/speakers?page=' + page+'&name='+this.search_msg)
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
                axios.get(this.$parent.host+'/api/v1/speakers')
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
