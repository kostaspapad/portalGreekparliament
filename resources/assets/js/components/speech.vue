<template>
    <div v-if="speechText && greekName" class="speech-container pl-4 py-2">
        <div class="speech-data-container p-2">
            <div class="row">
                <div class="speaker-image-container">
                    <div v-if="imagePath">
                        <img :src="'/img/' + imagePath" class="speech_speaker_img"/>
                    </div>
                </div>
                <div class="speech-container-speaker">
                    <h4 class="speech_speaker_name ml-2">
                        <a :href="/speaker/ + greekName" class="" style="margin-top:8px;">{{greekName}}</a>
                    </h4>
                    <div class="speech_speaker_party ml-2" v-bind:style="getPartyColor">
                        <h5>{{fullnameEl | capitalize}} ({{onBehalfOfId}})</h5>
                    </div>
                </div>
            </div>
            <div class="speech-container-speech ml-2 pt-2">
                <read-more more-str="read more" :text="speechText" link="#" less-str="read less" :max-chars="2000"></read-more>
            </div>
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
    export default {
        props: {
            speechID: null,
            speechText: String,
            greekName: String,
            imagePath: null,
            fullnameEl: null,
            onBehalfOfId: String,
            partyColor: String
        },
        data() {
            return {
                
            }
        },
        methods: {
           
        },
        computed: {
            getPartyColor: function () {
                return {
                    'color': this.partyColor
                }
            }
        },
        created() {

        }
    }
</script>