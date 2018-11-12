<template>
    <div class="container">
        <div class="conferences-container">
            <div v-if="ajaxDone">
                <!-- <div class="conference-title-box mb-4">
                    <h2 class="font-weight-bold text-center">Συνεδριάσεις</h2>
                </div> -->
                <div class="row pt-2">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-8">
                        <div v-if="ajaxData.conferenceData.data.data && hasData && !search.hasData" class="p-4 bg-white conference-content-box"
                            v-for="conference in ajaxData.conferenceData.data.data" :key="conference.id">
                            <router-link :to="'/conference/' + conference.conference_date + '/speeches'" class="conference-link">
                                <h3>
                                    {{conference.conference_date}}
                                </h3>
                                <div>
                                    <p class="session-margin">{{conference.session}}</p>
                                    <span>{{conference.time_period}}</span>
                                </div>
                            </router-link>
                        </div>
                        <!-- Single date filter -->
                        <div v-if="search.hasData && singleDate" class="p-4 bg-white conference-content-box">
                            <router-link :to="'/conference/' + search.singleDate.conference_date + '/speeches'" class="conference-link">
                                <h3>
                                    {{search.singleDate.conference_date}}
                                </h3>
                                <div>
                                    <p class="session-margin">{{search.singleDate.session}}</p>
                                    <span>{{search.singleDate.time_period}}</span>
                                </div>
                            </router-link>
                            <!-- <h3 class="show-details-dates">
                                <div @click="redirectToConference(ajaxData.conferenceData.data.data.conference_date)">{{ajaxData.conferenceData.data.data.conference_date}}</div>
                            </h3>
                            <div>
                                <p class="session-margin">{{ajaxData.conferenceData.data.data.session}}</p>
                                <span>{{ajaxData.conferenceData.data.data.time_period}}</span>
                            </div> -->
                        </div>
                        <!-- End of single date filter -->
                        <!-- Multiple Filter -->
                        <div v-if="search.multipleDates && search.hasData">
                            <div class="p-4 bg-white conference-content-box" v-for="conference in search.multipleDates.data.data" :key="conference.id">
                                <router-link :to="'/conference/' + conference.conference_date + '/speeches'" class="conference-link">
                                    <h3>
                                        {{conference.conference_date}}
                                    </h3>
                                    <div>
                                        <p class="session-margin">{{conference.session}}</p>
                                        <span>{{conference.time_period}}</span>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                        <!-- End of multiple filter -->
                        <div v-if="!search.hasData" class="col-12 mt-5 pagination-pad">
                            <pagination :data="ajaxData.conferenceData.data.meta" @pagination-change-page="changePage"
                                :limit=1>
                                <span slot="prev-nav">&lt;</span>
                                <span slot="next-nav">&gt;</span>
                            </pagination>
                        </div>
                        <!-- Multiple pagination -->
                        <div v-if="search.multipleDates && search.hasData" class="col-12 mt-5 pagination-pad">
                            <pagination :data="search.multipleDates.data.meta" @pagination-change-page="changePage"
                                :limit=1>
                                <span slot="prev-nav">&lt;</span>
                                <span slot="next-nav">&gt;</span>
                            </pagination>
                        </div>
                        <!-- End of multiple pagination -->
                        <div v-if="!hasData" class="col-12 col-sm-6 col-md-6 col-lg-8">
                            <h4>{{ $t("conferences.no_conferences_available") }}</h4>
                        </div>
                    </div>
                    <div class="datepicker col-12 col-sm-6 col-md-6 col-lg-4">
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
                            <vs-switch color="#4896e5" v-model="isMultipleFilter">
                                <span slot="on" class="switch-font">{{ $t("conferences.datepicker.from_to") }}</span>
                                <span slot="off" class="switch-font">{{ $t("conferences.datepicker.date") }}</span>
                            </vs-switch>
                        </div>
                        <div v-if="isMultipleFilter">
                            <label>{{ $t("conferences.datepicker.from") }}</label>
                            <datepicker v-model="startDate" :format="myFormattedDate" :bootstrap-styling="true"
                                wrapper-class="pickerDiv" placeholder="Select start date"></datepicker>
                            <label>{{ $t("conferences.datepicker.to") }}</label>
                            <datepicker v-model="endDate" :format="myFormattedDate" :bootstrap-styling="true"
                                wrapper-class="pickerDiv" placeholder="Select end date"></datepicker>
                            <div class="datepkr-btn">
                                <button class="btn reset-btn datepkr-btn-color" @click="getDates" :disabled="isDisabled">{{ $t("conferences.datepicker.submit") }}</button>
                                <button class="btn reset-btn datepkr-btn-color" @click="clearDatesInput('multiple')" :disabled="isDisabled">Επαναφορά</button>
                            </div>
                        </div>
                        <div v-else class="datepkr-toggle">
                            <label>{{ $t("conferences.datepicker.select_single_date") }}</label>
                            <datepicker v-model="singleDate" :format="myFormattedDate" :bootstrap-styling="true"
                                wrapper-class="pickerDiv" placeholder="Select date"></datepicker>
                            <div class="datepkr-btn">
                                <button class="btn reset-btn datepkr-btn-color" @click="getDate" :disabled="isDisabled">{{ $t("conferences.datepicker.submit") }}</button>
                                <button class="btn reset-btn datepkr-btn-color" @click="clearDatesInput('single')" :disabled="isDisabled">Επαναφορά</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <img :src=" 'img' + '/Spinner.gif' " class="m-auto d-block"/>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
    $containerTitleColor: #dbf0ff;
    $titleTextFontColor: #636b6f;
    $bodyTextFontColor: #636b6f;
    $containerColor: white;
    $containerBoxColor: white;
    $contentBoxColor: #e6e6e6;

    .conferences-container {
        background-color: $containerColor;
        border-radius: 5px;
    }

    .conference-title-box {
        background-color: $containerTitleColor;
        height: 100px;
        padding-top: 25px;
    }

    .pickerDiv {
        margin-bottom: 10px;
    }

    .conference-content-box {
        border-bottom: 1px solid $contentBoxColor;
        text-align: initial;
        background-color: $containerBoxColor;
        /* margin: 15px 0 15px 0; */

        .conference-link{
            display: block;
            color: inherit;
            transition: all .2s ease-in-out;
        }
        .conference-link:hover{
            color:#62b356;
        }
    }

    .conference-content-box:hover {
        cursor: hand;
        cursor: pointer;
        opacity: .9;
    }

    .switch-font {
        font-size: 15px;
    }

    .datepicker {
        padding: 10px 30px 10px 15px;
    }

    @media (min-width: 768px) {
        .conference-content-box:hover {
            position: relative;
            /* height: 200px; */
            width: inherit;
            background: #fff;
            border: none;
            /* top: -30px;*/
            padding: 20px;
            /* -webkit-box-shadow: 0px 0px 30px 10px rgba(18,18,18,0.5);
            -moz-box-shadow: 0px 0px 30px 10px rgba(18,18,18,0.5); */
            -webkit-box-shadow: 0px 0px 30px 10px #8888885c;
            -moz-box-shadow: 0px 0px 30px 10px #8888885c;
            /* box-shadow: 0px 0px 30px 10px rgba(18,18,18,0.5); */
            box-shadow: 0px 0px 30px 10px #8888885c;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            left: -20px;
        }
    }

    @media (min-width: 992px) {
        .conference-content-box:hover {
            left: -20px;
        }
    }
