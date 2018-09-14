<template>
    <div class="container">
        <div class="conferences-container">
            <div v-if="ajaxDone">
                <div class="conference-title-box mb-4">
                    <h2 class="font-weight-bold">Latest conferences</h2>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-8">

                        <div v-if="ajaxData.conferenceData.data.data && !noData && !singleDate" class="p-4 bg-white conference-content-box"
                            v-for="conference in ajaxData.conferenceData.data.data" :key="conference.id">
                            <h3 class="show-details-dates">
                                <div @click="redirectToConference(conference.conference_date)">{{conference.conference_date}}</div>
                            </h3>
                            <div>
                                <p style="margin: 0;">{{conference.session}}</p>
                                <span>{{conference.time_period}}</span>
                            </div>
                        </div>

                        <div v-if="singleDate && !noData" class="p-4 bg-white conference-content-box">
                            <h3 class="show-details-dates">
                                <div @click="redirectToConference(ajaxData.conferenceData.data.data.conference_date)">{{ajaxData.conferenceData.data.data.conference_date}}</div>
                            </h3>
                            <div>
                                <p style="margin: 0;">{{ajaxData.conferenceData.data.data.session}}</p>
                                <span>{{ajaxData.conferenceData.data.data.time_period}}</span>
                            </div>
                        </div>
                        <div class="col-12 mt-5" style="padding-left: 2.5rem;">
                            <pagination :data="ajaxData.conferenceData.data.meta" @pagination-change-page="changePage"
                                :limit=1>
                                <span slot="prev-nav">&lt;</span>
                                <span slot="next-nav">&gt;</span>
                            </pagination>
                        </div>
                        <div v-show="noData" class="col-12 col-sm-6 col-md-6 col-lg-8">
                            <h4>No data available</h4>
                        </div>
                    </div>

                    <div class="datepicker col-12 col-sm-6 col-md-6 col-lg-4">
                        <span @click="showInfoDiv = !showInfoDiv" v-show="!showInfoDiv" style="float:right;"><i class="fa fa-info-circle info-icon"></i></span>
                        <transition name="slide-fade">
                            <div v-if="showInfoDiv" class="alert alert-info" role="alert">
                                <button @click="showInfoDiv = !showInfoDiv" type="button" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="alert-heading">Informations</h4>
                                <p>You can choose to select between one date or a range of dates.</p>
                            </div>
                        </transition>
                        <div style="text-align:left;">
                            <toggle-button id="changed-font" v-model="isMultipleFilter" color="#82C7EB" :labels="{checked: 'Range of Dates', unchecked: 'Single Date'}"
                                :width="140" />
                        </div>
                        <div v-if="isMultipleFilter" style="background-color: ;">
                            <!-- <multiselect 
                            v-model="selected_date" 
                            :options="ajaxData.conferenceData" 
                            track-by="conference_date"
                            label="conference_date"
                            placeholder="Select Date"
                            :multiple="true" 
                            :close-on-select="false" 
                            :clear-on-select="false" 
                            :hide-selected="true" 
                            :preserve-search="true"
                            :closeOnSelect="true"
                            @select="getDatesDp"
                            @remove="removeOption"
                        >
                            <template slot="tag" slot-scope="props">
                                <span class="custom__tag">
                                    <span>{{ props.option.conference_date }}</span>
                                    <span class="custom__remove" @click="props.remove(props.option)">❌</span>
                                </span>
                            </template>
                        </multiselect> -->
                            <!-- <div v-show="selected_date.length" style="text-align: left;">
                            <button @click="resetDates" class="btn btn-sm reset-btn" style="margin-top: 5px;">Reset</button>
                        </div> -->
                            <label>Start Date</label>
                            <datepicker v-model="startDate" :format="myFormattedDate" :bootstrap-styling="true"
                                wrapper-class="pickerDiv" placeholder="Select start date"></datepicker>
                            <label>End Date</label>
                            <datepicker v-model="endDate" :format="myFormattedDate" :bootstrap-styling="true"
                                wrapper-class="pickerDiv" placeholder="Select end date"></datepicker>
                            <div style="text-align: left;">
                                <button class="btn reset-btn" @click="getDates" :disabled="isDisabled" style="background-color:rgb(23, 162, 184)">Apply</button>
                            </div>
                        </div>
                        <div v-else style="text-align: left;">
                            <label>Select Date</label>
                            <datepicker v-model="singleDate" :format="myFormattedDate" :bootstrap-styling="true"
                                wrapper-class="pickerDiv" placeholder="Select date"></datepicker>
                            <div style="text-align: left;">
                                <button class="btn reset-btn" @click="getDate" :disabled="isDisabled" style="background-color:rgb(23, 162, 184)">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <img :src="path + '/Spinner.gif' " />
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
    }

    .conference-content-box:hover {
        cursor: hand;
        cursor: pointer;
        opacity: .9;
    }

    .datepicker {
        padding-right: 30px;
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
                selected_date: [],
                startDate: null,
                endDate: null,
                singleDate: null,
                isMultipleFilter: false,
                showInfoDiv: false,
                noData: true,
                loading: true,
                ajaxDone: false,
                defaultImg: 'default_speaker_icon.png',
                order_field: 'conference_date',
                order_orientation: 'asc'
            }
        },
        methods: {
            changePage(page) {
                //for pagination
                var self = this
                let url = null
                if (this.startDate && this.endDate) {
                    url = '/api/v1/conference/start/' + this.startDate + '/end/' + this.endDate + '?page=' + page
                } else {
                    url = '/api/v1/conferences?page=' + page + '&order_field=' + this.order_field + '&orientation=' +
                        this.orientation
                }

                axios.get(this.$root.host + url)
                    .then(function (response) {
                        if (response.status == 200 && response.statusText == "OK") {
                            self.ajaxData.conferenceData = response
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
            redirectToConference(conference_date) {
                console.log(1);
                window.location = '/conference/' + conference_date + '/speeches'
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
                const self = this
                setTimeout(() => {
                    axios.get(this.$root.host +
                            '/api/v1/conferences?order_field=conference_date&orientation=desc')
                        .then(function (response) {
                            if (response.status == 200 && response.statusText == "OK") {
                                if (response.data.data.length > 0) {
                                    self.noData = false
                                    self.ajaxData.conferenceData = response
                                } else {
                                    self.noData = true
                                }
                            }
                            self.ajaxDone = true

                        }).catch(function (error) {
                            console.log(error)
                        })
                }, 1000)
            },
            getDates(date) {
                //get dates from datepicker
                const self = this

                if (this.startDate && this.endDate) {
                    this.startDate = moment(this.startDate).format('YYYY-MM-DD')
                    this.endDate = moment(this.endDate).format('YYYY-MM-DD')

                    axios.get(this.$root.host + '/api/v1/conference/start/' + this.startDate + '/end/' + this.endDate)
                        .then(function (response) {

                            if (response.status == 200 && response.statusText == "OK") {
                                if (response.data.data.length > 0) {
                                    self.noData = false
                                    self.ajaxData.conferenceData = response
                                } else {
                                    self.noData = true
                                }
                            }
                            self.ajaxDone = true
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
                const self = this
                if (this.singleDate) {
                    this.singleDate = moment(this.singleDate).format('YYYY-MM-DD')
                    axios.get(this.$root.host + '/api/v1/conference/date/' + this.singleDate)
                        .then(function (response) {
                            if (response.status == 200 && response.statusText == "OK") {
                                if (response.data.data != null) {
                                    self.noData = false
                                    self.ajaxData.conferenceData = response
                                } else {
                                    self.noData = true
                                }
                            }
                            self.ajaxDone = true
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
            }
        },
        created() {
            this.loading = false
            this.getLatestConferences()
        }
    }
</script>