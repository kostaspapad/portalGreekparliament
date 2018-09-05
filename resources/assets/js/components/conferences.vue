<template>
    <div class="container">
        <div v-if="!loading" class="row conferences-container">
            <div class="col-md-6 conference-title-box">
                <h4>Latest conferences</h4>
            </div>
            <div v-show="ajaxDone" class="col-12 col-sm-6 col-md-6 col-lg-8">
                
                <div v-if="ajaxData.conferenceData.data.data && !noData" class=""  v-for="conference in ajaxData.conferenceData.data.data" :key="conference.id" style="margin: 15px 0 15px 0;">
                    <div class="shadow p-3 mb-5 bg-white rounded">
                        <!-- <span class="show-details-dates">{{detail[0].Date}}</span> -->
                        <span class="show-details-dates">
                            <a :href=" '/conference/' + conference.conference_date + '/speeches' ">{{conference.conference_date}}</a>
                        </span>
                        <div>
                            <p style="margin: 0;">{{conference.session}}</p>
                            <span>{{conference.time_period}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12" style="padding-left: 2.5rem;">
                    <pagination :data="ajaxData.conferenceData.data.meta" @pagination-change-page="changePage" :limit=1>
                        <span slot="prev-nav">&lt;</span>
                        <span slot="next-nav">&gt;</span>
                    </pagination>
                </div>
                <div v-show="noData" class="col-12 col-sm-6 col-md-6 col-lg-8">
                    <h4>No data available</h4>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                <!-- <label>Filters</label> -->
                <span @click="showInfoDiv = !showInfoDiv" v-show="!showInfoDiv" style="float:right;"><i class="fa fa-info-circle info-icon"></i></span>
                <!-- <font-awesome :icon="info-circle"></font-awesome> -->
                <transition name="slide-fade">
                    <div v-if="showInfoDiv" class="alert alert-info" role="alert">
                        <button @click="showInfoDiv = !showInfoDiv" type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="alert-heading">Informations</h4>
                        <p>Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.</p>
                        <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
                    </div>
                </transition>
                <!-- <div style="text-align:left;">
                    <toggle-button 
                        id="changed-font"
                        v-model="isMultipleFilter"
                        color="#82C7EB" 
                        :labels="{checked: 'Multiple Dates', unchecked: 'Single Date'}"
                        :width="135" 
                    />
                </div> -->
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
                    <div v-show="selected_date.length" style="text-align: left;">
                        <button @click="resetDates" class="btn btn-sm reset-btn" style="margin-top: 5px;">Reset</button>
                    </div>
                </div>
                <div v-else style="text-align: left;">
                    <label>Start Date</label>
                    <datepicker v-model="startDate" :format="myFormattedDate" :bootstrap-styling="true" wrapper-class="pickerDiv" placeholder="Select start date"></datepicker>
                    <label>End Date</label>
                    <datepicker v-model="endDate" :format="myFormattedDate" :bootstrap-styling="true" wrapper-class="pickerDiv" placeholder="Select end date"></datepicker>
                    <div style="text-align: left;">
                        <button class="btn reset-btn" @click="getDates" :disabled="isDisabled" style="background-color:rgb(23, 162, 184)">Apply</button>
                    </div>
                </div>
            </div>
            
            <!-- <div class="col-12">
                <pagination :data="ajaxData.conferenceData" @pagination-change-page="changePage" :limit=2>
                    <span slot="prev-nav">&lt; Previous</span>
	                <span slot="next-nav">Next &gt;</span>
                </pagination>
            </div> -->
        </div>
        <div v-else>
            <img :src="path + '/Spinner.gif' "/>
        </div>
    </div>
</template>
<style scoped>
    .conferences-container {
        background-color: #ffffff;
        border-radius: 3px;
        padding-top: 20px;
    }
    .conference-title-box {
        background-color: red;
    }
    .pickerDiv{
        margin-bottom: 10px;
    }
</style>

<script>
    import moment from 'moment';
    export default {
        props: {
            conferences: null,
            path: String
        },
        data(){
            return {
                disabledFn: {
                    
                },
                ajaxData: {
                    conferenceData: [],
                    details: []
                },
                selected_date: [],
                startDate: null,
                endDate: null,
                show_modal: false,
                isMultipleFilter: false,
                showInfoDiv: false,
                noData: true,
                loading: true,
                ajaxDone: false,
                defaultImg: 'default_speaker_icon.png',
                startUrl: 'https://www.hellenicparliament.gr',
                order_field: 'conference_date',
                order_orientation: 'asc'
            }
        },
        methods:{
            showModal(speaker){
                //to open the modal
                this.show_modal = true;
                //console.log(speaker);
                //this.selected_speaker = speaker;
            },
            changePage(page){
                //for pagination
                var self = this;
                
                axios.get(this.$root.host+'/api/v1/conferences?page=' + page + '&order_field=' + self.order_field + '&orientation=' + self.orientation)
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        self.ajaxData.conferenceData = response
                    }
                })
                .catch(function (error) {
                    console.log(error);
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
            getConferences(){
                //get all conferences
                var self = this;
                axios.get(this.$root.host+'/api/v1/conferences')
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        self.ajaxData.conferenceData = response
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            printImg(img){
                //check if deafault img has different name then return our default img
                if(img == 'default-speaker.jpg'){
                    return this.defaultImg
                }else{
                    return img
                }
            },
            getLatestConferences() {
                const self = this

                axios.get(this.$root.host+'/api/v1/conferences?order_field=conference_date&orientation=desc')
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        if(response.data.data.length > 0){
                            self.noData = false
                            //self.ajaxData.details = response.data.data;
                            self.ajaxData.conferenceData = response
                        }else{
                            self.noData = true
                        }
                    }
                    self.ajaxDone = true
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getDates(date){
                //get dates from datepicker
                const self = this

                if(this.startDate && this.endDate){
                    this.startDate = moment(this.startDate).format('YYYY-MM-DD')
                    this.endDate = moment(this.endDate).format('YYYY-MM-DD')
                
                    axios.get(this.$root.host+'/api/v1/conference/start/'+this.startDate+'/end/'+this.endDate)
                    .then(function(response){
                        
                        if(response.status == 200 && response.statusText == "OK"){
                            if(response.data.data.length > 0){
                                self.noData = false
                                //self.ajaxData.details = response.data.data;
                                self.ajaxData.conferenceData = response.data.data
                            }else{
                                self.noData = true
                            }
                        }
                        self.ajaxDone = true
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            },
            getDatesDp(date){
                //get dates from Dropdown
                const self = this; //const means it will never reassigned
                
                const formattedDate = moment(date.Date).format('YYYY/MM/DD');
                
                let tmp = [];
                axios.get('http://95.85.38.123/api/conferences/' + formattedDate)
                .then(function(response){
                    //console.log(response);
                    if(response.status == 200 && response.statusText == "OK"){
                        tmp = response.data.conferences;
                        self.ajaxData.details.push(tmp);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            resetDates(){
                //reset selected dates from dropdown
                this.selected_date = [];
                this.ajaxData.details = [];
            },
            isEmpty(info){

            },
            removeOption(removedOption){
                //remove the selected option from array
                for(var i=0;i<this.ajaxData.details.length;i++){
                    for(var j=0;j<this.ajaxData.details[i].length;j++){
                        if(removedOption.Date == this.ajaxData.details[i][j].Date){
                            this.ajaxData.details.splice(i,1);
                            break;
                        }
                    }
                }
                //we do this so we can hide the reset button
                if(this.ajaxData.details.length == 0){
                    this.selected_date = [];
                }
            },
            myFormattedDate(date){
                return moment(date).format('DD/MM/YYYY');
            }
        },
        computed:{
            isDisabled(){
                if(this.startDate && this.endDate){
                    return false;
                }else{
                    return true;
                }
            }
        },
        created() {
            this.loading = false
            this.getLatestConferences()
            // var self = this;
            // //this.getConferences();
            // //console.log(typeof this.conferences);
            // this.ajaxData.conferenceData = this.conferences;
            // this.disabledFn = {
            //     customPredictor (date) {
            //         //console.log(moment(date).format('YYYY-MM-DD'));
            //         //console.log(self.ajaxData.conferenceData);
            //         var newDate = moment(date).format('YYYY-MM-DD');
            //         for(var data in self.ajaxData.conferenceData){
            //             //console.log(newDate + " " +  self.ajaxData.conferenceData[data]['Date']);
            //             if(self.ajaxData.conferenceData[data]['conference_date'] != newDate){
            //                 return true;
            //             }else{
            //                 return false;
            //             }
            //             //console.log(self.conferenceData[data]['Date']);
            //         }
            //     }
            // }
            //console.log(this.isJsonString(this.conferences));
        }
    }
</script>
