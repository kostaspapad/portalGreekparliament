<template>
    <div class="container">
        <div v-if="!loading" class="row speakers-bg">
            <div class="mt-5 col-12 col-md-6 speaker-search">
                <search-plugin></search-plugin>
            </div>
            <div class="col-12 text-left mt-2 mb-2">
                <ul class="sort-ul">
                    <li>
                        <strong>Sorted by </strong>
                        <span v-if="order_field == 'greek_name' ">Name</span>
                        <span v-else>Party</span>
                        <span>({{order_orientation | capitalizeAll}})</span>
                        <span @click=sortBy(order_field,order_orientation)><i class="fa fa-sort pointer"></i></span>
                    </li>
                    <li v-if="order_field == 'greek_name' ">
                        <span>Sort by </span>
                        <span class="sort-text" @click="sortBy('fullname_el')">Party</span>
                    </li>
                    <li v-else>
                        <span>Sort by </span>
                        <span class="sort-text" @click="sortBy('greek_name')">Name</span>
                    </li>
                </ul>
            </div>
            <div class="row w-100">
                <div class="row w-100">
                    <div class="col-12" style="padding-left: 2.5rem;">
                        <pagination :data="ajaxData.speakersData.data.meta" @pagination-change-page="changePage" :limit=1>
                            <span slot="prev-nav">&lt;</span>
                            <span slot="next-nav">&gt;</span>
                        </pagination>
                    </div>
                    <div class="col-12 speaker"
                        v-for="speaker in ajaxData.speakersData.data.data" 
                        :key="speaker.id" 
                        style="margin-bottom: 15px;">
                        <router-link :to="'/speaker/' + speaker.greek_name" class="person-link">
                            <img :src=" 'img' + '/' + printImg(speaker.image) " class="person-img">
                            <h2 class="person-name text-left">{{speaker.greek_name}}</h2>
                            <p class="person-membership text-left">
                                <span class="party-name">{{speaker.party_fullname}}</span>
                                <span class="party-indicator" :style="{ backgroundColor: speaker.color }"></span>
                            </p>
                        </router-link>
                    </div>
                    <div class="col-12" style="padding-left: 2.5rem;">
                        <pagination :data="ajaxData.speakersData.data.meta" @pagination-change-page="changePage" :limit=1>
                            <span slot="prev-nav">&lt;</span>
                            <span slot="next-nav">&gt;</span>
                        </pagination>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <img :src=" 'img' + '/Spinner.gif' " class="m-auto d-block"/>
        </div>
    </div>
</template>

<style scoped>
    .sort-ul{
        font-size: 0.9em;
        color: #6c6b68;
        /* margin-left: -2.3em; */
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
    .speaker-search, .speaker{
        padding-left: 2rem;
    }
    .speakers-bg{
        background-color: #ffffff;
    }
    .person-link{
        cursor: hand;
        cursor: pointer;
        display: block;
        padding: 1.2em 0 1.2em 60px;
        border-top: 1px solid #f3f1eb;
        position: relative;
        color: inherit;
        width: fit-content;
    }
    .person-img {
        position: absolute;
        top: 1.2em;
        left: 0;
        width: 45px;
    }
    .person-name{
        color: inherit;
        margin: 0;
        line-height: 1.2em;
        font-size: 1.4rem;
    }
    .person-name:hover{
        color:#62b356;
    }
    .person-link:hover, .person-link:focus {
        text-decoration: none;
        color: inherit;
    }
    .person-link:hover .person-name, .person-link:focus .person-name {
        color: #62b356;
        text-decoration: underline;
    }
    .person-membership {
        margin: 0.2em -0.3em 0 0;
        line-height: 1.4em;
        color: #878582;
    }
    @media (max-width: 460px) {
        .person-name{
            font-size: 1.2rem;
        }
    }
</style>

<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: {
            path: String
        },
        data() {
            return {
                ajaxData: {
                    speakersData: [],
                    search_data: []
                },
                selected_speaker: null,
                show_modal: false,
                defaultImg: 'default_speaker_icon.png',
                search_msg: '',
                search_result_msg: null,
                showResults: false,
                loading: true,
                order_field: 'greek_name',
                order_text: null,
                order_orientation: 'asc'
            }
        },
        methods: {
            sortBy(sortField, sortOrientation = null) {
                if (sortField) {
                    this.order_field = sortField
                }
                if (sortOrientation == 'asc') {
                    this.order_orientation = 'desc'
                } else {
                    this.order_orientation = 'asc'
                }
                if (this.order_field && this.order_orientation) {
                    this.getSpeakers()
                }
            },
            findSpeaker() {
                var self = this;

                axios.get(this.api_path+'speakers/search/'+self.search_msg)
                .then(function(response){
                    if (response.status == 200 && response.data.data.length > 0) {
                        self.ajaxData.search_data = response;
                        self.search_result_msg = "Search Results";
                        self.showResults = true;
                    } else {
                        self.search_result_msg = 'No results found';
                        self.showResults = false;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                }); 
            },
            changePage(page) {
                var self = this;
                
                axios.get(this.api_path+'speakers?page=' + page + '&order_field='+this.order_field+'&orientation=asc')
                .then(function(response) {
                    self.ajaxData.speakersData = response;
                })
            },
            redirectToSpeaker(speaker_name) {
                this.$router.push({ path: '/speaker/' + speaker_name })
            },
            getSpeakers() {
                var self = this;
                this.loading = true

                setTimeout( () => {
                    axios.get(this.api_path+'speakers?order_field='+this.order_field+'&orientation='+this.order_orientation)
                    .then(function(response){
                        if(response.status == 200 && response.data.data){
                            self.loading = false
                            self.ajaxData.speakersData = response
                        }
                    })
                }, 1000)
            },
            printImg(img) {
                if (img == 'default-speaker.jpg' || img == null) {
                    return this.defaultImg;
                } else {
                    return img;
                }
            },
        },
        computed:{
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        created() {
            this.getSpeakers();
        }
    }
</script>
