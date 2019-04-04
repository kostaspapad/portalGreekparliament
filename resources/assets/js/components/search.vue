<template>
    <div class="row m-0">
        <!-- Sidebar  -->
        <nav id="sidebar" class="d-none">
            <ul class="list-unstyled components">
                <search-plugin :taggable="true"></search-plugin>
                
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">{{$t("conferences.datepicker.date")}}</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <div class="datepicker pr-2 pl-2">
                                <span @click="showInfoDiv = !showInfoDiv" v-show="!showInfoDiv" class="datepkr-float">
                                    <i class="fa fa-info-circle info-icon"></i>
                                </span>
                                <transition name="slide-fade">
                                    <div v-if="showInfoDiv" class="alert alert-info" role="alert">
                                        <button @click="showInfoDiv = !showInfoDiv" type="button" class="close" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="alert-heading">{{ $t("conferences.search") }}</h4>
                                        <p>{{ $t("conferences.datepicker.select_date_ranges") }}</p>
                                        <p>Η Επαναφορά καθαρίζει το πεδίο στο οποίο βάλατε την ημερομηνία.</p>
                                    </div>
                                </transition>
                                <div class="datepkr-toggle">
                                    <vs-switch color="#4896e5" v-model="isMultipleFilter" class="switch-btn">
                                        <!-- <span slot="on" class="switch-font">{{ $t("conferences.datepicker.from_to") }}</span> -->
                                        <span slot="on" class="switch-font">Από έως</span>
                                        <!-- <span slot="off" class="switch-font">{{ $t("conferences.datepicker.date") }}</span> -->
                                        <span slot="off" class="switch-font">Ημερομηνία</span>
                                    </vs-switch>
                                </div>
                                <div v-if="isMultipleFilter">
                                    <label>{{ $t("conferences.datepicker.from") }}</label>
                                    <datepicker v-model="startDate" :format="myFormattedDate" :bootstrap-styling="true"
                                        wrapper-class="pickerDiv" placeholder="Επιλέξτε αρχική ημερομηνία"></datepicker>
                                    <label>{{ $t("conferences.datepicker.to") }}</label>
                                    <datepicker v-model="endDate" :format="myFormattedDate" :bootstrap-styling="true"
                                        wrapper-class="pickerDiv" placeholder="Επιλέξτε τελική ημερομηνία"></datepicker>
                                    <div class="datepkr-btn">
                                        <!-- <button class="btn reset-btn datepkr-btn-color" @click="getDates" :disabled="isDisabled">{{ $t("conferences.datepicker.submit") }}</button> -->
                                        <button class="btn reset-btn datepkr-btn-color" @click="clearDatesInput('multiple')" :disabled="isDisabled">Επαναφορά</button>
                                    </div>
                                </div>
                                <div v-else class="datepkr-toggle mt-2">
                                    <!-- <label>{{ $t("conferences.datepicker.select_single_date") }}</label> -->
                                    <datepicker v-model="singleDate" :format="myFormattedDate" :bootstrap-styling="true"
                                        wrapper-class="pickerDiv" placeholder="Επιλέξτε ημερομηνία"></datepicker>
                                    <div class="datepkr-btn">
                                        <!-- <button class="btn reset-btn datepkr-btn-color" @click="getDate" :disabled="isDisabled">{{ $t("conferences.datepicker.submit") }}</button> -->
                                        <button class="btn reset-btn datepkr-btn-color" @click="clearDatesInput('single')" :disabled="isDisabled">Επαναφορά</button>
                                    </div>
                                </div>
                                <!-- <div v-if="search.isDone && !search.hasData" class="col-12 mt-2">
                                    <h4>{{ $t("conferences.no_conferences_available") }}</h4>
                                </div> -->
                            </div>
                        </li>
                        <li>
                            
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a v-on:click="do_search" class="do-search">{{ $t("conferences.search") }}</a>
                </li>
                <li>
                    <a class="reset">Reset options</a>
                </li>
            </ul>
        </nav>

        <div class="my-filter-sidebar d-block d-sm-block d-md-none">
            <!-- <vs-button @click="activeMenu=!activeMenu" color="primary" type="filled">Φίλτρα</vs-button> -->
            <button @click="activeMenu = !activeMenu" class="btn">Φίλτρα</button>
            <vs-sidebar parent="body" color="primary" class="sidebarx search-sidebar-comp" spacer v-model="activeMenu">
                <div class="header-sidebar" slot="header">
                    <h4 class="text-white">Φίλτρα</h4>
                    <!-- <h3 @click="openSideMenu" class="close-filters"><span class="fas fa-times"></span></h3> -->
                </div>
                <div class="">
                    <ul class="my-filter-list">
                        <li>
                           <custom-multiselect v-model="tag_keywords" :options="user_tags_keywords" tag-placeholder="Add this as new tag" placeholder="Εισάγετε λέξεις κλειδιά" label="name" track-by="code" :multiple="true" :taggable="true" @tag="addTag" class="mt-3" :close-on-select="false" :clear-on-select="false" :max-height="100" ></custom-multiselect>
                        </li>
                        <li>
                            <!-- <search-plugin :taggable="true" :clearInputs="clearInputs" @getTaggedSpeakers="storeSpeakers"></search-plugin> -->
                            <custom-multiselect 
                                v-model="multiple_search_data.speakers" 
                                placeholder="Αναζήτηση ομιλιτή" 
                                open-direction="bottom" 
                                :options="search.speakers"
                                track-by="speaker_id"
                                label="greek_name"
                                :searchable="true" 
                                :loading="isLoading" 
                                :internal-search="false" 
                                :clear-on-select="false" 
                                :close-on-select="false"
                                :max-height="600" 
                                :show-no-results="true" 
                                :hide-selected="true"
                                :preserveSearch="true"
                                @search-change="asyncFind"
                                tag-placeholder="Add this as new tag"
                                :multiple="true"
                                :taggable="true"
                                class="mt-3">
                                <span slot="noResult">Δεν βρέθηκαν ομιλητές</span>
                            </custom-multiselect>
                        </li>
                        <li>
                            <!-- <search-plugin :parties="true" :selectedParties="multiple_search_data.parties" :clearInputs="clearInputs" :partiesData="parties_dropdown_options" @getTaggedParties="storeParties"></search-plugin> -->
                            <custom-multiselect 
                                v-model="multiple_search_data.parties" 
                                placeholder="Επιλογή κομμάτων" 
                                open-direction="bottom" 
                                :options="parties_dropdown_options"
                                track-by="party_id"
                                label="fullname_el"
                                :internal-search="true" 
                                :clear-on-select="false" 
                                :close-on-select="false"
                                :max-height="600" 
                                :show-no-results="true" 
                                :hide-selected="true"
                                tag-placeholder="Add this as new tag"
                                :multiple="true"
                                :taggable="true"
                                class="mt-3">
                                <span slot="noResult">Δεν βρέθηκαν κόμματα</span>
                            </custom-multiselect>
                        </li>
                        <li>
                            <div class="datepkr-toggle mt-3">
                                <vs-switch color="#4896e5" v-model="isMultipleFilter" class="switch-btn">
                                    <span slot="on" class="switch-font">Από έως</span>
                                    <span slot="off" class="switch-font">Ημερομηνία</span>
                                </vs-switch>
                            </div>
                            <div v-if="isMultipleFilter">
                                <label class="text-white">{{ $t("conferences.datepicker.from") }}</label>
                                <datepicker v-model="startDate" :format="myFormattedDate" :bootstrap-styling="true"
                                    wrapper-class="pickerDiv search-pickerDiv" placeholder="Επιλέξτε αρχική ημερομηνία"></datepicker>
                                <label class="text-white">{{ $t("conferences.datepicker.to") }}</label>
                                <datepicker v-model="endDate" :format="myFormattedDate" :bootstrap-styling="true"
                                    wrapper-class="pickerDiv search-pickerDiv" placeholder="Επιλέξτε τελική ημερομηνία"></datepicker>
                            </div>
                            <div v-else class="datepkr-toggle mt-2">
                                <datepicker v-model="singleDate" :format="myFormattedDate" :bootstrap-styling="true"
                                    wrapper-class="pickerDiv search-pickerDiv" placeholder="Επιλέξτε ημερομηνία"></datepicker>
                            </div>
                        </li>
                        <li>
                            <div class="search-btn-div mt-4">
                                <button @click="do_search" :disabled="canSearch" class="btn btn-secondary w-100">{{ $t("conferences.search") }}</button>
                                <button @click="clearAllFilters" class="btn btn-secondary w-100 mt-3">Καθαριστμός φίλτρων</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </vs-sidebar>
        </div>

        <div class="col-md-6 col-lg-5 col-xl-4 d-none d-sm-none d-md-block" :class="{ 'initial-height': !search.hasData }" style="background-color: #2B4162;">
            <custom-multiselect v-model="tag_keywords" :options="user_tags_keywords" tag-placeholder="Add this as new tag" placeholder="Εισάγετε λέξεις κλειδιά" label="name" track-by="code" :close-on-select="false" :multiple="true" :taggable="true" @tag="addTag" class="mt-3"></custom-multiselect>
            <!-- <search-plugin :clearInputs="clearInputs" :selectedParties="multiple_search_data.parties" @getTaggedSpeakers="storeSpeakers"></search-plugin> -->
            <custom-multiselect 
                v-model="multiple_search_data.speakers" 
                placeholder="Αναζήτηση ομιλιτή" 
                open-direction="bottom" 
                :options="search.speakers"
                track-by="speaker_id"
                label="greek_name"
                :searchable="true" 
                :preserveSearch="true"
                :loading="isLoading" 
                :internal-search="false" 
                :clear-on-select="false" 
                :close-on-select="false"
                :max-height="600" 
                :show-no-results="true" 
                :hide-selected="true"
                @search-change="asyncFind"
                tag-placeholder="Add this as new tag"
                :multiple="true"
                :taggable="true"
                class="mt-3"
                id="ajax">
                <span slot="noResult">Δεν βρέθηκαν ομιλητές</span>
            </custom-multiselect>
            <!-- <search-plugin :parties="true" :clearInputs="clearInputs" :partiesData="parties_dropdown_options" @getTaggedParties="storeParties"></search-plugin> -->
            <custom-multiselect 
                v-model="multiple_search_data.parties" 
                placeholder="Επιλογή κομμάτων" 
                open-direction="bottom" 
                :options="parties_dropdown_options"
                track-by="party_id"
                label="fullname_el"
                :internal-search="true" 
                :clear-on-select="false" 
                :close-on-select="false"
                :max-height="400" 
                :show-no-results="true" 
                :hide-selected="true"
                tag-placeholder="Add this as new tag"
                :multiple="true"
                :taggable="true"
                class="mt-3">
                <span slot="noResult">Δεν βρέθηκαν κόμματα</span>
            </custom-multiselect>
            <!-- Date selection -->
            <div class="datepkr-toggle mt-3">
                <vs-switch color="#4896e5" v-model="isMultipleFilter" class="switch-btn">
                    <!-- <span slot="on" class="switch-font">{{ $t("conferences.datepicker.from_to") }}</span> -->
                    <span slot="on" class="switch-font">Από έως</span>
                    <!-- <span slot="off" class="switch-font">{{ $t("conferences.datepicker.date") }}</span> -->
                    <span slot="off" class="switch-font">Ημερομηνία</span>
                </vs-switch>
            </div>
            <div v-if="isMultipleFilter">
                <label class="text-white">{{ $t("conferences.datepicker.from") }}</label>
                <datepicker v-model="startDate" :format="myFormattedDate" :bootstrap-styling="true"
                    wrapper-class="pickerDiv" placeholder="Επιλέξτε αρχική ημερομηνία"></datepicker>
                <label class="text-white">{{ $t("conferences.datepicker.to") }}</label>
                <datepicker v-model="endDate" :format="myFormattedDate" :bootstrap-styling="true"
                    wrapper-class="pickerDiv" placeholder="Επιλέξτε τελική ημερομηνία"></datepicker>
                <!-- <div class="datepkr-btn">
                    <button class="btn reset-btn datepkr-btn-color" @click="getDates" :disabled="isDisabled">{{ $t("conferences.datepicker.submit") }}</button>
                    <button class="btn reset-btn datepkr-btn-color" @click="clearDatesInput('multiple')" :disabled="isDisabled">Επαναφορά</button>
                </div> -->
            </div>
            <div v-else class="datepkr-toggle mt-2">
                <!-- <label>{{ $t("conferences.datepicker.select_single_date") }}</label> -->
                <datepicker v-model="singleDate" :format="myFormattedDate" :bootstrap-styling="true"
                    wrapper-class="pickerDiv" placeholder="Επιλέξτε ημερομηνία"></datepicker>
                <!-- <div class="datepkr-btn mt-3">
                    <button class="btn reset-btn datepkr-btn-color" @click="getDate" :disabled="isDisabled">{{ $t("conferences.datepicker.submit") }}</button>
                    <button class="btn reset-btn datepkr-btn-color" @click="clearDatesInput('single')" :disabled="isDisabled">Επαναφορά</button>
                </div> -->
            </div>
            <!-- end of date selection -->
            <div class="search-btn-div mt-4">
                <button @click="do_search" :disabled="canSearch" class="btn btn-secondary w-100">{{ $t("conferences.search") }}</button>
                <button @click="clearAllFilters" class="btn btn-secondary w-100 mt-3">Καθαριστμός φίλτρων</button>
            </div>
        </div>

        <!-- Page Content  -->
        <div class="col-12 col-md-6 col-lg-7 col-xl-8 search-data-scroll mt-5 mt-md-3" v-if="search.isDone && !search.loading">
            <div v-if="search.isDone && search.hasData && search.results.data.data.length > 0" class="col-12 p-0">
                <pagination :data="search.results.data.meta" @pagination-change-page="changePage" :limit=1>
                    <span slot="prev-nav">&lt;</span>
                    <span slot="next-nav">&gt;</span>
                </pagination>
            </div>
            <div v-for="search_data in search.results.data.data" :key="search_data.speech_id">
                <speech :speech="search_data" isFromConference=true :isFromSearchPage="true"></speech>
            </div>
            <div v-if="search.isDone && search.hasData && search.results.data.data.length > 0" class="col-12 p-0 mt-2">
                <pagination :data="search.results.data.meta" @pagination-change-page="changePage" :limit=1>
                    <span slot="prev-nav">&lt;</span>
                    <span slot="next-nav">&gt;</span>
                </pagination>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-7 col-xl-8 mt-5 mt-md-3" v-if="search.loading">
            <img :src=" 'img' + '/Spinner.gif' " class="m-auto d-block"/>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    // .wrapper {
    //     display: flex;
    //     width: 100%;
    //     align-items: stretch;
    // }
    .switch-btn{
        width: 130px!important;
        font-size: 1.5em;
    }
    // .multiselect__option--highlight {
    //     background: #41B883;
    //     background: #495057ad;
    //     outline: none;
    //     color: white;
    // }
    .initial-height {
        // height: 85vh;
        padding-bottom: 3em;
    }
    .search-data-scroll {
        height: 75vh;
        overflow-y: auto;
    }
    @media only screen and (min-width: 992px) and (max-width: 1199px){
        .search-data-scroll {
            flex: 0 0 57.333333%!important;
            max-width: 57.333333%!important;
        }
    }
    @media only screen and (min-width: 1200px) {
        .search-data-scroll {
            flex: 0 0 65.666667%!important;
        }
    }
    @media only screen and (max-width: 767px){
        .search-data-scroll {
            height: auto;
            overflow-y: initial;
        }
        .multiselect__content-wrapper{
            /* width: 350px!important; */
            position: relative!important;
            height: 350px!important;
        }
        .multiselect__option{
           /* white-space: normal!important; */
           word-break: break-word;
        }
    }
    @media only screen and (min-width: 768px) {
        .multiselect__option{
           white-space: normal!important;
        }
        .vs-sidebar--items {
            padding: 10px 10px;
            background: inherit;
            overflow-y: auto;
            list-style: none;
        }
    }
    
