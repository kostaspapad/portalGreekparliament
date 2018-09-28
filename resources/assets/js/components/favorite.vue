<template>
    <span>
        <a href="#" v-if="isFavorited" @click.prevent="unFavorite">
            <i class="fa fa-star fa-2x" aria-hidden="true" style="color:#fff645"></i>
        </a>
        <a href="#" v-else @click.prevent="favorite">
            <i class="fa fa-star-o fa-2x" aria-hidden="true" style="color:#fff645"></i>
        </a>
    </span>
</template>

<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: [
            'speech_id',
            'favorited'
        ],
        data () {
            return {
                isFavorited: null,
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
                    api.call('post', this.api_path + 'speech/favorite', {
                        'speech_id': self.speech_id
                    })
                    .then(({data}) => {
                        this.isFavorited = true
                    })
                }, 500)
            },
            unFavorite() {
                const self = this;
               
                setTimeout(() => {
                    api.call('delete', this.api_path + 'speech/favorite?speech_id='+this.speech_id)
                    .then(({data}) => {
                        this.isFavorited = false
                    })
                }, 500)
            }
        },
        computed: {
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        mounted() {
            this.isFavorited = this.isFavorite ? true : false;
        },
    }
</script>