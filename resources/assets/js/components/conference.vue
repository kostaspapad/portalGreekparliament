<template>
    <div class="container" v-if="ajaxDoneConfInfo">
        <!-- for small screen -->
        <!-- <div class="my-filter-sidebar d-block d-sm-block d-md-none">
            <button @click="openSideMenu" class="btn">Φίλτρα</button>
            <vs-sidebar parent="body" color="primary" class="sidebarx" spacer v-model="activeMenu">
                <div class="header-sidebar" slot="header">
                    <h4>Φίλτρα</h4>
                    <h3 @click="openSideMenu" class="close-filters"><span class="fas fa-times"></span></h3>
                </div>
                <div class="">
                    <ul class="my-filter-list">
                        <li>
                            <vs-checkbox icon-pack="fas" icon="fa-check" v-model="checkBox1" vs-value="luis">Luis</vs-checkbox>
                        </li>
                        <li>
                            <vs-checkbox icon-pack="fas" icon="fa-check" v-model="checkBox1" vs-value="carols">Carols</vs-checkbox>
                        </li>
                        <li>
                            <vs-checkbox icon-pack="fas" icon="fa-check" v-model="checkBox1" vs-value="summer">Summer</vs-checkbox>
                        </li>
                        <li>
                            <vs-checkbox icon-pack="fas" icon="fa-check" v-model="checkBox1" vs-value="lyon">Lyon</vs-checkbox>
                        </li>
                    </ul>
                </div>
            </vs-sidebar>
        </div> -->

        <!-- for big screen to display left -->
        <!-- <div class="col-md-3 d-none d-sm-none d-md-block">
            <div class="">
                <ul class="my-filter-list">
                    <li>
                        <vs-checkbox icon-pack="fas" icon="fa-check" v-model="checkBox1" vs-value="luis">Luis</vs-checkbox>
                    </li>
                    <li>
                        <vs-checkbox icon-pack="fas" icon="fa-check" v-model="checkBox1" vs-value="carols">Carols</vs-checkbox>
                    </li>
                    <li>
                        <vs-checkbox icon-pack="fas" icon="fa-check" v-model="checkBox1" vs-value="summer">Summer</vs-checkbox>
                    </li>
                    <li>
                        <vs-checkbox icon-pack="fas" icon="fa-check" v-model="checkBox1" vs-value="lyon">Lyon</vs-checkbox>
                    </li>
                </ul>
            </div>
        </div> -->
        <!-- <div class="container"> -->
        <div class="main-content-conferenece">
            <div class="conferences-container">
                <div v-if="ajaxDoneConfInfo">
                    <div class="conference-title-box py-4 pl-4">
                        <h2 class="font-weight-bold conference-title">{{ $t("conference.conference") }} · {{ $helpers.myFormattedDate(conf_date,'el') }}</h2>
                        <div>{{ajaxData.conferenceInfo.data.data.session}}</div>
                        <div>{{ajaxData.conferenceInfo.data.data.time_period}} Περίοδος</div>
                    </div>
                    <vs-tabs color='#007bff'>
                        <!-- Conference data -->
                        <vs-tab vs-label="Συνδεδρίαση">
                            <!-- Scroll to top btn -->
                            <div class="scroll-btn conf-scroll-btn-pos">
                                <a href="#" v-scroll-to=" {
                                        el: '#search-input',
                                        duration: 800,
                                        offset: -100,
                                        easing: 'linear',
                                        force: true,
                                        x: false,
                                        y: true
                                    } 
                                ">
                                    <i class="fas fa-chevron-circle-up"></i>
                                </a>
                            </div>
                            <!-- End of scroll to top btn -->
                            <div class="row w-100">
                                <div class="col-12 pt-3">
                                    <span class="documents-links" v-if="ajaxData.conferenceInfo">
                                        <a :href="startUrl + '/' + ajaxData.conferenceInfo.data.data.pdf_loc + '/' + ajaxData.conferenceInfo.data.data.pdf_name">
                                            {{ $t("conference.pdf") }} <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                                        </a>
                                        <a :href="startUrl + '/' + ajaxData.conferenceInfo.data.data.doc_location + '/' + ajaxData.conferenceInfo.data.data.doc_name">
                                            {{ $t("conference.word") }} <i class="fas fa-file-word" style="color: #007bff;"></i>
                                        </a>
                                    </span>
                                </div>
                                <div v-if="ajaxData.isLoaded && !search.speechesData.length" class="col-12 pl-3 mt-2">
                                    <pagination :data="ajaxData.conferenceData.data.meta" @pagination-change-page="changePage" :limit=1>
                                        <span slot="prev-nav">&lt;</span>
                                        <span slot="next-nav">&gt;</span>
                                    </pagination>
                                </div>
                                <div id="search-input" class="col-12 pl-3 mt-2">
                                    <vs-input
                                        icon-pack="fas"
                                        icon="fa-search" 
                                        placeholder="Αναζήτηση λέξεων ομιλιών"
                                        description-text="Γράψτε μόνο με πεζά γράμματα και χρησιμοποιήστε τόνους."
                                        v-model.trim="search_string"
                                        @keypress.enter="searchConferenceSpeeches"
                                        class="speech-search-input d-inline-block"
                                    />
                                    <div v-if="search_string" class="d-inline-block search-clear-icon">
                                        <span @click="clearInput" class="speech-search-input-icon pointer"><i class="fas fa-times-circle"></i></span>
                                    </div>
                                </div>
                                <div v-if="ajaxDoneConfSpeeches" class="col-12 col-sm-12 col-md-12 col-lg-12 p-0 pl-2">
                                    <div v-if="search.noDataMsg">
                                        <p>{{search.noDataMsg}}</p>
                                    </div>
                                    <div v-if="search.speechesData.length > 0" class="search-result">
                                        <vs-divider color="#636b6f">Αποτελέσματα</vs-divider>
                                        <div v-for="conference in search.speechesData" :key="conference.speech_id">
                                            <speech :speech="conference" isFromSearch=true isFromConference=true></speech>
                                        </div>
                                    </div>
                                    <div v-if="!search.speechesData.length" v-for="conference in ajaxData.conferenceData.data.data" :key="conference.speech_id">
                                        <speech :speech="conference" isFromConference=true></speech>
                                    </div>
                                </div>
                                <div v-if="ajaxDoneConfSpeeches">
                                    <div v-if="!ajaxData.conferenceData.data.data.length">
                                        <h4>{{ $t("conference.no_speeches_avail") }}</h4>
                                    </div>
                                </div>
                                <div v-if="ajaxData.isLoaded && !search.speechesData.length" class="col-12 pl-3">
                                    <pagination :data="ajaxData.conferenceData.data.meta" @pagination-change-page="changePage" :limit=1>
                                        <span slot="prev-nav">&lt;</span>
                                        <span slot="next-nav">&gt;</span>
                                    </pagination>
                                </div>
                            </div>
                        </vs-tab>
                        <!-- End of conference data -->
                        <!-- Chart tab -->
                        <vs-tab vs-label="Δεδομένα">
                            <h4 class="error" v-if="isLoaded"><b>{{ $t("conference.data_alert") }}</b></h4>
                            <pie-chart 
                                v-if="isLoaded"
                                :chart-data="ajaxData.party_count_speeches.party_count" 
                                :chart-labels="ajaxData.party_count_speeches.party_names"
                                :chart-bg-colors="ajaxData.party_count_speeches.party_colors"
                                :width="325">
                            </pie-chart>
                        </vs-tab>
                        <!-- End of chart tab -->
                    </vs-tabs>
                </div>
                <!-- If it's loading -->
                <!-- <div v-else class="col-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="m-auto d-block lds-css ng-scope" style="width: 200px; height: 200px;"><div style="width:100%;height:100%" class="lds-ripple"><div></div><div></div></div></div>
                </div> -->
                <!-- End of if it's loading -->
            </div>
        </div>
    </div>
    <!-- If it's loading -->
    <div v-else class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="m-auto d-block lds-css ng-scope" style="width: 200px; height: 200px;">
            <div style="width:100%;height:100%" class="lds-ripple">
                <div></div><div></div>
            </div>
        </div>
    </div>
    <!-- End of if it's loading -->