</style>
<script>
    import moment from 'moment';
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: {
            name: String,
            path: String
        },
        data(){
            return {
                tags: [],
                isActive: true,
                search: {
                    singleDate: [],
                    multipleDates: [],
                    hasData: false,
                    isDone: false,
                    type: '',
                    results: [],
                    speakers: [],
                    loading: false
                },
                isMultipleFilter: false,
                showInfoDiv: false,
                selected_date: [],
                startDate: null,
                endDate: null,
                singleDate: null,
                parties_dropdown_selected: [],
                parties_dropdown_options: [],
                parties: [],
                activeMenu: false,
                multiple_search_data: {
                    speakers: [],
                    parties: [],
                    tags: [
                        { name: 'Μνημόνιο', code: 1 }
                    ],
                    dateRange: {
                        startDate: null,
                        endDate: null
                    },
                    singleDate: null
                },
                user_tags_keywords: [
                    { name: 'Μνημόνιο', code:  1 }
                ],
                tag_keywords: [
                    { name: 'Μνημόνιο', code: 1 }
                ],
                clearInputs: false,
                isLoading: false
            }
        },
        methods:{
            changePage(page) {
                api.call('post',this.api_path + 'search?page=' + page, this.multiple_search_data)
                .then(response => {
                    if(response) {
                        this.search.results = response
                        // this.sortArrByDate()
                        // this.search.hasData = true
                    }else{
                        // this.search.hasData = false
                    }
                })
            },
            asyncFind(query){
                if(query.length > 2){
                    this.isLoading = true
                    if(this.debounceTimer){
                        clearTimeout(this.debounceTimer)   // clearing debounceTimer
                    }
                    this.debounceTimer = setTimeout( () =>{
                        axios.get(this.api_path + 'speakers/search/' + query)
                        .then( response => {
                            if(response.status == 200 && response.data.data.length > 0){
                                this.isLoading = false
                                this.search.speakers = response.data.data;
                            }else{
                                this.isLoading = false
                            }
                        })
                    }, 500)
                }
            },
            clearAllFilters() {
                this.multiple_search_data.speakers = []
                this.multiple_search_data.parties = []
                this.multiple_search_data.tags = []
                this.tag_keywords = []
                this.search.speakers = []
                this.startDate = null
                this.endDate = null
                this.singleDate = null
                // to send to the searchSpeaker component signal to clear the inputs
                this.clearInputs = true
                // after one sec return to initial value
                setTimeout(() => {
                    this.clearInputs = false
                }, 1000);
            },
            addTag(newTag) {
                // console.log(newTag)
                const tag = {
                    name: newTag,
                    code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
                }
                //this.user_tags_keywords.push(tag)
                this.tag_keywords.push(tag)
            },
            storeSpeakers(event) {
                // console.log(event)
                this.multiple_search_data.speakers = event;
            },
            storeParties(event) {
                // console.log(event)
                this.multiple_search_data.parties = event;
            },
            sortArrByDate() {
                this.search.results.data.data.sort( (a,b) => new Date(b.speech_conference_date) - new Date(a.speech_conference_date))
            },
            do_search() {
                this.search.isDone = false
                this.search.loading = true
                if(this.tag_keywords.length > 0) {
                    this.multiple_search_data.tags = this.tag_keywords
                }
                if(this.startDate && this.endDate) {
                    this.multiple_search_data.dateRange.startDate = moment(this.startDate).format('YYYY-MM-DD')
                    this.multiple_search_data.dateRange.endDate = moment(this.endDate).format('YYYY-MM-DD')
                }
                if(this.singleDate) {
                    this.multiple_search_data.singleDate = moment(this.singleDate).format('YYYY-MM-DD')
                }
                setTimeout(() => {
                    api.call('post',this.api_path + 'search', this.multiple_search_data)
                    .then( response => {
                        console.log(response)
                        if(response) {
                            this.search.results = response
                            // this.sortArrByDate()
                            this.search.hasData = true
                        }else{
                            this.search.hasData = false
                        }

                        this.search.isDone = true
                        this.search.loading = false
                    })
                }, 1500)
            },
            load_parties_dropdown() {
                setTimeout(() => {
                    api.call('get', this.api_path + 'parties')
                    .then( (response) => {
                        if (response.status == 200 && response.statusText == "OK") {
                            if (response.data) {
                                this.parties_dropdown_options = response.data.data
                                // response.data.data.forEach( (arrayItem) => {
                                //     this.parties_dropdown_options.push(arrayItem.fullname_el);
                                // });
                            }
                        }
                    })
                }, 500)
            },
            clear_parties_dropdown() {
                this.parties_dropdown_options = []
            },
            clearDatesInput(mode) {
                this.datepicker.hasData = false
                this.datepicker.isDone = false
                this.datepicker.type = ''
                if(mode == 'single'){
                    this.datepicker.singleDate = []
                    this.singleDate = ''
                }else if(mode == 'multiple'){
                    this.startDate = ''
                    this.endDate = ''
                    this.datepicker.multipleDates = []
                }
            },
            getDate() {
                this.datepicker.isDone = false
                this.datepicker.type = 'single'
                if (this.singleDate) {
                    this.singleDate = moment(this.singleDate).format('YYYY-MM-DD')
                    axios.get(this.api_path + 'conference/date/' + this.singleDate)
                    .then( response => {
                        if (response.status == 200 && response.statusText == "OK") {
                            if (response.data.data != null) {
                                this.datepicker.hasData = true
                                this.datepicker.singleDate = response.data.data
                            } else {
                                this.datepicker.hasData = false
                            }
                        }
                        this.ajaxDone = true
                        this.datepicker.isDone = true
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
                }
            },
            getDates(date) {
                //get dates from datepicker
                this.datepicker.isDone = false
                this.datepicker.type = 'multiple'
                if (this.startDate && this.endDate) {
                    this.startDate = moment(this.startDate).format('YYYY-MM-DD')
                    this.endDate = moment(this.endDate).format('YYYY-MM-DD')

                    axios.get(this.api_path + 'conference/start/' + 
                              this.startDate + '/end/' + 
                              this.endDate + '?order_field=' + 
                              this.order_field +'&orientation=' + 
                              this.order_orientation)
                        .then(response => {

                            if (response.status == 200 && response.statusText == "OK") {
                                if (response.data.data.length > 0) {
                                    this.datepicker.hasData = true
                                    this.datepicker.multipleDates = response
                                } else {
                                    this.datepicker.hasData = false
                                }
                            }
                            this.ajaxDone = true
                            this.datepicker.isDone = true
                        }).catch(function (error) {
                            console.log(error)
                        });
                }
            },
            resetDates() {
                //reset selected dates from dropdown
                this.selected_date = [];
                this.ajaxData.details = [];
            },
            myFormattedDate(date) {
                return moment(date).format('DD/MM/YYYY');
            },
            reset_all_opts: function () {
                this.isActive = true,
                this.search.singleDate = [],
                this.search.multipleDates = [],
                this.search.hasData = false,
                this.search.isDone = false,
                this.search.type = '',
                this.isMultipleFilter = false,
                this.showInfoDiv = false,
                this.selected_date = [],
                this.startDate = null,
                this.endDate = null,
                this.singleDate = null,
                this.parties_dropdown_selected = [],
                this.parties_dropdown_options = [],
                this.parties = []
            }
        },
        computed:{
            isDisabled() {
                if (this.singleDate && !this.isMultipleFilter) {
                    return (this.singleDate ? false : true)
                } else {
                    if (this.startDate && this.endDate && this.isMultipleFilter) {
                        return false;
                    } else {
                        return true;
                    }
                }
            },
            canSearch() {
                if(this.tag_keywords.length > 0) return false
                else return true
            },
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        watch: {
            isMultipleFilter: function(val) {
                if(val) {
                    this.singleDate = null
                    this.multiple_search_data.singleDate = null
                } else {
                    this.startDate = null
                    this.endDate = null
                    this.multiple_search_data.dateRange.startDate = null
                    this.multiple_search_data.dateRange.endDate = null
                }
            }
        },
        created() {
            this.load_parties_dropdown()
        }
    }
</script>