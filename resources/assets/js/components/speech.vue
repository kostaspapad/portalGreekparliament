<template>
    <div v-if="isDone">
        <!-- Speaker Profile -->
        <div v-if="isFromSpeakerProfile" class="speakerProfile-speech-data">
            <div class="row">
                <!-- <div v-if="speech.greek_name == ajaxData.speechesData.data.data.greek_name">
                    <small>
                        <p><ins><router-link :to="/speaker/+speech_data.speaker_id" class="text-info">{{speech_data.greek_name}}</router-link></ins></p>
                        · {{speech_data.speech_conference_date}}
                    </small>
                </div> -->
                <div>
                    <small><router-link :to="/speaker/+speech_data.speaker_id" class="text-info">{{speech_data.greek_name}}</router-link>
                        · {{speech_data.speech_conference_date}}</small>
                </div>
            </div>
            <div class="speech-container-speech pt-2">
                <read-more more-str="περισσότερα" :text="speech_data.speech" link="#" less-str="λιγότερα" :max-chars="2000"></read-more>
            </div>
        </div>
        <!-- End Speaker Profile -->

        <div v-if="speech_data.speech_id && speech_data.greek_name && speech_data.missing_prev && !isFromSearch && !isFromSpeakerProfile && isFromConference">
            <div class="speech-data-container p-2">
                <div class="row" style="margin-right: 0;">
                    <div class="speech-container-speech ml-2 pt-2">
                        Η ομιλία δεν ειναι διαθέσιμη
                    </div>
                </div>
            </div>
        </div>
        <div v-if="speech_data.speech_id && speech_data.greek_name && (isFromConference || singleSpeech)" :class="singleSpeech ? 'container' : 'speech-container3' " class="py-2">
            <div class="speech-data-container p-2">
                <div class="row" style="margin-right: 0;">
                    <div class="speaker-image-container">
                        <div v-if="speech_data.image">
                            <img :src="'/img/' + speech_data.image" class="speech_speaker_img"/>
                        </div>
                    </div>
                    <div class="speech-container-speaker" >
                        <h4 class="speech_speaker_name ml-2" :class="isFromSearchPage ? 'appear-in-search' : ''">
                            <router-link :to="/speaker/ + speech_data.speaker_id" class="" style="margin-top:8px;">{{speech_data.greek_name}}</router-link>
                        </h4>
                        <div class="speech_speaker_party ml-2" v-bind:style="getPartyColor" :class="isFromSearchPage ? 'appear-in-search' : ''">
                            <router-link :to=" /party/ + speech_data.fullname_el" style="color: inherit;">
                                <h5>{{speech_data.fullname_el | capitalize}} </h5>
                            </router-link>
                        </div>
                    </div>
                    
                    <div v-if="speech" class="ml-auto d-none d-sm-none d-md-block">
                        <div v-if="showDropButton">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-th-list"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">
                                    <div class="text-center mt-2">
                                        <router-link :to="/speech/ + speech_data.speech_id">
                                            <h6>Μετάβαση στην ομιλία</h6>
                                        </router-link>
                                    </div>
                                </a>
                                <!-- <a class="dropdown-item" href="#">
                                    <div v-if="speech && isFromSearchPage && !singleSpeech" class="text-center mt-2">
                                        <router-link :to="/conference/ + speech_data.speech_conference_date + '/speeches' ">
                                            Μετάβαση στην συνεδρία
                                            {{ $helpers.myFormattedDate(speech_data.speech_conference_date,'el') }}
                                        </router-link>
                                    </div>
                                </a> -->
                                <a v-if="isFromSearchPage" class="dropdown-item" href="#">
                                    <div class="text-center mt-2">
                                        <router-link :to="/conference/ + speech_data.speech_conference_date + '/speeches' ">
                                            <h6>Μετάβαση στην συνεδρία {{ $helpers.myFormattedDate(speech_data.speech_conference_date,'el') }}</h6>
                                        </router-link>
                                    </div>
                                </a>
                            </div>  
                            
                        </div>
                    </div>
                </div>

                    <!-- class="d-block d-sm-block d-md-none"  -->
                <div v-if="speech" :class="[ {'d-sm-block': showDropButton}, {'d-md-none': showDropButton},'d-block']">
                    <div v-if="isFromSearchPage" class="speech-link col-12 text-center mt-2">
                        <h5>{{ $helpers.myFormattedDate(speech_data.speech_conference_date,'el') }}</h5>
                        <router-link :to="/conference/ + speech_data.speech_conference_date + '/speeches' ">
                            <h5>Μετάβαση στην συνεδρία</h5>
                        </router-link>
                    </div>
                    <div class="speech-link col-12 text-center mt-2">
                        <router-link :to="/speech/ + speech_data.speech_id">
                            <h5>Μετάβαση στην ομιλία</h5>
                        </router-link>
                    </div>
                </div>
                
                <div class="speech-container-speech ml-2 pt-2" style="white-space: pre-line;" v-if="!isFromSearchPage">
                    <read-more more-str="περισσότερα" :text="speech_data.speech" link="#" less-str="λιγότερα" :max-chars="2000"></read-more>
                </div>
                <div class="speech-container-speech ml-2 pt-2" style="white-space: pre-line;" v-else>
                    <!-- <read-more more-str="περισσότερα" :text="speech_data.speech" link="#" less-str="λιγότερα" :max-chars="200" ></read-more> -->
                    <p>{{ speech_data.speech.substring(0,200) + '...' }}</p>
                </div>
                
                <div v-if="user && !isFromSearchPage" class="speech-actions mt-4">
                    <!-- Favorite -->
                    <favorite
                        :speech_id='speech_data.speech_id'
                        :favorited='speech_data.isFavorite'
                    ></favorite>
                    <!-- End of favorite -->
                    <div class="d-inline-block comment-text pointer" @click="isCommentOn = !isCommentOn" :class="isCommentOn ? 'hide-text' : 'show-text'">
                        <span v-if="!isCommentOn" class="show-letters d-none d-sm-block">{{ $t("speeches.show_comments") }} <i class="fas fa-comments"></i></span>
                        <span v-if="!isCommentOn" class="show-letters d-block d-sm-none">Σχόλια <i class="fas fa-comments"></i></span>
                        <span v-else class="hide-letters">{{ $t("speeches.hide_comments") }}</span>
                    </div>
                    <div class="d-inline-block report-button">
                        <vs-button 
                            color="rgb(255, 71, 87)" 
                            type="filled" 
                            @click="addReport" 
                            style="padding: 1vh;"
                            icon-pack="fas"
                            icon="fa-exclamation-triangle"
                            icon-after
                        >
                            {{ $t("speeches.report") }}
                        </vs-button>
                    </div>
                    <!-- Comments -->
                    <transition name="slide-fade">
                        <div v-if="isCommentOn" class="comments-area mt-2">
                            <comments v-if="!singleSpeech" :speech_id="speech_data.speech_id"></comments>
                            <comments v-else :speech_id="speech_data.speech_id" singleSpeech=true></comments>
                        </div>
                    </transition>
                    <!-- End of Comments -->
                    <!-- Modal -->
                    <modal v-if="showModal" title="Αναφορά" @close="closeModal" isLarge="true">
                        <slot>
                            <div class="modal-report-area">
                                <TextareaAutogrow 
                                    v-model="report_text"
                                    placeholder=""
                                    classes="form-control form-control-line textarea"
                                />
                                <div class="mt-2 text-right">
                                    <button @click="sendReport" :disabled="isDisabled" :class="{ 'not-allowed': isDisabled }" class="btn send-button">
                                        {{ $t("speeches.submit") }} <i class="fas fa-paper-plane"></i> 
                                    </button>
                                </div>
                            </div>
                        </slot>
                    </modal>
                    <!-- End of Modal -->
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .speech_speaker_name {
        a {
            color: #1b1b1b;
        }
        // color: red;
    }
    .speech-container {
        text-align: left;
        
    }
    .speech-data-container {
        background-color: #fff;
        color: #373737;
        box-shadow: 0 2px 3px rgba(10,10,10,.1), 0 0 0 1px
        rgba(10,10,10,.1);
        
    }
    @media only screen and (min-width: 550px) {
        .speech-data-container {
            padding: 1rem !important;
        }
    }
    @media only screen and (max-width: 340px) {
        .report-button {
            button {
                padding: 7px!important;
                font-size: .8em;
            }
        }
    }
    .speech_speaker_img{
        border-radius: 5%;
        // border: 1px solid #35495e;
        max-width: 60px !important;
    }
    .speech-container-options {
        text-align: right;
    }
    .comment-text {
        padding: 1vh;
        // background: #33a8ff63;
        border-radius: 3px;
        color: #4e5356;
        position: relative;
        top: -4px;
        transition: background .8s ease;
        box-shadow: 0 2px 3px rgba(10,10,10,.1), 0 0 0 1px
        rgba(10,10,10,.1);
        background-color: #fff;
        // color: #373737;
    }
    .comment-text.show-text {
        // animation-direction: alternate;
        // animation: showText 2s both;

        // @keyframes showText {
        //     0% {
        //         transform: rotateY(180deg);
        //     }
        //     100% {
        //         transform: rotateY(0deg);
        //     }
        // }
    }
    .comment-text.hide-text {
        // background: #CD5C5C;
        background: rgb(72, 73, 100);
        color: white;
        // transform: rotate(360deg);
        //transform: rotateY(180deg);
        // animation-direction: alternate;
        // animation: hideText 2s both;

        // @keyframes hideText {
        //     0% {
        //         transform: rotateY(0);
        //     }
        //     100% {
        //         transform: rotateY(180deg);
        //     }
        // }
    }
    .show-text > .show-letters {
        backface-visibility: hidden;
    }
    .hide-text > .hide-letters{
        // animation-direction: alternate;
        // animation: letters 2s both;
        backface-visibility: hidden;
        // @keyframes letters {
        //     0% {
        //         transform: rotateY(0);
        //     }
        //     100% {
        //         transform: rotateY(360deg);
        //     }
        // }
    }
    .report-button{
        position: absolute;
        right: 24px;
    }
    .modal-report-area{
        padding: 3em;
    }
    @media only screen and (min-width: 768px) and (max-width: 835px) {
        .speech_speaker_name.appear-in-search {
           font-size: 11px!important;
        }
        .speech_speaker_party.appear-in-search {
            a {
                h5 {
                    font-size: 11px!important;
                }
            }
        }
    }
    @media only screen and (min-width: 768px) and (max-width: 991px) {
        .speech_speaker_name.appear-in-search {
           font-size: 1.3em;
        }
        .speech_speaker_party.appear-in-search {
            a {
                h5 {
                    font-size: 1em;
                }
            }
        }
    }
