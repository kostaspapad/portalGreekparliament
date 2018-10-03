<template>
    <div class="text-center col-12">
        <table class="table table-hover table-sm table-responsive my-table">
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
                        <span v-if="info.isFavorite"><i class="fa fa-star fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
                        <span v-else><i class="fa fa-star-o fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- <vs-table :data="ajaxData.info_data">
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
            </template> -->
            <!-- <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in ajaxData.info_data" >
                    <vs-td :data="ajaxData.info_data[indextr].email">
                        {{data[indextr].conference_date}}
                    </vs-td>
                    <vs-td :data="ajaxData.info_data[indextr].username">
                        {{data[indextr].speech_id}}
                    </vs-td>
                    <vs-td :data="ajaxData.info_data[indextr].id">
                        {{data[indextr].total_comments}}
                    </vs-td>
                    <vs-td :data="ajaxData.info_data[indextr].id">
                        <span v-if="data[indextr].isFavorite"><i class="fa fa-star fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
                        <span v-else><i class="fa fa-star-o fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
                    </vs-td>
                </vs-tr>
            </template> -->
        <!-- </vs-table> -->
        <!-- <vs-table :data="users" :notSpacer="true" color="success">
            <template slot="header">
                <h3>
                Users
                </h3>
            </template>
            <template slot="thead">
                <vs-th>Email</vs-th>
                <vs-th>Name</vs-th>
                <vs-th>Website</vs-th>
                <vs-th>Nro</vs-th>
            </template>

            <template slot-scope="{data}">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data" state="true">
                    <vs-td :data="data[indextr].email">
                        {{data[indextr].email}}
                    </vs-td>

                    <vs-td :data="data[indextr].username">
                        {{data[indextr].name}}
                    </vs-td>

                    <vs-td :data="data[indextr].id">
                        {{data[indextr].id}}
                    </vs-td>

                    <vs-td :data="data[indextr].id">
                        {{data[indextr].website}}
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table> -->
    </div>
</template>

<style lang="scss" scoped>
    .link_to_speech {
        color: white;
    }
    .link_to_speech:hover {
        color: salmon;
    }
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
                users:[
                {
                    "id": 1,
                    "name": "Leanne Graham",
                    "username": "Bret",
                    "email": "Sincere@april.biz",
                    "website": "hildegard.org",
                },
                {
                    "id": 2,
                    "name": "Ervin Howell",
                    "username": "Antonette",
                    "email": "Shanna@melissa.tv",
                    "website": "anastasia.net",
                }],
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