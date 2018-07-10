<template>
    <div class="container">
        <div v-if="ajaxData" class="row">
           <div class="col-12 col-sm-3 col-md-3 col-lg-3 mt-2">
               <img :src="path + '/' + ajaxData.speakerData.image " alt="" class="img-fluid" style="width:50%;">
           </div>
           <div class="col-12 col-sm-9 col-md-9 col-lg-9 mt-2 p-0">
               <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a @click="currentTab = 'Information' " class="nav-item nav-link active" id="nav-information-tab" data-toggle="tab" href="#nav-information" role="tab" aria-controls="nav-information" aria-selected="true">Information</a>
                        <a @click="currentTab = 'Membership' " class="nav-item nav-link" id="nav-membership-tab" data-toggle="tab" href="#nav-membership" role="tab" aria-controls="nav-membership" aria-selected="false">Membership</a>
                        <a @click="currentTab = 'Speeches' " class="nav-item nav-link" id="nav-speeches-tab" data-toggle="tab" href="#nav-speeches" role="tab" aria-controls="nav-speeches" aria-selected="false">Speeches</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-information" role="tabpanel" aria-labelledby="nav-information-tab">
                        <table class="table mt-2 speaker-info" v-if="ajaxData.speakerData">
                            <thead>
                                
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ajaxData.speakerData.email}}</td>
                                </tr>
                                <tr>
                                    <td>Names</td>
                                    <td style="padding-right:0;">
                                        {{ajaxData.speakerData.greek_name}} 
                                        <span v-if="ajaxData.speakerData.greek_name != '' && ajaxData.speakerData.english_name != '' ">/</span> 
                                        {{ajaxData.speakerData.english_name}}</td>
                                </tr>
                                <tr>
                                    <td>Wiki EL</td>
                                    <td><a :href="ajaxData.speakerData.wiki_el">wiki_el</a></td>
                                </tr>
                                <tr>
                                    <td>Wiki EN</td>
                                    <td><a :href="ajaxData.speakerData.wiki_en">wiki_en</a></td>
                                </tr>
                                <tr>
                                    <td>Twitter</td>
                                    <td><a :href="ajaxData.speakerData.twitter">twitter</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="nav-membership" role="tabpanel" aria-labelledby="nav-membership-tab">{{currentTab}}</div>
                    <div class="tab-pane fade speeches-container" id="nav-speeches" role="tabpanel" aria-labelledby="nav-speeches-tab">
                        <div v-for="speech in ajaxData.speechesData" :key="speech.speech_id" class="speeches">
                            <!-- <blockquote class="blockquote">
                                <p>{{speech.speech}}</p>
                                <footer class="blockquote-footer"><small>{{speech.speech_conference_date}}</small></footer>
                            </blockquote> -->
                            <!-- <h5 class="mt-2" v-if="showDate">
                                <small>
                                    <mark  v-if="checkDate(speech.speech_conference_date)" style="background-color: #17a2b840;">{{conferenceDate}}</mark>
                                </small>
                            </h5> -->
                            <h5 class="mt-2">
                                <small>
                                    <mark>{{speech.speech_conference_date}}</mark>
                                </small>
                            </h5>
                            <div>
                                {{speech.speech}}
                            </div>
                        </div>
                    </div>
                </div>
           </div>
        </div>
        <div v-else>
            <img :src="path + '/Spinner.gif' "/>
        </div>
    </div>
</template>

<style scoped>
    .nav-tabs a{
        color: #17a2b8;
    }
    .speeches-container{
        height: 630px;
        overflow-y: scroll;
    }
    .speeches-container .speeches:not(:last-child){
        border-bottom: 1px dashed #17a2b8;
    }
    /* .speeches:last-child{
        border-bottom: none;
    } */
    .speaker-info td{
        border: 0;
    }
    @media (max-width: 352px) { 
        nav{
            font-size: 0.78rem;
        }
    }
</style>

<script>
    export default {
        props: {
            name: String,
            path: String
        },
        data(){
            return {
                ajaxData: {
                    speechesData: [],
                    speakerData: []
                },
                finalName: null,
                conferenceDate: null,
                showDate: true,
                currentTab: 'Information',
                defaultImg: 'default_speaker_icon.png'
            }
        },
        methods:{
            IsJsonString(str) {
                var json;
                try {
                    json = JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return json;
            },
            printImg(img){
                if(img == 'default-speaker.jpg' || img == null){
                    return this.defaultImg;
                }else{
                    return img;
                }
            },
            checkDate(conf_date){
                if(this.conferenceDate == null){
                    this.conferenceDate = conf_date
                }else{
                    if(this.conferenceDate == conf_date){
                        this.showDate = false
                    }else{
                        this.conferenceDate = conf_date
                        this.showDate = true
                    }
                }
            },
            getSpeakerSpeeches(){
                var self = this;
                axios.get(this.$parent.host+'/api/v1/speeches/speaker/name/'+this.finalName)
                .then(function(response){
                    console.log(response)
                    if(response.status == 200 && response.data.data){
                        self.ajaxData.speechesData = response.data.data
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getSpeakerData(){
                var self = this
                axios.get(this.$parent.host+'/api/v1/speaker/name/'+this.finalName)
                .then(function(response){
                    console.log(response)
                    if(response.status == 200 && response.data.data){
                        self.ajaxData.speakerData = response.data.data
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
            console.log(decodeURIComponent(this.name))
            this.finalName = decodeURIComponent(this.name)
            this.finalName = this.finalName.replace(/\+/g, " ")
            console.log(this.finalName)
            this.getSpeakerSpeeches()
            this.getSpeakerData()
        }
    }
</script>