</style>
<script>
    import { mapState, mapGetters, mapActions } from 'vuex'
    export default {
        props: {
            speech: null,
            isFromSearch: {
                default: false
            },
            isFromSpeakerProfile: {
                default: false
            },
            isFromConference: {
                default: false
            },
            isFromSearchPage: {
                default: false,
                type: Boolean
            },
            showDropButton: {
                default: false,
                type: Boolean
            }
        },
        data() {
            return {
                isCommentOn: false,
                speech_data: null,
                isDone: false,
                showModal: false,
                report_text: '',
                singleSpeech: false,
                startInterval: false
            }
        },
        methods: {
            addReport() {
                this.showModal = true
            },
            closeModal(){
                this.showModal = false
            },
            clearVariables() {
                this.report_text = ''
            },
            sendReport(){
                if(this.report_text){
                    let data = {
                        speech_id: this.speech_data.speech_id,
                        issue: this.report_text
                    }
                    api.call('post', this.api_path + 'reports/create',data)
                    .then( data => {
                        if(data.data.msg == "Report Submitted" && data.statusText == "Created" && data.status == 201){
                            this.closeModal()
                            this.clearVariables()
                        }
                    })
                }
            },
            ...mapActions([
                'GET_COMMENTS_CONFERENCE'
            ])
        },
        computed: {
            isDisabled(){
                return this.report_text ? false : true
            },
            getPartyColor() {
                return {
                    'color': this.speech_data.party_color
                }
            },
            isFavorited() {
                // console.log(this.user)
                if (this.speech_data.favorite === this.user.id) {
                    return true
                } else {
                    return false
                }
            },
            ...mapGetters({
                user: 'get_user'
            }),
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
         watch: {
            startInterval: function(newVal) {
                if(newVal){
                    this.$options.interval = setInterval( () => {
                        let data = { 
                            data: this.$route.params.speech_id, choice: 'single_speech' 
                        }
                        this.GET_COMMENTS_CONFERENCE(data)
                    }, 60000)
                }
            }
        },
        mounted() {
            if(!this.speech){
                api.call('get', this.api_path + 'speech/' + this.$route.params.speech_id)
                .then( data => {
                    if( data.status == 200 && data.data ) {
                        this.speech_data = data.data.data
                        this.isDone = true
                        this.singleSpeech = true
                    }
                })
                 if(this.user){
                    let data = {
                        data: this.$route.params.speech_id, choice: 'single_speech'
                    }
                    this.GET_COMMENTS_CONFERENCE(data)
                    this.startInterval = true
                }
            }else{
                this.speech_data = this.speech
                this.isDone = true
            }
        },
        beforeDestroy(){
            if(this.$options.interval){
                clearInterval(this.$options.interval)
            }
        }
    }
</script>
