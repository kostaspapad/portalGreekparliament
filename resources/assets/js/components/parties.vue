<template>
    <div class="container">
        <div class="parties-container">
            <div v-if="!loading" class="row parties-bg">
                <div class="row w-100"> <!-- Parties loop -->
                    <div class="row w-100">
                        <div class="col-12 party"
                            v-for="party in ajaxData.partiesData.data.data"
                            :key="party.party_id"
                            @click="redirectToParty(party.fullname_el)"
                            style="margin-bottom: 15px">
                            <router-link :to="/party/ + party.fullname_el" class="party-link">
                            <img :src=" '/img/parties/' + printImg(party.image) " class="party-img">
                            <h2 class="party-name text-left">{{party.fullname_el}}</h2>
                                <!--<p class="party-membership text-left">
                                    <span class="party-name">{{party.party_fullname}}</span>
                                    <span class="party-indicator" :style="{ backgroundColor: speaker.color }"></span>
                                </p>-->
                            </router-link>
                        </div>
                    </div>
                </div> <!-- Parties loop -->
            </div>
            <div v-else>
                <div class="m-auto d-block lds-css ng-scope" style="width: 200px; height: 200px;">
                    <div style="width:100%;height:100%" class="lds-ripple">
                        <div></div><div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    $containerColor: white;
    .parties-container {
        background-color: $containerColor;
        border-radius: 5px;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .sort-ul{
        font-size: 0.9em;
        color: #6c6b68;
        margin-left: -2.3em;
        margin-bottom: 0;
    }
    .sort-ul li{
        display: inline-block;
        margin-left: 0.5em;
    }
    .sort-text{
        color: inherit;
        text-decoration: underline;
        cursor: pointer;
    }
    .party-search, .party {
        padding-left: 2rem;
    }
    .party-indicator{
        content: "";
        display: inline-block;
        height: 0.6em;
        width: 0.6em;
        -moz-border-radius: 0.6em;
        -webkit-border-radius: 0.6em;
        border-radius: 0.6em;
        margin-right: 0.3em;
        vertical-align: 0.05em;
    }
    .parties-bg{
        background-color: #ffffff;
    }
    .party-link{
        display: block;
        padding: 1.2em 0 1.2em 60px;
        border-top: 1px solid #f3f1eb;
        position: relative;
        color: inherit;
        width: fit-content;
    }
    .party-img {
        position: absolute;
        top: 1.2em;
        left: 0;
        width: 45px;
    }
    .party-name{
        color: inherit;
        margin: 0;
        line-height: 1.2em;
        font-size: 1.4rem;
    }
    .party-name:hover{
        color:#62b356;
    }
    .party-link:hover, .party-link:focus {
        text-decoration: none;
        color: inherit;
    }
    .party-link:hover .party-name, .party-link:focus .party-name {
        color: #62b356;
        text-decoration: underline;
    }
    .party-membership {
        margin: 0.2em -0.3em 0 0;
        line-height: 1.4em;
        color: #878582;
    }
    @media (max-width: 460px) {
        .party-name{
            font-size: 1.2rem;
        }
    }
</style>
<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        data() {
            return {
                ajaxData: {
                    partiesData: [],
                    search_data: []
                },
                default_img: 'polical_party_default_image.png',
                search_msg: '',
                search_result_msg: null,
                // show_results: false,
                loading: true,
                order_field: 'fullname_el',
                order_text: null,
                order_orientation: 'asc'
            }
        },
        methods: {
            sortBy (sortField, sortOrientation = null) {
                if (sortField) {
                    this.order_field = sortField
                }
                if (sortOrientation == 'asc') {
                    this.order_orientation = 'desc'
                } else {
                    this.order_orientation = 'asc'
                }
                if (this.order_field && this.order_orientation) {
                    this.getParties()
                }
            },
            findParty() {
                var self = this

                axios.get(this.api_path + 'parties/search/' + self.search_msg)
                .then(function(response) {
                    if (response.status == 200) {
                        self.ajaxData.partiesData = response
                        // self.search_result_msg = "Search Results"
                        // self.show_results = true

                    } else {
                        self.search_result_msg = 'No results found'
                        // self.show_results = false
                    }
                })
            },
            redirectToParty(party_name) {
                this.$router.push({ path: '/party/' + party_name })
            },
            // changePage(page){
            //     var self = this
                
            //     axios.get(this.$root.host+'/api/v1/parties?page=' + page)
            //     .then(function(response){
            //         self.ajaxData.partiesData = response
            //     })
            //     .catch(function (error) {
            //         console.log(error)
            //     })
            // },
            // changePageParty(page){
            //     var self = this
            //     axios.get(this.$root.host+'/api/v1/parties?page=' + page+'&fullname_el='+this.search_msg)
            //     .then(function(response){
            //         self.ajaxData.search_data = response
            //     })
            //     .catch(function (error) {
            //         console.log(error)
            //     })
            // },
            getParties() {
                var self = this
                this.loading = true

                setTimeout( () => {
                    axios.get(this.api_path + 'parties')
                    .then(function(response){
                        if(response.status == 200 && response.data.data){
                            self.loading = false
                            self.ajaxData.partiesData = response
                        }
                    })
                }, 1000)
            },
            printImg(img){
                if (img == 'polical_party_default_image.png') {
                    return this.default_img
                } else if (img == null || img == '') {
                    return this.default_img
                } else {
                    return img
                }
            },
        },
        computed:{
            ...mapGetters({
                api_path: 'get_api_path'
                // speech_comments: 'get_conference_speech_comments'
            }),
            // is_search_msg_empty(){
                // //when search_msg is empty return true to show the initial "data"
                // if (this.search_msg.length > 0) {
                //     return false
                // } else {
                //     if (this.search_msg.length == 0) {
                //         this.show_results = false
                //         this.search_result_msg = null
                //         return true
                //     }
                // }
            // },
            // party_search_result_msg() {
            //     if (this.search_result_msg == 'Search Results' || this.search_result_msg == null) {
            //         return true
            //     } else {
            //         if (this.search_msg.length == 0) {
            //             // this.show_results = false
            //             // this.search_result_msg = ''
            //             return false
            //         }
            //     }
            // }
        },
        created() {
            this.getParties()
        }
    }
</script>
