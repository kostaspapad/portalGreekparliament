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
            <div>{{ user.name }}</div>
            <!--<div v-if="this.$root.user">
                <favorite
                    :speech_id='speech.speech_id'
                    :favorited='false'
                ></favorite>
           </div>-->
        </div>
    </div>
</template>
<style scoped>
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

</style>
<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: {
            speech: null
        },
        data() {
            return {
                
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

            },
            ...mapState([
                'title'
            ]),
            ...mapGetters({
                user: 'get_user'
            })
        },
        created() {
            //console.log(this.$vuelayout.user)
        }
    }
</script>