<template>
    <span>
        <a href="#" v-if="isFavorited" @click.prevent="unFavorite(speech)">
            <i class="fa fa-star fa-2x" aria-hidden="true" style="color:#fff645"></i>
        </a>
        <a href="#" v-else @click.prevent="favorite(speech)">
            <i class="fa fa-star-o fa-2x" aria-hidden="true" style="color:#fff645"></i>
        </a>
    </span>
</template>

<script>
    export default {
        props: [
            'speech_id',
            'favorited'
        ],
        data () {
            return {
                isFavorited: '',
            }
        },
        computed: {
            isFavorite() {
                return this.favorited;
            },
        },
        methods: {
            favorite(speech_id) {
                const self = this;
    
                axios.post('/speech/' + self.speech_id + '/favorite')
                    .then(response => this.isFavorited = true)
                    .catch(response => console.log(response.data));
            },
            unFavorite(speech_id) {
                const self = this;

                axios.delete('/speech/' + self.speech_id + '/favorite')
                    .then(response => this.isFavorited = false)
                    .catch(response => console.log(response.data));
            }
        },
        mounted() {
            this.isFavorited = this.isFavorite ? true : false;
        },
    }
</script>