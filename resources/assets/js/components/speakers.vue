<template>
    <div class="container">
        <div v-if="speakersData.data" class="row">
            <div class="col-12">
                <pagination :data="speakersData" @pagination-change-page="changePage" :limit=2>
                    <span slot="prev-nav">&lt; Previous</span>
	                <span slot="next-nav">Next &gt;</span>
                </pagination>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4"  v-for="speaker in speakersData.data" :key="speaker.id" style="margin-bottom: 15px;">
                <div class="card">
                    <div v-if="speaker.image != '' " class="card-img-top">
                        <img :src="path + '/' + printImg(speaker.image) " class="img-fluid img-style">
                    </div>
                    <div v-else class="card-img-top">
                        <img :src="path + '/' + printImg(speaker.image) " class="img-fluid img-style">
                    </div>
                    <div class="card-body">
                        <div class="names">
                            <h5 class="card-title">Names </h5>
                            <p class="card-text">{{speaker.greek_name}} / {{speaker.english_name}}</p>
                        </div>
                        <div class="email addSpace">
                            <h5 class="card-title">Email </h5>
                            <p class="card-text">{{speaker.email}}</p>
                        </div>
                        <div class="website addSpace">
                            <h5 class="card-title">Website </h5>
                            <p class="card-text"><a :href="speaker.website">{{speaker.website}}</a></p>
                        </div>
                        <a @click="showModal(speaker)" href="#" class="btn btn-success" style="margin-top:8px;">View more</a>
                    </div>
                    <div class="links card-footer">
                        <span><a :href="speaker.wiki_el"><i class="fa fa-wikipedia-w iconsFont"></i></a>(EL) </span>
                        <span><a :href="speaker.wiki_en"><i class="fa fa-wikipedia-w iconsFont"></i></a>(EN) </span>
                        <span class="twitter">
                            <a :href="speaker.twitter">
                                <i class="fa fa-twitter-square iconsFont" style="color:#007bff;"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <pagination :data="speakersData" @pagination-change-page="changePage" :limit=2>
                    <span slot="prev-nav">&lt; Previous</span>
	                <span slot="next-nav">Next &gt;</span>
                </pagination>
            </div>
        </div>
        <div v-else>
            <img :src="path + '/Spinner.gif' "/>
        </div>
        <modal title="Details" :speaker="selected_speaker" modalClasses="mine" :is-large="true" v-if="show_modal" @close="show_modal = false">
            <div class="modal-body">
                <div class="img-body"><img :src="path + '/' + selected_speaker.image" class="img-fluid img-style"></div>
                <div class="details-content text-left">
                    <table class="table bordeless">
                        <thead>

                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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
                            <li v-if="selected_speaker.website != '' "><a :href="selected_speaker.website">{{selected_speaker.website}}</a></li>
                            <li v-if="selected_speaker.wiki_el != '' "><a :href="selected_speaker.wiki_el">{{selected_speaker.wiki_el}}</a></li>
                            <li v-if="selected_speaker.wiki_en != '' "><a :href="selected_speaker.wiki_en">{{selected_speaker.wiki_en}}</a></li>
                            <li v-if="selected_speaker.twitter != '' "><a :href="selected_speaker.twitter">{{selected_speaker.twitter}}</a></li>
                        </ul>
                    </div>
                    <div v-else>
                        <label>Links:</label>
                        <span>No Links</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="show_modal = false">Close</button>
            </div>
        </modal>
    </div>
</template>
<script>
    export default {
        props: {
            //speakers: Object,
            path: String
        },
        data(){
            return {
                users: [],
                user_id: 0,
                speakersData: [],
                selected_speaker: null,
                show_modal: false,
                defaultImg: 'default_speaker_icon.png'
            }
        },
        methods:{
            showModal(speaker){
                this.show_modal = true;
                console.log(speaker);
                this.selected_speaker = speaker;
            },
            changePage(page){
                var self = this;
                axios.get('http://95.85.38.123/api/speakers?page=' + page)
                .then(function(response){
                    self.speakersData = response.data.speakers;
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
                axios.get('http://95.85.38.123/api/speakers')
                .then(function(response){
                    self.speakersData = response.data.speakers;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            printImg(img){
                if(img == 'default-speaker.jpg'){
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
            
        },
        created() {
            this.getSpeakers();
            // var jsonParsed;
            // console.log(this.speakers);
            // jsonParsed = this.isJsonString(this.speakers);
            // this.speakersData = jsonParsed;
            // console.log(jsonParsed);
            
        }
    }
</script>
