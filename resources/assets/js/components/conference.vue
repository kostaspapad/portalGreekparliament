<template>
    <div class="container">
        <div class="conferences-container">
            <div v-if="ajaxDoneConfInfo">
                <div class="conference-title-box py-4">
                    <h2 class="font-weight-bold">Conference · {{conf_date}}</h2>
                    <div>{{ajaxData.conferenceInfo.data.data.session}}</div>
                    <div>{{ajaxData.conferenceInfo.data.data.time_period}}</div>
                </div>
                <div class="col-12 pt-3">
                    <span v-if="ajaxData.conferenceInfo">
                        <a :href="startUrl + '/' + ajaxData.conferenceInfo.data.data.pdf_loc + '/' + ajaxData.conferenceInfo.data.data.pdf_name">
                            Download PDF <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                        </a>
                        <a :href="startUrl + '/' + ajaxData.conferenceInfo.data.data.doc_location + '/' + ajaxData.conferenceInfo.data.data.doc_name">
                            Download word <i class="fas fa-file-word" style="color: #007bff;"></i>
                        </a>
                    </span>
                </div>
                <div v-if="ajaxDoneConfSpeeches" class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div v-for="conference in ajaxData.conferenceData.data.data" :key="conference.speech_id">
                        <speech :speech="conference"></speech>
                    </div>
                </div>
                <div v-else>
                    <h4>No speeches available</h4>
                </div>
            </div>
            <div v-else class="col-12 col-sm-12 col-md-12 col-lg-12">
                <img :src=" '../../img' + '/Spinner.gif' " class="m-auto d-block"/>
            </div>
        </div>
    </div>
</template>
<style scoped>

    .conferences-bg {
        background-color: #ffffff;
    }
    .conferences-container {
        background-color: lightgrey;
        border-radius: 5px;
    }

    .conference-title-box {
        background-color: #dbf0ff;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
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
            //conf_date: null,
            path: String
        },
        data() {
            return {
                ajaxData: {
                    conferenceData: [],
                    conferenceInfo: null
                },
                conf_date: null,
                defaultImg: 'default_speaker_icon.png',
                startUrl: 'https://www.hellenicparliament.gr',
                ajaxDoneConfInfo: false,
                ajaxDoneConfSpeeches: false,
                noDataConfInfo: true,
                noDataConfSpeeches: true,
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

                setTimeout( () => {
                    axios.get(this.$root.host+'/api/v1/conference/'+ this.conf_date +'/speeches')
                    .then(function(response){
                        if(response.status == 200 && response.statusText == "OK"){
                            self.noDataConfSpeeches = false
                            self.ajaxData.conferenceData = response
                        } else {
                            self.noDataConfSpeeches = true
                        }
                        self.ajaxDoneConfSpeeches = true
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
                }, 1000)
            },
            getConferenceInfo(){
                var self = this;
                
                setTimeout( () => {
                    axios.get(this.$root.host+'/api/v1/conference/date/'+ this.conf_date)
                    .then(function(response){
                        if(response.status == 200 && response.statusText == "OK"){
                            self.noDataConfInfo = false
                            //because we only get one conference we add [0] to get it
                            self.ajaxData.conferenceInfo = response
                        } else {
                            self.noDataConfInfo = true
                        }
                        self.ajaxDoneConfInfo = true
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 1000)
            }
        },
        computed:{
            
        },
        created() {
            this.$route.params.conference_date ? this.conf_date = this.$route.params.conference_date : null          
            this.getConferenceInfo()
            this.getConferenceSpeeches()
        }
    }
</script>
