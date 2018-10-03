<template>
    <div class="container">
        <div class="party-container">
            <div v-if="ajaxDoneParty" class="row mr-0">
                <div class="w-100 bg-img">
                    <div class="container-fluid party-data">
                        <div class="party-info">
                            <div class="party-img">
                                <img :src=" '../img/parties/' + ajaxData.partyData.data.data.image " class="img-fluid" style="margin: 5px 0 5px 0;">
                            </div>
                            <div class="party-name">
                                <div class="party-name-info">
                                    <h1 class="text-left">{{ajaxData.partyData.data.data.fullname_el}}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div v-else>
                <img :src=" '../img' + '/Spinner.gif' " class="m-auto d-block"/>
            </div>
        </div>
    </div>

</template>


<style lang="scss" scoped>
    $ContainerColor: white;

    .party-container {
        background-color: $ContainerColor;
        border-radius: 5px;
    }

    .nav-tabs a {
        color: #17a2b8;
    }

    .bg-img {
        background-image: url(/img/bgvouli.jpg);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
    }

    .speeches-container {
        // height: 630px;
        // overflow-y: scroll;
    }

    // .speeches-container {
    //     height: 630px;
    //     overflow-y: scroll;
    // }

    /* .speeches-container .speeches:not(:last-child){
        border-bottom: 1px dashed #17a2b8;
    }*/
    /* .speeches:last-child{
        border-bottom: none;
    } */
    .party-info {
        display: table;
    }

    .party-data {
        /* background:  rgba(0,0,0,0.4); */
        color: white;
    }

    .party-img {
        width: 70px;
        vertical-align: top;
    }

    .party-name {
        vertical-align: middle;
        padding-left: 10px;
    }

    .party-name p {
        font-size: 1.33333em;
    }

    .party-data .party-info .party-name,
    .party-data .party-info .party-img {
        display: table-cell;
    }

    .party-data .party-info .party-name h1 {
        font-size: 1.5rem;
        margin: 0px;
        padding: 0px;
        line-height: 1em;
    }

    @media (max-width: 352px) {
        nav {
            font-size: 0.78rem;
        }
    }

    @media (max-width: 768px) {
        .bg-img {
            background-size: auto;
        }
    }

</style>

<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: {
            name: String,
            path: String
        },
        data(){
            return {
                ajaxData: {
                    speechesData: [],
                    partyData: []
                },
                finalName: null,
                conferenceDate: null,
                showDate: true,
                currentTab: 'Information',
                defaultImg: 'polical_party_default_image.png',
                noDataSpeeches: true,
                noDataParties: true,
                loading: true,
                ajaxDoneParty: false,
                ajaxDoneSpeeches: false,
            }
        },
        methods:{
            // printImg(img){
            //     if(img == 'polical_party_default_image.png' || img == null || img != ''){
            //         return this.defaultImg;
            //     }else{
            //         return img;
            //     }
            // },
            checkDate(conf_date){
                if(this.conferenceDate == null){
                    this.conferenceDate = conf_date
                }else{
                    if(this.conferenceDate == conf_date){
                        this.showDate = false
                    }else{
                        this.conferenceDate = conf_date
                        this.showDate = true
                    }
                }
            },
            getPartySpeeches(){
                var self = this;

                setTimeout(() => {
                    api.call('get', this.api_path+'speeches/party/party_id/'+this.finalName)
                        .then(function (response) {
                            if (response.status == 200) {
                                self.noDataSpeeches = false
                                self.ajaxData.speechesData = response

                            } else {
                                self.noDataSpeeches = true
                            }
                            self.ajaxDoneSpeeches = true
                        })
                }, 500)
            },
            getPartyData(){
                var self = this

                setTimeout(() => {
                    api.call('get', this.api_path+'party/name/'+this.finalName)
                        .then(function (response) {
                            if (response.status == 200) {
                                self.noDataParties = false
                                self.ajaxData.partyData = response

                            } else {
                                self.noDataParties = true
                            }
                            self.ajaxDoneParty = true
                        })
                }, 500)
            },
            searchPartySpeeches() {
                return
            }
        },
        computed:{
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        created() {
            this.loading = false
            //console.log(decodeURIComponent(this.name))
            this.finalName = this.$route.params.party_name
            this.finalName = this.finalName.replace(/\+/g, " ")
            console.log(this.finalName)
            // this.getPartySpeeches()
            this.getPartyData()
        }
    }
</script>