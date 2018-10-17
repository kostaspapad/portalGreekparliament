<template>
    <div class="text-center col-12" v-if="ajaxData.isDone">
        <table v-if="ajaxData.info_data.length" class="table table-hover table-sm table-responsive my-table">
            <thead>
                <tr>
                    <th scope="col">Conference Date</th>
                    <th scope="col">Speech</th>
                    <th scope="col">Total comments</th>
                    <th scope="col">Favorite</th>
                </tr>
            </thead>
            <expand-table-data 
                v-for="info in ajaxData.info_data" 
                :key="info.speech_id" 
                is="expand-table-data" 
                :infoData="info" 
            >
            </expand-table-data>
            
        </table>
        <div v-else>
            <h3>You don't have do any comments or favorite a speech!</h3>
        </div>

        <!-- Must remove bootstrap.scss to work correctly -->
        <!-- <vs-table :data="ajaxData.info_data" :notSpacer="true">
            <template slot="thead">
                <vs-th>Conference Date</vs-th>
                <vs-th>Speech</vs-th>
                <vs-th>Total Comments</vs-th>
                <vs-th>Favorite</vs-th>
            </template>
            <template slot-scope="{data}">
                <vs-tr v-for="(info,indextr) in ajaxData.info_data" :key="info.speech_id">
                    <vs-td :data="ajaxData.info_data[indextr].conference_date">
                        <router-link :to="/conference/+info.conference_date+'/speeches'" class="">
                            {{info.conference_date}}
                            
                        </router-link>
                    </vs-td>
                    <vs-td :data="ajaxData.info_data[indextr].speech_id">
                        <router-link :to="/speech/+info.speech_id" class="">
                            {{info.speech_id}}
                        </router-link>
                    </vs-td>
                    <vs-td :data="ajaxData.info_data[indextr].total_comments">{{info.total_comments}}</vs-td>
                    <vs-td :data="ajaxData.info_data[indextr].isFavorite">
                        <span v-if="info.isFavorite"><i class="fa fa-star fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
                        <span v-else><i class="fa fa-star-o fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table> -->
    </div>
</template>

<style lang="scss" scoped>
    .my-table {
        display: table;
        background-color: #6c757d;
        color:white;
    }
</style>

<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        data() {
            return {
                ajaxData: {
                    info_data: null,
                    isDone: false
                },
                expand: false
            }
        },
        methods: {
            getUserData() {
                this.ajaxData.isDone = false
                api.call('get', this.api_path + 'dashboard')
                .then( (data) => {
                    if( data.status == 200 && data.statusText == "OK" && data.data ){
                        this.ajaxData.info_data = data.data.data
                        this.ajaxData.isDone = true
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