<template>
    <div class="container mt-2">
        <div v-if="ajaxDone" class="row">
            <div class="col-12">
                <h3><p>{{conf_date}}</p></h3>
                <p v-if="ajaxData.conferenceInfo">
                    <span>{{ajaxData.conferenceInfo.session}}</span>
                    <span>,</span>
                    <span>{{ajaxData.conferenceInfo.time_period}}</span>
                </p>
                <span v-if="ajaxData.conferenceInfo">
                    <a :href="startUrl + '/' + ajaxData.conferenceInfo.pdf_loc + '/' + ajaxData.conferenceInfo.pdf_name">
                        Download PDF <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                    </a>
                </span>
                <span v-if="ajaxData.conferenceInfo">
                    <a :href="startUrl + '/' + ajaxData.conferenceInfo.doc_location + '/' + ajaxData.conferenceInfo.doc_name">
                        Download word <i class="fas fa-file-word" style="color: #007bff;"></i>
                    </a>
                </span>
            </div>
            <div v-if="ajaxData.conferenceData.data.length" class="col-12 col-sm-12 col-md-12 col-lg-12">
                <!-- <div class="conversation"  v-for="conference in ajaxData.conferenceData.data" :key="conference.speech_id">
                    <div class="rounded shadow-sm row" style="border: 1px solid #ddd;">
                        <div class="row" style="background-color: #dc3545;">
                            <div class="col-5 col-sm-3 col-md-2 col-lg-2">
                                <img :src="path + '/' + conference.image" class="img-fluid speech_speaker_img"/>
                            </div>
                            <div class="mt-2 col-7 col-sm-9 col-md-10 col-lg-10" style="/*margin-bottom: -15px;padding-left: 0;*/">
                                <h2 class="speech_speaker_name ml-2">{{conference.greek_name}}</h2>
                            </div>
                        </div>
                        <br/>
                        <div class="speech mb-2 col-12">
                            <span>{{conference.speech}}</span>
                        </div>
                    </div>
                </div> -->
                <div class="card conversation" v-for="conference in ajaxData.conferenceData.data" :key="conference.speech_id">
                    <!-- <div v-if="conference.image != '' " class="card-img-top-bg">
                        <img :src="path + '/' + printImg(speaker.image) " class="img-fluid img-style card-img-top">
                    </div>
                    <div v-else class="card-img-top">
                        <img :src="path + '/' + printImg(speaker.image) " class="img-fluid img-style card-img-top">
                    </div> -->
                    <div class="mt-2">
                        <img :src="path + '/' + conference.image" class="img-fluid speech_speaker_img card-img-top ml-2 mb-2"/>
                        <h2 class="speech_speaker_name ml-2">
                            <a :href="/speaker/ + conference.greek_name" class="" style="margin-top:8px;">{{conference.greek_name}}</a>
                        </h2>
                        <h4 class="speech_speaker_name ml-2">{{conference.fullname_el | capitalize}} ({{conference.on_behalf_of_id}})</h4>
                    </div>
                    <hr/>
                    <div class="card-body speech mb-2">
                        <read-more more-str="read more" :text="conference.speech" link="#" less-str="read less" :max-chars="2000"></read-more>
                    </div>
                    <!-- <div class="links card-footer">

                    </div> -->
                </div>
            </div>
            <div v-else class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4>No speeches available</h4>
            </div>
            <!-- <div v-show="noData" class="col-12 col-sm-6 col-md-6 col-lg-8 scrollable">
                <h4>No data available</h4>
            </div> -->
        </div>
        <div v-else>
            <img :src="path + '/Spinner.gif' "/>
        </div>
    </div>
</template>
<style scoped>
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
    export default {
        props: {
            conf_date: null,
            path: String
        },
        data(){
            return {
                ajaxData: {
                    conferenceData: [],
                    conferenceInfo: null
                },
                noData: true,
                ajaxDone: false,
                defaultImg: 'default_speaker_icon.png',
                startUrl: 'https://www.hellenicparliament.gr' 
            }
        },
        methods:{
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
                var self = this;
                self.ajaxDone = false
                axios.get(this.$root.host+'/api/v1/conference/'+ this.conf_date +'/speeches')
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        self.noData = false
                        self.ajaxData.conferenceData = response.data;
                        // let speech = null;
                        // let test;
                        // for (const key in self.ajaxData.conferenceData.data) {
                        //     if (self.ajaxData.conferenceData.data.hasOwnProperty(key)) {
                        //         const element = self.ajaxData.conferenceData.data[key];
                        //         //console.log(self.ajaxData.conferenceData.data[key])
                        //         test = element.speech.replace(/\s{2,}/gm,' ')
                        //         //console.log(test)
                        //         //console.log(speech)
                        //         self.ajaxData.conferenceData.data[key].speech = test
                        //         speech = self.ajaxData.conferenceData.data[key].speech.replace(/\\n/gm, 'SPIROSSSSSSSSSSSSSSSSS')
                        //         self.ajaxData.conferenceData.data[key].speech = speech
                        //         // console.log('--------------------------------------')
                        //         // console.log(self.ajaxData.conferenceData.data[key])

                        //     }
                        // }
                    }
                    self.ajaxDone = true
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getConferenceInfo(){
                var self = this;
                axios.get(this.$root.host+'/api/v1/conference/date/'+ this.conf_date)
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        //self.noData = false

                        //because we only get one conference we add [0] to get it
                        self.ajaxData.conferenceInfo = response.data.data[0];
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        computed:{
            
        },
        created() {
            this.getConferenceSpeeches()
            this.getConferenceInfo()
        }
    }
</script>
