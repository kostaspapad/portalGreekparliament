<template>
    <div v-if="speech.speech_id && speech.greek_name" class="speech-container py-2">
        <div class="speech-data-container p-2">
            <div class="row" style="margin-right: 0;">
                <div class="speaker-image-container">
                    <div v-if="speech.image">
                        <img :src="'/img/' + speech.image" class="speech_speaker_img"/>
                    </div>
                </div>
                <div class="speech-container-speaker">
                    <h4 class="speech_speaker_name ml-2">
                        <a :href="/speaker/ + speech.greek_name" class="" style="margin-top:8px;">{{speech.greek_name}}</a>
                    </h4>
                    <div class="speech_speaker_party ml-2" v-bind:style="getPartyColor">
                        <h5>{{speech.fullname_el | capitalize}} ({{speech.on_behalf_of_id}})</h5>
                    </div>
                </div>
            </div>
            <div class="speech-container-speech ml-2 pt-2">
                <read-more more-str="read more" :text="speech.speech" link="#" less-str="read less" :max-chars="2000"></read-more>
            </div>
            
            <div v-if="user.name">
                <favorite
                    :speech_id='speech.speech_id'
                    :favorited='isFavorited'
                ></favorite>
                <div class="d-inline-block comment-text pointer" @click="isCommentOn = !isCommentOn" :class="isCommentOn ? 'hide-text' : 'show-text'">
                    <span v-if="!isCommentOn" class="show-letters">Show comments</span>
                    <span v-else class="hide-letters">Hide comments</span>
                </div>
                <transition name="slide-fade">
                    <div v-if="isCommentOn">
                        <comments></comments>
                    </div>
                </transition>
           </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    .slide-fade-enter-active, .slide-fade-leave-active {
        transition: opacity .6s ease;
    }
    .slide-fade-enter, .slide-fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
    .speech-container {
        text-align: left;
    }
    .speech-data-container {
        background-color: ghostwhite;
        border-radius: 5px;
    }
    .speech_speaker_img{
        border-radius: 60px;
        border: 2px solid #35495e;
        max-width: 60px !important;
    }
    .speech-container-options {
        text-align: right;
    }
    .comment-text {
        padding: 1vh;
        background: #33a8ff63;
        border-radius: 50px;
        color: #4e5356;
        position: relative;
        top: -4px;
        transition: background .8s ease;
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
        background: salmon;
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
</style>
<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: {
            speech: null
        },
        data() {
            return {
                isCommentOn: false
            }
        },
        methods: {
            
        },
        computed: {
            getPartyColor() {
                return {
                    'color': this.speech.party_color
                }
            },
            isFavorited() {
                // console.log(this.user)
                if (this.speech.favorite === this.user.id) {
                    return true
                } else {
                    return false
                }
            },
            ...mapState([
                'title'
            ]),
            ...mapGetters({
                user: 'get_user'
            })
        },
        created() {

        }
    }
</script>