</template>
<style lang="scss" scoped>
    .conferences-bg {
        // background-color: #ffffff;
    }
    .conferences-container {
        background-color: #fff;
        margin-top: 20px;

        // border-radius: 5px;
    }

    .conference-title-box {
        color: white;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        // background-color: #6d7fcc;
        background-color: #2b4162 !important;
    }
    .pickerDiv{
        margin-bottom: 10px;
    }
    .conversation{
        margin: 15px 0 15px 0;
        text-align: left;
    }
    .speech_speaker_name{
        /* font-style: italic; */
        font-weight: bold;
        /* color: white; */
    }
    .speech_speaker_img{
        border-radius: 60px;
        border: 2px solid #35495e;
        max-width: 130px !important;
        /* max-width: 80px; */
        /* width: 70%; */
        /* padding: 5px; */
        /* margin: 13px 0 0 5px; */
    }
    .speech{
        /* margin: -50px 0 0 90px; */
        text-align: justify;
    }
    .conf-scroll-btn-pos{
        top: 50%;
    }
    
    
    .close-filters {
        position: absolute;
        right: 15px;
        top: 10px;
    }
</style>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex'
    export default {
        props: {
            path: String
        },
        data() {
            return {
                ajaxData: {
                    conferenceData: [],
                    conferenceInfo: null,
                    party_count_speeches: {
                        party_names: [],
                        party_count: [],
                        party_colors: []
                    },
                    isLoaded: false
                },
                search: {
                    speechesData: [],
                    noDataMsg: null
                },
                conf_date: null,
                defaultImg: 'default_speaker_icon.png',
                startUrl: 'https://www.hellenicparliament.gr',
                ajaxDoneConfInfo: false,
                ajaxDoneConfSpeeches: false,
                noDataConfInfo: true,
                noDataConfSpeeches: true,
                isLoaded: false,
                showChart: false,
                startInterval: false,
                search_string: null,
                order_orientation: 'desc',
                order_field: 'conference_date',
                activeMenu: false,
                checkBox1: ['luis']
            }
        },
        methods:{
            clearInput() {
                this.search_string = ''
                if(this.search.speechesData){
                    this.search.speechesData = []
                }
                this.search.noDataMsg = ''
            },
            searchConferenceSpeeches() {
                //search_string is not empty
                if(this.search_string){
                    let tmp_speech_data = this.ajaxData.conferenceData.data.data.filter(speech => {
                        //console.log(speech)
                        //console.log(speech.speech.includes(this.search_string))
                        return speech.speech.toLowerCase().includes(this.search_string)
                        // return post.title.toLowerCase().includes(this.search.toLowerCase())
                    })
                    //console.log(tmp_speech_data)

                    //the result found something
                    if(tmp_speech_data.length > 0){
                        this.search.speechesData = tmp_speech_data
                    }else{
                        //didn't found something
                        this.search.noDataMsg = 'Δεν βρέθηκαν ομιλίες που να περιλαμβάνουν τις λέξεις που δώσατε.'
                    }
                }else{
                    //if search_string is empty , clear the array and the no message text
                    if(this.search.speechesData){
                        this.search.speechesData = []
                    }
                    this.search.noDataMsg = ''
                }
                // filteredList() {
                //     return this.postList.filter(post => {
                //         return post.title.toLowerCase().includes(this.search.toLowerCase())
                //     })
                // }
                // if(this.search_string === ''){
                //     this.getConferenceSpeeches();
                // }
                // this.ajaxData.isLoaded = false
                // setTimeout(() => {
                //     api.call('get', this.api_path + 'conference/' + this.conf_date + '/speeches/search/' + this.search_string)
                //         .then( response => {
                //             if (response.status == 200) {
                //                 this.noDataConfSpeeches = false
                //                 this.ajaxData.conferenceData = response
                //                 this.ajaxData.isLoaded = true
                //             } else {
                //                 this.noDataConfSpeeches = true
                //             }

                //             this.ajaxDoneConfSpeeches = true
                //         })
                // }, 500)
            },
            changePage(page) {
                axios.get(this.api_path + 
                    'conference/' + this.conf_date + 
                    '/speeches?page=' + page
                )
                .then(response => {
                    this.ajaxData.conferenceData = response;
                })
            },
            isJsonString(str) {
                //decode JSON
                var json;
                try {
                    json = JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return json;
            },
            getConferenceSpeeches(){
                setTimeout( () => {
                    this.ajaxData.isLoaded = false
                    axios.get(this.api_path + 'conference/'+ this.conf_date +'/speeches')
                    .then(response => {
                        if(response.status == 200){
                            this.noDataConfSpeeches = false
                            this.ajaxData.conferenceData = response
                        } else {
                            this.noDataConfSpeeches = true
                        }
                        this.ajaxDoneConfSpeeches = true
                        this.ajaxData.isLoaded = true
                    })
                    .catch(error => {
                        console.log(error)
                    });
                }, 1000)
            },
            getConferenceInfo(){
                setTimeout( () => {
                    axios.get(this.api_path + 'conference/date/'+ this.conf_date)
                    .then(response => {
                        if(response.status == 200){
                            this.noDataConfInfo = false
                            //because we only get one conference we add [0] to get it
                            this.ajaxData.conferenceInfo = response
                        } else {
                            this.noDataConfInfo = true
                        }
                        this.ajaxDoneConfInfo = true
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 1000)
            },
            getPartyCountByConference() {
                api.call('get', this.api_path + 'conferences/count-party-speeches/' + this.conf_date)
                .then( response => {
                    if(response.status == 200 && response.data){
                        response.data.forEach( element => {
                            this.ajaxData.party_count_speeches.party_names.push(element.fullname_el)
                            this.ajaxData.party_count_speeches.party_count.push(element.party_count)
                            this.ajaxData.party_count_speeches.party_colors.push(element.color)
                            this.isLoaded = true
                        })
                    }

                })
            },
            openSideMenu() {
                // console.log(this.activeMenu)
                this.activeMenu = !this.activeMenu;
                
            },
            handleScroll(event) {
            //    console.log(event)
            },
            ...mapActions([
                'GET_COMMENTS_CONFERENCE'
            ])
        },
        computed:{
            ...mapGetters({
                user: 'get_user'
            }),
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        watch: {
            startInterval: function (newVal) {
                if(newVal){
                    this.$options.interval = setInterval( () => {
                        let data = {data: this.conf_date, choice: 'conference'}
                        this.GET_COMMENTS_CONFERENCE(data)
                    },60000)
                }
            },
            activeMenu: (newVal) => {
                // console.log(newVal)
                if(newVal){
                    $(document.body).addClass('no-overflow');
                } else {
                    $(document.body).removeClass('no-overflow');
                }
            }
        },
        created() {
            this.$route.params.conference_date ? this.conf_date = this.$route.params.conference_date : null          
            this.getConferenceInfo()
            this.getConferenceSpeeches()
            
            this.getPartyCountByConference()
            if(this.user){
                let data = {data: this.conf_date, choice: 'conference'}
                this.GET_COMMENTS_CONFERENCE(data)
                this.startInterval = true
            }
            window.addEventListener('scroll', this.handleScroll);
        },
        beforeDestroy(){
            if(this.$options.interval){
                clearInterval(this.$options.interval)
            }
            window.removeEventListener('scroll', this.handleScroll);
        }
    }
</script>
