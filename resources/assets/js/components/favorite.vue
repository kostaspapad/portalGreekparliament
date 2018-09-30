<template>
    <span>
        <a href="#" v-if="favorited" @click.prevent="unFavorite">
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
                let data = {
                    'speech_id': this.speech_id
                }
                setTimeout(() => {
                    api.call('post', this.api_path + 'speech/favorite', data)
                    .then(({data}) => {
                        this.favorited = 1
                    })
                }, 500)
            },
            unFavorite() {
                setTimeout(() => {
                    api.call('delete', this.api_path + 'speech/favorite?speech_id=' + this.speech_id)
                    .then(({data}) => {
                        this.favorited = 0
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