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
            favorite() {
                const self = this;

                setTimeout(() => {
                    api.call('post', '/api/v1/speech/favorite', {
                        'speech_id': self.speech_id
                    })
                    .then(({data}) => {
                        this.isFavorited = true
                    })
                    // .catch( (error) => {
                    //     if( error.data.status == 400 ){
                    //         console.log(error)
                    //         this.error_msg = "Could not favorite this speech"
                    //     }
                    // })
                }, 500)
            },
            unFavorite() {
                const self = this;
               
                setTimeout(() => {
                    api.call('delete', '/api/v1/speech/favorite', {
                        'speech_id': self.speech_id
                    })
                    .then(({data}) => {
                        this.isFavorited = false
                    })
                    // .catch( (error) => {
                    //     if( error.data.status == 400 ){
                    //         console.log(error)
                    //         this.error_msg = "Could not favorite this speech"
                    //     }
                    // })
                }, 500)
            }
        },
        mounted() {
            this.isFavorited = this.isFavorite ? true : false;
        },
    }
</script>