</style>

<script>
    import moment from 'moment';
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: {
            conferences: null,
            path: String
        },
        data() {
            return {
                ajaxData: {
                    conferenceData: [],
                    details: []
                },
                search: {
                    singleDate: [],
                    multipleDates: [],
                    hasData: false
                },
                selected_date: [],
                startDate: null,
                endDate: null,
                singleDate: null,
                isMultipleFilter: false,
                showInfoDiv: false,
                hasData: false,
                loading: true,
                ajaxDone: false,
                defaultImg: 'default_speaker_icon.png',
                order_field: 'conference_date',
                order_orientation: 'desc'
            }
        },
        methods: {
            changePage(page) {
                //for pagination
                let url = null
                if (this.startDate && this.endDate) {
                    url = 'conference/start/' + this.startDate + 
                          '/end/' + this.endDate + 
                          '?page=' + page + 
                          '&order_field=' + this.order_field +
                          '&orientation=' + this.order_orientation
                } else {
                    url = 'conferences?page=' + page + '&order_field=' + this.order_field + '&orientation=' +
                        this.order_orientation
                }

                axios.get(this.api_path + url)
                    .then(response => {
                        if (response.status == 200 && response.statusText == "OK") {
                            if(this.search.multipleDates){
                                //if we have searched something
                                this.search.multipleDates = response
                            }else{
                                this.ajaxData.conferenceData = response
                            }
                        }
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
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
            redirectToConference(conference_date,middle_click = null) {
                if(middle_click){
                    let routeData = this.$router.resolve({path: '/conference/' + conference_date + '/speeches'});
                    window.open(routeData.href, '_blank');
                }else{
                    this.$router.push({ path: '/conference/' + conference_date + '/speeches' })
                }
            },
            printImg(img) {
                //check if deafault img has different name then return our default img
                if (img == 'default-speaker.jpg') {
                    return this.defaultImg
                } else {
                    return img
                }
            },
            getLatestConferences() {
                setTimeout(() => {
                    axios.get(this.api_path +
                            'conferences?order_field=' + this.order_field +'&orientation=' + this.order_orientation)
                        .then(response => {
                            if (response.status == 200 && response.statusText == "OK") {
                                if (response.data.data.length > 0) {
                                    this.hasData = true
                                    this.ajaxData.conferenceData = response
                                } else {
                                    this.hasData = false
                                }
                            }
                            this.ajaxDone = true

                        }).catch(function (error) {
                            console.log(error)
                        })
                }, 1000)
            },
            getDates(date) {
                //get dates from datepicker

                if (this.startDate && this.endDate) {
                    this.startDate = moment(this.startDate).format('YYYY-MM-DD')
                    this.endDate = moment(this.endDate).format('YYYY-MM-DD')

                    axios.get(this.api_path + 'conference/start/' + this.startDate + '/end/' + this.endDate + '?order_field=' + this.order_field +'&orientation=' + this.order_orientation)
                        .then(response => {

                            if (response.status == 200 && response.statusText == "OK") {
                                if (response.data.data.length > 0) {
                                    this.search.hasData = true
                                    this.search.multipleDates = response
                                } else {
                                    this.search.hasData = false
                                }
                            }
                            this.ajaxDone = true
                        }).catch(function (error) {
                            console.log(error)
                        });
                }
            },
            // getDatesDp(date){
            //     //get dates from Dropdown
            //     const self = this //const means it will never reassigned

            //     const formattedDate = moment(date.Date).format('YYYY/MM/DD')

            //     let tmp = []
            //     axios.get('http://95.85.38.123/api/conferences/' + formattedDate)
            //     .then(function(response){
            //         if(response.status == 200 && response.statusText == "OK"){
            //             tmp = response.data.conferences
            //             self.ajaxData.details.push(tmp)
            //         }
            //     })
            //     .catch(function (error) {
            //         console.log(error)
            //     });
            // },
            getDate() {
                if (this.singleDate) {
                    this.singleDate = moment(this.singleDate).format('YYYY-MM-DD')
                    axios.get(this.api_path + 'conference/date/' + this.singleDate)
                        .then( response => {
                            if (response.status == 200 && response.statusText == "OK") {
                                if (response.data.data != null) {
                                    this.search.hasData = true
                                    this.search.singleDate = response.data.data
                                } else {
                                    this.search.hasData = false
                                }
                            }
                            this.ajaxDone = true
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                }
            },
            resetDates() {
                //reset selected dates from dropdown
                this.selected_date = [];
                this.ajaxData.details = [];
            },
            clearDatesInput(mode) {
                this.search.hasData = false
                if(mode == 'single'){
                    this.search.singleDate = []
                    this.singleDate = ''
                }else if(mode == 'multiple'){
                    this.startDate = ''
                    this.endDate = ''
                    this.search.multipleDates = []
                }
            },
            removeOption(removedOption) {
                //remove the selected option from array
                for (var i = 0; i < this.ajaxData.details.length; i++) {
                    for (var j = 0; j < this.ajaxData.details[i].length; j++) {
                        if (removedOption.Date == this.ajaxData.details[i][j].Date) {
                            this.ajaxData.details.splice(i, 1);
                            break;
                        }
                    }
                }
                //we do this so we can hide the reset button
                if (this.ajaxData.details.length == 0) {
                    this.selected_date = [];
                }
            },
            myFormattedDate(date) {
                return moment(date).format('DD/MM/YYYY');
            }
        },
        computed: {
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
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        created() {
            this.loading = false
            this.getLatestConferences()
        }
    }
</script>