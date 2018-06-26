<template>
    <div class="container">
        <div v-if="conferencesData" class="row">
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
                <div style="text-align:left;">
                    <toggle-button 
                        id="changed-font"
                        v-model="isMultipleFilter"
                        color="#82C7EB" 
                        :labels="{checked: 'Multiple Dates', unchecked: 'Single Date'}"
                        :width="135" 
                    />
                </div>
                <div v-if="isMultipleFilter" style="background-color: ;">
                    <multiselect 
                        v-model="selected_date" 
                        :options="conferencesData" 
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
                    </multiselect>
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
                        <button class="btn btn-primary reset-btn" @click="getDates" :disabled="isDisabled">Apply</button>
                    </div>
                </div>
            </div>
            <div v-show="details.length" class="col-12 col-sm-6 col-md-6 col-lg-8 scrollable">
                <div class=""  v-for="detail in details" :key="detail.id" style="margin: 15px 0 15px 0;">
                    <div class="rounded shadow-sm" style="border: 1px solid red">
                        <!-- <span class="show-details-dates">{{detail[0].Date}}</span> -->
                        <h1>HELLO SPIRO</h1>
                        {{detail}}
                        <span class="show-details-dates">{{detail.conference_date}}</span>
                        <div>{{detail.id}}</div>
                        <!-- <div v-for="info in detail" :key="info"> -->
                            <!-- {{info.ID}} -->
                            <!-- <div v-show="isEmpty(info)" v-show="info.DocumentLocation != '' && info.DocumentName != '' ">
                                <a :href="startUrl + '/' + info.DocumentLocation + '/' + info.DocumentName">Link to doc file</a>
                            </div>
                            <div v-show="isEmpty(info)" v-show="info.PDFdocumentLocation != '' && info.PDFdocuments != '' ">
                                <a :href="startUrl + '/' + info.DocumentLocation + '/' + info.PDFdocuments">Link to doc file</a>
                            </div> -->
                        <!-- </div> -->
                        
                    </div>
                </div>
            </div>
            <div v-show="noData" class="col-12 col-sm-6 col-md-6 col-lg-8 scrollable">
                <h4>No data available</h4>
            </div>
            <!-- <div class="col-12">
                <pagination :data="conferencesData" @pagination-change-page="changePage" :limit=2>
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
                conferencesData: [],
                details: [],
                selected_date: [],
                startDate: null,
                endDate: null,
                show_modal: false,
                isMultipleFilter: false,
                showInfoDiv: false,
                noData: false,
                defaultImg: 'default_speaker_icon.png',
                startUrl: 'https://www.hellenicparliament.gr' 
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
                axios.get('http://95.85.38.123/api/conferences?page=' + page)
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        self.conferencesData = response.data.conferences;
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
                axios.get('http://95.85.38.123/api/conferences')
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        self.conferencesData = response.data.conferences;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            printImg(img){
                //check if deafault img has different name then return our default img
                if(img == 'default-speaker.jpg'){
                    return this.defaultImg;
                }else{
                    return img;
                }
            },
            getDates(date){
                //get dates from datepicker
                const self = this;
                let dates = [];
                let tmp = [];
                if(this.startDate && this.endDate){
                    dates.push(moment(this.startDate).format('YYYY/MM/DD'));
                    dates.push(moment(this.endDate).format('YYYY/MM/DD'));
                }
                // let myJsonString = JSON.stringify(dates);
                console.log(dates);
                // axios({
                //     method:'get',
                //     url:'http://95.85.38.123/api/conferences',
                //     responseType:'json',
                //     data: {
                //         dates: myJsonString
                //     }
                // })
                axios.get('http://95.85.38.123/api/conferences' ,{
                    params: {
                        dates: dates
                    }
                })
                .then(function(response){
                    if(response.status == 200 && response.statusText == "OK"){
                        // tmp = response.data.conferences;
                        // self.details.push(tmp);
                        if(response.data.conferences.length > 0){
                            self.noData = false;
                            self.details = response.data.conferences;
                        }else{
                            self.noData = true;
                        }
                    }
                    //self.conferencesData = response.data.conferences;
                })
                .catch(function (error) {
                    console.log(error);
                });
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
                        self.details.push(tmp);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            resetDates(){
                //reset selected dates from dropdown
                this.selected_date = [];
                this.details = [];
            },
            isEmpty(info){

            },
            removeOption(removedOption){
                //remove the selected option from array
                for(var i=0;i<this.details.length;i++){
                    for(var j=0;j<this.details[i].length;j++){
                        if(removedOption.Date == this.details[i][j].Date){
                            this.details.splice(i,1);
                            break;
                        }
                    }
                }
                //we do this so we can hide the reset button
                if(this.details.length == 0){
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
            var self = this;
            //this.getConferences();
            //console.log(typeof this.conferences);
            this.conferencesData = this.conferences;
            this.disabledFn = {
                customPredictor (date) {
                    //console.log(moment(date).format('YYYY-MM-DD'));
                    //console.log(self.conferencesData);
                    var newDate = moment(date).format('YYYY-MM-DD');
                    for(var data in self.conferencesData){
                        //console.log(newDate + " " +  self.conferencesData[data]['Date']);
                        if(self.conferencesData[data]['Date'] != newDate){
                            return true;
                        }else{
                            return false;
                        }
                        //console.log(self.conferencesData[data]['Date']);
                    }
                }
            }
            //console.log(this.isJsonString(this.conferences));
        }
    }
</script>
