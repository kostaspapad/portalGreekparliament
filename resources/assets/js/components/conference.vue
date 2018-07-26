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
                <div class="conversation"  v-for="conference in ajaxData.conferenceData.data" :key="conference.speech_id">
                    <div class="rounded shadow-sm row" style="border: 1px solid #ddd;">
                        <div class="col-5 col-sm-3 col-md-2 col-lg-2">
                            <img :src="path + '/' + conference.image" class="img-fluid speech_speaker_img" style="border-radius: 40px;"/>
                        </div>
                        <div class="mt-2 col-7 col-sm-9 col-md-10 col-lg-10" style="/*margin-bottom: -15px;padding-left: 0;*/">
                            <span class="speech_speaker_name ml-2">{{conference.greek_name}}</span>
                        </div>
                        <br/>
                        <div class="speech mb-2 col-12">
                            <span>{{conference.speech}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="col-12 col-sm-12 col-md-12 col-lg-12">
                <h4>No speech available</h4>
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
        font-style: italic;
        font-weight: bold;
    }
    .speech_speaker_img{
        /* max-width: 80px; */
        /* width: 70%; */
        padding: 5px;
        /* margin: 13px 0 0 5px; */
    }
    .speech{
        /* margin: -50px 0 0 90px; */
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
                axios.get(this.$parent.host+'/api/v1/conference/'+ this.conf_date +'/speeches')
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        self.noData = false
                        self.ajaxData.conferenceData = response.data;
                    }
                    self.ajaxDone = true
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getConferenceInfo(){
                var self = this;
                axios.get(this.$parent.host+'/api/v1/conference/date/'+ this.conf_date)
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
