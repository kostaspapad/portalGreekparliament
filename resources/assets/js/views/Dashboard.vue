<template>
    <div class="text-center col-12">
        <table class="table table-hover table-sm" style="background-color: #6c757d;color:white;">
            <thead>
                <tr>
                    <th scope="col">Conference Date</th>
                    <th scope="col">Speech</th>
                    <th scope="col">Total comments</th>
                    <th scope="col">Favorite</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="info in ajaxData.info_data" :key="info.speech_id">
                    <td>
                        <router-link :to="/conference/+info.conference_date+'/speeches'" class="link_to_speech">
                            {{info.conference_date}}
                        </router-link>
                    </td>
                    <td>
                        <router-link :to="/speech/+info.speech_id" class="link_to_speech">
                            {{info.speech_id}}
                        </router-link>
                    </td>
                    <td>{{info.total_comments}}</td>
                    <td>
                        <span v-if="info.isFavorite"><i class="fa fa-star fa-2x" aria-hidden="true" style="color:#fff645"></i></i></span>
                        <span v-else><i class="fa fa-star-o fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style lang="scss" scoped>
    .link_to_speech {
        color: white;
    }
    .link_to_speech:hover {
        color: salmon;
    }
</style>

<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        data() {
            return {
                ajaxData: {
                    info_data: null
                }
            }
        },
        methods: {
            getUserData() {
                api.call('get', this.api_path + 'dashboard')
                .then( (data) => {
                    if( data.status == 200 && data.statusText == "OK" && data.data ){
                        this.ajaxData.info_data = data.data.data
                    }

                })
            }
        },
        computed: {
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        mounted() {
            this.getUserData()
        }
    }
</script>